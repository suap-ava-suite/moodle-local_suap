API & Services
==============

``local_suap`` exposes a custom HTTP API (without using Moodle core web services) to receive data from SUAP and return information consumed by the AVA Panel and other integrations. All services pass through a single entry point.

Entry Point and Authentication
------------------------------

``api/index.php`` handles requests at ``/local/suap/api/?<service_name>``:

* disables Moodle cookies/CSRF (``NO_MOODLE_COOKIES``) — the API is stateless and token-authenticated;
* validates the service name against a whitelist and includes the corresponding file;
* instantiates ``\local_suap\<service_name>_service`` and calls ``->call()``;
* any uncaught exception is formatted into a JSON response ``{"error": {"message", "code", "source", "trace"}}`` with HTTP status matching the exception code (or 500).

The base ``service`` class (``api/servicelib.php``) implements token authentication:

.. code-block:: php

   function authenticate() {
       // Requires Authentication (or authentication) header: "Token <auth_token>"
       // 400 if header missing; 401 if token invalid
   }

   function call() {
       $this->authenticate();
       echo json_encode($this->do_call());
   }

Each concrete service overrides ``do_call()``. The base implementation throws ``501 Not Implemented``.

Available Services
------------------

Whitelist of services enabled in ``api/index.php``:

.. list-table::
   :header-rows: 1
   :widths: 25 15 60

   * - Service
     - HTTP Method
     - Purpose
   * - ``health``
     - GET/POST
     - Returns plugin and Moodle version without side effects.
   * - ``get_diarios``
     - GET
     - Lists logbooks, coordination rooms, and practicals for a user.
   * - ``get_atualizacoes_counts``
     - GET
     - Returns unread messages/notifications count for a user (used by AVA Panel).
   * - ``set_favourite_course``
     - GET
     - Toggles favorite state of a course for a user.
   * - ``set_visible_course``
     - GET
     - Changes visibility of a course if user has ``moodle/course:visibility``.
   * - ``set_user_preference``
     - GET
     - Saves an arbitrary user preference (``name``/``value``) from AVA Panel.
   * - ``sync_up_enrolments``
     - POST
     - Primary service: receives course structure/enrolments and syncs categories, courses, users, cohorts, enrolments, and groups. See :doc:`sincronizacao`.
   * - ``sync_down_grades``
     - GET
     - Fetches grades (categories in ``notes_to_sync``) and completion rate for a logbook to be pulled back to SUAP.

``get_diarios``
---------------

Classifies courses assigned to a user based on the ``shortname`` pattern:

.. list-table::
   :header-rows: 1
   :widths: 30 70

   * - ``shortname`` Pattern
     - Classification
   * - ``ZL.<digits>...``
     - Coordination (``coordenacoes``)
   * - ``<something>.<11 to 14+ digits>``
     - Practical (``praticas``)
   * - ``AAAAA.P.CCCCC.TTT...#DDD``
     - Logbook (``diarios``)
   * - Other cases
     - Treated as logbook without structural regex filter.

Accepts query parameters: ``username``, ``semestre``, ``situacao``, ``ordenacao``, ``disciplina``, ``curso``, ``arquetipo``, ``q``, ``page``, ``page_size``.

``sync_down_grades``
--------------------

HTTP Call Example:

.. code-block:: http

   GET /local/suap/api/sync_down_grades.php?diario_id=20231.1.15806.1E.TEC.1386 HTTP/1.1

Returns student enrolment ID, full name, grades object, and activity completion percentage for students in the specified logbook.

Sample Payload — ``sync_up_enrolments``
----------------------------------------

Minimal POST payload:

.. code-block:: http

   POST /local/suap/api/?sync_up_enrolments HTTP/1.1
   Authentication: Token changeme

   {
       "curso": {"id": 1, "nome": "Technology in Computer Networks", "codigo": "00001", "descricao": "..."},
       "turma": {"id": 2, "codigo": "20221.6.00001.3E"},
       "campus": {"id": 1, "sigla": "EAD", "descricao": "Campus EaD"},
       "diario": {"id": 2, "sigla": "TEC.0001", "situacao": "Aberto", "descricao": "Database Systems", "descricao_historico": "Database Systems"},
       "componente": {"id": 1, "tipo": 1, "sigla": "TEC.0001", "periodo": null, "optativo": false, "descricao": "Database Systems", "qtd_avaliacoes": 2, "descricao_historico": "Database Systems"}
   }
