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

Eventos observados e auditoria de usuários
------------------------------------------

``classes/observer.php`` (``local_suap_observer``) está registrado em ``db/events.php`` para os eventos
``\core\event\user_enrolment_*``, ``user_created``, ``user_updated``, ``user_deleted``, ``cohort_member_added``,
``cohort_member_removed``, ``role_assigned`` e ``role_unassigned``. Cada evento gera uma linha na tabela
``local_suap_user_audit`` (trilha de auditoria): **quem** (``actoruserid`` e ``actorlabel``), **quando**
(``timecreated``), **o quê** (``targetuserid``, ``action``, ``field``, ``oldvalue``, ``newvalue``), **de onde**
(``origin``, ``ip``) e a requisição de origem (``requestref``, ex.: ``suap_enrolment_to_sync:123``).

Como o ator é identificado:

* Moodle web: ``actoruserid`` é o usuário logado e ``actorlabel`` é ``web``.
* APIs com token (SUAP, SGA, Painel AVA): não há usuário Moodle (``actoruserid`` 0); ``actorlabel`` identifica o
  sistema (``api:suap``, ``api:sga``, ``api:painelava``) e ``requestref`` aponta para a solicitação gravada.
* Login SUAP: ``login:suap``. Tarefas agendadas/adhoc: ``task:<nome>``. Linha de comando: ``cli``.

As escritas feitas pelos plugins passam por ``\local_suap\audit\writer``, que compara o estado antes e depois e
grava um registro por campo alterado (inclusive ``profile_field_*`` e preferências, que o Moodle não registra).
Campos sensíveis (senha, CPF, RG, nome da mãe, JSON de ``last_login``) são gravados mascarados (``***``).

Consulta: *Administração do site → Relatórios → Auditoria de alterações de usuários*
(``/local/suap/admin/useraudit.php``, capacidade ``local/suap:viewuseraudit``, concedida ao papel gerente).
Retenção: configuração ``user_audit_retention_months`` (padrão 24 meses), aplicada pela tarefa agendada
``purge_user_audit_task``. A tabela é coberta pelo provedor de privacidade do plugin (exportação e exclusão).
