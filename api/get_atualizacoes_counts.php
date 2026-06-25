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

require_once('../locallib.php');
require_once("servicelib.php");

class get_atualizacoes_counts_service extends \local_suap\service
{
    function do_call() {
        global $DB, $USER;
        $username = strtolower($_GET['username']);
        $USER = $DB->get_record('user', ['username' => $username]);
        if ($USER) {
            return $this->get_atualizacoes_counts($USER->id);
        } else {
            return [
                'error' => ['message' => "Usuário '{$username}' não existe", 'code' => 404],
                'unread_conversations_count' => 0,
                'unread_popup_notification_count' => 0,
            ];
        }
    }

    function get_atualizacoes_counts($useridto) {
        return [
            "unread_conversations_count" => \core_message_external::get_unread_conversations_count($useridto),
            "unread_popup_notification_count" => \message_popup_external::get_unread_popup_notification_count($useridto),
        ];
    }
}
