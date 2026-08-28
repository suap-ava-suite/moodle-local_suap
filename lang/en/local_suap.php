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
 * Plugin strings are defined here.
 *
 * @package     local_suap
 * @category    string
 * @copyright   2022 Kelson Medeiros <kelsoncm@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'SUAP Integration';
$string['suap:adminview'] = 'View SUAP admin';

// Auth token
$string['auth_token_header'] = 'Authentication token';
$string['auth_token_header_desc'] = 'The token that will be used by SUAP to authenticate itself with this Moodle installation';
$string["auth_token"] = 'SUAP auth token';
$string["auth_token_desc"] = 'The token that will be used by SUAP to authenticate itself with this Moodle installation';

$string['painel_url'] = 'Painel AVA URL';
$string['painel_url_desc'] = '(ex: https://ava.ifrn.edu.br)';

// Categories
$string['top_category_header'] = 'Top category';
$string['top_category_header_desc'] = 'Top category default settings';
$string["top_category_idnumber"] = 'Top category ID number';
$string["top_category_idnumber_desc"] = 'Used to identify where to place new courses. If a category with this ID number does not exist, a new category with this ID number will be created';
$string["top_category_name"] = 'Top category name';
$string["top_category_name_desc"] = 'Used only to create the new top category';
$string["top_category_parent"] = 'Top category parent';
$string["top_category_parent_desc"] = 'Used only to create the new top category';

// New user and new enrolment defaults
$string['user_and_enrolment_header'] = 'New user and new enrolment defaults';
$string['user_and_enrolment_header_desc'] = 'Default settings for new users and enrolments';

// User preferences
$string["default_user_preferences"] = 'Default user preferences';
$string["default_user_preferences_desc"] = 'Every new user (student or teacher) will have these preferences. Use one line per preference, like a .ini file.';

// Roles mapping
$string["roles_mapping"] = 'Roles mapping';
$string["roles_mapping_desc"] = 'Mapping of SUAP roles to Moodle roles, fields: (tipo_sala:papel_suap:role_shortname:enrol_type). Room type (tipo_sala) can be: diarios, coordenacoes, autoinscricoes, praticas, modelos, or default. SUAP role (papel_suap) can be: Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo, or Secretário de Curso, for example. Role shortname is the Moodle role shortname to be used in enrolment. Enrolment type can be: manual, self, guest, etc.';

// Default authentication method
$string["default_auth"] = 'Default authentication method';
$string["default_auth_desc"] = 'Default authentication method for new users. We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your users can benefit from SSO and Painel AVA for SUAP.';

// Authentication methods mapping
$string["auths_mapping"] = 'Authentication methods mapping';
$string["auths_mapping_desc"] = 'Authentication method mapping for each SUAP role, fields: (papel_suap:auth). SUAP role can be: Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo, or Secretário de Curso. Auth is the shortname of the Moodle authentication method to be used for users with that SUAP role.';

// Student
$string["student_settings_header"] = 'Student synchronization';
$string["student_settings_header_desc"] = 'Student synchronization settings';
$string["default_student_auth"] = 'Default authentication method for new student users';
$string["default_student_auth_desc"] = 'We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your students can benefit from SSO and Painel AVA for SUAP.';
$string["default_student_role_id"] = 'Default role ID for student enrolment';
$string["default_student_role_id_desc"] = 'Normally 5. Why? This is the Moodle default.';
$string["default_student_enrol_type"] = 'Default enrol_type for student enrolment';
$string["default_student_enrol_type_desc"] = 'Normally manual. Why? Because new students will be enrolled on SUAP and synched to Moodle';

// Teacher in course
$string["teacher_settings_header"] = 'Teacher synchronization';
$string["teacher_settings_header_desc"] = 'Teacher synchronization settings';
$string["default_teacher_auth"] = 'Default authentication method for new teacher users';
$string["default_teacher_auth_desc"] = 'We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your teachers can benefit from SSO and Painel AVA for SUAP.';
$string["default_teacher_role_id"] = 'Default role ID for teacher enrolment';
$string["default_teacher_role_id_desc"] = 'Normally 5. Why? This is the Moodle default.';
$string["default_teacher_enrol_type"] = 'Default enrol_type for teacher enrolment';
$string["default_teacher_enrol_type_desc"] = 'Normally manual. Why? Because new teachers will be enrolled on SUAP and synched to Moodle';

// Assistant in course
$string["assistant_settings_header"] = 'Tutor synchronization';
$string["assistant_settings_header_desc"] = 'Tutor synchronization settings';
$string["default_assistant_auth"] = 'Default authentication method for new tutor users';
$string["default_assistant_auth_desc"] = 'We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your tutors can benefit from SSO and Painel AVA for SUAP.';
$string["default_assistant_role_id"] = 'Default role ID for tutor enrolment';
$string["default_assistant_role_id_desc"] = 'Normally 5. Why? This is the Moodle default.';
$string["default_assistant_enrol_type"] = 'Default enrol_type for tutor enrolment';
$string["default_assistant_enrol_type_desc"] = 'Normally manual. Why? Because new tutors will be enrolled on SUAP and synched to Moodle';

// Instructor in course
$string["instructor_settings_header"] = 'Coordination room staff synchronization';
$string["instructor_settings_header_desc"] = 'Coordination room staff synchronization settings';
$string["default_instructor_auth"] = 'Default authentication method for new staff users in coordination rooms';
$string["default_instructor_auth_desc"] = 'We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your staff can benefit from SSO and Painel AVA for SUAP.';
$string["default_instructor_role_id"] = 'Default role ID for staff enrolment in coordination rooms';
$string["default_instructor_role_id_desc"] = 'Normally 4. Why? This is the Moodle default for non-editing teachers.';
$string["default_instructor_enrol_type"] = 'Default enrol_type for staff enrolment in coordination rooms';
$string["default_instructor_enrol_type_desc"] = 'Normally manual. Why? Because new staff in coordination rooms will be enrolled on SUAP and synched to Moodle';

// Former in course
$string["former_settings_header"] = 'Trainer synchronization';
$string["former_settings_header_desc"] = 'Trainer synchronization settings';
$string["default_former_auth"] = 'Default authentication method for new trainer users in coordination rooms';
$string["default_former_auth_desc"] = 'We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your trainers can benefit from SSO and Painel AVA for SUAP.';
$string["default_former_role_id"] = 'Default role ID for trainer enrolment in coordination rooms';
$string["default_former_role_id_desc"] = 'Normally 4. Why? This is the Moodle default for non-editing teachers.';
$string["default_former_enrol_type"] = 'Default enrol_type for trainer enrolment in coordination rooms';
$string["default_former_enrol_type_desc"] = 'Normally manual. Why? Because new trainers in coordination rooms will be enrolled on SUAP and synched to Moodle';

// Moderator in course
$string["moderator_settings_header"] = 'Moderator synchronization';
$string["moderator_settings_header_desc"] = 'Moderator synchronization settings';
$string["default_moderator_auth"] = 'Default authentication method for new moderator users in coordination rooms';
$string["default_moderator_auth_desc"] = 'We recommend configuring OAuth with SUAP, but the choice is yours. Why OAuth? Because your moderators can benefit from SSO and Painel AVA for SUAP.';
$string["default_moderator_role_id"] = 'Default role ID for moderator enrolment in coordination rooms';
$string["default_moderator_role_id_desc"] = 'Normally 4. Why? This is the Moodle default for non-editing teachers.';
$string["default_moderator_enrol_type"] = 'Default enrol_type for moderator enrolment in coordination rooms';
$string["default_moderator_enrol_type_desc"] = 'Normally manual. Why? Because new moderators in coordination rooms will be enrolled on SUAP and synched to Moodle';

// Task
$string["sync_up_enrolments_task"] = 'Sync Up Enrolments Task';
$string["sync_up_enrolments_task_desc"] = 'Sync Up Enrolments Task';
$string["generate_report_task"] = 'Create a report of self-instructional courses';

// Notas
$string["notes_to_sync_header"] = 'Grades synchronization';
$string["notes_to_sync_header_desc"] = 'Grades synchronization settings';
$string["notes_to_sync"] = 'Grades to synchronize';
$string["notes_to_sync_desc"] = 'Grades to synchronize';

// Grupos do curso
$string['groups_in_course_header'] = 'Groups in course';
$string['groups_in_course_header_desc'] = 'Groups in course settings';
$string["course_group_entrada"] = 'Sync groups for intake (entrada)';
$string["course_group_entrada_desc"] = 'Sync groups for intake (entrada)';
$string["course_group_turma"] = 'Sync groups for class (turma)';
$string["course_group_turma_desc"] = 'Sync groups for class (turma)';
$string["course_group_polo"] = 'Sync groups for campus/center (polo)';
$string["course_group_polo_desc"] = 'Sync groups for campus/center (polo)';
$string["course_group_programa"] = 'Sync groups for program (programa)';
$string["course_group_programa_desc"] = 'Sync groups for program (programa)';

// Grupos da sala de coordenação
$string['groups_in_room_header'] = 'Groups in coordination room';
$string['groups_in_room_header_desc'] = 'Groups in coordination room settings';
$string["room_group_entrada"] = 'Sync groups for intake (entrada)';
$string["room_group_entrada_desc"] = 'Sync groups for intake (entrada)';
$string["room_group_turma"] = 'Sync groups for class (turma)';
$string["room_group_turma_desc"] = 'Sync groups for class (turma)';
$string["room_group_polo"] = 'Sync groups for campus/center (polo)';
$string["room_group_polo_desc"] = 'Sync groups for campus/center (polo)';
$string["room_group_programa"] = 'Sync groups for program (programa)';
$string["room_group_programa_desc"] = 'Sync groups for program (programa)';

// Report UI
$string['last_updated'] = 'Last updated:';
$string['campus'] = 'Campus';
$string['total_enrolled'] = 'Total Enrolled';
$string['accessed'] = 'Accessed';
$string['never_accessed'] = 'Never Accessed';
$string['pct_access'] = '% Access';
$string['final_exam_takers'] = 'Took Final Assessment';
$string['pct_final_exam_takers'] = '% Took Final Assessment';
$string['passed'] = 'Passed';
$string['failed'] = 'Failed';
$string['pct_passed'] = '% Passed';
$string['avg_grade'] = 'Average Grade';
$string['with_certificate'] = 'With Certificate';
$string['eligible_without_certificate'] = 'Eligible without Cert.';
$string['completed'] = 'Completed';
$string['pct_completed'] = '% Completed';

// Admin & Integration Views UI
$string['view_integrations'] = 'View integrations';
$string['search_placeholder'] = 'Search...';
$string['timecreated'] = 'Time Created';
$string['status'] = 'Status';
$string['viewing_integration'] = 'Viewing integration';
$string['when'] = 'When';
$string['logs'] = 'Logs';
$string['view_logs'] = 'View logs';
$string['json'] = 'JSON';
$string['admin_title'] = 'SUAP Sync Admin';
$string['unauthorized_access'] = 'Unauthorized access';
$string['status_unprocessed'] = 'Unprocessed';
$string['status_success'] = 'Success';
$string['status_failed'] = 'Failed';
$string['status_unknown'] = 'Unknown';
$string['request_not_found'] = 'Request not found.';
$string['no_task_logs_found'] = 'No task logs found for this request.';
$string['log_id'] = 'Log ID';
$string['start_time'] = 'Start Time';
$string['end_time'] = 'End Time';
$string['result'] = 'Result';
$string['hostname'] = 'Hostname';
$string['pid'] = 'PID';
$string['action'] = 'Action';
$string['open'] = 'Open';
$string['request_label'] = 'Request #';
$string['search_label'] = 'Search: ';
$string['invalid_session'] = 'Invalid session. Please try again.';
