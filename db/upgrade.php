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
 * Plugin upgrade steps are defined here.
 *
 * @package     local_suap
 * @category    upgrade
 * @copyright   2022 Kelson Medeiros <kelsoncm@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/migrate.php');

/**
 * Upgrade the local_suap plugin.
 *
 * @param int $oldversion The version of the plugin being upgraded from.
 * @return bool Always true.
 */
function xmldb_local_suap_upgrade($oldversion) {
    suap_bulk_course_custom_field();
    suap_bulk_user_custom_field();

    global $DB;

    if ($oldversion < 20250428003) {
        $fields = $DB->get_records('customfield_field', ['shortname' => 'carga_horaria']);

        foreach ($fields as $field) {
            $field->type = 'text';
            $DB->update_record('customfield_field', $field);
        }
        upgrade_plugin_savepoint(true, 20250428003, 'local', 'suap');
    }

    if ($oldversion < 20260130081) {
        $dbman = $DB->get_manager();

        $table = new xmldb_table('local_suap_relatorio_cursos_autoinstrucionais');

        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);

            $table->add_field('curso_nome', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('campus', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);

            $table->add_field('diario_tipo', XMLDB_TYPE_CHAR, '50', null, XMLDB_NOTNULL);
            $table->add_field('quantidade_cursos', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1');

            $table->add_field('total_enrolled', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('accessed', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('no_access', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            $table->add_field('final_exam_takers', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('passed', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('failed', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            $table->add_field('avg_grade', XMLDB_TYPE_NUMBER, '10,2', null, XMLDB_NOTNULL, null, '0');

            $table->add_field('with_certificate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('without_certificate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('completed', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

            $table->add_field('timegenerated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $table->add_index('idx_curso_nome', XMLDB_INDEX_NOTUNIQUE, ['curso_nome']);
            $table->add_index('idx_campus', XMLDB_INDEX_NOTUNIQUE, ['campus']);
            $table->add_index('idx_diario_tipo', XMLDB_INDEX_NOTUNIQUE, ['diario_tipo']);
            $table->add_index('idx_timegenerated', XMLDB_INDEX_NOTUNIQUE, ['timegenerated']);

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 20260130081, 'local', 'suap');
    }

    if ($oldversion < 20260206084) {
        $dbman = $DB->get_manager();

        $table = new xmldb_table('local_suap_restricoes_autoinscricao');

        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);

            $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $table->add_field('chave', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('restricao', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL);
            $table->add_field('descricao', XMLDB_TYPE_TEXT, null, null);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);

            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

            $table->add_index('idx_courseid', XMLDB_INDEX_NOTUNIQUE, ['courseid']);
            $table->add_index('idx_chave', XMLDB_INDEX_NOTUNIQUE, ['chave']);

            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 20260206084, 'local', 'suap');
    }

    if ($oldversion < 20260829114) {
        upgrade_plugin_savepoint(true, 20260829114, 'local', 'suap');
    }

    if ($oldversion < 20261019120) {
        $dbman = $DB->get_manager();
        $table = new xmldb_table('local_suap_user_audit');
        if (!$dbman->table_exists($table)) {
            $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE);
            $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $table->add_field('targetuserid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL);
            $table->add_field('action', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL);
            $table->add_field('field', XMLDB_TYPE_CHAR, '100');
            $table->add_field('oldvalue', XMLDB_TYPE_CHAR, '1000');
            $table->add_field('newvalue', XMLDB_TYPE_CHAR, '1000');
            $table->add_field('actoruserid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
            $table->add_field('actorlabel', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL);
            $table->add_field('origin', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL);
            $table->add_field('ip', XMLDB_TYPE_CHAR, '45');
            $table->add_field('requestref', XMLDB_TYPE_CHAR, '100');
            $table->add_field('eventname', XMLDB_TYPE_CHAR, '255');
            $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
            $table->add_index('idx_target_time', XMLDB_INDEX_NOTUNIQUE, ['targetuserid', 'timecreated']);
            $table->add_index('idx_actor', XMLDB_INDEX_NOTUNIQUE, ['actoruserid']);
            $table->add_index('idx_actorlabel', XMLDB_INDEX_NOTUNIQUE, ['actorlabel']);
            $table->add_index('idx_timecreated', XMLDB_INDEX_NOTUNIQUE, ['timecreated']);
            $dbman->create_table($table);
        }
        upgrade_plugin_savepoint(true, 20261019120, 'local', 'suap');
    }

    return local_suap_migrate($oldversion);
}
