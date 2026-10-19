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
 * Gravação de registros de auditoria de usuários.
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_suap\audit;

defined('MOODLE_INTERNAL') || die();

/**
 * Grava linhas em local_suap_user_audit.
 */
class recorder {
    /** Tabela de auditoria. */
    const TABLE = 'local_suap_user_audit';
    /** Marcador no lugar de valores sensíveis. */
    const MASK = '***';

    /**
     * Campos cujo valor nunca é gravado em texto.
     *
     * @param string $field Nome do campo.
     * @return bool
     */
    public static function is_sensitive(string $field): bool {
        return (bool) preg_match(
            '/^(password|secret|token|profile_field_(cpf|rg|mae|nome_mae|passaporte|last_login|data_nascimento))$/i',
            $field
        );
    }

    /**
     * Converte um valor para texto gravável (mascarado se sensível).
     *
     * @param string $field Nome do campo.
     * @param mixed $value Valor.
     * @return string|null
     */
    public static function normalize(string $field, $value): ?string {
        if ($value === null) {
            return null;
        }
        if (self::is_sensitive($field)) {
            return self::MASK;
        }
        $text = is_scalar($value) ? (string) $value : json_encode($value, JSON_UNESCAPED_UNICODE);
        return \core_text::substr($text, 0, 1000);
    }

    /**
     * Registra uma alteração.
     *
     * @param int $targetuserid Usuário alterado.
     * @param string $action create, update, delete, suspend, unsuspend, auth_change, profile, pref, picture, enrol, cohort, role.
     * @param string|null $field Campo alterado.
     * @param mixed $old Valor anterior.
     * @param mixed $new Valor novo.
     * @param string|null $eventname Evento core de origem.
     * @param int|null $actoruserid Ator; padrão o usuário logado.
     * @return int Id do registro.
     */
    public static function record(
        int $targetuserid,
        string $action,
        ?string $field = null,
        $old = null,
        $new = null,
        ?string $eventname = null,
        ?int $actoruserid = null
    ): int {
        global $DB, $USER;

        if ($actoruserid === null) {
            $actoruserid = (int) ($USER->id ?? 0);
        }
        $row = (object) [
            'timecreated' => time(),
            'targetuserid' => $targetuserid,
            'action' => $action,
            'field' => $field,
            'oldvalue' => $field === null ? null : self::normalize($field, $old),
            'newvalue' => $field === null ? null : self::normalize($field, $new),
            'actoruserid' => $actoruserid,
            'actorlabel' => context::get_actorlabel(),
            'origin' => CLI_SCRIPT ? 'cli' : 'web',
            'ip' => CLI_SCRIPT ? null : \core_text::substr((string) getremoteaddr(null), 0, 45),
            'requestref' => context::get_requestref(),
            'eventname' => $eventname,
        ];
        return $DB->insert_record(self::TABLE, $row);
    }

    /**
     * Remove registros anteriores a N meses.
     *
     * @param int $months Retenção em meses.
     * @return void
     */
    public static function purge_older_than(int $months): void {
        global $DB;
        $limit = strtotime("-{$months} months");
        $DB->delete_records_select(self::TABLE, 'timecreated < :limit', ['limit' => $limit]);
    }
}
