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

$PAGE->set_url(new \moodle_url('/local/suap/admin/index.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('admin_title', 'local_suap'));


if (!is_siteadmin()) {
    echo $OUTPUT->header();
    echo get_string('unauthorized_access', 'local_suap');
    echo $OUTPUT->footer();
    die();
}

echo $OUTPUT->header();

$ordenacao = isset($_GET['ordenacao']) ? $_GET['ordenacao'] : 'DESC';

// Número de itens por página
$itensPorPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Consulta SQL personalizada para buscar os registros com LIMIT e OFFSET
$sql = "
    SELECT id, timecreated, processed
    FROM {suap_enrolment_to_sync}
    ORDER BY id $ordenacao, timecreated $ordenacao, processed $ordenacao
    LIMIT :limit OFFSET :offset
";

$params = [
    'limit' => $itensPorPagina,
    'offset' => ($paginaAtual - 1) * $itensPorPagina,
];
$registros = $DB->get_records_sql($sql, $params);

$statuses = [
    0 => get_string('status_unprocessed', 'local_suap'),
    1 => get_string('status_success', 'local_suap'),
    2 => get_string('status_failed', 'local_suap'),
];
foreach ($registros as $key => $value) {
    $value->status = $statuses[$value->processed] ?? get_string('status_unknown', 'local_suap');
}

// Consulta SQL para contar o total de registros
$sqlTotalRegistros = "SELECT COUNT(*) as total FROM {suap_enrolment_to_sync}";

$totalRegistros = $DB->get_field_sql($sqlTotalRegistros);

$numeroTotalDePaginas = ceil($totalRegistros / $itensPorPagina);

$primeirasPaginas = 5;
$ultimasPaginas = 3;

$paginaInicio = max(2, $paginaAtual - floor($primeirasPaginas / 2));
$paginaFim = $paginaInicio + $primeirasPaginas - 1;

$registrosPaginaAtual = array_slice($registros, 0, $itensPorPagina);

// verifica o numero total de páginas com o range de paginação, para delimitar um fim para a paginação, caso outras páginas sejam clicadas
if (in_array($numeroTotalDePaginas, range($paginaInicio, $paginaFim))) {
    $primeirosCinco = range($paginaInicio, $numeroTotalDePaginas);
} else {
    $primeirosCinco = range($paginaInicio, $paginaFim);
}

$ultimosTres = range($numeroTotalDePaginas, $numeroTotalDePaginas);

 $paginacaoVariada = [];

// Verifica se tem mais de 13 páginas. Se tiver, irá acrescentar a lógica de aparecer as 3 ultimas.
if ($numeroTotalDePaginas < $primeirasPaginas + $ultimasPaginas) {
    $paginacaoVariada = range($paginaInicio, $paginaFim);
} else {
    if ($paginaAtual < $numeroTotalDePaginas - 3 && $paginaAtual >= 5) {
        echo("TO AQUI");
        $mergeUnique = array_unique(array_merge($primeirosCinco, ['...'], $ultimosTres));
        $paginacaoVariada = array_merge(['...'], $mergeUnique);
    } else if ($paginaAtual < $numeroTotalDePaginas - 3) {
        $mergeUnique = array_unique(array_merge($primeirosCinco, ['...'], $ultimosTres));
        $paginacaoVariada = array_merge($mergeUnique);
    } else if ($paginaAtual >= 5) {
        $mergeUnique = array_unique(array_merge($primeirosCinco, $ultimosTres));
        $paginacaoVariada = array_merge(['...'], $mergeUnique);
    } else {
        $paginacaoVariada = array_unique(array_merge($primeirosCinco, $ultimosTres));
    }
}

$templatecontext = [
    'linhas' => $registrosPaginaAtual,
    'paginas' => $paginacaoVariada,
];

echo $OUTPUT->render_from_template('local_suap/index', $templatecontext);
echo $OUTPUT->footer();
