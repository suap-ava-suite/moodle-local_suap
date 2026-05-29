<?php
namespace local_suap\task;

defined('MOODLE_INTERNAL') || die();

class sync_up_enrolments_task extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('sync_up_enrolments_task', 'local_suap');
    }

    public function execute() {
        global $DB, $CFG;

        require_once($CFG->dirroot . "/local/suap/api/sync_up_enrolments.php");

        $items = $DB->get_records_sql("SELECT * FROM {suap_enrolment_to_sync} WHERE processed = 0 ORDER BY id ASC ");
        echo "Encontrados " . count($items) . " itens para processar.\n";
        foreach ($items as $item) {
            $start_time = microtime(true);
            echo "\n\nVou processar a solicitação {$item->id}.\n";
            try {
                // throw new \Exception("Teste de erro para item ID {$item->id}");
                $service = new \local_suap\sync_up_enrolments_service();
                $service->validate_json($item->json);
                $service->process(true);
                $item->processed = 1; // sucesso
                $DB->update_record('suap_enrolment_to_sync', $item);
                $elapsed_time = round(microtime(true) - $start_time, 2);
                echo "\nSolicitação {$item->id} processada com sucesso em {$elapsed_time} segundos.";
            } catch (\Throwable $e) {
                $elapsed_time = round(microtime(true) - $start_time, 2);
                echo "\nSolicitação {$item->id} processada com erro (" . $e->getMessage() . ")e m {$elapsed_time} segundos.";
                $item->processed = 2; // falha
                $DB->update_record('suap_enrolment_to_sync', $item);   
            }
        }
        
        echo "\nSincronização concluída.\n";
    }
}
