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

User change audit
-----------------

``local_suap_observer`` listens to ``user_enrolment_*``, ``user_created``, ``user_updated``, ``user_deleted``,
``cohort_member_*`` and ``role_*`` events and writes to ``local_suap_user_audit``: who (``actoruserid`` /
``actorlabel``: ``web``, ``api:suap``, ``api:sga``, ``api:painelava``, ``login:suap``, ``task:<name>``, ``cli``),
when, what (field, old and new value), where from (origin, IP) and the originating request (``requestref``).
Writes made by the plugins go through ``\local_suap\audit\writer`` which records a per-field diff, including
profile fields and preferences. Sensitive fields are masked. View it under *Site administration → Reports → User
change audit* (capability ``local/suap:viewuseraudit``). Retention is set by ``user_audit_retention_months``
(default 24) and enforced by the ``purge_user_audit_task`` scheduled task.
