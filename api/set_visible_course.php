<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace local_suap;

// Desabilita verificação CSRF para esta API
if (!defined('NO_MOODLE_COOKIES')) {
    define('NO_MOODLE_COOKIES', true);
}

require_once('../../../config.php');
require_once('../../../course/externallib.php');
require_once('../locallib.php');
require_once("servicelib.php");

class set_visible_course_service extends \local_suap\service
{
    function do_call() {
        global $DB, $USER;

        $USER = $DB->get_record('user', ['username' => strtolower($_GET['username'])]);

        $coursecontext = \context_course::instance($_GET['courseid']);
        if (!has_capability('moodle/course:visibility', $coursecontext, $USER)) {
            throw new \Exception('Sem permissão de alterar a visibilidade deste curso.', 403);
        }

        $course = $DB->get_record('course', ['id' => $_GET['courseid']]);

        $visible = $_GET['visible'];

        return $this->execute($course, $visible);
    }

    function execute($course, $visible) {
        global $DB;

        $course->visible = $visible;
        $DB->update_record('course', $course);
        return ["error" => false];
    }
}
