<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_strathdocs\output;
use core\output\named_templatable;
use renderable;
use renderer_base;
use moodle_url;

class docmanager_buttons implements named_templatable, renderable
{
    public const DOC_ACTION_CREATE = "create";
    public const DOC_ACTION_EDIT = "edit";

    public function __construct(
        public string $action,
        public moodle_url $actionUrl,
        public moodle_url $defaultActionUrl
    ) {

    }

    public function get_template_name(\renderer_base $renderer): string {
        return "local_strathdocs/docmanager_buttons";
    }

    public function export_for_template(renderer_base $output) {
        $data = new \stdClass();
        $data->action = $this->action;
        $data->create = $this->action == self::DOC_ACTION_CREATE ;
        $data->actionurl = $this->actionUrl->out();
        $data->actiontext = get_string("action_{$this->action}", 'local_strathdocs');
        $data->defaultaction = $this->defaultActionUrl->out();
        $data->defaultactiontext = get_string("viewcoredoc", 'local_strathdocs');
        return $data;
    }
}
