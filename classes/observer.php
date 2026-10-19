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
 * Observers de eventos core que alimentam a auditoria de usuários.
 *
 * @package    local_suap
 * @copyright  2022 kelson Medeiros {@link https://github.com/kelsoncm}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Observers de eventos core que alimentam a auditoria de usuários.
 */
class local_suap_observer {
    /**
     * Grava a auditoria de um evento core.
     *
     * O ator é o usuário do evento (web) ou, quando for 0 (APIs/tasks), o rótulo definido em
     * \local_suap\audit\context.
     *
     * @param \core\event\base $event Evento core.
     * @param string $action Ação de auditoria.
     * @param int|null $targetuserid Usuário alvo (padrão: relateduserid).
     */
    private static function audit(\core\event\base $event, string $action, ?int $targetuserid = null): void {
        $target = $targetuserid ?? (int) ($event->relateduserid ?? 0);
        if (!$target) {
            return;
        }
        if (\local_suap\audit\context::was_logged($target, $action)) {
            return;
        }
        \local_suap\audit\recorder::record(
            $target,
            $action,
            null,
            null,
            null,
            $event->eventname,
            (int) $event->userid
        );
    }

    /**
     * Audita a criação de matrícula.
     *
     * @param \core\event\user_enrolment_created $event Evento core.
     */
    public static function user_enrolment_created(\core\event\user_enrolment_created $event) {
        self::audit($event, 'enrol');
    }

    /**
     * Audita a remoção de matrícula.
     *
     * @param \core\event\user_enrolment_deleted $event Evento core.
     */
    public static function user_enrolment_deleted(\core\event\user_enrolment_deleted $event) {
        self::audit($event, 'enrol');
    }

    /**
     * Audita a alteração de matrícula.
     *
     * @param \core\event\user_enrolment_updated $event Evento core.
     */
    public static function user_enrolment_updated(\core\event\user_enrolment_updated $event) {
        self::audit($event, 'enrol');
    }

    /**
     * Audita a criação de usuário.
     *
     * @param \core\event\user_created $event Evento core.
     */
    public static function user_created(\core\event\user_created $event) {
        self::audit($event, 'create', (int) $event->objectid);
    }

    /**
     * Audita a exclusão de usuário.
     *
     * @param \core\event\user_deleted $event Evento core.
     */
    public static function user_deleted(\core\event\user_deleted $event) {
        self::audit($event, 'delete', (int) $event->objectid);
    }

    /**
     * Audita a alteração de usuário.
     *
     * @param \core\event\user_updated $event Evento core.
     */
    public static function user_updated(\core\event\user_updated $event) {
        self::audit($event, 'update', (int) $event->objectid);
    }

    /**
     * Audita a entrada em coorte.
     *
     * @param \core\event\cohort_member_added $event Evento core.
     */
    public static function cohort_member_added(\core\event\cohort_member_added $event) {
        self::audit($event, 'cohort');
    }

    /**
     * Audita a saída de coorte.
     *
     * @param \core\event\cohort_member_removed $event Evento core.
     */
    public static function cohort_member_removed(\core\event\cohort_member_removed $event) {
        self::audit($event, 'cohort');
    }

    /**
     * Audita a atribuição de papel.
     *
     * @param \core\event\role_assigned $event Evento core.
     */
    public static function role_assigned(\core\event\role_assigned $event) {
        self::audit($event, 'role');
    }

    /**
     * Audita a remoção de papel.
     *
     * @param \core\event\role_unassigned $event Evento core.
     */
    public static function role_unassigned(\core\event\role_unassigned $event) {
        self::audit($event, 'role');
    }
}
