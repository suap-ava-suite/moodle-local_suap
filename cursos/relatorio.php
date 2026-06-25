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

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();
require_capability('local/suap:view_mooc_reports', context_system::instance());

$PAGE->set_url(new moodle_url('/local/suap/cursos/relatorio.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('generate_report_task', 'local_suap'));
$PAGE->set_heading(get_string('generate_report_task', 'local_suap'));

echo $OUTPUT->header();

// Criar o renderable que busca os dados
$relatorio = new \local_suap\output\relatorio_page();

// Renderizar usando o renderer
$renderer = $PAGE->get_renderer('local_suap');
echo $renderer->render($relatorio);

echo $OUTPUT->footer();
