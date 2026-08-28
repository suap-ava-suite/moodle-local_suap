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
    echo get_string('unauthorized_access', 'local_suap');
    echo $OUTPUT->footer();
    die();
}

$PAGE->set_url(new moodle_url('/local/suap/tasklogs_by_request.php', ['requestid' => $requestid]));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('logs', 'local_suap'));
$PAGE->set_heading(get_string('logs', 'local_suap'));

echo $OUTPUT->header();

$request = $DB->get_record('suap_enrolment_to_sync', ['id' => $requestid], '*', IGNORE_MISSING);
if (!$request) {
    echo $OUTPUT->notification(get_string('request_not_found', 'local_suap'), 'error');
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

echo html_writer::tag('h2', get_string('request_label', 'local_suap') . (int)$request->id);
echo html_writer::tag('p', get_string('search_label', 'local_suap') . s($needle));

if (!$logs) {
    echo $OUTPUT->notification(get_string('no_task_logs_found', 'local_suap'), 'warning');
    echo $OUTPUT->footer();
    exit;
}

$table = new html_table();
$table->head = [
    get_string('log_id', 'local_suap'),
    get_string('start_time', 'local_suap'),
    get_string('end_time', 'local_suap'),
    get_string('result', 'local_suap'),
    get_string('hostname', 'local_suap'),
    get_string('pid', 'local_suap'),
    get_string('action', 'local_suap'),
];
$table->data = [];

foreach ($logs as $log) {
    $table->data[] = [
        (int)$log->id,
        userdate((int)$log->timestart),
        $log->timeend ? userdate((int)$log->timeend) : '-',
        (int)$log->result === 0 ? get_string('status_success', 'local_suap') : ((int)$log->result === 1 ? get_string('status_failed', 'local_suap') : get_string('status_unknown', 'local_suap')),
        s((string)$log->hostname),
        $log->pid ? (int)$log->pid : '-',
        html_writer::link(new moodle_url('/admin/tasklogs.php', ['logid' => $log->id]), get_string('open', 'local_suap')),
    ];
}

echo html_writer::table($table);
echo $OUTPUT->footer();
