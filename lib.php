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
 * Configurable dashboard page by role Library
 *
 * @package   local_dash_by_role
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * When enabling and disabling the dash by role plugin, we need to force the default my moodle page
 *
 * @return void
 */
function local_dash_by_role_enable_disable_plugin_callback() {
    global $CFG, $DB;

    $enabled = get_config('enabledashbyrole');

    if ($enabled) {
        set_config('forcedefaultmymoodle', 1);
    } else {
        unset_config('forcedefaultmymoodle');
    }
}
