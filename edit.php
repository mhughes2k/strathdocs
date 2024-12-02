<?php
require_once(__DIR__. '/../../config.php');
require_login();
use local_strathdocs\doc_manager;

require_capability('local/strathdocs:managedocs', \context_system::instance());

$action = optional_param('action', false, PARAM_ALPHA);
if ($action == "create") {
    $type = required_param('type', PARAM_ALPHA);
    $module = required_param('module', PARAM_ALPHA);
    $code = required_param('code', PARAM_ALPHANUM);
}

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
    $doc = $manager->create_doc();

    $key = "{$data->type}/{$data->module}/{$data->code}";
    $doc->title = $data->title;
    $doc->content = $data->content['text'];
    $doc->fixlinks = [];
    $doc->hidecoredoclink = $data->hidecoredoclink;
    for ($i = 0; $i < $data->fixlinks_repeats; $i++) {
        $fixlink = new \stdClass();
        $fixlink->title = $data->fixtitle[$i];
        $fixlink->url = $data->fixurl[$i];
        $fixlink->description = $data->fixdescription[$i]['text'];
        $doc->fixlinks[] = $fixlink;
    }
    var_dump($doc);
    $doc = $manager->add_doc($data->type, $data->module, $data->code, $doc);
    $version = $CFG->branch;
    $lang = current_language();
    redirect(new moodle_url("/local/strathdocs/index.php/{$version}/{$lang}/{$type}/{$module}/{$code}"));
} else {
    $form->set_data([
        'type' => $type,
        'module' => $module,
        'code' => $code,
        'action' => $action,
    ]);
}

echo $OUTPUT->header();

echo $form->display();

echo $OUTPUT->footer();
