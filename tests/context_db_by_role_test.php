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
 * Context by role test
 *
 * @package   local_dash_by_role
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class context_db_by_role_test extends advanced_testcase {
    /**
     * Test get name
     * @covers \local_dash_by_role\context_db_by_role::get_name
     */
    public function test_get_context_name() {
        $this->resetAfterTest();
        $rid1 = $this->getDataGenerator()->create_role(['name' => 'Role 1']);
        $context = \local_dash_by_role\context_db_by_role::instance($rid1);
        $this->assertEquals("Dashboard by role: Role 1", $context->get_context_name());
    }

    /**
     * Test get level name
     * @covers \local_dash_by_role\context_db_by_role::get_level_name
     */
    public function test_get_level_name() {
        $this->assertEquals("Dashboard by role", context_db_by_role::get_level_name());
    }

    /**
     * Test get url
     * @covers \local_dash_by_role\context_db_by_role::get_url
     */
    public function test_get_url() {
        $this->resetAfterTest();
        $rid1 = $this->getDataGenerator()->create_role(['name' => 'Role 1']);
        $context = \local_dash_by_role\context_db_by_role::instance($rid1);
        $this->assertEquals("https://www.example.com/moodle/role/define.php?id=" . $rid1, $context->get_url()->out());
    }

    /**
     * Test get capabilities
     * @covers \local_dash_by_role\context_db_by_role::get_capabilities
     */
    public function test_get_capabilities() {
        $this->resetAfterTest();
        $rid1 = $this->getDataGenerator()->create_role(['name' => 'Role 1']);
        $context = \local_dash_by_role\context_db_by_role::instance($rid1);
        $this->assertEquals([], $context->get_capabilities());
    }

    /**
     * Test get instance
     * @covers \local_dash_by_role\context_db_by_role::instance
     */
    public function test_instance() {
        $this->resetAfterTest();
        $rid1 = $this->getDataGenerator()->create_role(['name' => 'Role 1']);
        $context = \local_dash_by_role\context_db_by_role::instance($rid1);
        $this->assertEquals($rid1, $context->instanceid);
    }
}
