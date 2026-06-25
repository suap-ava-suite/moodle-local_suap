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

/**
 * Local stuff for category enrolment plugin.
 *
 * @package    local_suap
 * @copyright  2022 kelson Medeiros {@link https://github.com/kelsoncm}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


class local_suap_observer {
    public static function user_enrolment_created(\core\event\user_enrolment_created $event) {
        global $DB;
    }

    public static function user_enrolment_deleted(\core\event\user_enrolment_deleted $event) {
        global $DB;
    }

    public static function user_enrolment_updated(\core\event\user_enrolment_updated $event) {
        global $DB;
    }

    public static function user_created(\core\event\user_created $event) {
        global $DB;
        $default_user_preferences = get_config('local_suap', 'default_user_preferences');
        $data = $event->get_data();
        $user = $DB->get_record("user", ["id" => $data['objectid']]);

        // foreach (preg_split('/\r\n|\r|\n/', $default_user_preferences) as $preference) {
        // $parts = explode("=", $preference);
        // if (count($parts) == 2) {
        // \set_user_preference($parts[0], $parts[1], $user);
        // }
        // }
    }

    public static function user_deleted(\core\event\user_deleted $event) {
        global $DB;
    }

    public static function user_updated(\core\event\user_updated $event) {
        global $DB;
    }
}
