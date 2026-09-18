Desenvolvimento
================

Versionamento
-------------

``version.php`` segue o mesmo padrão observado nos demais plugins da suíte:

* ``$plugin->version`` no formato ``YYYY_MM_DD_XXX``, onde ``YYYY_MM_DD`` reflete a data da alteração e ``XXX`` é
  um contador de 3 dígitos.
* ``$plugin->release`` no formato ``4.5.XXX``, com o mesmo ``XXX`` usado em ``version``.
* Alterações que adicionam um novo passo de upgrade em ``db/upgrade.php`` (um novo ``upgrade_plugin_savepoint(...)``)
  precisam incrementar ``version``/``release`` — é isso que o passo **Check upgrade savepoints**
  (``moodle-plugin-ci savepoints``) do workflow de CI valida.
* O workflow ``release.yml`` valida, adicionalmente, que os 3 últimos dígitos de ``version`` e ``release``
  coincidem, e que ``release`` corresponde exatamente ao nome da tag Git publicada.

.. note::
   Esta documentação (pasta ``docs/``) não altera ``db/`` nem ``lang/`` e não introduz nenhum novo savepoint —
   por isso, sua adição não exige incrementar ``version.php``.

CI/CD
-----

``.github/workflows/ci.yml`` — **Moodle Plugin CI**
    Executa em todo *push* e *pull request* para ``main``. Usa ``moodlehq/moodle-plugin-ci`` em uma matriz de PHP
    (``7.4``, ``8.0``, ``8.1``) × Moodle (``MOODLE_401_STABLE``, ``MOODLE_402_STABLE``, ``MOODLE_403_STABLE``) ×
    banco (``pgsql``, ``mariadb``). Etapas: PHP Lint, PHP Copy/Paste Detector e PHP Mess Detector (não
    bloqueantes), Moodle Code Checker (PHPCS, 0 *warnings* tolerados), Moodle PHPDoc Checker (0 *warnings*),
    ``validate``, ``savepoints`` (valida o versionamento acima), Mustache Lint, Grunt (não bloqueante), PHPUnit
    (``--fail-on-warning``) e Behat com Chrome.

``.github/workflows/release.yml`` — **Release**
    Disparado por *push* de qualquer tag (``git tag -a 4.5.XXX -m "..."; git push origin 4.5.XXX``). Extrai
    ``version``/``release``/``component`` de ``version.php``, valida a correspondência descrita acima, empacota um
    ZIP instalável (``local_suap-<version>.zip``, com o conteúdo do repositório copiado para uma pasta chamada
    ``suap`` — o nome do componente sem o prefixo ``local_``, excluindo ``.git``, ``.github``, ``node_modules``,
    ``.gitignore``, ``tests`` e ``vendor``) e publica uma GitHub Release com notas geradas automaticamente. O ZIP
    pode ser instalado diretamente em **Administração do site → Plugins → Instalar plugins**.

``.github/workflows/docs.yml`` — **Build & Deploy Documentation**
    Publica esta documentação (Sphinx) no GitHub Pages a cada *push* em ``main`` que altere ``docs/**`` ou o
    próprio workflow. Veja abaixo.

Documentação
------------

Esta documentação usa `Sphinx <https://www.sphinx-doc.org/>`_ com o tema
`moodle-docs-theme <https://pypi.org/project/moodle-docs-theme/>`_ e arquivos ``.rst`` em ``docs/pt-br/`` e ``docs/en/``. Para gerar localmente a versão em Português:

.. code-block:: bash

   pip install sphinx moodle-docs-theme
   sphinx-build -W -b html docs/pt-br docs/_build/html/pt-br

Para gerar localmente a versão em Inglês:

.. code-block:: bash

   sphinx-build -W -b html docs/en docs/_build/html/en

O workflow ``docs.yml`` roda esses mesmos comandos em CI e publica o resultado via ``actions/deploy-pages``.

Empacotamento manual
-----------------------

O workflow de release automatiza o empacotamento, mas o mesmo resultado pode ser reproduzido localmente: copiar o
conteúdo do repositório para uma pasta chamada ``suap`` (o nome do componente sem o prefixo ``local_``),
excluindo ``.git``, ``.github``, ``node_modules``, ``.gitignore``, ``tests`` e ``vendor``, e compactar essa pasta
em ``local_suap-<version>.zip``.

Convenção de commits
-----------------------

O ``README.md`` deste repositório define os seguintes prefixos de commit:

.. list-table::
   :header-rows: 1
   :widths: 20 80

   * - Prefixo
     - Uso
   * - ``feat:``
     - Novas funcionalidades.
   * - ``fix:``
     - Correção de bugs.
   * - ``refactor:``
     - Refatoração ou performance (sem impacto em lógica).
   * - ``style:``
     - Estilo ou formatação de código (sem impacto em lógica).
   * - ``test:``
     - Testes.
   * - ``doc:``
     - Documentação no código ou do repositório.
   * - ``env:``
     - CI/CD ou *settings*.
   * - ``build:``
     - Build ou dependências.

Como contribuir: pre-commit com ``act``
---------------------------------------

O hook de *pre-commit* (``.pre-commit-config.yaml``) executa localmente o mesmo workflow de CI do GitHub
(``.github/workflows/ci.yml``, job ``ci``) usando o `act <https://nektosact.com/>`_, dentro do Docker. Por isso **não é
necessário ter PHP (nem Moodle) instalado**: basta ter Python, pre-commit, Docker e ``act``.

Requisitos:

* `Python <https://www.python.org/>`_ 3 e o `pre-commit <https://pre-commit.com/>`_;
* `Docker <https://www.docker.com/>`_ em execução;
* `act <https://nektosact.com/installation/>`_ (no Windows: ``winget install nektos.act``).

Preparação (uma única vez):

.. code-block:: bash

   pip install pre-commit
   pre-commit install

Na primeira execução o ``act`` pergunta qual imagem Docker usar e falha em terminais não interativos. Para evitar isso,
crie o arquivo de configuração do ``act`` (Linux/macOS: ``~/.config/act/actrc``; Windows:
``%LOCALAPPDATA%ctctrc``) com o equivalente à imagem "Medium":

.. code-block:: text

   -P ubuntu-latest=catthehacker/ubuntu:act-latest

A cada ``git commit`` o hook executa:

.. code-block:: bash

   act -j ci --matrix php:8.3 --matrix database:pgsql --matrix moodle-branch:MOODLE_405_STABLE --reuse

Para rodar o hook manualmente, sem commitar: ``pre-commit run --all-files``.

.. note::
   O CI completo no GitHub usa uma matriz maior (Moodle 4.4 e 4.5, ``pgsql`` e ``mariadb``); o hook valida apenas uma
   combinação para manter o tempo razoável. A primeira execução baixa imagens Docker e instala o Moodle, e por isso
   demora bem mais. As etapas marcadas como não bloqueantes no workflow (ex.: Moodle Code Checker) não reprovam o commit.
