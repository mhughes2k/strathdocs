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

namespace local_strathdocs;
require_once($CFG->libdir.'/formslib.php');
use moodleform;

class docform extends moodleform {

    protected function definition() {

        $mform = $this->_form;
        $mform->addElement('hidden', 'numfixes');
        $mform->setType('numfixes', PARAM_INT);
        $mform->addElement('hidden', 'action');
        $mform->setType('action', PARAM_ALPHA);

        $mform->addElement('text','type', get_string('type', 'local_strathdocs'));
        $mform->setType('type', PARAM_ALPHAEXT);
        $mform->addElement('text','module', get_string('module', 'local_strathdocs'));
        $mform->setType('module', PARAM_ALPHAEXT);
        $mform->addElement('text','code', get_string('code', 'local_strathdocs'));
        $mform->setType('code', PARAM_ALPHAEXT);

        $mform->addElement('text', 'title', get_string('title', 'local_strathdocs'));
        $mform->setType('title',PARAM_RAW);
        $mform->addElement('editor', 'content', get_string('content', 'local_strathdocs'));
        $mform->addElement('checkbox', 'hidecoredoclink', get_string('hidecoredoclink', 'local_strathdocs'), false);

        $mform->addElement('header', 'hdrfixlinks', get_string('fixlinks', 'local_strathdocs'));

    }

    public function definition_after_data() {
        $this->generate_fixlinks();
        $this->add_action_buttons();
    }

    protected function generate_fixlinks() {
        $mform = $this->_form;
//        $fixlinks = $mform->addElement('group', 'fixlinks', get_string('fixlinks', 'local_strathdocs'), '', false);
        $repeats = [
            $mform->createElement('text', 'fixtitle', get_string('fixtitle', 'local_strathdocs')),
//            $mform->createElement('text', 'fixcap', get_string('fixcap', 'local_strathdocs')),
            $mform->createElement('text', 'fixurl', get_string('fixurl', 'local_strathdocs')),
            $mform->createElement('editor', 'fixdescription', get_string('fixdescription', 'local_strathdocs')),
        ];
        $repeatno = 1;
        $repeatno = $mform->getElementValue('numfixes');

        $options = [
            'fixtitle' => [
                'type' => PARAM_TEXT,
            ],
            'fixurl' => [
                'type' => PARAM_URL,
            ],
            'fixdescription' => [
                'type' => PARAM_CLEANHTML,
            ],

        ];
        $this->repeat_elements(
            $repeats,
            $repeatno,
            $options,
            'fixlinks_repeats',
            'fixlinks_add',
            1,
            null,
            true,
            'delete'
        );
    }
}
