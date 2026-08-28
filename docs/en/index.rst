local_suap
==========

.. image:: https://img.shields.io/badge/License-GPLv3-blue.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/blob/main/LICENSE
   :alt: License

.. image:: https://github.com/suap-ava-suite/moodle-local_suap/actions/workflows/ci.yml/badge.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/actions/workflows/ci.yml
   :alt: Moodle Plugin CI

.. image:: https://img.shields.io/github/v/release/suap-ava-suite/moodle-local_suap
   :target: https://github.com/suap-ava-suite/moodle-local_suap/releases
   :alt: Latest release

.. image:: https://img.shields.io/badge/Moodle-4.1%20--%204.3-orange.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/blob/main/.github/workflows/ci.yml
   :alt: Moodle compatibility

.. image:: https://img.shields.io/badge/PHP-8.3-777bb4.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/blob/main/.github/workflows/ci.yml
   :alt: PHP compatibility

.. image:: https://github.com/suap-ava-suite/moodle-local_suap/actions/workflows/docs.yml/badge.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/actions/workflows/docs.yml
   :alt: Build & Deploy Documentation

``local_suap`` is a ``local`` plugin for Moodle that implements the Moodle-side core of the SUAP/AVA Suite integration: it exposes a token-authenticated HTTP API that receives course structures, enrolments, cohorts, and grades from SUAP and materializes them as categories, courses (rooms), users, enrolments, groups, and cohorts in Moodle. It also maintains custom user and course profile fields, scheduled report tasks, and administration pages for monitoring synchronizations.

Contents
--------

.. toctree::
   :maxdepth: 2

   visao-geral
   instalacao
   api
   sincronizacao
   administracao
   faq
   desenvolvimento
