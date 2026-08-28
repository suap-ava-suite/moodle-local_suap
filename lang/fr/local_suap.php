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

$string['pluginname'] = 'Intégration SUAP';
$string['suap:adminview'] = 'Voir l\'administration de SUAP';

// Auth token
$string['auth_token_header'] = 'Jeton d\'authentification';
$string['auth_token_header_desc'] = 'Le jeton qui sera utilisé par SUAP pour s\'authentifier sur cette installation Moodle';
$string["auth_token"] = 'Jeton d\'authentification SUAP';
$string["auth_token_desc"] = 'Le jeton qui sera utilisé par SUAP pour s\'authentifier sur cette installation Moodle';

$string['painel_url'] = 'URL du panneau AVA';
$string['painel_url_desc'] = '(ex. https://ava.ifrn.edu.br)';

// Categories
$string['top_category_header'] = 'Catégorie principale';
$string['top_category_header_desc'] = 'Paramètres par défaut de la catégorie principale';
$string["top_category_idnumber"] = 'Numéro d\'identification de la catégorie supérieure';
$string["top_category_idnumber_desc"] = 'Utilisé pour identifier où placer les nouveaux cours. Si aucune catégorie avec cet idnumber n\'existe, une nouvelle catégorie sera créée';
$string["top_category_name"] = 'Nom de la catégorie principale';
$string["top_category_name_desc"] = 'Utilisé uniquement pour créer la nouvelle catégorie principale';
$string["top_category_parent"] = 'Parent de la catégorie supérieure';
$string["top_category_parent_desc"] = 'Utilisé uniquement pour créer la nouvelle catégorie principale';

// New user and new enrolment defaults
$string['user_and_enrolment_header'] = 'Valeurs par défaut pour nouveaux utilisateurs et inscriptions';
$string['user_and_enrolment_header_desc'] = 'Paramètres par défaut pour nouveaux utilisateurs et inscriptions';

// User preferences
$string["default_user_preferences"] = 'Préférences utilisateur par défaut';
$string["default_user_preferences_desc"] = 'Chaque nouvel utilisateur (étudiant ou enseignant) aura ces préférences. Utilisez une ligne par préférence, comme un fichier .ini.';

// Roles mapping
$string["roles_mapping"] = 'Mappage des rôles';
$string["roles_mapping_desc"] = 'Mappage des rôles SUAP vers les rôles Moodle, champs : (tipo_sala:papel_suap:role_shortname:enrol_type). Le type de salle (tipo_sala) peut être : diarios, coordenacoes, autoinscricoes, praticas, modelos ou default. Le rôle SUAP (papel_suap) peut être : Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo ou Secretário de Curso, par exemple. Role shortname est le nom court du rôle Moodle. Le type d\'inscription peut être : manual, self, guest, etc.';

// Default authentication method
$string["default_auth"] = 'Méthode d\'authentification par défaut';
$string["default_auth_desc"] = 'Méthode d\'authentification par défaut pour les nouveaux utilisateurs. Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos utilisateurs peuvent bénéficier du SSO et du panneau AVA pour SUAP.';

// Authentication methods mapping
$string["auths_mapping"] = 'Mappage des méthodes d\'authentification';
$string["auths_mapping_desc"] = 'Mappage des méthodes d\'authentification pour chaque rôle SUAP, champs : (papel_suap:auth). Le rôle SUAP peut être : Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo ou Secretário de Curso. Auth est le nom court de la méthode d\'authentification Moodle à utiliser pour les utilisateurs ayant ce rôle SUAP.';

// Student
$string["student_settings_header"] = 'Synchronisation des étudiants';
$string["student_settings_header_desc"] = 'Paramètres de synchronisation des étudiants';
$string["default_student_auth"] = 'Méthode d\'authentification par défaut pour les nouveaux utilisateurs étudiants';
$string["default_student_auth_desc"] = 'Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos étudiants peuvent bénéficier du SSO et du panneau AVA pour SUAP.';
$string["default_student_role_id"] = 'ID de rôle par défaut pour une inscription d\'étudiant';
$string["default_student_role_id_desc"] = 'Normalement 5. Pourquoi ? C\'est la valeur par défaut de Moodle.';
$string["default_student_enrol_type"] = 'Type d\'inscription par défaut pour un étudiant';
$string["default_student_enrol_type_desc"] = 'Normalement manuel. Pourquoi ? Parce que les nouveaux étudiants seront inscrits dans SUAP et synchronisés avec Moodle';

// Teacher
$string["teacher_settings_header"] = 'Synchronisation des enseignants';
$string["teacher_settings_header_desc"] = 'Paramètres de synchronisation des enseignants';
$string["default_teacher_auth"] = 'Méthode d\'authentification par défaut pour les nouveaux utilisateurs enseignants';
$string["default_teacher_auth_desc"] = 'Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos enseignants peuvent bénéficier du SSO et du panneau AVA pour SUAP.';
$string["default_teacher_role_id"] = 'ID de rôle par défaut pour une inscription d\'enseignant';
$string["default_teacher_role_id_desc"] = 'Normalement 5. Pourquoi ? C\'est la valeur par défaut de Moodle.';
$string["default_teacher_enrol_type"] = 'Type d\'inscription par défaut pour un enseignant';
$string["default_teacher_enrol_type_desc"] = 'Normalement manuel. Pourquoi ? Parce que les nouveaux enseignants seront inscrits dans SUAP et synchronisés avec Moodle';

// Tutores
$string["assistant_settings_header"] = 'Synchronisation des tuteurs';
$string["assistant_settings_header_desc"] = 'Paramètres de synchronisation des tuteurs';
$string["default_assistant_auth"] = 'Méthode d\'authentification par défaut pour les nouveaux utilisateurs tuteurs';
$string["default_assistant_auth_desc"] = 'Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos tuteurs peuvent bénéficier du SSO et du panneau AVA pour SUAP.';
$string["default_assistant_role_id"] = 'ID de rôle par défaut pour une inscription de tuteur';
$string["default_assistant_role_id_desc"] = 'Normalement 5. Pourquoi ? C\'est la valeur par défaut de Moodle.';
$string["default_assistant_enrol_type"] = 'Type d\'inscription par défaut pour un tuteur';
$string["default_assistant_enrol_type_desc"] = 'Normalement manuel. Pourquoi ? Parce que les nouveaux tuteurs seront inscrits dans SUAP et synchronisés avec Moodle';

// Docentes nas salas de coordenação
$string["instructor_settings_header"] = 'Synchronisation des collaborateurs dans les salles de coordination';
$string["instructor_settings_header_desc"] = 'Paramètres de synchronisation des collaborateurs dans les salles de coordination';
$string["default_instructor_auth"] = 'Méthode d\'authentification par défaut pour les nouveaux enseignants dans les salles de coordination';
$string["default_instructor_auth_desc"] = 'Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos enseignants peuvent bénéficier du SSO et du panneau AVA pour SUAP.';
$string["default_instructor_role_id"] = 'ID de rôle par défaut pour une inscription d\'enseignant dans les salles de coordination';
$string["default_instructor_role_id_desc"] = 'Normalement 4. Pourquoi ? C\'est la valeur par défaut de Moodle pour les enseignants non éditeurs.';
$string["default_instructor_enrol_type"] = 'Type d\'inscription par défaut pour un enseignant dans les salles de coordination';
$string["default_instructor_enrol_type_desc"] = 'Normalement manuel. Pourquoi ? Parce que les nouveaux enseignants des salles de coordination seront inscrits dans SUAP et synchronisés avec Moodle';

// Formador
$string["former_settings_header"] = 'Synchronisation des formateurs';
$string["former_settings_header_desc"] = 'Paramètres de synchronisation des formateurs';
$string["default_former_auth"] = 'Méthode d\'authentification par défaut pour les nouveaux formateurs dans les salles de coordination';
$string["default_former_auth_desc"] = 'Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos formateurs peuvent bénéficier du SSO et du panneau AVA pour SUAP.';
$string["default_former_role_id"] = 'ID de rôle par défaut pour une inscription de formateur dans les salles de coordination';
$string["default_former_role_id_desc"] = 'Normalement 4. Pourquoi ? C\'est la valeur par défaut de Moodle pour les enseignants non éditeurs.';
$string["default_former_enrol_type"] = 'Type d\'inscription par défaut pour un formateur dans les salles de coordination';
$string["default_former_enrol_type_desc"] = 'Normalement manuel. Pourquoi ? Parce que les nouveaux formateurs des salles de coordination seront inscrits dans SUAP et synchronisés avec Moodle';

// Mediador
$string["moderator_settings_header"] = 'Synchronisation des modérateurs';
$string["moderator_settings_header_desc"] = 'Paramètres de synchronisation des modérateurs';
$string["default_moderator_auth"] = 'Méthode d\'authentification par défaut pour les nouveaux modérateurs dans les salles de coordination';
$string["default_moderator_auth_desc"] = 'Nous recommandons de configurer OAuth avec SUAP, mais le choix vous appartient. Pourquoi OAuth ? Parce que vos modérateurs peuvent bénéficier du SSO et du panneau AVA pour SUAP.';
$string["default_moderator_role_id"] = 'ID de rôle par défaut pour une inscription de modérateur dans les salles de coordination';
$string["default_moderator_role_id_desc"] = 'Normalement 4. Pourquoi ? C\'est la valeur par défaut de Moodle pour les enseignants non éditeurs.';
$string["default_moderator_enrol_type"] = 'Type d\'inscription par défaut pour un modérateur dans les salles de coordination';
$string["default_moderator_enrol_type_desc"] = 'Normalement manuel. Pourquoi ? Parce que les nouveaux modérateurs des salles de coordination seront inscrits dans SUAP et synchronisés avec Moodle';

// Task
$string["sync_up_enrolments_task"] = 'Tâche de synchronisation des inscriptions';
$string["sync_up_enrolments_task_desc"] = 'Tâche de synchronisation des inscriptions';
$string["generate_report_task"] = 'Créer un rapport sur les cours en auto-apprentissage';

// Notas
$string["notes_to_sync_header"] = 'Synchronisation des notes';
$string["notes_to_sync_header_desc"] = 'Paramètres de synchronisation des notes';
$string["notes_to_sync"] = 'Notes à synchroniser';
$string["notes_to_sync_desc"] = 'Notes à synchroniser';

// Grupos do curso
$string['groups_in_course_header'] = 'Groupes dans le cours';
$string['groups_in_course_header_desc'] = 'Paramètres des groupes dans le cours';
$string["course_group_entrada"] = 'Synchroniser les groupes pour l\'admission (entrada)';
$string["course_group_entrada_desc"] = 'Synchroniser les groupes pour l\'admission (entrada)';
$string["course_group_turma"] = 'Synchroniser les groupes pour la classe (turma)';
$string["course_group_turma_desc"] = 'Synchroniser les groupes pour la classe (turma)';
$string["course_group_polo"] = 'Synchroniser les groupes pour le centre (polo)';
$string["course_group_polo_desc"] = 'Synchroniser les groupes pour le centre (polo)';
$string["course_group_programa"] = 'Synchroniser les groupes pour le programme';
$string["course_group_programa_desc"] = 'Synchroniser les groupes pour le programme';

// Grupos da sala de coordenação
$string['groups_in_room_header'] = 'Groupes dans la salle de coordination';
$string['groups_in_room_header_desc'] = 'Paramètres des groupes dans la salle de coordination';
$string["room_group_entrada"] = 'Synchroniser les groupes pour l\'admission (entrada)';
$string["room_group_entrada_desc"] = 'Synchroniser les groupes pour l\'admission (entrada)';
$string["room_group_turma"] = 'Synchroniser les groupes pour la classe (turma)';
$string["room_group_turma_desc"] = 'Synchroniser les groupes pour la classe (turma)';
$string["room_group_polo"] = 'Synchroniser les groupes pour le centre (polo)';
$string["room_group_polo_desc"] = 'Synchroniser les groupes pour le centre (polo)';
$string["room_group_programa"] = 'Synchroniser les groupes pour le programme';
$string["room_group_programa_desc"] = 'Synchroniser les groupes pour le programme';

// Report UI
$string['last_updated'] = 'Dernière mise à jour :';
$string['campus'] = 'Campus';
$string['total_enrolled'] = 'Total des inscrits';
$string['accessed'] = 'Accédé';
$string['never_accessed'] = 'Jamais accédé';
$string['pct_access'] = '% Accès';
$string['final_exam_takers'] = 'Ont passé l\'évaluation finale';
$string['pct_final_exam_takers'] = '% Ont passé l\'évaluation finale';
$string['passed'] = 'Admis';
$string['failed'] = 'Échoués';
$string['pct_passed'] = '% Admis';
$string['avg_grade'] = 'Note moyenne';
$string['with_certificate'] = 'Avec certificat';
$string['eligible_without_certificate'] = 'Éligibles sans cert.';
$string['completed'] = 'Terminés';
$string['pct_completed'] = '% Terminés';

// Admin & Integration Views UI
$string['view_integrations'] = 'Afficher les intégrations';
$string['search_placeholder'] = 'Rechercher...';
$string['timecreated'] = 'Date de création';
$string['status'] = 'Statut';
$string['viewing_integration'] = 'Affichage d\'une intégration';
$string['when'] = 'Quand';
$string['logs'] = 'Journaux';
$string['view_logs'] = 'Voir les journaux';
$string['json'] = 'JSON';
$string['admin_title'] = 'Admin de sync SUAP';
$string['unauthorized_access'] = 'Accès non autorisé';
$string['status_unprocessed'] = 'Non traité';
$string['status_success'] = 'Succès';
$string['status_failed'] = 'Échec';
$string['status_unknown'] = 'Inconnu';
$string['request_not_found'] = 'Demande introuvable.';
$string['no_task_logs_found'] = 'Aucun journal de tâche trouvé pour cette demande.';
$string['log_id'] = 'ID de journal';
$string['start_time'] = 'Heure de début';
$string['end_time'] = 'Heure de fin';
$string['result'] = 'Résultat';
$string['hostname'] = 'Nom d\'hôte';
$string['pid'] = 'PID';
$string['action'] = 'Action';
$string['open'] = 'Ouvrir';
$string['request_label'] = 'Demande #';
$string['search_label'] = 'Recherche : ';
$string['invalid_session'] = 'Session invalide. Veuillez réessayer.';
