Development
===========

Versioning
----------

``version.php`` follows the suite standard:

* ``$plugin->version`` format ``YYYY_MM_DD_XXX``, where ``YYYY_MM_DD`` reflects the change date and ``XXX`` is a 3-digit counter.
* ``$plugin->release`` format ``4.5.XXX``, sharing the same ``XXX`` counter.
* Database upgrade changes in ``db/upgrade.php`` require incrementing ``version``/``release``.

CI/CD
-----

``.github/workflows/ci.yml`` — **Moodle Plugin CI**
    Runs on push and pull requests to ``main``. Executes PHP Lint, PHPCS, PHPDoc, unit tests (PHPUnit), and Behat tests.

``.github/workflows/release.yml`` — **Release**
    Triggered by git tags matching release versions. Builds installable ZIP assets and publishes GitHub Releases.

``.github/workflows/docs.yml`` — **Build & Deploy Documentation**
    Compiles Sphinx documentation for both Portuguese (pt-BR) and English (en) and deploys them to GitHub Pages.

Documentation
-------------

Documentation uses `Sphinx <https://www.sphinx-doc.org/>`_ with `moodle-docs-theme <https://pypi.org/project/moodle-docs-theme/>`_ and ``.rst`` files located under ``docs/pt-br/`` and ``docs/en/``.

To build the English documentation locally:

.. code-block:: bash

   pip install sphinx moodle-docs-theme
   sphinx-build -W -b html docs/en docs/_build/html/en

To build the Portuguese documentation locally:

.. code-block:: bash

   sphinx-build -W -b html docs/pt-br docs/_build/html/pt-br

The ``docs.yml`` workflow executes these commands in CI and deploys the output via ``actions/deploy-pages``.

Commit Conventions
------------------

Supported commit message prefixes:

.. list-table::
   :header-rows: 1
   :widths: 20 80

   * - Prefix
     - Usage
   * - ``feat:``
     - New features.
   * - ``fix:``
     - Bug fixes.
   * - ``refactor:``
     - Refactoring or performance improvements.
   * - ``style:``
     - Code style or formatting.
   * - ``test:``
     - Tests.
   * - ``doc:``
     - Documentation updates.
   * - ``env:``
     - CI/CD or configuration changes.
   * - ``build:``
     - Dependencies or build tools.

.. note::
   The repository uses ``.pre-commit-config.yaml`` and ``.githooks/pre-commit`` to enforce automated tests and checks before committing code.
