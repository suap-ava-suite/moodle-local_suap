API e serviços
==============

``local_suap`` expõe uma pequena API HTTP própria (sem usar o webservices core do Moodle) para receber dados do
SUAP e devolver informações consumidas pelo Painel AVA e por outras integrações. Todos os serviços passam por um
único ponto de entrada.

Ponto de entrada e autenticação
-----------------------------------

``api/index.php`` recebe requisições em ``/local/suap/api/?<nome_do_serviço>``:

* desabilita cookies/CSRF do Moodle (``NO_MOODLE_COOKIES``) — a API é *stateless*, autenticada por token;
* valida o nome do serviço contra uma lista fixa (*whitelist*) e inclui o arquivo correspondente;
* instancia a classe ``\local_suap\<nome_do_serviço>_service`` e chama ``->call()``;
* qualquer exceção lançada é capturada e convertida em uma resposta JSON
  ``{"error": {"message", "code", "source", "trace"}}`` com o HTTP status igual ao código da exceção (ou 500).

A classe base ``service`` (``api/servicelib.php``) implementa a autenticação comum a (quase) todos os serviços:

.. code-block:: php

   function authenticate() {
       // Exige o header Authentication (ou authentication): "Token <auth_token>"
       // 400 se o header não vier; 401 se o valor não bater com config('auth_token')
   }

   function call() {
       $this->authenticate();
       echo json_encode($this->do_call());
   }

Cada serviço concreto sobrescreve apenas ``do_call()``. A implementação padrão de ``do_call()`` na classe base
lança ``501 Não implementado``.

.. note::
   ``sync_user_preference.php`` (usado pelo Moodle para *enviar* preferências ao Painel AVA) **não** estende
   ``service``/``servicelib`` — é um script independente que autentica a chamada *de saída* ao Painel AVA usando
   o mesmo ``auth_token`` como header ``Authorization: Token <auth_token>``, e não expõe um serviço autenticável
   por terceiros da mesma forma que os demais.

Serviços disponíveis
------------------------

Lista de serviços habilitados em ``api/index.php`` (``$whitelist``):

.. list-table::
   :header-rows: 1
   :widths: 25 15 60

   * - Serviço
     - Método HTTP
     - Finalidade
   * - ``health``
     - GET/POST
     - Retorna versão do plugin e do Moodle, sem efeitos colaterais. Útil para validar o token configurado.
   * - ``get_diarios``
     - GET
     - Lista diários, coordenações e práticas de um usuário, com filtros de semestre/disciplina/curso/busca.
   * - ``get_atualizacoes_counts``
     - GET
     - Retorna contagem de conversas e notificações não lidas de um usuário (usado pelo Painel AVA).
   * - ``set_favourite_course``
     - GET
     - Marca/desmarca um curso como favorito para um usuário.
   * - ``set_visible_course``
     - GET
     - Altera a visibilidade de um curso, se o usuário tiver a capability ``moodle/course:visibility``.
   * - ``set_user_preference``
     - GET
     - Grava uma preferência de usuário arbitrária (``name``/``value``) recebida do Painel AVA.
   * - ``sync_up_enrolments``
     - POST
     - Serviço principal: recebe a estrutura de curso/turma/matrículas e sincroniza categorias, cursos, usuários,
       coortes, inscrições e grupos. Veja :doc:`sincronizacao`.
   * - ``sync_down_grades``
     - GET
     - Consulta notas (categorias configuradas em ``notes_to_sync``) e percentual de completude dos alunos de um
       diário, para o SUAP puxar de volta.

.. note::
   O código lista ``sync_down_attendances`` como comentado (``// 'sync_down_attendances',``) dentro da
   *whitelist* — ainda não está habilitado. Existe um arquivo de exemplo vazio
   (``examples/sync_down_attendances_sample.json``) reservado para essa futura funcionalidade, mas nenhum
   ``api/sync_down_attendances.php`` foi implementado no código-fonte atual.

``get_diarios``
------------------

Classifica os cursos aos quais o usuário tem *role assignment* em contexto de curso conforme o padrão do
``shortname``:

.. list-table::
   :header-rows: 1
   :widths: 30 70

   * - Padrão do ``shortname``
     - Classificação
   * - ``ZL.<dígitos>...``
     - Coordenação (``coordenacoes``)
   * - ``<algo>.<11 a 14+ dígitos>``
     - Prática (``praticas``)
   * - ``AAAAA.P.CCCCC.TTT...#DDD`` (regex ``REGEX_CODIGO_DIARIO``)
     - Diário — semestre, período, curso, turma e disciplina extraídos por grupos de captura da regex.
   * - Demais casos
     - Também tratado como diário, sem filtro estrutural aplicado.

Aceita os parâmetros de busca ``username``, ``semestre``, ``situacao``, ``ordenacao``, ``disciplina``, ``curso``,
``arquetipo`` (default ``student``), ``q``, ``page``, ``page_size``, e retorna também as listas de valores
possíveis (``semestres``, ``disciplinas``, ``cursos``) para popular filtros no Painel AVA.

``sync_down_grades``
------------------------

Exemplo de chamada (de ``requests.http``):

.. code-block:: http

   GET /local/suap/api/sync_down_grades.php?diario_id=20231.1.15806.1E.TEC.1386 HTTP/1.1

Retorna, por aluno matriculado no diário (``course.idnumber LIKE '%#<diario_id>'``), a matrícula, o nome completo,
um objeto ``notas`` com as notas das categorias configuradas em ``notes_to_sync`` (chaveadas pelo ``idnumber`` da
categoria de notas) e o percentual de ``completude`` de atividades marcadas como rastreáveis no curso.

.. warning::
   Este arquivo é chamado tanto por ``api/index.php?sync_down_grades`` (via ``sync_down_grades_service``, exigindo
   o header ``Authentication``) quanto, segundo o comentário no próprio código, por acesso direto ao arquivo
   (``api/sync_down_grades.php?diario_id=...``). O bloco ``catch`` interno usa ``echo`` em vez de lançar a exceção
   adiante, então uma falha nesse serviço específico não segue o mesmo formato de erro padronizado por
   ``exception_handler`` em ``api/index.php``.

Exemplo de payload — ``sync_up_enrolments``
------------------------------------------------

Payload mínimo (apenas dados obrigatórios de curso/turma), de ``requests.http``:

.. code-block:: http

   POST /local/suap/api/?sync_up_enrolments HTTP/1.1
   Authentication: Token changeme

   {
       "curso": {"id": 1, "nome": "Tecnologia em Redes de Computadores", "codigo": "00001", "descricao": "..."},
       "turma": {"id": 2, "codigo": "20221.6.00001.3E"},
       "campus": {"id": 1, "sigla": "EAD", "descricao": "Campus EaD"},
       "diario": {"id": 2, "sigla": "TEC.0001", "situacao": "Aberto", "descricao": "Bancos de Dados", "descricao_historico": "Bancos de Dados"},
       "componente": {"id": 1, "tipo": 1, "sigla": "TEC.0001", "periodo": null, "optativo": false, "descricao": "Bancos de Dados", "qtd_avaliacoes": 2, "descricao_historico": "Bancos de Dados"}
   }

O diretório ``examples/`` traz payloads mais completos (``sync_up_enrolments_sample.json``, incluindo
``alunos``/``professores``/``coortes``) e ``schemas/sync_up_enrolments.schema.json`` documenta parcialmente (em
JSON Schema draft‑2019‑09) a estrutura esperada — não é validado automaticamente contra o payload recebido hoje
(a suíte usa a biblioteca ``Jsv4`` em ``classes/Jsv4/`` para validação de JSON Schema, mas o schema atual só cobre
o campo ``curso``; o README do repositório já registra isso como pendência: "O JSON está conforme o esquema
(falta)").

``examples/sync_up.json`` descreve um formato mais genérico de sincronização em massa (``users``, ``cohorts`` etc.)
que **não corresponde** ao contrato efetivo de ``api/sync_up_enrolments.php`` — trate-o como um rascunho/proposta
histórica, não como documentação do comportamento atual.
