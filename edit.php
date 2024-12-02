<?php
require_once(__DIR__. '/../../config.php');
require_login();
use local_strathdocs\doc_manager;

require_capability('local/strathdocs:managedocs', \context_system::instance());

$action = optional_param('action', false, PARAM_ALPHA);
//if ($action == doc_manager::DOC_ACTION_CREATE) {
$type = required_param('type', PARAM_ALPHAEXT);
$module = required_param('module', PARAM_ALPHAEXT);
$code = required_param('code', PARAM_ALPHAEXT);

$PAGE->set_context(\context_system::instance());
$PAGE->set_url(new moodle_url('/local/strathdocs/edit.php', [
    'type' => $type,
    'module' => $module,
    'code' => $code,
    'action' => $action,
]));
$relativepath = implode("/", [$type, $module, $code]);
$PAGE->set_heading("Creating Override Documentation for ". $relativepath);

$manager = new doc_manager();

$form = new \local_strathdocs\docform(null, ['type' => $type, 'module' => $module, 'code' => $code]);
if ($form->is_cancelled()) {
    redirect(new moodle_url("/local/strathdocs/index.php/{$type}/{$module}/{$code}"));
}
if ($data = $form->get_data()) {
    var_dump($data);
    if ($action == doc_manager::DOC_ACTION_CREATE) {
        $doc = [] ;//$manager->create_doc();
    } else if ($action == doc_manager::DOC_ACTION_EDIT) {
        $doc = $manager->get_doc($type, $module, $code);
    }

    $key = "{$data->type}/{$data->module}/{$data->code}";
    $doc['title'] = $data->title;
    $doc['content'] = $data->content['text'];
    $doc['fixlinks'] = [];
    $doc['hidecoredoclink'] = $data->hidecoredoclink ?? false;
    for ($i = 0; $i < $data->fixlinks_repeats; $i++) {
        $fixlink = new \stdClass();
        $fixlink->title = $data->fixtitle[$i];
        $fixlink->url = $data->fixurl[$i];
        $fixlink->description = $data->fixdescription[$i]['text'];
        $doc['fixlinks'][] = $fixlink;
    }
    if ($action == doc_manager::DOC_ACTION_CREATE) {
        $doc = $manager->add_doc($data->type, $data->module, $data->code, $doc);
    } else if ($action == doc_manager::DOC_ACTION_EDIT) {
        $doc = $manager->set_doc($type, $module, $code, $doc);
    }

    $version = $CFG->branch;
    $lang = current_language();
    redirect(new moodle_url("/local/strathdocs/index.php/{$version}/{$lang}/{$type}/{$module}/{$code}"));
} else {
    $doc = $manager->get_doc($type, $module, $code);

    $fixtitles = [];
    $fixurls = [];
    $fixdescriptions = [];
    $fixcaps = [];
    $i = 0;

    foreach ($doc['fixlinks'] ??[] as $fixlink) {
        $fixtitles[$i] = $fixlink['title'];
        $fixurls[$i] = $fixlink['url'] ?? "";
        $fixdescriptions[$i] = $fixlink['description'] ?? "";
//        $fixcaps[$i] = "Not implemented yet"; //$fixlink['capabilities'] ?? "";
        $i++;
    }

    $form->set_data([
        'type' => $doc['type'] ?? $type,
        'module' => $doc['module'] ?? $module,
        'code' => $doc['code'] ?? $code,
        'title' => $doc['title'] ?? "",
        'content' => [
            'text' => $doc['content'] ?? "",
            'format' => FORMAT_HTML,
        ],
        'action' => $action,
        'fixtitle' => $fixtitles,
        'fixcap' => $fixcaps,
        'fixurl' => $fixurls,
        'fixdescription' => $fixdescriptions,
        'numfixes' => count($doc['fixlinks']?? []),
    ]);
}

echo $OUTPUT->header();

echo $form->display();

echo $OUTPUT->footer();
