<?php
namespace local_strathdocs;

class doc_manager {

    public const DOC_ACTION_CREATE = "create";
    public const DOC_ACTION_EDIT = "edit";
    private $config = null;
    function __construct() {
        $config = json_decode(get_config('local_strathdocs', 'documents'), true);
        if (empty($config)) {
            $defaultconfig = "{}";//get_string('defaultdocs', 'local_strathdocs');
            $this->config = json_decode($defaultconfig, true);
        } else {
            $this->config = $config;
        }
    }

    public function get_doc($type, $module, $code) {
        $docpath = "{$type}/{$module}/{$code}";
//        if (debugging($docpath, DEBUG_DEVELOPER)) {
//            print_r($this->config);
//        }
        if (isset($this->config[$docpath])) {
//            if (debugging("Content for $docpath", DEBUG_DEVELOPER)) {
//                print_r($this->config[$docpath]);
//            }
            $doc = $this->config[$docpath];
            $doc['type'] = $type;
            $doc['module'] = $module;
            $doc['code'] = $code;
            return $doc;
        }
        return false;
    }

    public function get_doc_by_string($path) {
        list($type, $module, $code) = explode('/', $path);
        return $this->get_doc($type, $module, $code);
    }

    public function add_doc($type, $module, $code, $doc) {
        $docpath = "{$type}/{$module}/{$code}";
        unset($doc->type);
        unset($doc->module);
        unset($doc->code);
        $this->config[$docpath] = $doc;
        set_config('documents', json_encode($this->config), 'local_strathdocs');
        return $this->get_doc($type, $module, $code);
    }
    public function set_doc($type, $module, $code, $doc) {
        $docpath = "{$type}/{$module}/{$code}";
        unset($doc->type);
        unset($doc->module);
        unset($doc->code);
        $this->config[$docpath] = $doc;
        set_config('documents', json_encode($this->config), 'local_strathdocs');
        return $this->get_doc($type, $module, $code);
    }
    /**
     * Returns a blank document.
     * @return void
     */
    public function create_doc() {
        $doc = json_decode('{
            "title": " ",
            "content":"",
            "fixlinks": [
                {
                    "title": "",
                    "url": "",
                    "description": ""
                }
            ]
        }');
        return $doc;
    }
}
