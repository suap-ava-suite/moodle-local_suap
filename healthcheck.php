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

require_once("../../config.php");
$pgversion = $DB->get_record_sql('SELECT version()');

echo "<pre>\n";
echo "\nHostname: " . gethostname();
echo "\nPHP: " . phpversion();
echo "\nMoodle: {$CFG->release}";
echo "\nBanco: {$pgversion->version}";
var_dump($CFG->dboptions);
echo "\nWWW root: {$CFG->wwwroot}";
echo "\nSession handler: {$CFG->session_handler_class}";
echo "\nReverse proxy: {$CFG->reverseproxy}";
echo "\nSSL proxy: {$CFG->sslproxy}";
echo "\nCache JS: {$CFG->cachejs}";
echo "\nCache Template: {$CFG->cachetemplates}";
echo "\nCache Lang String: {$CFG->langstringcache}";
echo "\nRota no .htaccess: OK";
echo "\nTudo bem até aqu: sim.";
