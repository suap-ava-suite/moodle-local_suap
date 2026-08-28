# docs/en/conf.py
import os
import sys

import moodle_docs_theme

sys.path.insert(0, os.path.abspath(".."))

project = "local_suap"
language = "en"

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
    "tagline": "Moodle local plugin that integrates course structures, users, enrolments, and cohorts with SUAP",
    "github_url": "https://github.com/suap-ava-suite/moodle-local_suap",
    "github_repo": "suap-ava-suite/moodle-local_suap",
    "github_version": "main",
    "doc_path": "docs/en/",
    "show_edit_on_github": True,
    "enable_dark_mode": True,
    "navigation_links": (
        "Home|index, Overview|visao-geral, Installation|instalacao, "
        "API & Services|api, SUAP → Moodle Synchronization|sincronizacao, "
        "Administration|administracao, FAQ|faq, "
        "Development|desenvolvimento"
    ),
}
