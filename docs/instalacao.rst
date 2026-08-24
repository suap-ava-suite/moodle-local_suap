Instalação
==========

Instalação do plugin
----------------------

1. Copie (ou instale via ZIP gerado pelo workflow de release, veja :doc:`desenvolvimento`) o conteúdo deste
   repositório para ``<moodle>/local/suap``.
2. Acesse **Administração do site → Notificações** para disparar a rotina de instalação do Moodle.

Na instalação (``db/install.php``), o plugin:

* cria as categorias e campos de perfil customizados de curso e de usuário usados pela sincronização (função
  ``suap_bulk_course_custom_field()``/``suap_bulk_user_custom_field()`` em ``db/migrate.php``);
* cria as tabelas próprias do plugin (``suap_enrolment_to_sync``, ``suap_learning_path``,
  ``suap_learning_path_course``, além das declaradas em ``db/install.xml``:
  ``local_suap_relatorio_cursos_autoinstrucionais`` e ``local_suap_restricoes_autoinscricao``).

Essas mesmas funções são reexecutadas em todo upgrade (``db/upgrade.php``), o que permite adicionar novos campos
customizados em versões futuras sem exigir reinstalação.

Tela de configuração
-----------------------

Em **Administração do site → Plugins → Plugins locais → SUAP Integration** (registrada por ``settings.php`` via
``suap_admin_settingspage`` em ``adminlib.php``), as configurações estão agrupadas assim:

.. list-table:: Token e Painel AVA
   :header-rows: 1
   :widths: 30 70

   * - Configuração
     - Descrição
   * - ``auth_token``
     - Token que o SUAP/Integrador AVA deve enviar no header ``Authentication: Token <valor>`` para autenticar
       chamadas à API (veja :doc:`api`). Valor padrão de exemplo: ``changeme`` — **troque em produção**.
   * - ``painel_url``
     - URL base do Painel AVA (ex.: ``https://ava.ifrn.edu.br``), usada pelo endpoint ``sync_user_preference``
       para encaminhar preferências de usuário.

.. list-table:: Categoria principal
   :header-rows: 1
   :widths: 30 70

   * - Configuração
     - Descrição
   * - ``top_category_idnumber``
     - ``idnumber`` da categoria raiz onde novos cursos são organizados. Padrão: ``diarios``.
   * - ``top_category_name``
     - Nome usado apenas se a categoria raiz precisar ser criada. Padrão: ``Diários``.
   * - ``top_category_parent``
     - Categoria pai da categoria raiz. Padrão: ``0`` (nível superior).

.. list-table:: Novo usuário e inscrição
   :header-rows: 1
   :widths: 30 70

   * - Configuração
     - Descrição
   * - ``default_user_preferences``
     - Preferências aplicadas a **todo novo usuário** (aluno ou professor) no momento da criação da conta, uma por
       linha, no formato ``chave=valor`` (como um arquivo ``.ini``). É a configuração lida por ``auth_suap`` — veja
       :doc:`visao-geral`.
   * - ``roles_mapping``
     - JSON que mapeia ``tipo_sala`` (``diarios``, ``coordenacoes``, ``autoinscricoes``, ``praticas``,
       ``modelos``) × papel SUAP (``Principal``, ``Formador``, ``Mediador``, ``Conteudista``, ``Tutor``,
       ``Coordenador de Curso``, ``Tutor presencial``, ``Coordenador de Polo``, ``Secretário de Curso``, ``Aluno``)
       para ``{"role": <shortname da role Moodle>, "enrol": <shortname do método de inscrição>}``.
   * - ``default_auth``
     - Método de autenticação padrão para usuários cujo papel não está em ``auths_mapping``. Padrão: ``oauth2``.
   * - ``auths_mapping``
     - Texto no formato ``Papel SUAP : método_auth`` (uma linha por papel), mapeando cada papel a um método de
       autenticação Moodle diferente do padrão.

.. list-table:: Notas e grupos
   :header-rows: 1
   :widths: 30 70

   * - Configuração
     - Descrição
   * - ``notes_to_sync``
     - Lista, entre aspas simples separadas por vírgula (ex.: ``'N1', 'N2', 'N3', 'N4', 'NAF'``), dos ``idnumber``
       de categorias de nota devolvidos pelo endpoint ``sync_down_grades``.
   * - ``course_group_entrada`` / ``course_group_turma`` / ``course_group_polo`` / ``course_group_programa``
     - Habilitam (checkbox) a criação de grupos de Entrada/Turma/Polo/Programa dentro das salas de aula (diários).
   * - ``room_group_entrada`` / ``room_group_turma`` / ``room_group_polo`` / ``room_group_programa``
     - Mesma coisa, para as salas de coordenação de curso.

.. list-table:: Papéis por perfil SUAP
   :header-rows: 1
   :widths: 40 60

   * - Grupo de configurações
     - Campos
   * - Estudantes (``default_student_*``)
     - ``_auth`` (padrão ``oauth2``), ``_role_id`` (padrão ``5``), ``_enrol_type`` (``suap`` se o plugin
       ``enrol_suap`` estiver instalado, senão ``manual``).
   * - Professores (``default_teacher_*``)
     - Mesma estrutura, ``_role_id`` padrão ``3``.
   * - Tutores/assistentes (``default_assistant_*``)
     - Mesma estrutura, ``_role_id`` padrão ``4``.
   * - Colaboradores egressos (``default_former_*``)
     - Mesma estrutura, ``_role_id`` padrão ``4``.
   * - Moderadores (``default_moderator_*``)
     - Mesma estrutura, ``_role_id`` padrão ``4``.
   * - Docentes em salas de coordenação (``default_instructor_*``)
     - Mesma estrutura, ``_role_id`` padrão ``4``.

.. note::
   Essas configurações ``default_*_role_id``/``default_*_enrol_type`` existem na tela de administração, mas o
   fluxo efetivo de sincronização de matrículas em ``api/sync_up_enrolments.php`` resolve papel e método de
   inscrição a partir de ``roles_mapping`` (por ``sala_tipo`` + papel SUAP), não diretamente destes campos
   individuais. Trate-os como valores de referência/legado ao configurar uma instalação nova — o comportamento
   efetivo está documentado em :doc:`sincronizacao`.

Capacidades (capabilities)
-----------------------------

Definidas em ``db/access.php``:

.. list-table::
   :header-rows: 1
   :widths: 35 65

   * - Capability
     - Descrição
   * - ``local/suap:adminview``
     - Concedida por padrão a ``manager``. Controla o acesso a telas administrativas do plugin.
   * - ``local/suap:view_mooc_reports``
     - Concedida por padrão a ``manager``. Necessária para acessar ``cursos/relatorio.php`` (relatório de cursos
       autoinstrucionais, veja :doc:`administracao`).

.. warning::
   As páginas ``admin/index.php`` e ``admin/view.php`` (histórico de sincronizações) checam apenas
   ``is_siteadmin()``, não a capability ``local/suap:adminview`` — administradores de site sempre têm acesso,
   independentemente de quem mais receba essa capability.

Testando a instalação
------------------------

* ``GET /local/suap/healthcheck.php`` retorna ``{"component", "release", "version"}`` sem exigir autenticação —
  útil para confirmar que o plugin está instalado e qual versão está ativa.
* ``POST /local/suap/api/?health`` (com o header ``Authentication: Token <auth_token>``) retorna o mesmo tipo de
  informação, mas passando pelo pipeline de autenticação da API — útil para validar o token configurado. Veja
  :doc:`api` para o contrato completo dos serviços.

.. danger::
   ``health.php`` (na raiz do plugin, diferente de ``api/health.php``) é um script de diagnóstico que expõe
   informações do servidor (hostname, versão do PHP, opções de conexão com o banco, caminho do ``wwwroot``,
   configuração de proxy/SSL) e **não exige autenticação nem ``require_login()``**. Restrinja o acesso a este
   arquivo por outra camada (proxy reverso, firewall) ou remova-o em produção — o código-fonte atual não impõe
   nenhum controle de acesso a ele.
