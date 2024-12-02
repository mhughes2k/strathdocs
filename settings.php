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
defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree && $ADMIN->locate('localplugins')) {
    $lsettings = new admin_settingpage('local_strathdocs',
        get_string('settingspagetitle', 'local_strathdocs'));
    $ADMIN->add('localplugins', $lsettings);

    $lsettings->add(new admin_setting_configtextarea('local_strathdocs/documents',
            get_string('config_documents', 'local_strathdocs'),
            get_string('config_documents_desc', 'local_strathdocs'),
            get_string('defaultdocs', 'local_strathdocs'))
    );

}
