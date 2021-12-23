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

use coding_exception;
use context;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Context for configurable dashboard page by role
 *
 * Provide the context for this dashboard by role
 *
 * @package   local_dash_by_role
 * @copyright 2021 - CALL Learning - Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class context_db_by_role extends context {
    /**
     * @var int CONTEXT_LEVEL
     */
    const CONTEXT_LEVEL = 120;


    /**
     * Please use context_user::instance($userid) if you need the instance of context.
     * Alternatively if you know only the context id use context::instance_by_id($contextid)
     *
     * @param stdClass $record
     */
    protected function __construct(stdClass $record) {
        parent::__construct($record);
        if ($record->contextlevel != self::CONTEXT_LEVEL) {
            throw new coding_exception('Invalid $record->contextlevel in context_global_dashboard constructor.');
        }
    }

    /**
     * Returns human readable context level name.
     *
     * @return string the human readable context level name.
     */
    public static function get_level_name() {
        return get_string('pluginname', 'local_dash_by_role');
    }

    /**
     * Returns human readable context identifier.
     *
     * @param boolean $withprefix whether to prefix the name of the context with User
     * @param boolean $short does not apply to user context
     * @param boolean $escape does not apply to user context
     * @return string the human readable context name.
     */
    public function get_context_name($withprefix = true, $short = false, $escape = true) {
        global $DB;

        $name = '';
        if ($role = $DB->get_record('role', array('id' => $this->_instanceid))) {
            if ($withprefix) {
                $name = get_string('pluginname', 'local_dash_by_role') . ': ';
            }
            $name .= role_get_name($role);
        }
        return $name;
    }

    /**
     * Returns the most relevant URL for this context.
     *
     * @return moodle_url
     */
    public function get_url() {
        $url = new moodle_url('/role/define.php', array('id' => $this->_instanceid));
        return $url;
    }

    /**
     * Returns array of relevant context capability records.
     *
     * @return array
     */
    public function get_capabilities($sort = 'ORDER BY contextlevel,component,name') {
        global $DB;

        $sql = "SELECT *
                  FROM {capabilities}
                 WHERE contextlevel = " . self::CONTEXT_LEVEL;

        return $DB->get_records_sql($sql . ' ' . $sort);
    }

    /**
     * Returns context instance.
     *
     * @param int $roleid id from {role} table
     * @param int $strictness
     * @return context_db_by_role|bool context instance
     */
    public static function instance($roleid, $strictness = MUST_EXIST) {
        global $DB;

        if ($context = context::cache_get(self::CONTEXT_LEVEL, $roleid)) {
            return $context;
        }

        if (!$record = $DB->get_record('context', array('contextlevel' => self::CONTEXT_LEVEL, 'instanceid' => $roleid))) {
            if ($role = $DB->get_record('role', array('id' => $roleid), '*', $strictness)) {
                $record = context::insert_context_record(self::CONTEXT_LEVEL, $role->id, '/' . SYSCONTEXTID, 0);
            }
        }

        if ($record) {
            $context = new context_db_by_role($record);
            context::cache_add($context);
            return $context;
        }

        return false;
    }

    /**
     * Create missing context instances at user context level
     *
     */
    protected static function create_level_instances() {
        global $DB;

        $sql = "SELECT " . self::CONTEXT_LEVEL . ", r.id
                  FROM {role} r
                 WHERE NOT EXISTS (SELECT 'x'
                                         FROM {context} cx
                                        WHERE r.id = cx.instanceid AND cx.contextlevel=" . self::CONTEXT_LEVEL . ")";
        $contextdata = $DB->get_recordset_sql($sql);
        foreach ($contextdata as $context) {
            context::insert_context_record(self::CONTEXT_LEVEL, $context->id, null);
        }
        $contextdata->close();
    }

    /**
     * Returns sql necessary for purging of stale context instances.
     *
     * @return string cleanup SQL
     */
    protected static function get_cleanup_sql() {
        $sql = "SELECT c.*
                    FROM {context} c
         LEFT OUTER JOIN {role} r ON c.instanceid = r.id
                   WHERE r.id IS NULL AND c.contextlevel = " . self::CONTEXT_LEVEL . "
               ";

        return $sql;
    }

    /**
     * Rebuild context paths and depths at user context level.
     *
     * @param bool $force
     */
    protected static function build_paths($force) {
        global $DB;

        // First update normal users.
        $path = $DB->sql_concat('?', 'id');
        $pathstart = '/' . SYSCONTEXTID . '/';
        $params = array($pathstart);

        if ($force) {
            $where = "depth <> 2 OR path IS NULL OR path <> ({$path})";
            $params[] = $pathstart;
        } else {
            $where = "depth = 0 OR path IS NULL";
        }

        $sql = "UPDATE {context}
                   SET depth = 2,
                       path = {$path}
                 WHERE contextlevel = " . self::CONTEXT_LEVEL . "
                   AND ($where)";
        $DB->execute($sql, $params);
    }
}
