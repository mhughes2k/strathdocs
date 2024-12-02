<?php
require_once(__DIR__. '/../../config.php');
use local_strathdocs\doc_manager;

$relativepath = get_file_argument();

if (!$relativepath) {
    throw new \moodle_exception('invalidargorconf', 'local_strathdocs');
} else if (substr($relativepath, 0,1) != '/') {
    throw new \moodle_exception('pathdoesnotstartslash', 'local_strathdocs');
}

//var_dump(explode('/', ltrim($relativepath, '/')));
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
if (!is_null($moduleinfo)) {
    if ($moduleinfo->is_standard()) {
        unset($CFG->docsroot);
        $corepath = "{$module}/{$code}";
        $docsurl = get_docs_url($corepath);
        redirect($docsurl);
        exit();
    }
}

$isdocmanager = has_capability('local/strathdocs:managedocs', \context_system::instance());

$manager = new \local_strathdocs\doc_manager();

$doc = $manager->get_doc($type, $module, $code);
if ($doc !== false) {
    $PAGE->set_url(new moodle_url('/local/strathdocs/index.php/'.$relativepath));
    $PAGE->set_context(\context_system::instance());
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title("Documentation for \"{$doc['title']}\"");
    $PAGE->set_heading($doc['title']);
    echo $OUTPUT->header();
    p($doc['content']);
    if (!empty($doc['fixlinks'])) {
        // Only display solution links that can be seen.
        $links = array_filter(
            $doc['fixlinks'],
            function ($link) use ($PAGE) {
                $caps = isset($link['capabilities']) ? $link['capabilities']: [];
                if (!empty($caps)) {
                    foreach($caps as $cap) {
                        if (!has_capability($cap, $PAGE->context)) {
                            return false;
                        }
                    }
                }
                return true;
            });
        if (!empty($links)) {
            echo \html_writer::tag('h3', get_string('fixlinks', 'local_strathdocs'));
            echo \html_writer::alist(
                array_map(function ($link) {
                    $content = $link['title'];
                    if (isset($link['url'])) {
                        $content = \html_writer::link($link['url'], $link['title']);
                    }
                    if (isset($link['description'])) {
                        $content .= \html_writer::tag('div', $link['description']);
                    }
                    return $content;
                },
                $links
                )
            );
        }
    }

    // Display message about there potentially being Core Moodle Docs.
    unset($CFG->docroot);
    $corepath = "{$module}/{$code}";
    $docsurl = new \moodle_url(get_docs_url($corepath));
    if ($doc['hidecoredoclink']??true) {
        echo \html_writer::tag('p',
            get_string('possiblecoredoc', 'local_strathdocs',
                (object)['coredocurl' => $docsurl, 'relativepath' => $relativepath]
            ));
    }

    if ($PAGE->user_is_editing() && $isdocmanager) {
        // TODO Display management UI.
        $editurl = new moodle_url('/local/strathdocs/edit.php', [
            'type' => $type,
            'module' => $module,
            'code' => $code,
            'action' => 'edit'
        ]);


        $docmanagerui = new \local_strathdocs\output\docmanager_buttons(
            \local_strathdocs\output\docmanager_buttons::DOC_ACTION_EDIT,
            $editurl,
            $docsurl
        );

        echo $OUTPUT->render($docmanagerui);
    }
    echo $OUTPUT->footer();
    exit();
} else {
    // No override page found, but we're a document manager so may want to add one.
    if ($isdocmanager) {
        $editurl = new moodle_url('/local/strathdocs/edit.php', [
            'type' => $type,
            'module' => $module,
            'code' => $code,
            'action' => 'create'
        ]);
        $corepath = "{$module}/{$code}";
        unset($CFG->docroot);
        $docsurl = new \moodle_url(get_docs_url($corepath));
        // Provide option to override the message.
        $docmanagerui = new \local_strathdocs\output\docmanager_buttons(
            \local_strathdocs\output\docmanager_buttons::DOC_ACTION_CREATE,
            $editurl,
            $docsurl
        );

        $message =  \html_writer::tag('p', get_string('docnotfound', 'local_strathdocs'));

        $PAGE->set_context(\context_system::instance());
        $PAGE->set_url(new moodle_url('/local/strathdocs/index.php/'.$relativepath));
        $PAGE->set_heading("Documentation for ". $relativepath);

        echo $OUTPUT->header();
        echo $message;
        if ($PAGE->user_is_editing() && $isdocmanager) {
            echo $OUTPUT->render($docmanagerui);
        }
        echo $OUTPUT->footer();
        exit();
    } else {
        // Fall back to Moodle Docs.
        unset($CFG->docroot);
        $corepath = "{$module}/{$code}";
        $docsurl = get_docs_url($corepath);

        redirect($docsurl);
        exit();
    }
}


