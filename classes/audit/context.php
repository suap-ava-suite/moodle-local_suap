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
 * Contexto do ator das alterações (quem/qual sistema está chamando).
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_suap\audit;

defined('MOODLE_INTERNAL') || die();

/**
 * Guarda, durante a requisição/task, o rótulo do ator e a referência da requisição.
 */
class context {
    /** @var string|null Rótulo do ator, ex.: api:suap, login:suap, task:sync_up_enrolments. */
    private static ?string $actorlabel = null;
    /** @var string|null Referência da requisição, ex.: suap_enrolment_to_sync:123. */
    private static ?string $requestref = null;
    /** @var array<string,bool> Alterações já registradas pelo writer (evita duplicar no observer). */
    private static array $logged = [];

    /**
     * Define o ator da requisição atual.
     *
     * @param string $actorlabel Rótulo do ator.
     * @param string|null $requestref Referência da requisição.
     */
    public static function set(string $actorlabel, ?string $requestref = null): void {
        self::$actorlabel = $actorlabel;
        self::$requestref = $requestref;
    }

    /**
     * Limpa o contexto (usado em testes e ao final de tasks).
     */
    public static function reset(): void {
        self::$actorlabel = null;
        self::$requestref = null;
        self::$logged = [];
    }

    /**
     * Rótulo do ator: o definido, ou deduzido do ambiente.
     *
     * @return string
     */
    public static function get_actorlabel(): string {
        if (self::$actorlabel !== null) {
            return self::$actorlabel;
        }
        if (CLI_SCRIPT) {
            return 'cli';
        }
        return 'web';
    }

    /**
     * Referência da requisição.
     *
     * @return string|null
     */
    public static function get_requestref(): ?string {
        return self::$requestref;
    }

    /**
     * Marca que o writer já registrou esta ação para o usuário.
     *
     * @param int $userid Usuário alvo.
     * @param string $action Ação.
     */
    public static function mark_logged(int $userid, string $action): void {
        self::$logged["$userid:$action"] = true;
    }

    /**
     * Informa se o writer já registrou esta ação para o usuário.
     *
     * @param int $userid Usuário alvo.
     * @param string $action Ação.
     * @return bool
     */
    public static function was_logged(int $userid, string $action): bool {
        return !empty(self::$logged["$userid:$action"]);
    }
}
