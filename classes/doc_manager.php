<?php
namespace local_strathdocs;

class doc_manager {

    private $config = null;
    function __construct() {
        $config = json_decode(get_config('local_strathdocs', 'documents'));
        if (empty($config)) {
            $defaultconfig = get_string('defaultdocs', 'local_strathdocs');
            $this->config =json_decode($defaultconfig, true);
        } else {
            $this->config = $config;
        }
    }

    public function get_doc($type, $module, $code) {
        $docpath = "{$type}/{$module}/{$code}";
        if (isset($this->config[$docpath])) {
            return $this->config[$docpath];
        }
        return false;
    }

    public function get_doc_by_string($path) {
        list($type, $module, $code) = explode('/', $path);
        return $this->get_doc($type, $module, $code);
    }

    public function add_doc($type, $module, $code, $doc) {
        $docpath = "{$type}/{$module}/{$code}";
        $this->config[$docpath] = $doc;
        set_config('documents', json_encode($this->config), 'local_strathdocs');
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