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

namespace local_suap\task;

defined('MOODLE_INTERNAL') || die();

class sync_up_enrolments_task extends \core\task\adhoc_task {
    public function get_name() {
        return get_string('sync_up_enrolments_task', 'local_suap');
    }

    public function execute() {
        global $DB, $CFG;

        require_once($CFG->dirroot . "/local/suap/api/sync_up_enrolments.php");

        $customdata = $this->get_custom_data();
        if (empty($customdata->id)) {
            throw new \Exception("ID da solicitação não fornecido nos dados customizados da tarefa.");
        }

        $item = $DB->get_record('suap_enrolment_to_sync', ['id' => $customdata->id]);
        if (!$item) {
            throw new \Exception("Solicitação {$customdata->id} não encontrada no banco de dados.");
        }

        if ($item->processed == 1) {
            echo "Solicitação {$item->id} já foi processada com sucesso anteriormente.\n";
            return;
        }

        $start_time = microtime(true);
        echo "\n\nVou processar a solicitação {$item->id}.\n";
        try {
            $service = new \local_suap\sync_up_enrolments_service();
            $service->validate_json($item->json);
            $service->process(true);
            $item->processed = 1; // sucesso
            $DB->update_record('suap_enrolment_to_sync', $item);
            $elapsed_time = round(microtime(true) - $start_time, 2);
            echo "\nSolicitação {$item->id} processada com sucesso em {$elapsed_time} segundos.";
        } catch (\Throwable $e) {
            $elapsed_time = round(microtime(true) - $start_time, 2);

            $errormessage = $e->getMessage();
            if (isset($e->debuginfo)) {
                $errormessage .= "\nDebug info: " . $e->debuginfo;
            }

            echo "\nSolicitação {$item->id} processada com erro (\n" . $errormessage . "\n) em {$elapsed_time} segundos.";

            $item->processed = 2; // falha
            try {
                $DB->update_record('suap_enrolment_to_sync', $item);
            } catch (\Throwable $db_error) {
                echo "\nErro ao atualizar status da solicitação no banco: " . $db_error->getMessage();
            }

            throw $e; // Relança para o Moodle registrar a falha na tarefa adhoc
        }
    }
}
