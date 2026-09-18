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

How to contribute: pre-commit with ``act``
-------------------------------------------

The *pre-commit* hook (``.pre-commit-config.yaml``) runs the same CI workflow used on GitHub
(``.github/workflows/ci.yml``, job ``ci``) locally through `act <https://nektosact.com/>`_, inside Docker. You therefore
**do not need PHP (or Moodle) installed**: only Python, pre-commit, Docker and ``act``.

Requirements:

* `Python <https://www.python.org/>`_ 3 and `pre-commit <https://pre-commit.com/>`_;
* `Docker <https://www.docker.com/>`_ running;
* `act <https://nektosact.com/installation/>`_ (on Windows: ``winget install nektos.act``).

One-time setup:

.. code-block:: bash

   pip install pre-commit
   pre-commit install

On its first run ``act`` asks which Docker image to use and fails in non-interactive terminals. To avoid that, create
the ``act`` config file (Linux/macOS: ``~/.config/act/actrc``; Windows: ``%LOCALAPPDATA%ctctrc``) with the
equivalent of the "Medium" image:

.. code-block:: text

   -P ubuntu-latest=catthehacker/ubuntu:act-latest

On every ``git commit`` the hook runs:

.. code-block:: bash

   act -j ci --matrix php:8.3 --matrix database:pgsql --matrix moodle-branch:MOODLE_405_STABLE --reuse

To run the hook manually, without committing: ``pre-commit run --all-files``.

.. note::
   The full CI on GitHub uses a larger matrix (Moodle 4.4 and 4.5, ``pgsql`` and ``mariadb``); the hook validates a
   single combination to keep the run time reasonable. The first run downloads Docker images and installs Moodle, so it
   takes much longer. Steps marked as non-blocking in the workflow (e.g. Moodle Code Checker) do not fail the commit.
