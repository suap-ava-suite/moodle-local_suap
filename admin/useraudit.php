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
 * Consulta da auditoria de alterações em usuários.
 *
 * @package   local_suap
 * @copyright 2026 IFRN
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(\dirname(\dirname(\dirname(__DIR__))) . '/config.php');
require_once($CFG->libdir . '/adminlib.php');

$userid = optional_param('userid', 0, PARAM_INT);
$actor = optional_param('actor', '', PARAM_TEXT);
$from = optional_param('from', '', PARAM_TEXT);
$to = optional_param('to', '', PARAM_TEXT);
$page = optional_param('page', 0, PARAM_INT);
$perpage = 50;

require_login();
$context = context_system::instance();
require_capability('local/suap:viewuseraudit', $context);

$url = new moodle_url('/local/suap/admin/useraudit.php', [
    'userid' => $userid, 'actor' => $actor, 'from' => $from, 'to' => $to,
]);
$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(get_string('useraudit', 'local_suap'));
$PAGE->set_heading(get_string('useraudit', 'local_suap'));

$where = ['1 = 1'];
$params = [];
if ($userid) {
    $where[] = '(a.targetuserid = :userid OR a.actoruserid = :actoruserid)';
    $params['userid'] = $userid;
    $params['actoruserid'] = $userid;
}
if ($actor !== '') {
    $where[] = $DB->sql_like('a.actorlabel', ':actor', false);
    $params['actor'] = '%' . $DB->sql_like_escape($actor) . '%';
}
$fromts = $from !== '' ? strtotime($from . ' 00:00:00') : false;
if ($fromts) {
    $where[] = 'a.timecreated >= :fromts';
    $params['fromts'] = $fromts;
}
$tots = $to !== '' ? strtotime($to . ' 23:59:59') : false;
if ($tots) {
    $where[] = 'a.timecreated <= :tots';
    $params['tots'] = $tots;
}
$wheresql = implode(' AND ', $where);

$total = $DB->count_records_sql("SELECT COUNT(1) FROM {local_suap_user_audit} a WHERE $wheresql", $params);
$records = $DB->get_records_sql(
    "SELECT a.* FROM {local_suap_user_audit} a WHERE $wheresql ORDER BY a.timecreated DESC, a.id DESC",
    $params,
    $page * $perpage,
    $perpage
);

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('useraudit', 'local_suap'));

echo html_writer::start_tag('form', ['method' => 'get', 'action' => (new moodle_url('/local/suap/admin/useraudit.php'))->out(false), 'class' => 'form-inline mb-3']);
$fields = [
    'userid' => ['audit_filter_user', $userid ?: '', 'number'],
    'actor' => ['audit_filter_actor', $actor, 'text'],
    'from' => ['audit_filter_from', $from, 'text'],
    'to' => ['audit_filter_to', $to, 'text'],
];
foreach ($fields as $name => [$label, $value, $type]) {
    echo html_writer::tag('label', get_string($label, 'local_suap'), ['for' => "id_$name", 'class' => 'mr-1']);
    echo html_writer::empty_tag('input', [
        'type' => $type, 'name' => $name, 'id' => "id_$name", 'value' => $value, 'class' => 'form-control mr-3',
    ]);
}
echo html_writer::empty_tag('input', [
    'type' => 'submit', 'class' => 'btn btn-primary', 'value' => get_string('audit_filter_apply', 'local_suap'),
]);
echo html_writer::end_tag('form');

if (!$records) {
    echo $OUTPUT->notification(get_string('audit_empty', 'local_suap'), 'info');
} else {
    $table = new html_table();
    $table->head = array_map(
        fn($k) => get_string($k, 'local_suap'),
        ['audit_time', 'audit_target', 'audit_action', 'audit_field', 'audit_old', 'audit_new', 'audit_actor', 'audit_origin', 'audit_request']
    );
    foreach ($records as $r) {
        $actorcell = s($r->actorlabel) . ($r->actoruserid ? ' (#' . (int) $r->actoruserid . ')' : '');
        $table->data[] = [
            userdate($r->timecreated, '%Y-%m-%d %H:%M:%S'),
            html_writer::link(new moodle_url('/user/profile.php', ['id' => $r->targetuserid]), '#' . (int) $r->targetuserid),
            s($r->action),
            s((string) $r->field),
            s((string) $r->oldvalue),
            s((string) $r->newvalue),
            $actorcell,
            s($r->origin) . ($r->ip ? ' ' . s($r->ip) : ''),
            s((string) $r->requestref),
        ];
    }
    echo html_writer::table($table);
    echo $OUTPUT->paging_bar($total, $page, $perpage, $url);
}

echo $OUTPUT->footer();
