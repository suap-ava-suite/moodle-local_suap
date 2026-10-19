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
 * Escritas de usuário com registro de diff (antes/depois).
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_suap\audit;

defined('MOODLE_INTERNAL') || die();

require_once($GLOBALS['CFG']->dirroot . '/user/lib.php');
require_once($GLOBALS['CFG']->dirroot . '/user/profile/lib.php');

/**
 * Envolve as funções core de escrita e grava as diferenças em local_suap_user_audit.
 *
 * Use no lugar de user_update_user, profile_save_custom_fields, set_user_preference e
 * escritas diretas em {user}, para que o diff seja registrado.
 */
class writer {
    /**
     * Campos de {user} comparados.
     *
     * @param \stdClass $before Estado anterior.
     * @param \stdClass $changes Valores informados.
     * @return array<string,array{0:mixed,1:mixed}> campo => [antes, depois]
     */
    private static function diff_user(\stdClass $before, \stdClass $changes): array {
        $diff = [];
        foreach ((array) $changes as $field => $value) {
            if ($field === 'id' || !property_exists($before, $field)) {
                continue;
            }
            if ((string) $before->$field !== (string) $value) {
                $diff[$field] = [$before->$field, $value];
            }
        }
        return $diff;
    }

    /**
     * Ação de auditoria conforme o campo alterado.
     *
     * @param string $field Campo.
     * @param mixed $new Valor novo.
     * @return string
     */
    private static function action_for(string $field, $new): string {
        switch ($field) {
            case 'suspended':
                return (int) $new ? 'suspend' : 'unsuspend';
            case 'deleted':
                return 'delete';
            case 'auth':
                return 'auth_change';
            case 'picture':
                return 'picture';
            default:
                return 'update';
        }
    }

    /**
     * Equivalente a user_create_user() com registro.
     *
     * @param \stdClass $user Dados do usuário.
     * @param bool $updatepassword Atualizar senha.
     * @param bool $triggerevent Disparar evento core.
     * @return int Id do usuário.
     */
    public static function create_user(\stdClass $user, bool $updatepassword = true, bool $triggerevent = true): int {
        $id = user_create_user($user, $updatepassword, $triggerevent);
        recorder::record($id, 'create', 'username', null, $user->username ?? null, 'local_suap\audit\writer::create_user');
        context::mark_logged($id, 'create');
        return $id;
    }

    /**
     * Equivalente a user_update_user() com diff por campo.
     *
     * @param \stdClass $user Campos a atualizar (com id).
     * @param bool $updatepassword Atualizar senha.
     * @param bool $triggerevent Disparar evento core.
     * @return array<string,array{0:mixed,1:mixed}> Diff aplicado.
     */
    public static function update_user(\stdClass $user, bool $updatepassword = true, bool $triggerevent = true): array {
        global $DB;
        $before = $DB->get_record('user', ['id' => $user->id], '*', MUST_EXIST);
        $diff = self::diff_user($before, $user);
        user_update_user($user, $updatepassword, $triggerevent);
        foreach ($diff as $field => [$old, $new]) {
            recorder::record((int) $user->id, self::action_for($field, $new), $field, $old, $new);
        }
        if ($diff) {
            context::mark_logged((int) $user->id, 'update');
        }
        return $diff;
    }

    /**
     * Atualização direta de um campo de {user} (sem evento core), com registro.
     *
     * @param int $userid Usuário.
     * @param string $field Campo.
     * @param mixed $value Valor novo.
     * @return bool True se houve mudança.
     */
    public static function set_user_field(int $userid, string $field, $value): bool {
        global $DB;
        $old = $DB->get_field('user', $field, ['id' => $userid], MUST_EXIST);
        if ((string) $old === (string) $value) {
            return false;
        }
        $DB->set_field('user', $field, $value, ['id' => $userid]);
        recorder::record($userid, self::action_for($field, $value), $field, $old, $value);
        return true;
    }

    /**
     * Equivalente a profile_save_custom_fields() com diff por campo.
     *
     * @param int $userid Usuário.
     * @param array $profilefields Mapa shortname => valor.
     * @return void
     */
    public static function save_profile_fields(int $userid, array $profilefields): void {
        global $DB;
        $old = [];
        foreach (array_keys($profilefields) as $shortname) {
            $fieldid = $DB->get_field('user_info_field', 'id', ['shortname' => $shortname]);
            $old[$shortname] = $fieldid
                ? $DB->get_field('user_info_data', 'data', ['userid' => $userid, 'fieldid' => $fieldid])
                : null;
        }
        profile_save_custom_fields($userid, $profilefields);
        foreach ($profilefields as $shortname => $value) {
            $previous = $old[$shortname] === false ? null : $old[$shortname];
            if ((string) $previous !== (string) $value) {
                recorder::record($userid, 'profile', 'profile_field_' . $shortname, $previous, $value);
            }
        }
    }

    /**
     * Foto do estado atual do usuário: campos de {user} e campos de perfil (profile_field_*).
     *
     * @param int $userid Usuário.
     * @return array<string,mixed>
     */
    public static function snapshot_user(int $userid): array {
        global $DB;
        $user = $DB->get_record('user', ['id' => $userid]);
        $snapshot = $user ? (array) $user : [];
        foreach (['timemodified', 'currentlogin', 'lastlogin', 'lastaccess', 'lastip'] as $volatile) {
            unset($snapshot[$volatile]);
        }
        $sql = 'SELECT f.shortname, d.data FROM {user_info_data} d JOIN {user_info_field} f ON f.id = d.fieldid
                 WHERE d.userid = :userid';
        foreach ($DB->get_records_sql($sql, ['userid' => $userid]) as $row) {
            $snapshot['profile_field_' . $row->shortname] = $row->data;
        }
        return $snapshot;
    }

    /**
     * Grava as diferenças entre duas fotos (ver snapshot_user) feitas em volta de uma escrita opaca.
     *
     * @param int $userid Usuário.
     * @param array $before Foto anterior.
     * @param array $after Foto posterior.
     * @return int Quantidade de campos alterados.
     */
    public static function record_diff(int $userid, array $before, array $after): int {
        $count = 0;
        foreach ($after as $field => $new) {
            $old = $before[$field] ?? null;
            if ((string) $old === (string) $new) {
                continue;
            }
            $action = strpos($field, 'profile_field_') === 0 ? 'profile' : self::action_for($field, $new);
            recorder::record($userid, $action, $field, $old, $new);
            $count++;
        }
        if ($count) {
            context::mark_logged($userid, 'update');
        }
        return $count;
    }

    /**
     * Equivalente a set_user_preference() com registro.
     *
     * @param string $name Nome da preferência.
     * @param mixed $value Valor.
     * @param \stdClass|int $user Usuário ou id.
     * @return bool True se gravou.
     */
    public static function set_preference(string $name, $value, $user): bool {
        $userid = is_object($user) ? (int) $user->id : (int) $user;
        $old = get_user_preferences($name, null, $userid);
        if ($old !== null && (string) $old === (string) $value) {
            return false;
        }
        $result = set_user_preference($name, $value, $user);
        if ($result) {
            recorder::record($userid, 'pref', 'preference:' . $name, $old, $value);
        }
        return $result;
    }
}
