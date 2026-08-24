# docs/conf.py
import os
import sys

import moodle_docs_theme

sys.path.insert(0, os.path.abspath(".."))

project = "local_suap"

extensions = [
    "sphinx.ext.githubpages",
    "moodle_docs_theme",
]

templates_path = ["_templates"]
exclude_patterns = ["_build", "Thumbs.db", ".DS_Store"]

root_doc = "index"

html_theme = "moodle_docs_theme"
html_theme_path = [moodle_docs_theme.get_html_theme_path()]
html_static_path = ["_static"]

html_theme_options = {
    "project_name": "local_suap",
    "tagline": "Plugin local do Moodle que integra estrutura de cursos, usuários, inscrições e coortes com o SUAP",
    "github_url": "https://github.com/suap-ava-suite/moodle-local_suap",
    "github_repo": "suap-ava-suite/moodle-local_suap",
    "github_version": "main",
    "doc_path": "docs/",
    "show_edit_on_github": True,
    "enable_dark_mode": True,
    "navigation_links": (
        "Início|index, Visão geral|visao-geral, Instalação|instalacao, "
        "API e serviços|api, Sincronização SUAP → Moodle|sincronizacao, "
        "Administração|administracao, Perguntas frequentes|faq, "
        "Desenvolvimento|desenvolvimento"
    ),
}
