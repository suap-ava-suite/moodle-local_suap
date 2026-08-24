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

.. image:: https://img.shields.io/badge/PHP-7.4%20%7C%208.0%20%7C%208.1-777bb4.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/blob/main/.github/workflows/ci.yml
   :alt: PHP compatibility

.. image:: https://github.com/suap-ava-suite/moodle-local_suap/actions/workflows/docs.yml/badge.svg
   :target: https://github.com/suap-ava-suite/moodle-local_suap/actions/workflows/docs.yml
   :alt: Build & Deploy Documentation

``local_suap`` é um plugin ``local`` para o Moodle que implementa o lado "de dentro do Moodle" da integração
SUAP/AVA Suite: expõe uma API HTTP autenticada por token que recebe estruturas de curso, matrículas, coortes e
notas vindas do SUAP e as materializa como categorias, cursos (salas), usuários, inscrições, grupos e coortes no
Moodle. Também mantém campos de perfil e de curso customizados, tarefas agendadas de relatório e páginas
administrativas para acompanhar as sincronizações.

Conteúdo
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
