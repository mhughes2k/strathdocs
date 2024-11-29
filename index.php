<?php
require_once(__DIR__. '/../../config.php');
use local_strathdocs\doc_manager;

$relativepath = get_file_argument();

if (!$relativepath) {
    throw new \moodle_exception('invalidargorconf', 'local_strathdocs');
} else if (substr($relativepath, 0,1) != '/') {
    throw new \moodle_exception('pathdoesnotstartslash', 'local_strathdocs');
}

list($branch, $lang, $type, $module, $code) = explode('/', ltrim($relativepath, '/'));

//if (count($args) == 0) {
//    throw new \moodle_exception('invalidargorconf', 'local_strathdocs');
//}
//
//$lang = $args[0];
//$type = $args[1]; // Should be error
//$module = $args[2];
//$code = $args[3];
//var_dump("branch: $branch, lang: $lang, type: $type, module: $module, code: $code");
// Check if core component
$core = core_component::get_component_names();
$pm = core_plugin_manager::instance();

$moduleinfo = $pm->get_plugin_info($module);
if ($moduleinfo->is_standard()) {
    $corepath = "{$module}/{$code}";
    $docsurl = get_docs_url($corepath);
    redirect($docsurl);
    exit();
}

$isdocmanager = has_capability('local/strathdocs:managedocs', \context_system::instance());

$manager = new \local_strathdocs\doc_manager();

$doc = $manager->get_doc($type, $module, $code);
if ($doc !== false) {
    $PAGE->set_url(new moodle_url('/local/strathdocs/index.php/'.$relativepath));
    $PAGE->set_context(\context_system::instance());
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading($doc['title']);
    echo $OUTPUT->header();
    p($doc['content']);
    if (!empty($doc['fixlinks'])) {
        echo \html_writer::tag('h3', get_string('fixlinks', 'local_strathdocs'));

        echo \html_writer::alist(array_map(function ($link) {
            $content =  \html_writer::link($link['url'], $link['title']) ;
            if (isset($link['description'])) {
                $content .= \html_writer::tag('div', $link['description']);
            }
            return $content;
        }, $doc['fixlinks']));
    }
    echo $OUTPUT->footer();
    exit();
} else {
    if ($isdocmanager) {
        // Provide option to override the message.
        $overrideurl = new moodle_url('/local/strathdocs/edit.php', [
            'type' => $type,
            'module' => $module,
            'code' => $code,
            'action' => 'create'
        ]);
        $message =  \html_writer::tag('p', get_string('docnotfound', 'local_strathdocs'));
        $overridebutton = new single_button($overrideurl, get_string('overridedoc', 'local_strathdocs'));

        $PAGE->set_context(\context_system::instance());
        $PAGE->set_url(new moodle_url('/local/strathdocs/index.php/'.$relativepath));
        $PAGE->set_heading("Documentation for ". $relativepath);
        $corepath = "{$module}/{$code}";
        unset($CFG->docroot);
        $docsurl = get_docs_url($corepath);
        $docsbutton = new single_button(new \moodle_url($docsurl), get_string('viewcoredoc', 'local_strathdocs'));
        echo $OUTPUT->header();
        echo $message;
        echo $OUTPUT->render($overridebutton);
        echo $OUTPUT->render($docsbutton);
        echo $OUTPUT->footer();
    } else {
// Fall back to Moodle Docs
        unset($CFG->docroot);
        $corepath = "{$module}/{$code}";
        $docsurl = get_docs_url($corepath);

        redirect($docsurl);
        exit();
//print_error("notfound");
    }
}


