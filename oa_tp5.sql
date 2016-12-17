/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : oa_tp5

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2016-12-17 14:33:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for duty
-- ----------------------------
DROP TABLE IF EXISTS `duty`;
CREATE TABLE `duty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '职位id',
  `name` varchar(20) NOT NULL COMMENT '职能名称',
  `status` tinyint(3) unsigned NOT NULL COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '删除标志位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of duty
-- ----------------------------
INSERT INTO `duty` VALUES ('1', 'PHP', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '项目id',
  `pid` int(10) unsigned NOT NULL COMMENT '父级菜单ID',
  `name` varchar(20) NOT NULL COMMENT '部门名称',
  `route` varchar(40) NOT NULL COMMENT '菜单路由',
  `icon` varchar(20) NOT NULL COMMENT '菜单图标',
  `description` varchar(255) NOT NULL COMMENT '菜单描述',
  `sort` smallint(5) unsigned NOT NULL COMMENT '菜单排序',
  `status` tinyint(3) unsigned NOT NULL COMMENT '状态',
  `is_show` tinyint(3) unsigned NOT NULL COMMENT '显示标志位',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(3) unsigned NOT NULL COMMENT '删除标志位',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------

-- ----------------------------
-- Table structure for position
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '职位id',
  `name` varchar(20) NOT NULL COMMENT '职位名称',
  `status` tinyint(3) unsigned NOT NULL COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(3) unsigned NOT NULL COMMENT '删除标志位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES ('1', '员工', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '项目id',
  `name` varchar(20) NOT NULL COMMENT '部门名称',
  `status` tinyint(3) unsigned NOT NULL COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(3) unsigned NOT NULL COMMENT '删除标志位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project
-- ----------------------------
INSERT INTO `project` VALUES ('1', '项目1', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色id',
  `name` varchar(40) NOT NULL COMMENT '角色名称',
  `description` varchar(255) NOT NULL COMMENT '角色描述',
  `status` tinyint(3) unsigned NOT NULL COMMENT '状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(3) unsigned NOT NULL COMMENT '删除标志位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '超级管理员', '超级管理员', '1', '0', '0', '0');
INSERT INTO `role` VALUES ('2', '管理员', '管理员', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES ('1', '1');
INSERT INTO `role_user` VALUES ('2', '2');
INSERT INTO `role_user` VALUES ('3', '1');
INSERT INTO `role_user` VALUES ('4', '1');
INSERT INTO `role_user` VALUES ('5', '1');
INSERT INTO `role_user` VALUES ('6', '1');
INSERT INTO `role_user` VALUES ('6', '2');
INSERT INTO `role_user` VALUES ('7', '2');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `account` varchar(20) NOT NULL COMMENT '用户账号',
  `name` varchar(20) NOT NULL COMMENT '用户名称',
  `password` char(32) NOT NULL,
  `project_id` int(11) unsigned NOT NULL COMMENT '项目id',
  `duty_id` int(11) unsigned NOT NULL COMMENT '职能id',
  `position_id` int(11) unsigned NOT NULL COMMENT '职位id',
  `sex` tinyint(3) unsigned NOT NULL COMMENT '性别',
  `birthday` int(10) unsigned NOT NULL COMMENT '生日',
  `pic` varchar(200) NOT NULL COMMENT '用户照片',
  `email` varchar(50) NOT NULL,
  `tel` int(20) unsigned NOT NULL COMMENT '电话',
  `entry_time` int(10) unsigned NOT NULL COMMENT '入职时间',
  `last_login_ip` varchar(40) NOT NULL COMMENT '最后登陆IP',
  `login_count` int(8) unsigned NOT NULL COMMENT '登陆次数',
  `status` tinyint(3) unsigned NOT NULL COMMENT '账号状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(3) unsigned NOT NULL COMMENT '删除标志位',
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `project_id` (`project_id`),
  KEY `function_id` (`duty_id`),
  KEY `position_id` (`position_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'test', '测试', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1476201600', '', '0', '0', '1477645310', '0', '0');
INSERT INTO `user` VALUES ('2', 'test2', '测试2', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1476201600', '', '0', '0', '1477646258', '0', '0');
INSERT INTO `user` VALUES ('3', 'test3', '测试3', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1475078400', '', '0', '0', '1477647397', '0', '0');
INSERT INTO `user` VALUES ('4', 'test4', '测试4', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1476806400', '', '0', '0', '1477647546', '0', '0');
INSERT INTO `user` VALUES ('5', 'test5', '测试4', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1476806400', '', '0', '0', '1477647565', '0', '0');
INSERT INTO `user` VALUES ('6', 'test6', '测试4', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1476806400', '', '0', '0', '1477647955', '0', '0');
INSERT INTO `user` VALUES ('7', 'test7', '测试7', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '', '0', '1476892800', '', '0', '0', '1477655688', '0', '0');
