<?php

$string['pluginname'] = 'Strathclyde Docs';
$string['fixlinks'] = 'Possible Solutions';
$string['config_documents'] = 'Documents';
$string['config_documents_desc'] = '';
$string['settingspagetitle'] = 'Override Help Documentation';
$string['action_create'] = 'Create Documentation Override';
$string['action_edit'] = 'Edit Documentation Override';
$string['strathdocs:managedocs'] = 'Manage custom docs';
$string['docnotfound'] = 'Document not found';
$string['editdoc'] = 'Edit Documentation Override';
$string['overridedoc'] = 'Create Override Documentation';
$string['viewcoredoc'] = 'View Moodle Core Documentation';
$string['possiblecoredoc'] = 'There may be information available for "<a href="{$a->coredocurl}">{$a->relativepath}</a>" on the Moodle Core Documentation site.';
$string['type'] = 'Type';
$string['module'] = 'Module';
$string['code'] = 'Code';
$string['title'] = 'Page Title';
$string['title_help'] = 'Page Title';
$string['content'] = 'Page Content';
$string['content_help'] = 'Page Content';
$string['fixtitle'] = 'Title';
$string['fixurl'] = 'URL';
$string['fixdescription'] = 'Description';
$string['defaultdocs'] = '{
    "error/mod_seacow/invalidapikeysetting": {
        "title": "Invalid API Key Setting",
        "content":"The configured API key is in correct or missing",
        "hidecoredoclink": false,
        "fixlinks": [
            {
                "capabilities":[
                    "moodle/site:config"
                ],
                "title": "Configure API Key",
                "url": "/admin/settings.php?section=modsettingseacow",
                "description": "<p>Setting the API in the Seacow settings should fix this</p>"
            },
            {
                "title": "Contact Myplace Support to Configure SEACOW API Key",
                "description": "<p>Setting the API in the Seacow settings should fix this.</p>"
            }
        ]
    }
}';
