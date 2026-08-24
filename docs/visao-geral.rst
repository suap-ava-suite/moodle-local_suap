Visão geral
===========

O que o plugin faz
-------------------

``local_suap`` é um plugin do tipo ``local`` para o Moodle. Ele é o lado "de dentro do Moodle" da integração entre
o SUAP Edu (sistema de gestão acadêmica do IFRN) e o Moodle: recebe, via uma API HTTP própria, estruturas de curso,
matrícula, coorte e nota vindas do SUAP (tipicamente através do Integrador AVA) e as aplica no Moodle, criando ou
atualizando:

* categorias de curso (hierarquia Diários → Campus → Curso → Semestre → Turma);
* cursos/salas (diários, salas de coordenação, autoinscrição, práticas e modelos);
* usuários (alunos, docentes e equipe de apoio) e dezenas de campos de perfil customizados;
* coortes (grupos globais) e seus membros;
* inscrições (enrolments) e papéis (roles), inclusive suspensão automática de alunos que saem da lista oficial;
* grupos dentro de cada sala (por entrada, turma, polo e programa).

Além da sincronização "de cima para baixo" (SUAP → Moodle), o plugin expõe endpoints para o sentido inverso: envio
de notas do quadro de notas do Moodle de volta para o SUAP (``sync_down_grades``) e sincronização de preferências
de usuário entre o Moodle e o Painel AVA (``sync_user_preference``/``set_user_preference``). Veja :doc:`api` para a
lista completa de serviços e :doc:`sincronizacao` para o passo a passo da sincronização de matrículas.

Requisitos
----------

* ``$plugin->requires`` = ``2021051700`` em ``version.php`` (Moodle 3.11+).
* A esteira de CI (``.github/workflows/ci.yml``) testa o plugin contra ``MOODLE_401_STABLE``,
  ``MOODLE_402_STABLE`` e ``MOODLE_403_STABLE``, com PHP ``7.4``, ``8.0`` e ``8.1``, em PostgreSQL e MariaDB.

.. note::
   O requisito declarado em ``version.php`` (Moodle 3.11+) é mais permissivo do que as versões efetivamente
   cobertas pela matriz de CI (Moodle 4.1 a 4.3). Trate a faixa testada em CI como a garantia real de
   compatibilidade.

Integração com ``auth_suap``
------------------------------

``local_suap`` não depende de nenhum outro plugin da suíte para funcionar, mas é consumido por
`auth_suap <https://github.com/suap-ava-suite/moodle-auth_suap>`_ (plugin de autenticação OAuth2 do SUAP): se
``local_suap`` estiver instalado, ``auth_suap`` lê a configuração ``default_user_preferences`` (definida na tela de
administração deste plugin, veja :doc:`instalacao`) e aplica essas preferências ao usuário **apenas na criação da
conta** feita pelo login OAuth2. O próprio ``local_suap`` também aplica essas preferências quando cria um usuário
via ``sync_up_enrolments`` (veja :doc:`sincronizacao`). Este é o único ponto de contrato direto entre os dois
plugins — não há chamadas diretas de código entre eles, apenas leitura dessa configuração compartilhada via
``get_config('local_suap', 'default_user_preferences')``.

Estrutura do repositório
-------------------------

.. code-block:: text

   local_suap/
   ├── adminlib.php                  # Definição da tela de configurações (admin/settings.php)
   ├── locallib.php                  # Helpers genéricos: get_or_create, create_or_update, custom fields
   ├── login.php                     # Redireciona para o login OAuth2 (auth/oauth2)
   ├── health.php                    # Página de diagnóstico bruta (não é a API health)
   ├── healthcheck.php               # Endpoint simples: component/release/version em JSON
   ├── settings.php                  # Registra a página de admin no menu Servidor
   ├── version.php                   # Versão/release/maturidade do plugin
   ├── admin/
   │   ├── index.php                  # Lista as solicitações de sincronização recebidas
   │   ├── view.php                   # Detalha uma solicitação (JSON recebido, status, logs)
   │   └── tasklogs.php                # Logs da tarefa agendada associados a uma solicitação
   ├── api/
   │   ├── index.php                   # Dispatcher: valida o serviço solicitado e delega
   │   ├── servicelib.php               # Classe base "service": autenticação por token e contrato de resposta
   │   ├── health.php                   # Serviço "health": versão do plugin/Moodle sem side effects
   │   ├── get_diarios.php               # Lista diários/coordenações/práticas de um usuário
   │   ├── get_atualizacoes_counts.php    # Contagem de notificações/mensagens não lidas
   │   ├── set_favourite_course.php        # Favoritar/desfavoritar curso
   │   ├── set_visible_course.php           # Alterar visibilidade de um curso
   │   ├── set_user_preference.php           # Grava uma preferência de usuário recebida do Painel AVA
   │   ├── sync_user_preference.php           # Encaminha uma preferência do Moodle para o Painel AVA
   │   ├── sync_up_enrolments.php              # Serviço principal: sincroniza categorias/cursos/usuários/inscrições
   │   └── sync_down_grades.php                 # Consulta notas e completude de um diário para o SUAP
   ├── classes/
   │   ├── observer.php                 # Observadores de eventos de usuário/inscrição (em grande parte no-op)
   │   ├── Jsv4/                        # Biblioteca de validação de JSON Schema (draft-04)
   │   ├── task/
   │   │   ├── generate_report_task.php  # Tarefa agendada: relatório de cursos autoinstrucionais
   │   │   └── sync_up_enrolments_task.php # Tarefa assíncrona (adhoc) que processa uma sincronização em background
   │   └── output/
   │       ├── renderer.php              # Renderer do plugin
   │       └── relatorio_page.php        # Monta os dados do relatório de cursos autoinstrucionais
   ├── cursos/relatorio.php             # Página que exibe o relatório de cursos autoinstrucionais
   ├── db/
   │   ├── install.php                   # Cria campos customizados e tabelas na instalação
   │   ├── install.xml                   # Definição das tabelas próprias do plugin
   │   ├── upgrade.php                    # Passos de upgrade (savepoints) e recriação de campos customizados
   │   ├── uninstall.php                   # Sem lógica própria de desinstalação
   │   ├── access.php                      # Capabilities: local/suap:adminview, local/suap:view_mooc_reports
   │   ├── events.php                       # Observadores registrados
   │   ├── tasks.php                        # Agendamento da generate_report_task
   │   └── migrate.php                      # Funções compartilhadas de criação de campos/tabelas
   ├── examples/                        # Exemplos de payload JSON aceitos pela API
   ├── schemas/                         # JSON Schema (parcial) do payload de sync_up_enrolments
   ├── templates/                        # Mustache: listagem/detalhe de sincronizações e relatório
   ├── lang/{en,pt_br}/local_suap.php    # Strings de idioma e descrições das configurações
   ├── requests.http                     # Exemplos de chamada à API (REST Client do VS Code)
   ├── docs/                             # Esta documentação (Sphinx)
   └── .github/workflows/
       ├── ci.yml                        # moodle-plugin-ci (lint, PHPCS, PHPUnit, Behat, matriz PHP × Moodle)
       ├── release.yml                    # Gera ZIP instalável em cada tag
       └── docs.yml                       # Publica esta documentação no GitHub Pages

Organização
-----------

O repositório vive na organização `suap-ava-suite <https://github.com/suap-ava-suite>`_ como ``moodle-local_suap``,
ao lado de outros componentes da suíte AVA/SUAP usados pelo IFRN — entre eles ``auth_suap`` (autenticação OAuth2,
veja acima) e o Painel AVA, sistema externo consumido/alimentado pelos endpoints ``painel_url`` e
``sync_user_preference``/``set_user_preference`` descritos em :doc:`api`.
