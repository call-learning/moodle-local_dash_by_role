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

/**
 * This file defines settingpages and externalpages under the "courses" category
 *
 * @package   local_dash_by_role
 * @copyright 2023 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;
if ($hassiteconfig) {
    // Create a global Advanced Feature Toggle.
    $enableoption = new admin_setting_configcheckbox('enabledashbyrole',
        new lang_string('enabledashbyrole', 'local_dash_by_role'),
        new lang_string('enabledashbyrole', 'local_dash_by_role'),
        0);
    $enableoption->set_updatedcallback('local_dash_by_role_enable_disable_plugin_callback');

    $optionalsubsystems = $ADMIN->locate('optionalsubsystems');
    $optionalsubsystems->add($enableoption);
}
