/*
 Navicat Premium Data Transfer

 Source Server         : Vagrant数据库
 Source Server Type    : MySQL
 Source Server Version : 50727
 Source Host           : localhost:33060
 Source Schema         : datareport

 Target Server Type    : MySQL
 Target Server Version : 50727
 File Encoding         : 65001

 Date: 15/04/2020 18:36:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ydy_admin_logs
-- ----------------------------
DROP TABLE IF EXISTS `ydy_admin_logs`;
CREATE TABLE `ydy_admin_logs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `admin_id` bigint(20) NOT NULL COMMENT '管理员ID',
  `admin_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '管理员名称',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内容',
  `from_ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源ip',
  `ctime` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时间',
  `is_del` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1正常 2删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ydy_admin_logs
-- ----------------------------
INSERT INTO `ydy_admin_logs` VALUES (1, 1, '2', '管理员<span\">1</span>于<span>2</span><span>3</span><span>4</span>', '192.168.10.1', '1586922889', 1);
INSERT INTO `ydy_admin_logs` VALUES (2, 1, 'admin', '管理员<span>admin</span>于<span>1586943513</span><span>登录</span><span>后台管理系统</span>', '192.168.10.1', '1586943513', 1);
INSERT INTO `ydy_admin_logs` VALUES (3, 1, 'admin', '管理员<span>admin</span>于<span>2020-04-15 09:39:36</span><span>登录</span><span>后台管理系统</span>', '192.168.10.1', '1586943576', 1);
INSERT INTO `ydy_admin_logs` VALUES (4, 1, 'admin', '管理员<span>admin</span>于<span>2020-04-15 10:06:38</span><span>登录</span><span>后台管理系统</span>', '192.168.10.1', '1586945198', 1);
INSERT INTO `ydy_admin_logs` VALUES (5, 1, 'admin', '管理员<span>admin</span>于<span>2020-04-15 10:12:01</span><span>登录</span><span>后台管理系统</span>', '192.168.10.1', '1586945521', 1);

-- ----------------------------
-- Table structure for ydy_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `ydy_admin_menu`;
CREATE TABLE `ydy_admin_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` smallint(6) NOT NULL COMMENT '角色ID',
  `menu_id` smallint(6) NOT NULL COMMENT '菜单ID',
  `ctime` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时间',
  `is_del` tinyint(4) NOT NULL COMMENT '1正常 2删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ydy_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `ydy_admin_role`;
CREATE TABLE `ydy_admin_role`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态 1正常 2关闭',
  `is_del` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态 1正常 2删除',
  `ctime` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ydy_admin_role
-- ----------------------------
INSERT INTO `ydy_admin_role` VALUES (1, '超级管理员', 'super users', 1, 1, '1586835255');

-- ----------------------------
-- Table structure for ydy_admins
-- ----------------------------
DROP TABLE IF EXISTS `ydy_admins`;
CREATE TABLE `ydy_admins`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `role_id` bigint(20) NOT NULL COMMENT '角色Id',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名称,默认为对应用户中文拼音',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户登录密码',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '实名',
  `desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '描述，50个字',
  `head_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '用户头像',
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '手机号码',
  `is_system` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1为有权限 2为超级管理员',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1正常 2关闭',
  `ctime` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '创建时间戳',
  `is_del` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1正常 2删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ydy_admins
-- ----------------------------
INSERT INTO `ydy_admins` VALUES (1, 1, 'admin', '$2y$10$XphF5ApAkSIqtygGUnvpEekFnCYKeSbTKW7b1W8TjlKfbWbNSjpjG', 'admin', 'super admin', NULL, 'admin@123.com', '15070204712', 1, 1, '1586835255', 1);

-- ----------------------------
-- Table structure for ydy_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `ydy_failed_jobs`;
CREATE TABLE `ydy_failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ydy_menu
-- ----------------------------
DROP TABLE IF EXISTS `ydy_menu`;
CREATE TABLE `ydy_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NOT NULL COMMENT '菜单父类',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
  `desc` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单描述',
  `is_display` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1显示 2不显示 是否显示',
  `url_as` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路由名称',
  `front_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '前端菜单',
  `icon_text` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `sort` smallint(6) NOT NULL DEFAULT 0 COMMENT '菜单排序',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1启用 2不启用 是否启用',
  `ctime` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时间',
  `is_del` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1正常 2删除',
  `from_type` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1后端 2前端',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ydy_menu
-- ----------------------------
INSERT INTO `ydy_menu` VALUES (1, 0, '权限管理', '权限管理', 1, 'admin.permission', '', 'layui-icon-auz', 0, 1, '1586835255', 1, 1);
INSERT INTO `ydy_menu` VALUES (2, 1, '用户角色', '用户角色', 1, 'admin.role.index', '', '', 0, 1, '1586835255', 1, 1);
INSERT INTO `ydy_menu` VALUES (3, 1, '用户管理', '用户管理', 1, 'admin.role.users', '', '', 0, 1, '1586835255', 1, 1);
INSERT INTO `ydy_menu` VALUES (4, 1, '菜单管理', '菜单管理', 1, 'admin.menu.index', '', '', 0, 1, '1586835255', 1, 1);
INSERT INTO `ydy_menu` VALUES (5, 2, '添加角色', '', 2, 'admin.role.addgroup', '', '', 0, 1, '1575876772', 1, 1);
INSERT INTO `ydy_menu` VALUES (6, 2, '编辑角色', '', 2, 'admin.role.editgroup', '', '', 0, 1, '1575876806', 1, 1);
INSERT INTO `ydy_menu` VALUES (7, 2, '删除角色', '', 2, 'admin.role.delgroup', '', '', 0, 1, '1575876892', 1, 1);
INSERT INTO `ydy_menu` VALUES (8, 3, '成员添加', '', 2, 'admin.role.addadmins', '', '', 0, 1, '1575877168', 1, 1);
INSERT INTO `ydy_menu` VALUES (9, 3, '编辑成员', '', 2, 'admin.role.editadmins', '', '', 0, 1, '1575877200', 1, 1);
INSERT INTO `ydy_menu` VALUES (10, 3, '删除成员', '', 2, 'admin.role.deladmins', '', '', 0, 1, '1575877232', 1, 1);
INSERT INTO `ydy_menu` VALUES (11, 4, '编辑菜单', '编辑菜单', 2, 'admin.menu.editmenu', '', '', 0, 1, '1571796417', 1, 1);
INSERT INTO `ydy_menu` VALUES (12, 4, '删除菜单', '删除菜单', 2, 'admin.menu.deletemenu', '', '', 0, 1, '1571796454', 1, 1);
INSERT INTO `ydy_menu` VALUES (13, 4, '添加菜单', '添加菜单', 2, 'admin.menu.addmenu', '', '', 0, 1, '1571797302', 1, 1);

-- ----------------------------
-- Table structure for ydy_migrations
-- ----------------------------
DROP TABLE IF EXISTS `ydy_migrations`;
CREATE TABLE `ydy_migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ydy_migrations
-- ----------------------------
INSERT INTO `ydy_migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `ydy_migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `ydy_migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `ydy_migrations` VALUES (4, '2020_04_14_014358_create_admin_role_table', 1);
INSERT INTO `ydy_migrations` VALUES (5, '2020_04_14_014934_create_admins_table', 1);
INSERT INTO `ydy_migrations` VALUES (6, '2020_04_14_015240_create_admin_logs_table', 1);
INSERT INTO `ydy_migrations` VALUES (7, '2020_04_14_073218_create_admin_menu_table', 2);
INSERT INTO `ydy_migrations` VALUES (8, '2020_04_14_073552_create_menu_table', 2);
INSERT INTO `ydy_migrations` VALUES (9, '2020_04_14_074118_create_set_config_table', 3);
INSERT INTO `ydy_migrations` VALUES (10, '2020_04_14_091737_alter_menu_table', 3);

-- ----------------------------
-- Table structure for ydy_password_resets
-- ----------------------------
DROP TABLE IF EXISTS `ydy_password_resets`;
CREATE TABLE `ydy_password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `ydy_password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for ydy_set_config
-- ----------------------------
DROP TABLE IF EXISTS `ydy_set_config`;
CREATE TABLE `ydy_set_config`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `config_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '键',
  `config_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '键值',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1启用 2不启用 是否启用',
  `ctime` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '创建时间',
  `is_del` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1正常 2删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ydy_set_config
-- ----------------------------
INSERT INTO `ydy_set_config` VALUES (1, 'title', '测试平台', 1, '1586856784', 1);
INSERT INTO `ydy_set_config` VALUES (2, 'desc', '测试平台', 1, '1586856784', 1);
INSERT INTO `ydy_set_config` VALUES (3, 'keywords', '关键词', 1, '1586856784', 1);
INSERT INTO `ydy_set_config` VALUES (4, 'logo', '平台logo', 1, '1586856784', 1);
INSERT INTO `ydy_set_config` VALUES (5, 'status', '1', 1, '1586856784', 1);
INSERT INTO `ydy_set_config` VALUES (6, 'phone', '15070204712', 1, '1586856784', 1);
INSERT INTO `ydy_set_config` VALUES (7, 'email', '741623760@qq.com', 1, '1586856784', 1);

-- ----------------------------
-- Table structure for ydy_users
-- ----------------------------
DROP TABLE IF EXISTS `ydy_users`;
CREATE TABLE `ydy_users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `ydy_users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
