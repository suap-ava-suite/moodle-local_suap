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

require_once('../../config.php');

if (property_exists($_SESSION['USER'], 'sesskey')) {
    $wantsurl = "$CFG->wwwroot{$_SERVER['REQUEST_URI']}";
    $sesskey = $_SESSION['USER']->sesskey;
    redirect("$CFG->wwwroot/auth/oauth2/login.php?id=1&sesskey=$sesskey&wantsurl=$wantsurl");
} else {
    // require_once "../../login/logout.php";
    redirect("$CFG->wwwroot/auth/oauth2/login.php?errorcode=4&id=1&sesskey=$sesskey&wantsurl=$wantsurl");
    echo "Sessão inválida. Por favor, <a href='$CFG->wwwroot'>tente novamente</a>.";
}
