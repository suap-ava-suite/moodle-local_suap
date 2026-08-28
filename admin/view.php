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

namespace local_suap\admin;

require_once(\dirname(\dirname(\dirname(__DIR__))) . '/config.php');

$PAGE->set_url(new \moodle_url('/local/suap/admin/view.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('admin_title', 'local_suap') . ' :: ' . get_string('viewing_integration', 'local_suap'));

if (!is_siteadmin()) {
    echo $OUTPUT->header();
    echo get_string('unauthorized_access', 'local_suap');
    echo $OUTPUT->footer();
    die();
}

echo $OUTPUT->header();
$linha = $DB->get_record("suap_enrolment_to_sync", ['id' => required_param('id', PARAM_INT)]);
$statuses = [
    0 => get_string('status_unprocessed', 'local_suap'),
    1 => get_string('status_success', 'local_suap'),
    2 => get_string('status_failed', 'local_suap'),
];
$json = json_decode($linha->json ?? "{}");
$linha->status = $statuses[$linha->processed] ?? get_string('status_unknown', 'local_suap');
$linha->solicitacao_url = is_object($json) ? ($json->solicitacao_url ?? null) : null;
$linha->json = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
$linha->log_url = new \moodle_url('/local/suap/admin/tasklogs.php', ['requestid' => $linha->id]);
$templatecontext = ['linha' => $linha];
echo $OUTPUT->render_from_template('local_suap/view', $templatecontext);
echo $OUTPUT->footer();
