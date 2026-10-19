<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Provedor de privacidade do local_suap.
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_suap\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

defined('MOODLE_INTERNAL') || die();

/**
 * Declara, exporta e remove a trilha de auditoria (como alvo e como ator).
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {
    /**
     * Metadados.
     *
     * @param collection $collection Coleção.
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table('local_suap_user_audit', [
            'targetuserid' => 'privacy:metadata:audit:targetuserid',
            'actoruserid' => 'privacy:metadata:audit:actoruserid',
            'action' => 'privacy:metadata:audit:action',
            'field' => 'privacy:metadata:audit:field',
            'oldvalue' => 'privacy:metadata:audit:oldvalue',
            'newvalue' => 'privacy:metadata:audit:newvalue',
            'actorlabel' => 'privacy:metadata:audit:actorlabel',
            'ip' => 'privacy:metadata:audit:ip',
            'timecreated' => 'privacy:metadata:audit:timecreated',
        ], 'privacy:metadata:audit');
        return $collection;
    }

    /**
     * Contextos com dados do usuário.
     *
     * @param int $userid Usuário.
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();
        $contextlist->add_from_sql(
            'SELECT :ctx FROM {local_suap_user_audit} a WHERE a.targetuserid = :t OR a.actoruserid = :a',
            ['ctx' => SYSCONTEXTID, 't' => $userid, 'a' => $userid]
        );
        return $contextlist;
    }

    /**
     * Usuários no contexto.
     *
     * @param userlist $userlist Lista.
     */
    public static function get_users_in_context(userlist $userlist) {
        if (!$userlist->get_context() instanceof \context_system) {
            return;
        }
        $userlist->add_from_sql('targetuserid', 'SELECT targetuserid FROM {local_suap_user_audit}', []);
        $userlist->add_from_sql('actoruserid', 'SELECT actoruserid FROM {local_suap_user_audit} WHERE actoruserid > 0', []);
    }

    /**
     * Exporta os dados.
     *
     * @param approved_contextlist $contextlist Contextos aprovados.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;
        $userid = $contextlist->get_user()->id;
        $rows = $DB->get_records_select(
            'local_suap_user_audit',
            'targetuserid = :t OR actoruserid = :a',
            ['t' => $userid, 'a' => $userid],
            'timecreated ASC'
        );
        if (!$rows) {
            return;
        }
        $data = [];
        foreach ($rows as $row) {
            $row->timecreated = \core_privacy\local\request\transform::datetime($row->timecreated);
            $data[] = $row;
        }
        writer::with_context(\context_system::instance())->export_data(
            [get_string('pluginname', 'local_suap'), get_string('useraudit', 'local_suap')],
            (object) ['records' => $data]
        );
    }

    /**
     * Remove os dados de todos os usuários do contexto.
     *
     * @param \context $context Contexto.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        if ($context instanceof \context_system) {
            $DB->delete_records('local_suap_user_audit');
        }
    }

    /**
     * Remove os dados do usuário (alvo) e anonimiza o ator.
     *
     * @param approved_contextlist $contextlist Contextos aprovados.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        $userid = $contextlist->get_user()->id;
        $DB->delete_records('local_suap_user_audit', ['targetuserid' => $userid]);
        $DB->set_field('local_suap_user_audit', 'actoruserid', 0, ['actoruserid' => $userid]);
    }

    /**
     * Remove os dados de vários usuários.
     *
     * @param approved_userlist $userlist Lista aprovada.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;
        if (!$userlist->get_context() instanceof \context_system) {
            return;
        }
        [$insql, $params] = $DB->get_in_or_equal($userlist->get_userids(), SQL_PARAMS_NAMED);
        $DB->delete_records_select('local_suap_user_audit', "targetuserid $insql", $params);
        $DB->set_field_select('local_suap_user_audit', 'actoruserid', 0, "actoruserid $insql", $params);
    }
}
