Overview
========

What the plugin does
--------------------

``local_suap`` is a ``local`` type plugin for Moodle. It represents the Moodle-side integration between SUAP Edu (IFRN's academic management system) and Moodle: via its HTTP API, it receives course structures, enrolments, cohorts, and grades from SUAP (typically through the Integrador AVA) and applies them to Moodle, creating or updating:

* course categories (hierarchy: Logbooks/Diários → Campus → Course → Term → Class/Turma);
* courses/rooms (logbooks/diários, coordination rooms, self-enrolment, practicals, and models);
* users (students, teachers, and support staff) and dozens of custom profile fields;
* cohorts (global groups) and their members;
* enrolments and role assignments, including automatic suspension of students who leave official lists;
* groups within each room (by entry year/term, class, campus hub, and program).

In addition to top-down synchronization (SUAP → Moodle), the plugin exposes endpoints for the reverse direction: returning grades from Moodle's gradebook back to SUAP (``sync_down_grades``) and synchronizing user preferences between Moodle and the AVA Panel (``sync_user_preference``/``set_user_preference``). See :doc:`api` for the complete service list and :doc:`sincronizacao` for the step-by-step enrolment synchronization process.

Requirements
------------

* ``$plugin->requires`` = ``2021051700`` in ``version.php`` (Moodle 3.11+).
* The CI pipeline (``.github/workflows/ci.yml``) tests the plugin against ``MOODLE_401_STABLE``, ``MOODLE_402_STABLE``, and ``MOODLE_403_STABLE`` with PHP ``8.3`` on PostgreSQL and MariaDB.

.. note::
   The requirement declared in ``version.php`` (Moodle 3.11+) is more permissive than the versions covered in CI (Moodle 4.1 to 4.3). Treat the CI matrix as the verified compatibility guarantee.

Integration with ``auth_suap``
------------------------------

``local_suap`` does not depend on any other suite plugin to function, but it is consumed by `auth_suap <https://github.com/suap-ava-suite/moodle-auth_suap>`_ (SUAP OAuth2 authentication plugin): if ``local_suap`` is installed, ``auth_suap`` reads the ``default_user_preferences`` setting (configured in this plugin's admin settings, see :doc:`instalacao`) and applies those preferences **upon initial account creation** performed by OAuth2 login. ``local_suap`` also applies these preferences when creating a user via ``sync_up_enrolments`` (see :doc:`sincronizacao`). This shared configuration via ``get_config('local_suap', 'default_user_preferences')`` is the direct interface between the two plugins.

Repository Structure
--------------------

.. code-block:: text

   local_suap/
   ├── adminlib.php                  # Admin settings page definition (admin/settings.php)
   ├── locallib.php                  # Generic helpers: get_or_create, create_or_update, custom fields
   ├── login.php                     # Redirects to OAuth2 login (auth/oauth2)
   ├── health.php                    # Raw diagnostic page (not the health API)
   ├── healthcheck.php               # Simple endpoint: component/release/version in JSON
   ├── settings.php                  # Registers admin page in Server menu
   ├── version.php                   # Plugin version/release/maturity
   ├── admin/
   │   ├── index.php                  # Lists received sync requests
   │   ├── view.php                   # Details a sync request (JSON payload, status, logs)
   │   └── tasklogs.php                # Scheduled task logs linked to a request
   ├── api/
   │   ├── index.php                   # Dispatcher: validates requested service and delegates
   │   ├── servicelib.php               # Base service class: token authentication and response contract
   │   ├── health.php                   # "health" service: plugin/Moodle version without side effects
   │   ├── get_diarios.php               # Lists logbooks/coordinations/practicals of a user
   │   ├── get_atualizacoes_counts.php    # Unread messages/notifications count
   │   ├── set_favourite_course.php        # Favorite/unfavorite course
   │   ├── set_visible_course.php           # Change course visibility
   │   ├── set_user_preference.php           # Saves user preference from AVA Panel
   │   ├── sync_user_preference.php           # Forwards Moodle user preference to AVA Panel
   │   ├── sync_up_enrolments.php              # Main service: syncs categories/courses/users/enrolments
   │   └── sync_down_grades.php                 # Fetches grades and completion rates of a logbook for SUAP
   ├── classes/
   │   ├── observer.php                 # User/enrolment event observers
   │   ├── Jsv4/                        # JSON Schema validation library (draft-04)
   │   ├── task/
   │   │   ├── generate_report_task.php  # Scheduled task: self-instructional course report
   │   │   └── sync_up_enrolments_task.php # Async adhoc task processing sync in background
   │   └── output/
   │       ├── renderer.php              # Plugin renderer
   │       └── relatorio_page.php        # Self-instructional course report page renderer
   ├── cursos/relatorio.php             # Self-instructional course report page
   ├── db/
   │   ├── install.php                   # Creates custom fields and tables on installation
   │   ├── install.xml                   # Table definitions
   │   ├── upgrade.php                    # Upgrade savepoints and field recreations
   │   ├── uninstall.php                   # Uninstallation hook
   │   ├── access.php                      # Capabilities: local/suap:adminview, local/suap:view_mooc_reports
   │   ├── events.php                       # Registered observers
   │   ├── tasks.php                        # Scheduling for generate_report_task
   │   └── migrate.php                      # Shared migration functions
   ├── examples/                        # Sample JSON payloads accepted by API
   ├── schemas/                         # Partial JSON schema for sync_up_enrolments payload
   ├── templates/                        # Mustache templates: sync list/detail and reports
   ├── lang/{en,es,fr,nl,pt_br,zh_cn}/local_suap.php # Language strings
   ├── requests.http                     # API HTTP call examples
   ├── docs/                             # Documentation (Sphinx)
   └── .github/workflows/
       ├── ci.yml                        # moodle-plugin-ci workflow
       ├── release.yml                    # Generates installable ZIP on git tag
       └── docs.yml                       # Publishes documentation to GitHub Pages

Organization
------------

The repository lives under the `suap-ava-suite <https://github.com/suap-ava-suite>`_ organization as ``moodle-local_suap`` alongside other AVA/SUAP suite components used by IFRN — such as ``auth_suap`` and the AVA Panel.
