/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : oa_tp5

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-01-24 15:54:56
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
-- Table structure for flow
-- ----------------------------
DROP TABLE IF EXISTS `flow`;
CREATE TABLE `flow` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流程id',
  `title` varchar(255) NOT NULL COMMENT '流程标题',
  `type` tinyint(3) unsigned NOT NULL COMMENT '流程类型',
  `user_id` int(10) unsigned NOT NULL COMMENT '发起人id',
  `step` smallint(3) unsigned NOT NULL COMMENT '当前流程步骤',
  `status` tinyint(1) unsigned NOT NULL COMMENT '流程状态',
  `is_edit` tinyint(1) unsigned NOT NULL COMMENT '可编辑标记位',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(1) unsigned NOT NULL COMMENT '删除标记位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow
-- ----------------------------
INSERT INTO `flow` VALUES ('1', '请假申请20170111111929简焕新', '1', '1', '4', '1', '0', '1484105389', '1484291657', '0');
INSERT INTO `flow` VALUES ('2', '请假申请20170113152813简焕新', '1', '1', '3', '1', '0', '1484292511', '1484294446', '0');
INSERT INTO `flow` VALUES ('3', '请假申请20170113155752简焕新', '1', '1', '4', '1', '0', '1484294281', '1484294476', '0');
INSERT INTO `flow` VALUES ('4', '请假申请20170113160209简焕新', '1', '1', '3', '1', '0', '1484294537', '1484880567', '0');
INSERT INTO `flow` VALUES ('5', '请假申请20170120104818简焕新', '1', '1', '3', '1', '0', '1484880505', '1484895313', '0');
INSERT INTO `flow` VALUES ('6', '请假申请20170120145440主管', '1', '2', '2', '0', '0', '1484895290', '1484895307', '0');
INSERT INTO `flow` VALUES ('7', '请假申请20170120155436主管', '1', '2', '2', '1', '1', '1484898893', '1484898893', '0');
INSERT INTO `flow` VALUES ('8', '请假申请20170120155558主管', '1', '2', '2', '1', '0', '1484898965', '1484898965', '0');
INSERT INTO `flow` VALUES ('9', '请假申请20170120160754简焕新', '1', '1', '1', '2', '0', '1484899681', '1484899693', '0');
INSERT INTO `flow` VALUES ('10', '请假申请20170120161730简焕新', '1', '1', '1', '0', '1', '1484900255', '1485152444', '0');
INSERT INTO `flow` VALUES ('11', '请假申请20170122135419简焕新', '1', '1', '1', '2', '0', '1485064464', '1485152416', '0');

-- ----------------------------
-- Table structure for flow_audit
-- ----------------------------
DROP TABLE IF EXISTS `flow_audit`;
CREATE TABLE `flow_audit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置类型id',
  `name` varchar(50) NOT NULL COMMENT '流程名称',
  `type` int(10) unsigned NOT NULL COMMENT '流程类型',
  `audit_conf` varchar(255) NOT NULL COMMENT '流程配置',
  `status` tinyint(1) unsigned NOT NULL COMMENT '流程组状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(1) unsigned NOT NULL COMMENT '删除标记位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow_audit
-- ----------------------------
INSERT INTO `flow_audit` VALUES ('1', '请假申请-员工-普通', '1', 'po|2,pr,ur|150|leave_day/elt/2', '1', '0', '1484545470', '0');
INSERT INTO `flow_audit` VALUES ('2', '请假申请-主管-普通', '1', 'po|2', '1', '0', '1484898860', '0');
INSERT INTO `flow_audit` VALUES ('3', '测试', '1', '', '1', '1484033950', '1484036296', '1');
INSERT INTO `flow_audit` VALUES ('4', '测试2', '1', '123', '1', '1484034085', '1484036303', '1');

-- ----------------------------
-- Table structure for flow_audit_config
-- ----------------------------
DROP TABLE IF EXISTS `flow_audit_config`;
CREATE TABLE `flow_audit_config` (
  `type` int(10) unsigned NOT NULL COMMENT '流程类型',
  `project_id` int(10) unsigned NOT NULL COMMENT '项目id',
  `duty_id` int(11) NOT NULL,
  `position_id` int(10) unsigned NOT NULL COMMENT '职位id',
  `audit_id` int(10) unsigned NOT NULL COMMENT '审核配置id',
  PRIMARY KEY (`type`,`duty_id`,`project_id`,`position_id`,`audit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow_audit_config
-- ----------------------------
INSERT INTO `flow_audit_config` VALUES ('1', '1', '1', '1', '1');
INSERT INTO `flow_audit_config` VALUES ('1', '1', '1', '2', '2');

-- ----------------------------
-- Table structure for flow_classify
-- ----------------------------
DROP TABLE IF EXISTS `flow_classify`;
CREATE TABLE `flow_classify` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流程组id',
  `name` varchar(40) NOT NULL COMMENT '流程组名称',
  `status` tinyint(1) unsigned NOT NULL COMMENT '流程组状态',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(1) unsigned NOT NULL COMMENT '删除标记位',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow_classify
-- ----------------------------
INSERT INTO `flow_classify` VALUES ('1', '考勤相关', '1', '1483759283', '1484535140', '0');
INSERT INTO `flow_classify` VALUES ('2', '行政相关', '1', '1483759294', '1483759294', '0');

-- ----------------------------
-- Table structure for flow_config
-- ----------------------------
DROP TABLE IF EXISTS `flow_config`;
CREATE TABLE `flow_config` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `report_user` varchar(100) DEFAULT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `classify` smallint(2) unsigned NOT NULL,
  `controller` varchar(50) NOT NULL COMMENT '流程控制器',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow_config
-- ----------------------------
INSERT INTO `flow_config` VALUES ('5', '增员申请', '郑晗雪|163', '1', '1', '');
INSERT INTO `flow_config` VALUES ('4', '加班申请', '郑晗雪|163', '1', '1', '');
INSERT INTO `flow_config` VALUES ('3', '印章使用', '郑晗雪|163', '1', '2', '');
INSERT INTO `flow_config` VALUES ('2', '异常出勤', '郑晗雪|163', '1', '1', '');
INSERT INTO `flow_config` VALUES ('1', '请假申请', '郑晗雪|163', '1', '1', 'leave');
INSERT INTO `flow_config` VALUES ('6', '转正申请', '郑晗雪|163', '1', '1', '');
INSERT INTO `flow_config` VALUES ('7', '采购申请', '郑晗雪|163', '1', '2', '');
INSERT INTO `flow_config` VALUES ('8', '异动申请', '曾甘霖|171', '1', '1', '');
INSERT INTO `flow_config` VALUES ('9', '测试流程', '', '1', '1', '');

-- ----------------------------
-- Table structure for flow_log
-- ----------------------------
DROP TABLE IF EXISTS `flow_log`;
CREATE TABLE `flow_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流程日志id',
  `flow_id` int(10) unsigned NOT NULL COMMENT '流程id',
  `user_id` int(10) unsigned NOT NULL COMMENT '审核人id',
  `step` smallint(3) unsigned NOT NULL COMMENT '当前流程步骤',
  `result` tinyint(1) unsigned DEFAULT NULL COMMENT '审核结果',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  `is_del` tinyint(1) unsigned NOT NULL COMMENT '删除标记位',
  `comment` text NOT NULL COMMENT '审核意见',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of flow_log
-- ----------------------------
INSERT INTO `flow_log` VALUES ('1', '1', '2', '1', '1', '1484105389', '1484212931', '0', '同意~~~');
INSERT INTO `flow_log` VALUES ('4', '1', '3', '2', '1', '1484212931', '1484214950', '0', '部门同意~~~');
INSERT INTO `flow_log` VALUES ('5', '1', '150', '3', '1', '1484214950', '1484291657', '0', '1111111111');
INSERT INTO `flow_log` VALUES ('6', '2', '2', '1', '1', '1484292511', '1484294258', '0', '111');
INSERT INTO `flow_log` VALUES ('7', '2', '3', '2', '1', '1484294258', '1484294446', '0', '222222');
INSERT INTO `flow_log` VALUES ('8', '3', '2', '1', '1', '1484294281', '1484294420', '0', '3333');
INSERT INTO `flow_log` VALUES ('9', '3', '3', '2', '1', '1484294420', '1484294438', '0', '11111');
INSERT INTO `flow_log` VALUES ('10', '3', '150', '3', '1', '1484294438', '1484294476', '0', '11111');
INSERT INTO `flow_log` VALUES ('11', '4', '2', '1', '1', '1484294537', '1484294556', '0', '11111');
INSERT INTO `flow_log` VALUES ('12', '4', '3', '2', '1', '1484294556', '1484880567', '0', '1111');
INSERT INTO `flow_log` VALUES ('13', '5', '2', '1', '1', '1484880505', '1484892900', '0', '22222');
INSERT INTO `flow_log` VALUES ('14', '0', '0', '0', '1', '1484892866', '1484892866', '0', '22222');
INSERT INTO `flow_log` VALUES ('15', '0', '0', '0', '1', '1484892868', '1484892868', '0', '22222');
INSERT INTO `flow_log` VALUES ('16', '0', '0', '0', '1', '1484892872', '1484892872', '0', '22222');
INSERT INTO `flow_log` VALUES ('17', '5', '3', '2', '1', '1484892900', '1484895313', '0', '22222');
INSERT INTO `flow_log` VALUES ('18', '6', '3', '1', '1', '1484895290', '1484895307', '0', '1111111');
INSERT INTO `flow_log` VALUES ('19', '6', '150', '2', null, '1484895307', '1484895307', '0', '');
INSERT INTO `flow_log` VALUES ('20', '9', '2', '1', '0', '1484899681', '1484899693', '0', '333');
INSERT INTO `flow_log` VALUES ('21', '10', '2', '1', '3', '1484900255', '1484902559', '0', '444444');
INSERT INTO `flow_log` VALUES ('23', '11', '2', '1', '3', '1485064464', '1485152145', '0', '333');
INSERT INTO `flow_log` VALUES ('24', '10', '3', '2', null, '1485065569', '1485065569', '1', '');
INSERT INTO `flow_log` VALUES ('25', '10', '150', '3', null, '1485065643', '1485065643', '1', '');
INSERT INTO `flow_log` VALUES ('26', '10', '2', '1', '3', '1485071403', '1485071626', '1', '1111');
INSERT INTO `flow_log` VALUES ('27', '10', '2', '1', '3', '1485071483', '1485071658', '1', '5555');
INSERT INTO `flow_log` VALUES ('28', '10', '2', '1', null, '1485071537', '1485071537', '1', '');
INSERT INTO `flow_log` VALUES ('29', '10', '2', '1', '3', '1485071585', '1485071728', '0', '123123123123');
INSERT INTO `flow_log` VALUES ('30', '10', '2', '1', null, '1485071768', '1485071768', '1', '');
INSERT INTO `flow_log` VALUES ('31', '10', '2', '1', '3', '1485152062', '1485152436', '0', '444');
INSERT INTO `flow_log` VALUES ('32', '11', '2', '1', '0', '1485152154', '1485152416', '0', '111111111');
INSERT INTO `flow_log` VALUES ('33', '10', '2', '1', null, '1485152444', '1485152444', '0', '');

-- ----------------------------
-- Table structure for leave
-- ----------------------------
DROP TABLE IF EXISTS `leave`;
CREATE TABLE `leave` (
  `flow_id` smallint(5) unsigned NOT NULL,
  `user_id` smallint(5) unsigned NOT NULL,
  `start_time` int(10) unsigned NOT NULL,
  `end_time` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `describe` text NOT NULL,
  `leave_type` tinyint(2) unsigned NOT NULL,
  `leave_day` varchar(4) NOT NULL,
  `overtime` varchar(125) NOT NULL COMMENT '补休时间',
  `other_type` varchar(40) NOT NULL COMMENT '其他带薪假期类别',
  `create_time` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`flow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of leave
-- ----------------------------
INSERT INTO `leave` VALUES ('1', '1', '1484201400', '1484106540', '0', '1233', '1', '2', '', '', '1484105389', '1484106593');
INSERT INTO `leave` VALUES ('2', '1', '1484292480', '1484292480', '0', '测试2', '2', '1', '', '', '1484292511', '1484292818');
INSERT INTO `leave` VALUES ('3', '1', '1484294220', '1484294220', '0', '测试3', '1', '3', '', '', '1484294281', '1484294281');
INSERT INTO `leave` VALUES ('4', '1', '1484294520', '1484294520', '0', '侧视33', '1', '1', '', '', '1484294537', '1484294537');
INSERT INTO `leave` VALUES ('5', '1', '1484880480', '1484880480', '0', '123', '1', '1', '', '', '1484880505', '1484880505');
INSERT INTO `leave` VALUES ('6', '2', '1484895240', '1484895240', '0', '123123', '1', '1', '', '', '1484895290', '1484895290');
INSERT INTO `leave` VALUES ('7', '2', '1484898840', '1484898840', '0', '1111', '2', '1', '', '', '1484898893', '1484898893');
INSERT INTO `leave` VALUES ('8', '2', '1484898960', '1484898960', '0', '22222', '1', '2', '', '', '1484898965', '1484898965');
INSERT INTO `leave` VALUES ('9', '1', '1484899620', '1484899620', '0', '123', '1', '1', '', '', '1484899681', '1484899681');
INSERT INTO `leave` VALUES ('10', '1', '1484900220', '1484900220', '0', '8789', '1', '1', '', '', '1484900255', '1485152444');
INSERT INTO `leave` VALUES ('11', '1', '1485064440', '1485064440', '0', '123', '2', '1', '', '', '1485064464', '1485152154');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '项目id',
  `pid` int(10) unsigned NOT NULL COMMENT '父级菜单ID',
  `name` varchar(20) NOT NULL COMMENT '菜单名称',
  `controller` varchar(40) NOT NULL COMMENT '控制器名称',
  `action` varchar(40) NOT NULL COMMENT '方法名称',
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
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '后台管理', '', 'javascript:;', 'fa-cog', '', '10', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('2', '1', '用户管理', 'user', 'index', 'fa-users', '', '1', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('4', '0', '流程相关', '', 'javascript:;', 'fa-share', '', '1', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('5', '4', '发起', 'flow', 'index', 'fa-paper-plane-o', '', '1', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('6', '4', '已提交', 'flow', 'submit', 'fa-legal', '', '2', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('7', '1', '角色管理', 'role', 'index', 'fa-key', '', '3', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('8', '1', '菜单管理', 'menu', 'index', 'fa-bars', '', '1', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('9', '4', '待审核', 'flow', 'confirm', 'fa-eye', '', '3', '1', '1', '0', '0', '0');
INSERT INTO `menu` VALUES ('10', '4', '流程配置', 'flow', 'manage', 'fa-eye', '', '6', '1', '1', '0', '0', '0');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES ('1', '员工', '1', '0', '0', '0');
INSERT INTO `position` VALUES ('2', '主管', '1', '0', '0', '0');

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
  `leader_id` int(10) unsigned NOT NULL COMMENT '部门负责人id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of project
-- ----------------------------
INSERT INTO `project` VALUES ('1', '项目1', '1', '0', '0', '0', '3');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', '超级管理员', '超级管理员', '1', '0', '0', '0');
INSERT INTO `role` VALUES ('2', '管理员', '管理员', '1', '0', '0', '0');
INSERT INTO `role` VALUES ('3', '测试', '测试角色', '1', '0', '0', '0');

-- ----------------------------
-- Table structure for role_menu
-- ----------------------------
DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu` (
  `role_id` int(10) unsigned NOT NULL COMMENT '角色id',
  `menu_id` int(10) unsigned NOT NULL COMMENT '菜单id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_menu
-- ----------------------------
INSERT INTO `role_menu` VALUES ('1', '7');
INSERT INTO `role_menu` VALUES ('1', '8');
INSERT INTO `role_menu` VALUES ('1', '2');
INSERT INTO `role_menu` VALUES ('1', '1');
INSERT INTO `role_menu` VALUES ('1', '10');
INSERT INTO `role_menu` VALUES ('1', '9');
INSERT INTO `role_menu` VALUES ('1', '6');
INSERT INTO `role_menu` VALUES ('3', '6');
INSERT INTO `role_menu` VALUES ('2', '8');
INSERT INTO `role_menu` VALUES ('2', '2');
INSERT INTO `role_menu` VALUES ('2', '1');
INSERT INTO `role_menu` VALUES ('2', '9');
INSERT INTO `role_menu` VALUES ('3', '5');
INSERT INTO `role_menu` VALUES ('3', '4');
INSERT INTO `role_menu` VALUES ('2', '6');
INSERT INTO `role_menu` VALUES ('2', '5');
INSERT INTO `role_menu` VALUES ('2', '4');
INSERT INTO `role_menu` VALUES ('1', '5');
INSERT INTO `role_menu` VALUES ('2', '7');
INSERT INTO `role_menu` VALUES ('1', '4');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES ('1', '2');
INSERT INTO `role_user` VALUES ('2', '2');
INSERT INTO `role_user` VALUES ('150', '1');
INSERT INTO `role_user` VALUES ('3', '1');
INSERT INTO `role_user` VALUES ('1', '1');
INSERT INTO `role_user` VALUES ('3', '2');

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
  `tel` bigint(20) unsigned NOT NULL COMMENT '电话',
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
) ENGINE=MyISAM AUTO_INCREMENT=151 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', 'jianhuanxin', '简焕新', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '1', '0', '0', '', '133451314@qq.com', '13129071469', '1482422400', '', '0', '1', '1483082559', '0', '0');
INSERT INTO `user` VALUES ('2', 'zhuguan', '主管', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '2', '0', '0', '', '', '0', '1483545600', '', '0', '1', '1483603639', '0', '0');
INSERT INTO `user` VALUES ('150', 'zengganlin', '曽甘霖', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '2', '0', '0', '', '', '0', '1483545600', '', '0', '1', '1483603639', '0', '0');
INSERT INTO `user` VALUES ('3', 'bumen', '部门', 'e10adc3949ba59abbe56e057f20f883e', '1', '1', '2', '0', '0', '', '', '1', '1483545600', '', '0', '1', '1483603639', '0', '0');
