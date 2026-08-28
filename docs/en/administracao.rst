Administration
==============

This plugin adds administrative screens for monitoring synchronization and reviewing automated reports.

Synchronization History
-----------------------

Requests received by ``sync_up_enrolments`` are logged in ``suap_enrolment_to_sync``.

``/local/suap/admin/index.php``
   Paginated list (10 items per page) of received synchronization requests with ID, creation date, and status (**Not processed**, **Success**, or **Failure**). Requires ``is_siteadmin()``.

``/local/suap/admin/view.php?id=<id>``
   Request detail view displaying formatted payload JSON, status, and task log links. Requires ``is_siteadmin()``.

``/local/suap/admin/tasklogs.php?requestid=<id>``
   Searches ``mdl_task_log`` for executions of ``local_suap\task\sync_up_enrolments_task`` processing request ``<id>``. Requires ``require_login()`` + ``is_siteadmin()``.

Asynchronous Synchronization Task
---------------------------------

``local_suap\task\sync_up_enrolments_task`` (adhoc task) executes background enrolments and marks request records as processed (``processed = 1``) or failed (``processed = 2``).

Self-Instructional Course Report
--------------------------------

``/local/suap/cursos/relatorio.php``
   Displays statistics (enrollees, activity accesses, completions, issued certificates) for self-instructional courses (``diario_tipo = 'minicurso'``). Requires ``require_login()`` + capability ``local/suap:view_mooc_reports``.

Pre-calculated daily at 02:00 by scheduled cron task ``local_suap\task\generate_report_task``.

Health Check Mechanisms
-----------------------

.. list-table::
   :header-rows: 1
   :widths: 30 15 55

   * - Endpoint
     - Authentication
     - Purpose
   * - ``/local/suap/healthcheck.php``
     - None
     - Returns ``{"component", "release", "version"}`` directly from ``version.php``.
   * - ``/local/suap/api/?health``
     - Token
     - Returns plugin and Moodle versions while validating API authentication token.
   * - ``/local/suap/health.php``
     - None
     - Legacy diagnostic script displaying server details.
