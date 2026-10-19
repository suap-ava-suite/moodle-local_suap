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
 * Remove registros de auditoria de usuários além do período de retenção.
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_suap\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Tarefa agendada de retenção da auditoria.
 */
class purge_user_audit_task extends \core\task\scheduled_task {
    /**
     * Nome da tarefa.
     *
     * @return string
     */
    public function get_name() {
        return get_string('purge_user_audit_task', 'local_suap');
    }

    /**
     * Executa a limpeza.
     */
    public function execute() {
        $months = (int) get_config('local_suap', 'user_audit_retention_months');
        if ($months <= 0) {
            $months = 24;
        }
        \local_suap\audit\recorder::purge_older_than($months);
    }
}
