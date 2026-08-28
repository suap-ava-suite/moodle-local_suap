Administração
==============

Este plugin adiciona páginas próprias (fora do fluxo padrão de administração do Moodle) para acompanhar
sincronizações e consultar relatórios. Nenhuma delas está registrada em um menu de navegação do Moodle — o acesso
é sempre pela URL direta.

Histórico de sincronizações
--------------------------------

Toda chamada recebida por ``sync_up_enrolments`` (veja :doc:`api` e :doc:`sincronizacao`) é gravada na tabela
``suap_enrolment_to_sync`` antes de ser processada, com o JSON recebido, o horário e o status de processamento.

``/local/suap/admin/index.php``
   Lista paginada (10 itens por página) das solicitações recebidas, com ID, data de criação e status
   (**Não processado**, **Sucesso** ou **Falha**). Exige ``is_siteadmin()``.

``/local/suap/admin/view.php?id=<id>``
   Detalha uma solicitação: status, o JSON recebido (formatado) e um link para os logs da tarefa que a processou.
   Exige ``is_siteadmin()``. É o link retornado pela própria API em
   ``sincronizacao_url`` na resposta de ``sync_up_enrolments`` — veja :doc:`sincronizacao`.

``/local/suap/admin/tasklogs.php?requestid=<id>``
   Busca, em ``mdl_task_log``, as execuções da tarefa ``local_suap\task\sync_up_enrolments_task`` cujo *output*
   contenha a string "Vou processar a solicitação ``<id>``.", e lista início, fim, resultado, hostname e PID de
   cada execução, com link para o log completo em ``/admin/tasklogs.php``. Exige ``require_login()`` +
   ``is_siteadmin()``.

.. warning::
   As três páginas acima verificam apenas ``is_siteadmin()``, não a capability ``local/suap:adminview`` definida
   em ``db/access.php`` — mesmo que essa capability seja concedida a outros papéis, apenas administradores de
   site conseguem acessá-las hoje.

Tarefa assíncrona de sincronização
----------------------------------------

``local_suap\task\sync_up_enrolments_task`` (tarefa **adhoc**, não agendada por cron) processa em segundo plano
uma solicitação previamente gravada: valida o JSON, executa ``sync_up_enrolments_service::process(true)``, e marca
o registro como processado (``processed = 1``) ou com falha (``processed = 2``), relançando a exceção para que o
Moodle registre a falha no log de tarefas. Ela é enfileirada automaticamente por
``api/sync_up_enrolments.php`` a cada chamada recebida — não é preciso acioná-la manualmente.

Relatório de cursos autoinstrucionais
--------------------------------------------

``/local/suap/cursos/relatorio.php``
   Exibe estatísticas (inscritos, acessos, aprovação, certificados emitidos, conclusão) agregadas por curso e
   campus para salas classificadas como ``minicurso`` no campo customizado ``diario_tipo``. Exige
   ``require_login()`` + a capability ``local/suap:view_mooc_reports``.

Os dados são pré-calculados pela tarefa **agendada** (cron) ``local_suap\task\generate_report_task``
(``db/tasks.php``), configurada para rodar diariamente às 2h. A cada execução ela:

* consulta cursos visíveis, com ``diario_tipo = 'minicurso'`` e dentro da janela ``startdate``/``enddate``;
* agrupa por nome de curso + campus e calcula, por grupo: inscritos, acessos, quem nunca acessou, quantos
  fizeram a última atividade avaliativa do curso, aprovados, reprovados, média das notas, com/sem certificado
  emitido (via ``tool_certificate_issues``, se o plugin de certificados estiver instalado) e concluintes;
* substitui integralmente o conteúdo da tabela ``local_suap_relatorio_cursos_autoinstrucionais`` a cada execução
  (``DELETE`` seguido de novos ``INSERT``).

A página lê sempre o lote mais recente (maior ``timegenerated``) dessa tabela — não recalcula nada em tempo real.

.. note::
   O critério de "aprovado com certificado elegível" usa
   ``max(0, min(passed, final_exam_takers) - with_certificate)`` como estimativa de quem passou mas ainda não
   emitiu certificado, e usa essa mesma estimativa como *fallback* para "concluído" quando a contagem direta de
   ``course_completions`` retorna zero. Trate os números de certificado/conclusão desse relatório como uma
   aproximação, não como contagem exata quando as duas fontes divergem.

Verificação de saúde (health check)
------------------------------------------

Três mecanismos distintos existem no repositório, com propósitos diferentes:

.. list-table::
   :header-rows: 1
   :widths: 30 15 55

   * - Endpoint
     - Autenticação
     - Finalidade
   * - ``/local/suap/healthcheck.php``
     - Nenhuma
     - Retorna ``{"component", "release", "version"}`` do plugin, lendo ``version.php`` diretamente. Pensado para
       *health checks* de infraestrutura (load balancer, orquestrador).
   * - ``/local/suap/api/?health``
     - Token (``Authentication: Token <auth_token>``)
     - Serviço da API (veja :doc:`api`) que retorna versão do plugin **e** do Moodle, validando também que o
       token de autenticação está correto.
   * - ``/local/suap/health.php``
     - **Nenhuma**
     - Script de diagnóstico legado que expõe hostname, versão do PHP, opções de conexão com o banco, caminho do
       ``wwwroot``, classe de sessão e configuração de proxy/SSL. Veja o aviso em :doc:`instalacao` — não possui
       ``require_login()`` nem qualquer outro controle de acesso no código-fonte atual.

Eventos observados
-----------------------

``classes/observer.php`` (``local_suap_observer``) está registrado em ``db/events.php`` para os eventos
``\core\event\user_enrolment_created``, ``user_enrolment_deleted``, ``user_enrolment_updated``, ``user_created``,
``user_deleted`` e ``user_updated``.

.. note::
   No código-fonte atual, todos os métodos desse *observer* estão vazios (ou com a lógica antiga comentada, como
   em ``user_created()``, que já chegou a aplicar ``default_user_preferences`` neste ponto). Os observadores estão
   registrados, mas não produzem nenhum efeito colateral hoje — a aplicação de ``default_user_preferences``
   acontece apenas em ``sync_user()`` (dentro de ``sync_up_enrolments``), não por estes eventos.
