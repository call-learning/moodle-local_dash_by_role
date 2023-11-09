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
 * My Moodle -- a user's personal dashboard
 *
 * - each user can currently have their own page (cloned from system and then customised)
 * - only the user can see their own dashboard
 * - users can add any blocks they want
 * - the administrators can define a default site dashboard for users who have
 *   not created their own dashboard
 *
 * This script implements the user's view of the dashboard, and allows editing
 * of the dashboard.
 *
 * @package    local_dash_by_role
 * @copyright  2010 Remote-Learner.net
 * @author     Hubert Chathi <hubert@remote-learner.net>
 * @author     Olav Jordan <olav.jordan@remote-learner.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $PAGE, $SITE, $CFG, $FULLME, $OUTPUT;

require_once($CFG->dirroot . '/my/lib.php');
require_once($CFG->libdir.'/adminlib.php');

redirect_if_major_upgrade_required();

$resetall = optional_param('resetall', false, PARAM_BOOL);

$pagetitle = get_string('mypage', 'admin');

// START Dash by role.
$roleid = optional_param('roleid', 0, PARAM_INT);
$extraparams = [];
if ($roleid) {
    $extraparams = ['roleid' => $roleid];
}
// END Dash by role.

$PAGE->set_secondary_active_tab('appearance');
$PAGE->set_blocks_editing_capability('moodle/my:configsyspages');
$PAGE->set_url(new moodle_url('/my/indexsys.php'));
admin_externalpage_setup('mypage', '', null, '', ['pagelayout' => 'mydashboard', 'nosearch' => true]);
$PAGE->add_body_class('limitedwidth');
$PAGE->set_pagetype('my-index');
$PAGE->blocks->add_region('content');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($pagetitle);
$PAGE->set_secondary_navigation(false);
$PAGE->set_primary_active_tab('myhome');
// START Dash by role.
$context = \local_dash_by_role\utils::get_page_context($roleid);
$PAGE->set_context($context);
// END Dash by role.

// If we are resetting all, just output a progress bar.
if ($resetall && confirm_sesskey()) {
    echo $OUTPUT->header($pagetitle);
    echo $OUTPUT->heading(get_string('resettingdashboards', 'my'), 3);

    $progressbar = new progress_bar();
    $progressbar->create();

    \core\session\manager::write_close();
    my_reset_page_for_all_users(MY_PAGE_PRIVATE, 'my-index', $progressbar);
    core\notification::success(get_string('alldashboardswerereset', 'my'));
    echo $OUTPUT->continue_button($PAGE->url);
    echo $OUTPUT->footer();
    die();
}

// @codingStandardsIgnoreFile
// phpcs:ignoreFile -- this is a old core file.

// Get the My Moodle page info.  Should always return something unless the database is broken.
if (!$currentpage = my_get_page(null, MY_PAGE_PRIVATE)) {
    throw new \moodle_exception('mymoodlesetup');
}
$PAGE->set_subpage($currentpage->id);

// Display a button to reset everyone's dashboard.
$url = $PAGE->url;
$url->params(['resetall' => true, 'sesskey' => sesskey()]);
$button = $OUTPUT->single_button($url, get_string('reseteveryonesdashboard', 'my'));

// START Dash by role.
$context = context_system::instance();
$roles = role_fix_names(get_assignable_roles($context), $context, ROLENAME_ALIAS, true);
$selecturl = new moodle_url($PAGE->url);
$selecturl->remove_params(['roleid']);
$roles = [0 => get_string('all', 'local_dash_by_role')] + $roles;
$select = new single_select($selecturl, 'roleid', $roles, $roleid);
$select->label = get_string('forrole', 'local_dash_by_role');
/* @var core_renderer $OUTPUT */
$button = html_writer::div($OUTPUT->render($select), 'px-2') . $button;
// END Dash by role.

$PAGE->set_button($button . $PAGE->button);

echo $OUTPUT->header();

echo $OUTPUT->addblockbutton('content');

echo $OUTPUT->custom_block_region('content');

echo $OUTPUT->footer();
die();
