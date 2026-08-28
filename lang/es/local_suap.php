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

$string['pluginname'] = 'Integración SUAP';
$string['suap:adminview'] = 'Ver la administración de SUAP';

// Auth token
$string['auth_token_header'] = 'Token de autenticación';
$string['auth_token_header_desc'] = 'El token que utilizará SUAP para autenticarse en esta instalación de Moodle';
$string["auth_token"] = 'Token de autenticación de SUAP';
$string["auth_token_desc"] = 'El token que utilizará SUAP para autenticarse en esta instalación de Moodle';

$string['painel_url'] = 'URL del Panel AVA';
$string['painel_url_desc'] = '(ej. https://ava.ifrn.edu.br)';

// Categories
$string['top_category_header'] = 'Categoría principal';
$string['top_category_header_desc'] = 'Configuración predeterminada de la categoría principal';
$string["top_category_idnumber"] = 'Número de identificación de la categoría superior';
$string["top_category_idnumber_desc"] = 'Se utiliza para identificar dónde colocar los nuevos cursos; si no existe una categoría con este idnumber, se creará una nueva categoría con este idnumber';
$string["top_category_name"] = 'Nombre de la categoría principal';
$string["top_category_name_desc"] = 'Se utiliza únicamente para crear la nueva categoría principal';
$string["top_category_parent"] = 'Categoría padre superior';
$string["top_category_parent_desc"] = 'Se utiliza únicamente para crear la nueva categoría principal';

// New user and new enrolment defaults
$string['user_and_enrolment_header'] = 'Valores predeterminados para nuevos usuarios e inscripciones';
$string['user_and_enrolment_header_desc'] = 'Configuración predeterminada para nuevos usuarios e inscripciones';

// User preferences
$string["default_user_preferences"] = 'Preferencias predeterminadas del usuario';
$string["default_user_preferences_desc"] = 'Cada nuevo usuario (estudiante o profesor) tendrá estas preferencias. Use una línea por preferencia, como un archivo .ini.';

// Roles mapping
$string["roles_mapping"] = 'Mapeo de roles';
$string["roles_mapping_desc"] = 'Mapeo de roles SUAP a roles de Moodle, campos: (tipo_sala:papel_suap:role_shortname:enrol_type). El tipo de sala (tipo_sala) puede ser: diarios, coordenacoes, autoinscricoes, praticas, modelos o default. El papel SUAP (papel_suap) puede ser: Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo o Secretário de Curso, por ejemplo. Shortname del rol es el nombre corto del rol Moodle. Tipo de inscripción puede ser: manual, self, guest, etc.';

// Default authentication method
$string["default_auth"] = 'Método de autenticación predeterminado';
$string["default_auth_desc"] = 'Método de autenticación predeterminado para nuevos usuarios. Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus usuarios pueden aprovechar el SSO y el Panel AVA para SUAP.';

// Authentication methods mapping
$string["auths_mapping"] = 'Mapeo de métodos de autenticación';
$string["auths_mapping_desc"] = 'Mapeo del método de autenticación para cada rol SUAP, campos: (papel_suap:auth). El rol SUAP puede ser: Principal, Formador, Mediador, Tutor, Conteudista, Coordenador de Curso, Tutor presencial, Coordenador de Polo o Secretário de Curso. Auth es el nombre corto del método de autenticación Moodle que se utilizará para los usuarios con ese rol SUAP.';

// Student
$string["student_settings_header"] = 'Sincronización de estudiantes';
$string["student_settings_header_desc"] = 'Configuración de sincronización de estudiantes';
$string["default_student_auth"] = 'Método de autenticación predeterminado para nuevos usuarios estudiantes';
$string["default_student_auth_desc"] = 'Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus estudiantes pueden aprovechar el SSO y el Panel AVA para SUAP.';
$string["default_student_role_id"] = 'ID de rol predeterminado para una inscripción de estudiante';
$string["default_student_role_id_desc"] = 'Normalmente 5. ¿Por qué? Este es el estándar de Moodle.';
$string["default_student_enrol_type"] = 'Enrol_type predeterminado para una inscripción de estudiante';
$string["default_student_enrol_type_desc"] = 'Normalmente manual. ¿Por qué? Porque los nuevos estudiantes serán inscritos en SUAP y sincronizados con Moodle';

// Teacher
$string["teacher_settings_header"] = 'Sincronización de profesores';
$string["teacher_settings_header_desc"] = 'Configuración de sincronización de profesores';
$string["default_teacher_auth"] = 'Método de autenticación predeterminado para nuevos usuarios profesores';
$string["default_teacher_auth_desc"] = 'Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus profesores pueden aprovechar el SSO y el Panel AVA para SUAP.';
$string["default_teacher_role_id"] = 'ID de rol predeterminado para una inscripción de profesor';
$string["default_teacher_role_id_desc"] = 'Normalmente 5. ¿Por qué? Este es el estándar de Moodle.';
$string["default_teacher_enrol_type"] = 'Enrol_type predeterminado para una inscripción de profesor';
$string["default_teacher_enrol_type_desc"] = 'Normalmente manual. ¿Por qué? Porque los nuevos profesores serán inscritos en SUAP y sincronizados con Moodle';

// Tutores
$string["assistant_settings_header"] = 'Sincronización de tutores';
$string["assistant_settings_header_desc"] = 'Configuración de sincronización de tutores';
$string["default_assistant_auth"] = 'Método de autenticación predeterminado para nuevos usuarios tutores';
$string["default_assistant_auth_desc"] = 'Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus tutores pueden aprovechar el SSO y el Panel AVA para SUAP.';
$string["default_assistant_role_id"] = 'ID de rol predeterminado para una inscripción de tutor';
$string["default_assistant_role_id_desc"] = 'Normalmente 5. ¿Por qué? Este es el estándar de Moodle.';
$string["default_assistant_enrol_type"] = 'Enrol_type predeterminado para una inscripción de tutor';
$string["default_assistant_enrol_type_desc"] = 'Normalmente manual. ¿Por qué? Porque los nuevos tutores serán inscritos en SUAP y sincronizados con Moodle';

// Docentes nas salas de coordenação
$string["instructor_settings_header"] = 'Sincronización de colaboradores en salas de coordinación';
$string["instructor_settings_header_desc"] = 'Configuración de sincronización de colaboradores en salas de coordinación';
$string["default_instructor_auth"] = 'Método de autenticación predeterminado para nuevos usuarios docentes en salas de coordinación';
$string["default_instructor_auth_desc"] = 'Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus docentes pueden aprovechar el SSO y el Panel AVA para SUAP.';
$string["default_instructor_role_id"] = 'ID de rol predeterminado para una inscripción como docente en salas de coordinación';
$string["default_instructor_role_id_desc"] = 'Normalmente 4. ¿Por qué? Este es el estándar de Moodle para profesores sin permiso de edición.';
$string["default_instructor_enrol_type"] = 'Enrol_type predeterminado para una inscripción como docente en salas de coordinación';
$string["default_instructor_enrol_type_desc"] = 'Normalmente manual. ¿Por qué? Porque los nuevos docentes en salas de coordinación serán inscritos en SUAP y sincronizados con Moodle';

// Formador
$string["former_settings_header"] = 'Sincronización de formadores';
$string["former_settings_header_desc"] = 'Configuración de sincronización de formadores';
$string["default_former_auth"] = 'Método de autenticación predeterminado para nuevos usuarios formadores en salas de coordinación';
$string["default_former_auth_desc"] = 'Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus formadores pueden aprovechar el SSO y el Panel AVA para SUAP.';
$string["default_former_role_id"] = 'ID de rol predeterminado para una inscripción como formador en salas de coordinación';
$string["default_former_role_id_desc"] = 'Normalmente 4. ¿Por qué? Este es el estándar de Moodle para profesores sin permiso de edición.';
$string["default_former_enrol_type"] = 'Enrol_type predeterminado para una inscripción como formador en salas de coordinación';
$string["default_former_enrol_type_desc"] = 'Normalmente manual. ¿Por qué? Porque los nuevos formadores en salas de coordinación serán inscritos en SUAP y sincronizados con Moodle';

// Mediador
$string["moderator_settings_header"] = 'Sincronización de moderadores';
$string["moderator_settings_header_desc"] = 'Configuración de sincronización de moderadores';
$string["default_moderator_auth"] = 'Método de autenticación predeterminado para nuevos usuarios moderadores en salas de coordinación';
$string["default_moderator_auth_desc"] = 'Recomendamos configurar OAuth con SUAP, pero la elección es suya. ¿Por qué OAuth? Porque sus moderadores pueden aprovechar el SSO y el Panel AVA para SUAP.';
$string["default_moderator_role_id"] = 'ID de rol predeterminado para una inscripción como moderador en salas de coordinación';
$string["default_moderator_role_id_desc"] = 'Normalmente 4. ¿Por qué? Este es el estándar de Moodle para profesores sin permiso de edición.';
$string["default_moderator_enrol_type"] = 'Enrol_type predeterminado para una inscripción como moderador en salas de coordinación';
$string["default_moderator_enrol_type_desc"] = 'Normalmente manual. ¿Por qué? Porque los nuevos moderadores en salas de coordinación serán inscritos en SUAP y sincronizados con Moodle';

// Task
$string["sync_up_enrolments_task"] = 'Tarea de sincronización de inscripciones';
$string["sync_up_enrolments_task_desc"] = 'Tarea de sincronización de inscripciones';
$string["generate_report_task"] = 'Crear informe de cursos autoinstructivos';

// Notas
$string["notes_to_sync_header"] = 'Sincronización de calificaciones';
$string["notes_to_sync_header_desc"] = 'Configuración de sincronización de calificaciones';
$string["notes_to_sync"] = 'Calificaciones a sincronizar';
$string["notes_to_sync_desc"] = 'Calificaciones a sincronizar';

// Grupos do curso
$string['groups_in_course_header'] = 'Grupos en el curso';
$string['groups_in_course_header_desc'] = 'Configuración de grupos en el curso';
$string["course_group_entrada"] = 'Sincronizar grupos para ingreso (entrada)';
$string["course_group_entrada_desc"] = 'Sincronizar grupos para ingreso (entrada)';
$string["course_group_turma"] = 'Sincronizar grupos para clase (turma)';
$string["course_group_turma_desc"] = 'Sincronizar grupos para clase (turma)';
$string["course_group_polo"] = 'Sincronizar grupos para centro/polo';
$string["course_group_polo_desc"] = 'Sincronizar grupos para centro/polo';
$string["course_group_programa"] = 'Sincronizar grupos para programa';
$string["course_group_programa_desc"] = 'Sincronizar grupos para programa';

// Grupos da sala de coordenação
$string['groups_in_room_header'] = 'Grupos en sala de coordinación';
$string['groups_in_room_header_desc'] = 'Configuración de grupos en sala de coordinación';
$string["room_group_entrada"] = 'Sincronizar grupos para ingreso (entrada)';
$string["room_group_entrada_desc"] = 'Sincronizar grupos para ingreso (entrada)';
$string["room_group_turma"] = 'Sincronizar grupos para clase (turma)';
$string["room_group_turma_desc"] = 'Sincronizar grupos para clase (turma)';
$string["room_group_polo"] = 'Sincronizar grupos para centro/polo';
$string["room_group_polo_desc"] = 'Sincronizar grupos para centro/polo';
$string["room_group_programa"] = 'Sincronizar grupos para programa';
$string["room_group_programa_desc"] = 'Sincronizar grupos para programa';

// Report UI
$string['last_updated'] = 'Última actualización:';
$string['campus'] = 'Campus';
$string['total_enrolled'] = 'Total inscritos';
$string['accessed'] = 'Accedieron';
$string['never_accessed'] = 'Nunca accedieron';
$string['pct_access'] = '% Acceso';
$string['final_exam_takers'] = 'Realizaron evaluación final';
$string['pct_final_exam_takers'] = '% Realizaron evaluación final';
$string['passed'] = 'Aprobados';
$string['failed'] = 'Reprobados';
$string['pct_passed'] = '% Aprobados';
$string['avg_grade'] = 'Nota promedio';
$string['with_certificate'] = 'Con certificado';
$string['eligible_without_certificate'] = 'Aptos sin cert.';
$string['completed'] = 'Completados';
$string['pct_completed'] = '% Completados';

// Admin & Integration Views UI
$string['view_integrations'] = 'Ver integraciones';
$string['search_placeholder'] = 'Buscar...';
$string['timecreated'] = 'Fecha de creación';
$string['status'] = 'Estado';
$string['viewing_integration'] = 'Visualizando una integración';
$string['when'] = 'Cuándo';
$string['logs'] = 'Registros';
$string['view_logs'] = 'Ver registros';
$string['json'] = 'JSON';
$string['admin_title'] = 'Administración de Sincronización SUAP';
$string['unauthorized_access'] = 'Acceso no autorizado';
$string['status_unprocessed'] = 'No procesado';
$string['status_success'] = 'Éxito';
$string['status_failed'] = 'Fallo';
$string['status_unknown'] = 'Desconodido';
$string['request_not_found'] = 'Solicitud no encontrada.';
$string['no_task_logs_found'] = 'No se encontraron registros de tareas para esta solicitud.';
$string['log_id'] = 'ID de Registro';
$string['start_time'] = 'Inicio';
$string['end_time'] = 'Fin';
$string['result'] = 'Resultado';
$string['hostname'] = 'Nombre de host';
$string['pid'] = 'PID';
$string['action'] = 'Acción';
$string['open'] = 'Abrir';
$string['request_label'] = 'Solicitud #';
$string['search_label'] = 'Búsqueda: ';
$string['invalid_session'] = 'Sesión inválida. Por favor, intente nuevamente.';
