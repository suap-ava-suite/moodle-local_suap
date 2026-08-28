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

$string['pluginname'] = 'SUAP-integratie';
$string['suap:adminview'] = 'SUAP-beheer bekijken';

// Auth token
$string['auth_token_header'] = 'Authenticatietoken';
$string['auth_token_header_desc'] = 'Het token dat door SUAP wordt gebruikt om zich te authenticeren bij deze Moodle-installatie';
$string["auth_token"] = 'SUAP authenticatietoken';
$string["auth_token_desc"] = 'Het token dat door SUAP wordt gebruikt om zich te authenticeren bij deze Moodle-installatie';

$string['painel_url'] = 'URL van het AVA-paneel';
$string['painel_url_desc'] = '(bijv. https://ava.ifrn.edu.br)';

// Categories
$string['top_category_header'] = 'Hoofdcategorie';
$string['top_category_header_desc'] = 'Standaardinstellingen voor hoofdcategorie';
$string["top_category_idnumber"] = 'ID-nummer van hoofdcategorie';
$string["top_category_idnumber_desc"] = 'Gebruikt om te bepalen waar nieuwe cursussen worden geplaatst. Als er geen categorie met dit ID-nummer bestaat, wordt een nieuwe categorie gemaakt';
$string["top_category_name"] = 'Naam van hoofdcategorie';
$string["top_category_name_desc"] = 'Alleen gebruikt voor het maken van de nieuwe hoofdcategorie';
$string["top_category_parent"] = 'Bovenliggende categorie van hoofdcategorie';
$string["top_category_parent_desc"] = 'Alleen gebruikt voor het maken van de nieuwe hoofdcategorie';

// New user and new enrolment defaults
$string['user_and_enrolment_header'] = 'Standaarden voor nieuwe gebruikers en inschrijvingen';
$string['user_and_enrolment_header_desc'] = 'Standaardinstellingen voor nieuwe gebruikers en inschrijvingen';

// User preferences
$string["default_user_preferences"] = 'Standaard gebruikersvoorkeuren';
$string["default_user_preferences_desc"] = 'Elke nieuwe gebruiker (student of docent) krijgt deze voorkeuren. Gebruik één regel per voorkeur, zoals in een .ini-bestand.';

// Roles mapping
$string["roles_mapping"] = 'Rollenkoppeling';
$string["roles_mapping_desc"] = 'Koppeling van SUAP-rollen aan Moodle-rollen, velden: (tipo_sala:papel_suap:role_shortname:enrol_type). Room-type (tipo_sala) kan zijn: diarios, coordenacoes, autoinscricoes, praticas, modelos of default. SUAP-rol (papel_suap) kan bijvoorbeeld zijn: Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo of Secretário de Curso. Role shortname is de korte naam van de Moodle-rol. Inschrijftype kan zijn: manual, self, guest, etc.';

// Default authentication method
$string["default_auth"] = 'Standaard authenticatiemethode';
$string["default_auth_desc"] = 'Standaard authenticatiemethode voor nieuwe gebruikers. We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw gebruikers zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';

// Authentication methods mapping
$string["auths_mapping"] = 'Koppeling van authenticatiemethoden';
$string["auths_mapping_desc"] = 'Koppeling van authenticatiemethode voor elke SUAP-rol, velden: (papel_suap:auth). SUAP-rol kan zijn: Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo of Secretário de Curso. Auth is de korte naam van de Moodle-authenticatiemethode voor gebruikers met die SUAP-rol.';

// Student
$string["student_settings_header"] = 'Studentensynchronisatie';
$string["student_settings_header_desc"] = 'Instellingen voor studentensynchronisatie';
$string["default_student_auth"] = 'Standaard authenticatiemethode voor nieuwe studentgebruikers';
$string["default_student_auth_desc"] = 'We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw studenten zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';
$string["default_student_role_id"] = 'Standaard rol-ID voor studentinschrijving';
$string["default_student_role_id_desc"] = 'Normaliter 5. Waarom? Dit is de Moodle-standaard.';
$string["default_student_enrol_type"] = 'Standaard inschrijftype voor studentinschrijving';
$string["default_student_enrol_type_desc"] = 'Normaliter handmatig (manual). Waarom? Omdat nieuwe studenten worden ingeschreven in SUAP en gesynchroniseerd met Moodle';

// Teacher
$string["teacher_settings_header"] = 'Docentensynchronisatie';
$string["teacher_settings_header_desc"] = 'Instellingen voor docentensynchronisatie';
$string["default_teacher_auth"] = 'Standaard authenticatiemethode voor nieuwe docentgebruikers';
$string["default_teacher_auth_desc"] = 'We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw docenten zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';
$string["default_teacher_role_id"] = 'Standaard rol-ID voor docentinschrijving';
$string["default_teacher_role_id_desc"] = 'Normaliter 5. Waarom? Dit is de Moodle-standaard.';
$string["default_teacher_enrol_type"] = 'Standaard inschrijftype voor docentinschrijving';
$string["default_teacher_enrol_type_desc"] = 'Normaliter handmatig (manual). Waarom? Omdat nieuwe docenten worden ingeschreven in SUAP en gesynchroniseerd met Moodle';

// Tutores
$string["assistant_settings_header"] = 'Tutorsynchronisatie';
$string["assistant_settings_header_desc"] = 'Instellingen voor tutorsynchronisatie';
$string["default_assistant_auth"] = 'Standaard authenticatiemethode voor nieuwe tutorgebruikers';
$string["default_assistant_auth_desc"] = 'We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw tutors zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';
$string["default_assistant_role_id"] = 'Standaard rol-ID voor tutorinschrijving';
$string["default_assistant_role_id_desc"] = 'Normaliter 5. Waarom? Dit is de Moodle-standaard.';
$string["default_assistant_enrol_type"] = 'Standaard inschrijftype voor tutorinschrijving';
$string["default_assistant_enrol_type_desc"] = 'Normaliter handmatig (manual). Waarom? Omdat nieuwe tutors worden ingeschreven in SUAP en gesynchroniseerd met Moodle';

// Docentes nas salas de coordenação
$string["instructor_settings_header"] = 'Synchronisatie van medewerkers in coördinatieruimtes';
$string["instructor_settings_header_desc"] = 'Instellingen voor synchronisatie van medewerkers in coördinatieruimtes';
$string["default_instructor_auth"] = 'Standaard authenticatiemethode voor nieuwe medewerkers in coördinatieruimtes';
$string["default_instructor_auth_desc"] = 'We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw medewerkers zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';
$string["default_instructor_role_id"] = 'Standaard rol-ID voor medewerkerinschrijving in coördinatieruimtes';
$string["default_instructor_role_id_desc"] = 'Normaliter 4. Waarom? Dit is de Moodle-standaard voor niet-bewerkende docenten.';
$string["default_instructor_enrol_type"] = 'Standaard inschrijftype voor medewerkerinschrijving in coördinatieruimtes';
$string["default_instructor_enrol_type_desc"] = 'Normaliter handmatig (manual). Waarom? Omdat новые medewerkers in coördinatieruimtes worden ingeschreven in SUAP en gesynchroniseerd met Moodle';

// Formador
$string["former_settings_header"] = 'Opleiderssynchronisatie';
$string["former_settings_header_desc"] = 'Instellingen voor opleiderssynchronisatie';
$string["default_former_auth"] = 'Standaard authenticatiemethode voor nieuwe opleiders in coördinatieruimtes';
$string["default_former_auth_desc"] = 'We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw opleiders zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';
$string["default_former_role_id"] = 'Standaard rol-ID voor opleiderinschrijving in coördinatieruimtes';
$string["default_former_role_id_desc"] = 'Normaliter 4. Waarom? Dit is de Moodle-standaard voor niet-bewerkende docenten.';
$string["default_former_enrol_type"] = 'Standaard inschrijftype voor opleiderinschrijving in coördinatieruimtes';
$string["default_former_enrol_type_desc"] = 'Normaliter handmatig (manual). Waarom? Omdat nieuwe opleiders in coördinatieruimtes worden ingeschreven in SUAP en gesynchroniseerd met Moodle';

// Mediador
$string["moderator_settings_header"] = 'Moderatorsynchronisatie';
$string["moderator_settings_header_desc"] = 'Instellingen voor moderatorsynchronisatie';
$string["default_moderator_auth"] = 'Standaard authenticatiemethode voor nieuwe moderators in coördinatieruimtes';
$string["default_moderator_auth_desc"] = 'We raden aan om OAuth met SUAP te configureren, maar de keuze is aan u. Waarom OAuth? Omdat uw moderators zo kunnen profiteren van SSO en het AVA-paneel voor SUAP.';
$string["default_moderator_role_id"] = 'Standaard rol-ID voor moderatorinschrijving in coördinatieruimtes';
$string["default_moderator_role_id_desc"] = 'Normaliter 4. Waarom? Dit is de Moodle-standaard voor niet-bewerkende docenten.';
$string["default_moderator_enrol_type"] = 'Standaard inschrijftype voor moderatorinschrijving in coördinatieruimtes';
$string["default_moderator_enrol_type_desc"] = 'Normaliter handmatig (manual). Waarom? Omdat nieuwe moderators in coördinatieruimtes worden ingeschreven in SUAP en gesynchroniseerd met Moodle';

// Task
$string["sync_up_enrolments_task"] = 'Inschrijvingen synchroniseren taak';
$string["sync_up_enrolments_task_desc"] = 'Inschrijvingen synchroniseren taak';
$string["generate_report_task"] = 'Rapport van zelfstudiecursussen maken';

// Notas
$string["notes_to_sync_header"] = 'Cijfersynchronisatie';
$string["notes_to_sync_header_desc"] = 'Instellingen voor cijfersynchronisatie';
$string["notes_to_sync"] = 'Te synchroniseren cijfers';
$string["notes_to_sync_desc"] = 'Te synchroniseren cijfers';

// Grupos do curso
$string['groups_in_course_header'] = 'Groepen in cursus';
$string['groups_in_course_header_desc'] = 'Instellingen voor groepen in cursus';
$string["course_group_entrada"] = 'Groepen synchroniseren voor instroom (entrada)';
$string["course_group_entrada_desc"] = 'Groepen synchroniseren voor instroom (entrada)';
$string["course_group_turma"] = 'Groepen synchroniseren voor klas (turma)';
$string["course_group_turma_desc"] = 'Groepen synchroniseren voor klas (turma)';
$string["course_group_polo"] = 'Groepen synchroniseren voor locatie/centrum (polo)';
$string["course_group_polo_desc"] = 'Groepen synchroniseren voor locatie/centrum (polo)';
$string["course_group_programa"] = 'Groepen synchroniseren voor programma';
$string["course_group_programa_desc"] = 'Groepen synchroniseren voor programma';

// Grupos da sala de coordenação
$string['groups_in_room_header'] = 'Groepen in coördinatieruimte';
$string['groups_in_room_header_desc'] = 'Instellingen voor groepen in coördinatieruimte';
$string["room_group_entrada"] = 'Groepen synchroniseren voor instroom (entrada)';
$string["room_group_entrada_desc"] = 'Groepen synchroniseren voor instroom (entrada)';
$string["room_group_turma"] = 'Groepen synchroniseren voor klas (turma)';
$string["room_group_turma_desc"] = 'Groepen synchroniseren voor klas (turma)';
$string["room_group_polo"] = 'Groepen synchroniseren voor locatie/centrum (polo)';
$string["room_group_polo_desc"] = 'Groepen synchroniseren voor locatie/centrum (polo)';
$string["room_group_programa"] = 'Groepen synchroniseren voor programma';
$string["room_group_programa_desc"] = 'Groepen synchroniseren voor programma';

// Report UI
$string['last_updated'] = 'Laatste update:';
$string['campus'] = 'Campus';
$string['total_enrolled'] = 'Totaal ingeschreven';
$string['accessed'] = 'Geopend';
$string['never_accessed'] = 'Nooit geopend';
$string['pct_access'] = '% Toegang';
$string['final_exam_takers'] = 'Eindbeoordeling gemaakt';
$string['pct_final_exam_takers'] = '% Eindbeoordeling gemaakt';
$string['passed'] = 'Geslaagd';
$string['failed'] = 'Gezakt';
$string['pct_passed'] = '% Geslaagd';
$string['avg_grade'] = 'Gemiddeld cijfer';
$string['with_certificate'] = 'Met certificaat';
$string['eligible_without_certificate'] = 'In aanmerking zonder cert.';
$string['completed'] = 'Voltooid';
$string['pct_completed'] = '% Voltooid';

// Admin & Integration Views UI
$string['view_integrations'] = 'Integraties bekijken';
$string['search_placeholder'] = 'Zoeken...';
$string['timecreated'] = 'Aanmaakdatum';
$string['status'] = 'Status';
$string['viewing_integration'] = 'Integratie bekijken';
$string['when'] = 'Wanneer';
$string['logs'] = 'Logs';
$string['view_logs'] = 'Logs bekijken';
$string['json'] = 'JSON';
$string['admin_title'] = 'SUAP Sync Beheer';
$string['unauthorized_access'] = 'Onbevoegde toegang';
$string['status_unprocessed'] = 'Niet verwerkt';
$string['status_success'] = 'Geslaagd';
$string['status_failed'] = 'Mislukt';
$string['status_unknown'] = 'Onbekend';
$string['request_not_found'] = 'Verzoek niet gevonden.';
$string['no_task_logs_found'] = 'Geen taaklogboeken gevonden voor dit verzoek.';
$string['log_id'] = 'Log ID';
$string['start_time'] = 'Begintijd';
$string['end_time'] = 'Eindtijd';
$string['result'] = 'Resultaat';
$string['hostname'] = 'Hostnaam';
$string['pid'] = 'PID';
$string['action'] = 'Actie';
$string['open'] = 'Openen';
$string['request_label'] = 'Verzoek #';
$string['search_label'] = 'Zoeken: ';
$string['invalid_session'] = 'Ongeldige sessie. Probeer het opnieuw.';
