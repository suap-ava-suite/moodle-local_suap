Installation
============

Plugin Installation
-------------------

1. Copy (or install via the release workflow ZIP, see :doc:`desenvolvimento`) the contents of this repository to ``<moodle>/local/suap``.
2. Navigate to **Site administration → Notifications** to trigger Moodle's installation routines.

During installation (``db/install.php``), the plugin:

* creates custom profile fields for course and user used by synchronization (functions ``suap_bulk_course_custom_field()``/``suap_bulk_user_custom_field()`` in ``db/migrate.php``);
* creates custom tables (``suap_enrolment_to_sync``, ``suap_learning_path``, ``suap_learning_path_course``, plus those declared in ``db/install.xml``: ``local_suap_relatorio_cursos_autoinstrucionais`` and ``local_suap_restricoes_autoinscricao``).

These functions are re-executed during upgrade steps (``db/upgrade.php``), allowing seamless addition of custom fields in future versions.

Settings Screen
---------------

At **Site administration → Plugins → Local plugins → SUAP Integration** (registered by ``settings.php`` via ``suap_admin_settingspage`` in ``adminlib.php``), settings are grouped as follows:

.. list-table:: Token and AVA Panel
   :header-rows: 1
   :widths: 30 70

   * - Setting
     - Description
   * - ``auth_token``
     - Token sent by SUAP / Integrador AVA in the ``Authentication: Token <value>`` header to authenticate API calls (see :doc:`api`). Default placeholder value: ``changeme`` — **change in production**.
   * - ``painel_url``
     - Base URL of the AVA Panel (e.g., ``https://ava.ifrn.edu.br``), used by ``sync_user_preference`` endpoint to forward user preferences.

.. list-table:: Root Category
   :header-rows: 1
   :widths: 30 70

   * - Setting
     - Description
   * - ``top_category_idnumber``
     - ``idnumber`` of the root category where new courses are organized. Default: ``diarios``.
   * - ``top_category_name``
     - Name used if the root category needs to be created. Default: ``Diários``.
   * - ``top_category_parent``
     - Parent category of the root category. Default: ``0`` (top level).

.. list-table:: New User and Enrolment
   :header-rows: 1
   :widths: 30 70

   * - Setting
     - Description
   * - ``default_user_preferences``
     - Preferences applied to **every new user** (student or teacher) upon account creation, one per line in ``key=value`` format. Configuration read by ``auth_suap`` — see :doc:`visao-geral`.
   * - ``roles_mapping``
     - JSON mapping ``room_type`` (``diarios``, ``coordenacoes``, ``autoinscricoes``, ``praticas``, ``modelos``) × SUAP role to ``{"role": <Moodle role shortname>, "enrol": <enrolment method shortname>}``.
   * - ``default_auth``
     - Default authentication method for users whose role is not in ``auths_mapping``. Default: ``oauth2``.
   * - ``auths_mapping``
     - Text in ``SUAP Role : auth_method`` format (one line per role) mapping roles to non-default auth methods.

.. list-table:: Grades and Groups
   :header-rows: 1
   :widths: 30 70

   * - Setting
     - Description
   * - ``notes_to_sync``
     - Comma-separated list in single quotes (e.g., ``'N1', 'N2', 'N3', 'N4', 'NAF'``) of grade category ``idnumber``s returned by ``sync_down_grades``.
   * - ``course_group_entrada`` / ``course_group_turma`` / ``course_group_polo`` / ``course_group_programa``
     - Enable creation of Entry / Class / Hub / Program groups inside course rooms.
   * - ``room_group_entrada`` / ``room_group_turma`` / ``room_group_polo`` / ``room_group_programa``
     - Same settings for coordination rooms.

Capabilities
------------

Defined in ``db/access.php``:

.. list-table::
   :header-rows: 1
   :widths: 35 65

   * - Capability
     - Description
   * - ``local/suap:adminview``
     - Granted by default to ``manager``. Controls access to plugin administration screens.
   * - ``local/suap:view_mooc_reports``
     - Granted by default to ``manager``. Required to access ``cursos/relatorio.php`` (self-instructional course report, see :doc:`administracao`).

.. warning::
   ``admin/index.php`` and ``admin/view.php`` check only ``is_siteadmin()``, not ``local/suap:adminview``. Site administrators always have access.

Testing the Installation
------------------------

* ``GET /local/suap/healthcheck.php`` returns ``{"component", "release", "version"}`` without requiring authentication — useful for confirming that the plugin is installed.
* ``POST /local/suap/api/?health`` (with header ``Authentication: Token <auth_token>``) returns version info while validating authentication. See :doc:`api`.
