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

echo $OUTPUT->header();
var_dump($manager->create_doc());
echo $OUTPUT->footer();
