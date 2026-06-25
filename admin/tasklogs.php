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

require_once(\dirname(\dirname(\dirname(__DIR__))) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');

$requestid = required_param('requestid', PARAM_INT);

require_login();
if (!is_siteadmin()) {
    echo $OUTPUT->header();
    echo "Fazes o quê aqui?";
    echo $OUTPUT->footer();
    die();
}

$PAGE->set_url(new moodle_url('/local/suap/tasklogs_by_request.php', ['requestid' => $requestid]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title('Task logs');
$PAGE->set_heading('Task logs');

echo $OUTPUT->header();

$request = $DB->get_record('suap_enrolment_to_sync', ['id' => $requestid], '*', IGNORE_MISSING);
if (!$request) {
    echo $OUTPUT->notification('Solicitação não encontrada.', 'error');
    echo $OUTPUT->footer();
    exit;
}

$classname = 'local_suap\task\sync_up_enrolments_task';
$needle = 'Vou processar a solicitação ' . $requestid . '.';

$sql = "SELECT id, timestart, timeend, result, output, userid, hostname, pid
          FROM {task_log}
         WHERE classname = :classname
           AND output LIKE :needle
      ORDER BY timestart DESC";

$logs = $DB->get_records_sql($sql, [
    'classname' => $classname,
    'needle' => '%' . $needle . '%',
]);

echo html_writer::tag('h2', 'Solicitação #' . (int)$request->id);
echo html_writer::tag('p', 'Busca: ' . s($needle));

if (!$logs) {
    echo $OUTPUT->notification('Nenhum task log encontrado para esta solicitação.', 'warning');
    echo $OUTPUT->footer();
    exit;
}

$table = new html_table();
$table->head = ['Log ID', 'Início', 'Fim', 'Resultado', 'Hostname', 'PID', 'Ação'];
$table->data = [];

foreach ($logs as $log) {
    $table->data[] = [
        (int)$log->id,
        userdate((int)$log->timestart),
        $log->timeend ? userdate((int)$log->timeend) : '-',
        (int)$log->result === 0 ? 'Sucesso' : ((int)$log->result === 1 ? 'Falha' : 'Desconecido'),
        s((string)$log->hostname),
        $log->pid ? (int)$log->pid : '-',
        html_writer::link(new moodle_url('/admin/tasklogs.php', ['logid' => $log->id]), 'Abrir'),
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();
