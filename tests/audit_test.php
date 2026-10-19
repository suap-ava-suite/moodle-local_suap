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

namespace local_suap;

use local_suap\audit\context;
use local_suap\audit\recorder;

/**
 * Testes da auditoria de usuários.
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers    \local_suap\audit\recorder
 * @covers    \local_suap\audit\context
 * @covers    \local_suap_observer
 */
final class audit_test extends \advanced_testcase {
    protected function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();
        context::reset();
    }

    public function test_sensitive_values_are_masked(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user();
        $DB->delete_records(recorder::TABLE);
        recorder::record($user->id, 'profile', 'profile_field_cpf', '111', '222');
        recorder::record($user->id, 'update', 'firstname', 'A', 'B');
        $rows = array_values($DB->get_records(recorder::TABLE, null, 'id'));
        $this->assertSame(recorder::MASK, $rows[0]->oldvalue);
        $this->assertSame(recorder::MASK, $rows[0]->newvalue);
        $this->assertSame('A', $rows[1]->oldvalue);
        $this->assertSame('B', $rows[1]->newvalue);
    }

    public function test_actor_label_and_request_come_from_context(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user();
        $DB->delete_records(recorder::TABLE);
        context::set('api:suap', 'suap_enrolment_to_sync:7');
        $this->setUser(0);
        recorder::record($user->id, 'update', 'email', 'a@x', 'b@x');
        $row = $DB->get_record(recorder::TABLE, ['targetuserid' => $user->id]);
        $this->assertSame('api:suap', $row->actorlabel);
        $this->assertSame('suap_enrolment_to_sync:7', $row->requestref);
        $this->assertEquals(0, $row->actoruserid);
    }

    public function test_observer_records_user_update_with_system_label(): void {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        $user = $this->getDataGenerator()->create_user();
        $DB->delete_records(recorder::TABLE);
        context::set('task:sync_user_names');
        $this->setUser(0);
        user_update_user((object) ['id' => $user->id, 'firstname' => 'Novo'], false, true);
        $row = $DB->get_record(recorder::TABLE, ['targetuserid' => $user->id, 'action' => 'update']);
        $this->assertNotFalse($row);
        $this->assertSame('task:sync_user_names', $row->actorlabel);
        $this->assertEquals(0, $row->actoruserid);
    }

    public function test_observer_records_real_actor_on_web(): void {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');
        $admin = get_admin();
        $user = $this->getDataGenerator()->create_user();
        $DB->delete_records(recorder::TABLE);
        $this->setUser($admin);
        user_update_user((object) ['id' => $user->id, 'lastname' => 'Silva'], false, true);
        $row = $DB->get_record(recorder::TABLE, ['targetuserid' => $user->id, 'action' => 'update']);
        $this->assertEquals($admin->id, $row->actoruserid);
    }

    public function test_writer_update_user_records_field_diff_and_actions(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user(['firstname' => 'Antes', 'auth' => 'manual']);
        $DB->delete_records(recorder::TABLE);
        context::set('api:sga', 'sga_enrolment_to_sync:3');
        $this->setUser(0);
        $diff = \local_suap\audit\writer::update_user(
            (object) ['id' => $user->id, 'firstname' => 'Depois', 'auth' => 'nologin', 'suspended' => 1],
            false,
            false
        );
        $this->assertSame(['firstname', 'auth', 'suspended'], array_keys($diff));
        $rows = $DB->get_records(recorder::TABLE, ['targetuserid' => $user->id], '', 'field, action, oldvalue, newvalue, actorlabel');
        $this->assertSame('update', $rows['firstname']->action);
        $this->assertSame('Antes', $rows['firstname']->oldvalue);
        $this->assertSame('auth_change', $rows['auth']->action);
        $this->assertSame('suspend', $rows['suspended']->action);
        $this->assertSame('api:sga', $rows['auth']->actorlabel);
        // O observer não duplica a linha do evento user_updated.
        $this->assertFalse($DB->record_exists(recorder::TABLE, ['targetuserid' => $user->id, 'action' => 'update', 'field' => null]));
    }

    public function test_writer_without_changes_records_nothing(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user(['firstname' => 'Igual']);
        $DB->delete_records(recorder::TABLE);
        $diff = \local_suap\audit\writer::update_user((object) ['id' => $user->id, 'firstname' => 'Igual'], false, false);
        $this->assertSame([], $diff);
        $this->assertSame(0, $DB->count_records(recorder::TABLE));
    }

    public function test_writer_preference_and_profile_snapshot(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user();
        $this->getDataGenerator()->create_custom_profile_field(['shortname' => 'audit_test_field', 'name' => 'Campus', 'datatype' => 'text']);
        $DB->delete_records(recorder::TABLE);
        \local_suap\audit\writer::set_preference('htmleditor', '0', $user);
        \local_suap\audit\writer::save_profile_fields((int) $user->id, ['audit_test_field' => 'CNAT']);
        $pref = $DB->get_record(recorder::TABLE, ['targetuserid' => $user->id, 'action' => 'pref']);
        $this->assertSame('preference:htmleditor', $pref->field);
        $this->assertSame('0', $pref->newvalue);
        $profile = $DB->get_record(recorder::TABLE, ['targetuserid' => $user->id, 'action' => 'profile']);
        $this->assertSame('profile_field_audit_test_field', $profile->field);
        $this->assertSame('CNAT', $profile->newvalue);
        // Repetir o mesmo valor não gera nova linha de preferência.
        \local_suap\audit\writer::set_preference('htmleditor', '0', $user);
        $this->assertSame(1, $DB->count_records(recorder::TABLE, ['targetuserid' => $user->id, 'action' => 'pref']));
    }

    public function test_purge_removes_only_old_records(): void {
        global $DB;
        $user = $this->getDataGenerator()->create_user();
        $DB->delete_records(recorder::TABLE);
        $old = recorder::record($user->id, 'update', 'email', 'a', 'b');
        $DB->set_field(recorder::TABLE, 'timecreated', strtotime('-30 months'), ['id' => $old]);
        $new = recorder::record($user->id, 'update', 'email', 'b', 'c');
        recorder::purge_older_than(24);
        $this->assertFalse($DB->record_exists(recorder::TABLE, ['id' => $old]));
        $this->assertTrue($DB->record_exists(recorder::TABLE, ['id' => $new]));
    }
}
