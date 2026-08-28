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

$string['pluginname'] = 'SUAP 集成';
$string['suap:adminview'] = '查看 SUAP 管理';

// Auth token
$string['auth_token_header'] = '身份验证令牌';
$string['auth_token_header_desc'] = 'SUAP 用于对此 Moodle 安装进行身份验证的令牌';
$string["auth_token"] = 'SUAP 认证令牌';
$string["auth_token_desc"] = 'SUAP 用于对此 Moodle 安装进行身份验证的令牌';

$string['painel_url'] = 'AVA 面板 URL';
$string['painel_url_desc'] = '（例如：https://ava.ifrn.edu.br）';

// Categories
$string['top_category_header'] = '顶层类别';
$string['top_category_header_desc'] = '顶层类别默认设置';
$string["top_category_idnumber"] = '顶层类别标识编号';
$string["top_category_idnumber_desc"] = '用于标识放置新课程的位置，如果不存在具有此 ID 编号的类别，则使用此 ID 编号创建新类别';
$string["top_category_name"] = '顶层类别名称';
$string["top_category_name_desc"] = '仅用于创建新的顶层类别';
$string["top_category_parent"] = '顶层类别的父级';
$string["top_category_parent_desc"] = '仅用于创建新的顶层类别';

// New user and new enrolment defaults
$string['user_and_enrolment_header'] = '新用户与新选课默认设置';
$string['user_and_enrolment_header_desc'] = '新用户与新选课的默认设置';

// User preferences
$string["default_user_preferences"] = '默认用户偏好设置';
$string["default_user_preferences_desc"] = '每个新用户（学生或教师）都将拥有这些偏好设置。每行写一项偏好，类似于 .ini 文件。';

// Roles mapping
$string["roles_mapping"] = '角色映射';
$string["roles_mapping_desc"] = 'SUAP 角色到 Moodle 角色的映射，字段：（tipo_sala:papel_suap:role_shortname:enrol_type）。空间类型（tipo_sala）可以是：diarios、coordenacoes、autoinscricoes、praticas、modelos 或 default。SUAP 角色（papel_suap）例如可以是：Principal、Formador、Mediador、Tutor、Conteudista、Coordenador de Curso、Tutor presencial、Coordenador de Polo 或 Secretário de Curso。Role shortname 是选课时使用的 Moodle 角色简称。选课类型可以是：manual、self、guest 等。';

// Default authentication method
$string["default_auth"] = '默认身份验证方法';
$string["default_auth_desc"] = '新用户的默认身份验证方法。我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为您的用户可以享受 SUAP 的 SSO 和 AVA 面板。';

// Authentication methods mapping
$string["auths_mapping"] = '身份验证方法映射';
$string["auths_mapping_desc"] = '每个 SUAP 角色的身份验证方法映射，字段：（papel_suap:auth）。SUAP 角色可以是：Principal、Formador、Mediador、Tutor、Conteudista、Coordenador de Curso、Tutor presencial、Coordenador de Polo 或 Secretário de Curso。Auth 是用于具有该 SUAP 角色的用户的 Moodle 身份验证方法简称。';

// Student
$string["student_settings_header"] = '学生同步';
$string["student_settings_header_desc"] = '学生同步设置';
$string["default_student_auth"] = '新学生用户的默认身份验证方法';
$string["default_student_auth_desc"] = '我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为您的学生可以享受 SUAP 的 SSO 和 AVA 面板。';
$string["default_student_role_id"] = '学生选课的默认角色 ID';
$string["default_student_role_id_desc"] = '通常为 5。为什么？这是 Moodle 的默认设置。';
$string["default_student_enrol_type"] = '学生选课的默认 enrolment_type';
$string["default_student_enrol_type_desc"] = '通常为 manual（手动）。为什么？因为新学生将在 SUAP 中注册并同步到 Moodle';

// Teacher
$string["teacher_settings_header"] = '教师同步';
$string["teacher_settings_header_desc"] = '教师同步设置';
$string["default_teacher_auth"] = '新教师用户的默认身份验证方法';
$string["default_teacher_auth_desc"] = '我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为您的教师可以享受 SUAP 的 SSO 和 AVA 面板。';
$string["default_teacher_role_id"] = '教师选课的默认角色 ID';
$string["default_teacher_role_id_desc"] = '通常为 5。为什么？这是 Moodle 的默认设置。';
$string["default_teacher_enrol_type"] = '教师选课的默认 enrolment_type';
$string["default_teacher_enrol_type_desc"] = '通常为 manual（手动）。为什么？因为新教师将在 SUAP 中注册并同步到 Moodle';

// Tutores
$string["assistant_settings_header"] = '助教/导师同步';
$string["assistant_settings_header_desc"] = '助教/导师同步设置';
$string["default_assistant_auth"] = '新助教/导师用户的默认身份验证方法';
$string["default_assistant_auth_desc"] = '我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为您的助教/导师可以享受 SUAP 的 SSO 和 AVA 面板。';
$string["default_assistant_role_id"] = '助教/导师选课的默认角色 ID';
$string["default_assistant_role_id_desc"] = '通常为 5。为什么？这是 Moodle 的默认设置。';
$string["default_assistant_enrol_type"] = '助教/导师选课的默认 enrolment_type';
$string["default_assistant_enrol_type_desc"] = '通常为 manual（手动）。为什么？因为新助教/导师将在 SUAP 中注册并同步到 Moodle';

// Docentes nas salas de coordenação
$string["instructor_settings_header"] = '协调室工作人员同步';
$string["instructor_settings_header_desc"] = '协调室工作人员同步设置';
$string["default_instructor_auth"] = '协调室中新教师用户的默认身份验证方法';
$string["default_instructor_auth_desc"] = '我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为您的教职工可以享受 SUAP 的 SSO 和 AVA 面板。';
$string["default_instructor_role_id"] = '协调室中教职工选课的默认角色 ID';
$string["default_instructor_role_id_desc"] = '通常为 4。为什么？这是 Moodle 无编辑权限教师的默认设置。';
$string["default_instructor_enrol_type"] = '协调室中教职工选课的默认 enrolment_type';
$string["default_instructor_enrol_type_desc"] = '通常为 manual（手动）。为什么？因为协调室中的新教职工将在 SUAP 中注册并同步到 Moodle';

// Formador
$string["former_settings_header"] = '培训师/培训人员同步';
$string["former_settings_header_desc"] = '培训师/培训人员同步设置';
$string["default_former_auth"] = '协调室中新培训师用户的默认身份验证方法';
$string["default_former_auth_desc"] = '我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为您的培训师可以享受 SUAP 的 SSO 和 AVA 面板。';
$string["default_former_role_id"] = '协调室中培训师选课的默认角色 ID';
$string["default_former_role_id_desc"] = '通常为 4。为什么？这是 Moodle 无编辑权限教师的默认设置。';
$string["default_former_enrol_type"] = '协调室中培训师选课的默认 enrolment_type';
$string["default_former_enrol_type_desc"] = '通常为 manual（手动）。为什么？因为协调室中的新培训师将在 SUAP 中注册并同步到 Moodle';

// Mediador
$string["moderator_settings_header"] = '主持人/版主同步';
$string["moderator_settings_header_desc"] = '主持人/版主同步设置';
$string["default_moderator_auth"] = '协调室中新主持人/版主用户的默认身份验证方法';
$string["default_moderator_auth_desc"] = '我们建议您将 OAuth 与 SUAP 一起配置，但选择权在于您。为什么选择 OAuth？因为 your 主持人/版主可以享受 SUAP 的 SSO 和 AVA 面板。';
$string["default_moderator_role_id"] = '协调室中主持人/版主选课的默认角色 ID';
$string["default_moderator_role_id_desc"] = '通常为 4。为什么？这是 Moodle 无编辑权限教师的默认设置。';
$string["default_moderator_enrol_type"] = '协调室中主持人/版主选课的默认 enrolment_type';
$string["default_moderator_enrol_type_desc"] = '通常为 manual（手动）。为什么？因为协调室中的新主持人/版主将在 SUAP 中注册并同步到 Moodle';

// Task
$string["sync_up_enrolments_task"] = '选课同步任务';
$string["sync_up_enrolments_task_desc"] = '选课同步任务';
$string["generate_report_task"] = '创建自学课程报告';

// Notas
$string["notes_to_sync_header"] = '成绩同步';
$string["notes_to_sync_header_desc"] = '成绩同步设置';
$string["notes_to_sync"] = '要同步的成绩';
$string["notes_to_sync_desc"] = '要同步的成绩';

// Grupos do curso
$string['groups_in_course_header'] = '课程中的分组';
$string['groups_in_course_header_desc'] = '课程中的分组设置';
$string["course_group_entrada"] = '同步入学（entrada）分组';
$string["course_group_entrada_desc"] = '同步入学（entrada）分组';
$string["course_group_turma"] = '同步班级（turma）分组';
$string["course_group_turma_desc"] = '同步班级（turma）分组';
$string["course_group_polo"] = '同步分校/中心（polo）分组';
$string["course_group_polo_desc"] = '同步分校/中心（polo）分组';
$string["course_group_programa"] = '同步项目（programa）分组';
$string["course_group_programa_desc"] = '同步项目（programa）分组';

// Grupos da sala de coordenação
$string['groups_in_room_header'] = '协调室中的分组';
$string['groups_in_room_header_desc'] = '协调室中的分组设置';
$string["room_group_entrada"] = '同步入学（entrada）分组';
$string["room_group_entrada_desc"] = '同步入学（entrada）分组';
$string["room_group_turma"] = '同步班级（turma）分组';
$string["room_group_turma_desc"] = '同步班级（turma）分组';
$string["room_group_polo"] = '同步分校/中心（polo）分组';
$string["room_group_polo_desc"] = '同步分校/中心（polo）分组';
$string["room_group_programa"] = '同步项目（programa）分组';
$string["room_group_programa_desc"] = '同步项目（programa）分组';

// Report UI
$string['last_updated'] = '最后更新：';
$string['campus'] = '校区';
$string['total_enrolled'] = '总报名人数';
$string['accessed'] = '已访问';
$string['never_accessed'] = '从未访问';
$string['pct_access'] = '访问率 %';
$string['final_exam_takers'] = '完成期末评估人数';
$string['pct_final_exam_takers'] = '期末评估完成率 %';
$string['passed'] = '通过人数';
$string['failed'] = '未通过人数';
$string['pct_passed'] = '通过率 %';
$string['avg_grade'] = '平均成绩';
$string['with_certificate'] = '已获得证书';
$string['eligible_without_certificate'] = '符合条件但未领证书';
$string['completed'] = '已完成';
$string['pct_completed'] = '完成率 %';

// Admin & Integration Views UI
$string['view_integrations'] = '查看集成';
$string['search_placeholder'] = '搜索...';
$string['timecreated'] = '创建时间';
$string['status'] = '状态';
$string['viewing_integration'] = '查看集成详情';
$string['when'] = '时间';
$string['logs'] = '日志';
$string['view_logs'] = '查看日志';
$string['json'] = 'JSON';
$string['admin_title'] = 'SUAP 同步管理';
$string['unauthorized_access'] = '未授权访问';
$string['status_unprocessed'] = '未处理';
$string['status_success'] = '成功';
$string['status_failed'] = '失败';
$string['status_unknown'] = '未知';
$string['request_not_found'] = '未找到请求。';
$string['no_task_logs_found'] = '未找到此请求的任务日志。';
$string['log_id'] = '日志 ID';
$string['start_time'] = '开始时间';
$string['end_time'] = '结束时间';
$string['result'] = '结果';
$string['hostname'] = '主机名';
$string['pid'] = '进程 ID (PID)';
$string['action'] = '操作';
$string['open'] = '打开';
$string['request_label'] = '请求 #';
$string['search_label'] = '搜索：';
$string['invalid_session'] = '会话无效。请重试。';
