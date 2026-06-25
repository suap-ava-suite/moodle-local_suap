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

// Desabilita verificação CSRF para esta API
if (!defined('NO_MOODLE_COOKIES')) {
    define('NO_MOODLE_COOKIES', true);
}

require_once('../../../config.php');
require_once('../locallib.php');
require_once("servicelib.php");

class set_user_preference_service extends \local_suap\service
{
    function do_call() {
        global $DB, $USER;

        // 🔍 Buscar usuário pelo username informado
        $username = optional_param('username', null, PARAM_USERNAME);
        if ($username === null) {
            throw new \Exception("Parâmetro 'username' é obrigatório", 400);
        }

        $USER = $DB->get_record('user', ['username' => strtolower($_GET['username'])]);
        if (!$USER) {
            throw new \Exception('Usuário não encontrado.', 404);
        }

        // 🧰 Pega os parâmetros enviados
        $name = optional_param('name', null, PARAM_ALPHANUMEXT);
        $value = optional_param('value', null, PARAM_RAW);

        if ($name === null || $value === null) {
            throw new \Exception("Parâmetros 'name' e 'value' são obrigatórios", 400);
        }

        // ✅ Salva a preferência usando a API oficial
        if (in_array($value, [true, 'true', 1, '1'], true)) {
            $value = '1';
        } else if (in_array($value, [false, 'false', 0, '0'], true)) {
            $value = '0';
        } else if (is_numeric($value)) {
            $value = (string)intval($value);
        } else {
            $value = (string)$value;
        }
        set_user_preference($name, $value, $USER->id);

        // 📬 Retorna uma resposta simples em JSON
        return [
            'error' => false,
            'message' => 'Preferência atualizada com sucesso',
            'user' => [
                'id' => $USER->id,
                'username' => $USER->username,
                'fullname' => fullname($USER),
            ],
            'preference' => [
                'name' => $name,
                'value' => $value,
            ],
        ];
    }
}
