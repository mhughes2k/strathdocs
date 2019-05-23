<?php
require_once(__DIR__. '/../../config.php');

$relativepath = get_file_argument();

if (!$relativepath) {
    print_error('invalidargorconf');
} else if ($relativepath{0} != '/') {
    print_error('pathdoesnotstartslash');
}

$args = explode('/', ltrim($relativepath, '/'));

if (count($args) == 0) {
    print_error('invalidarguments');
}

$lang = $args[0];
$type = $args[1]; // Should be error
$module = $args[2];
$code = $args[3];


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

$sm = get_string_manager();
$codeurl = $code .'_url';
if ($sm->string_exists($code, $module)) {
    $docsurl = get_string($codeurl, $module);
    //echo $docsurl;
    redirect($docsurl);
    exit();
}
// Fall back to Moodle Docs
$corepath = "{$module}/{$code}";
$docsurl = get_docs_url($corepath);
redirect($docsurl);
exit();
//print_error("notfound");
