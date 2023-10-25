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
namespace local_dash_by_role;

use context_system;

/**
 * Helper class for Dash by role
 *
 * @package   local_dash_by_role
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils {

    /**
     * Get page context for role
     *
     * @param int $roleid
     * @return context_system|context_db_by_role
     */
    public static function get_page_context($roleid = 0) {
        if ($roleid) {
            return \local_dash_by_role\context_db_by_role::instance($roleid);
        }
        return context_system::instance();
    }

    /**
     * Get context by role for the current user
     *
     * @return context_system|context_db_by_role
     */
    public static function get_page_context_for_user_role() {
        $roles = get_user_roles(context_system::instance());
        // Roles are sorted by sortorder, so if more than one, get the first one.
        $roleid = 0;
        if (!empty($roles)) {
            $role = array_shift($roles);
            $roleid = $role->roleid;
        }
        return self::get_page_context($roleid);
    }
}
