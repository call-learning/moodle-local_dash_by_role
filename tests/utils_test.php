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

use advanced_testcase;
/**
 * Utils test
 *
 * @package   local_dash_by_role
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class utils_test extends advanced_testcase {
    /**
     * Check that context level is setup correctly when fetching get page context.
     *
     * @return void
     * @covers \local_dash_by_role\utils::get_page_context
     */
    public function test_get_page_context() {
        $this->resetAfterTest();
        $roles = array_values(role_get_names());
        $this->assertEquals(\context_system::instance()->contextlevel, utils::get_page_context()->contextlevel);
        $this->assertEquals(context_db_by_role::CONTEXT_LEVEL, utils::get_page_context($roles[0]->id)->contextlevel);
    }

    /**
     * Check that context level is setup correctly when fetching get page context.
     *
     * @return void
     * @covers \local_dash_by_role\utils::get_page_context_for_user_role
     */
    public function test_get_page_context_for_user_role() {
        $this->resetAfterTest();
        $this->setGuestUser();
        $this->assertEquals(\context_system::instance()->contextlevel, utils::get_page_context_for_user_role()->contextlevel);
        $user = $this->getDataGenerator()->create_user();

        $rid1 = $this->getDataGenerator()->create_role(['name' => 'Role 1']);
        $rid2 = $this->getDataGenerator()->create_role(['name' => 'Role 2']);
        $this->setUser($user);
        $this->getDataGenerator()->role_assign($rid1, $user->id);
        $this->getDataGenerator()->role_assign($rid2, $user->id);

        // Check that get the right context id.
        $this->assertEquals(context_db_by_role::CONTEXT_LEVEL, utils::get_page_context_for_user_role()->contextlevel);
        $this->assertEquals($rid1, utils::get_page_context_for_user_role()->instanceid);
        $roles = array_filter(role_get_names(), function($r) {
            return $r->shortname == 'manager';
        });
        $role = end($roles);

        // Check that we take the role with the highest priority.
        $this->getDataGenerator()->role_assign($role->id, $user->id);
        $this->assertEquals($role->id, utils::get_page_context_for_user_role()->instanceid);
    }
}
