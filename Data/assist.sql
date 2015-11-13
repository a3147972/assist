/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : assist

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2015-11-13 10:00:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_about
-- ----------------------------
DROP TABLE IF EXISTS `think_about`;
CREATE TABLE `think_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_about
-- ----------------------------

-- ----------------------------
-- Table structure for think_admin
-- ----------------------------
DROP TABLE IF EXISTS `think_admin`;
CREATE TABLE `think_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `nickname` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `is_enable` int(11) NOT NULL COMMENT '状态 1-正常 0-禁用',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_admin
-- ----------------------------
INSERT INTO `think_admin` VALUES ('1', 'admin', '超级管理员', '21232f297a57a5a743894a0e4a801fc3', '1', '1446445041', '1446445041');

-- ----------------------------
-- Table structure for think_assist
-- ----------------------------
DROP TABLE IF EXISTS `think_assist`;
CREATE TABLE `think_assist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员id',
  `money` int(11) NOT NULL COMMENT '提供援助的金额',
  `match_money` int(11) NOT NULL DEFAULT '0' COMMENT '已匹配金额',
  `status` int(11) DEFAULT '0' COMMENT '状态 0-等待匹配 1- 部分匹配 2-完全匹配',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='援助记录表';

-- ----------------------------
-- Records of think_assist
-- ----------------------------

-- ----------------------------
-- Table structure for think_c_log
-- ----------------------------
DROP TABLE IF EXISTS `think_c_log`;
CREATE TABLE `think_c_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(1) NOT NULL COMMENT '收入类型 1-增加 2-减少',
  `money` int(11) NOT NULL COMMENT '金额',
  `source` int(2) NOT NULL COMMENT '来源 1-推荐奖励 2-管理奖励 3-提供援助 4-拉黑扣除 5-冻结扣除 6-充值',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_c_log
-- ----------------------------

-- ----------------------------
-- Table structure for think_earn
-- ----------------------------
DROP TABLE IF EXISTS `think_earn`;
CREATE TABLE `think_earn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `money` int(11) NOT NULL COMMENT '收益金额',
  `match_money` int(11) NOT NULL DEFAULT '0' COMMENT '已匹配金额',
  `money_type` int(11) NOT NULL COMMENT '钱包类型 1-奖金钱包 2-收益钱包',
  `status` int(11) NOT NULL COMMENT '0:等待匹配 1:部分匹配 2:匹配完成',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收益提交记录';

-- ----------------------------
-- Records of think_earn
-- ----------------------------

-- ----------------------------
-- Table structure for think_letter
-- ----------------------------
DROP TABLE IF EXISTS `think_letter`;
CREATE TABLE `think_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '发信人，0为管理员',
  `to_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '收信人,0为管理员',
  `title` varchar(64) NOT NULL COMMENT '标题',
  `content` varchar(64) NOT NULL COMMENT '内容',
  `is_read` int(1) NOT NULL DEFAULT '0' COMMENT '是否已读 0-未读 1-已读',
  `create_time` int(11) NOT NULL COMMENT '发信时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_letter
-- ----------------------------

-- ----------------------------
-- Table structure for think_order
-- ----------------------------
DROP TABLE IF EXISTS `think_order`;
CREATE TABLE `think_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(30) NOT NULL COMMENT '订单号',
  `assist_id` int(11) NOT NULL COMMENT '援助记录id',
  `earn_id` int(11) NOT NULL COMMENT '收益记录id',
  `money` int(11) NOT NULL COMMENT '匹配金额',
  `transfer_pic` varchar(500) NOT NULL COMMENT '转账图片',
  `status` int(11) NOT NULL COMMENT '订单状态 1-完成 0-等待确认',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_order
-- ----------------------------

-- ----------------------------
-- Table structure for think_pin_deal
-- ----------------------------
DROP TABLE IF EXISTS `think_pin_deal`;
CREATE TABLE `think_pin_deal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '赠送人',
  `to_user_id` int(11) NOT NULL COMMENT '被赠与人',
  `amount` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_pin_deal
-- ----------------------------

-- ----------------------------
-- Table structure for think_pin_log
-- ----------------------------
DROP TABLE IF EXISTS `think_pin_log`;
CREATE TABLE `think_pin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '会员id',
  `type` int(1) NOT NULL COMMENT '操作 1-增加 2-减少',
  `source` int(11) NOT NULL COMMENT '来源 1-转账 2-排队',
  `amount` int(11) NOT NULL COMMENT '数量',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_pin_log
-- ----------------------------

-- ----------------------------
-- Table structure for think_r_log
-- ----------------------------
DROP TABLE IF EXISTS `think_r_log`;
CREATE TABLE `think_r_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` int(1) NOT NULL COMMENT '收益类型 1-增加 2-减少',
  `source` int(1) NOT NULL COMMENT '来源 1-获取收益 2-拉黑扣除 3-冻结扣除 4-充值',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_r_log
-- ----------------------------

-- ----------------------------
-- Table structure for think_user
-- ----------------------------
DROP TABLE IF EXISTS `think_user`;
CREATE TABLE `think_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `pay_password` varchar(64) NOT NULL,
  `c_money` int(11) NOT NULL DEFAULT '0' COMMENT '奖金钱包',
  `r_money` int(11) NOT NULL DEFAULT '0' COMMENT '收益钱包',
  `pin` int(11) NOT NULL DEFAULT '0' COMMENT '门票',
  `name` varchar(64) NOT NULL,
  `phone` char(16) DEFAULT NULL COMMENT '电话',
  `email` varchar(64) NOT NULL,
  `province` varchar(64) NOT NULL COMMENT '省份',
  `city` varchar(64) NOT NULL,
  `alipay_account` varchar(64) NOT NULL,
  `bank_name` varchar(64) NOT NULL COMMENT '银行名称',
  `bank_address` varchar(64) CHARACTER SET utf8mb4 NOT NULL COMMENT '开户行',
  `bank_code` varchar(64) NOT NULL COMMENT '银行卡号',
  `bank_account` varchar(64) NOT NULL COMMENT '户主',
  `iban_code` varchar(64) DEFAULT NULL COMMENT '国际银行帐户号码',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '状态 1-正常 0-禁用 -1终止 2-冻结 3-拉黑',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Records of think_user
-- ----------------------------
INSERT INTO `think_user` VALUES ('2', '0', '1', 'test1', '4297f44b13955235245b2497399d7a93', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '0', '测试一', '18888888888', '18888888888@qq.com', '北京', '北京市', '18888888888', '建设银行', '北京支行', '622202020000000000', '测试', null, '1', '1446782283', '1446782412');

-- ----------------------------
-- Table structure for think_user_level
-- ----------------------------
DROP TABLE IF EXISTS `think_user_level`;
CREATE TABLE `think_user_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '会员级别名称',
  `queue_max_time_day` int(11) NOT NULL COMMENT '每日最大排队次数',
  `queue_max_time_month` int(11) NOT NULL COMMENT '每月最大排队次数',
  `queue_min_time_month` int(11) NOT NULL COMMENT '每月最少排队次数',
  `recommend_reward` int(11) NOT NULL COMMENT '推荐奖励百分比',
  `manage_reward` int(11) NOT NULL COMMENT '管理奖励百分比',
  `earnings_max_7_day` int(11) NOT NULL COMMENT '7天最高收益',
  `upgrade_count` int(11) NOT NULL COMMENT '升级规则,需要多少直线下级可以升级',
  `black_c_penalty` int(11) NOT NULL COMMENT '拉黑奖金钱包罚金',
  `black_r_penalty` int(11) NOT NULL COMMENT '拉黑收益钱包罚金',
  `freeze_c_penalty` int(11) NOT NULL COMMENT '冻结收益钱包罚金',
  `freeze_r_penalty` int(11) NOT NULL COMMENT '冻结收益钱包罚金',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='会员级别表';

-- ----------------------------
-- Records of think_user_level
-- ----------------------------
INSERT INTO `think_user_level` VALUES ('1', '普通会员', '4', '8', '2', '10', '0', '4000', '5', '0', '0', '0', '0', '1446780283', '1447295140');
INSERT INTO `think_user_level` VALUES ('2', '一星会员', '5', '15', '8', '10', '5', '10000', '5', '0', '0', '0', '0', '1446781136', '1446781136');
INSERT INTO `think_user_level` VALUES ('3', '二星会员', '7', '20', '15', '10', '3', '15000', '3', '0', '0', '0', '0', '1446781184', '1446781184');
INSERT INTO `think_user_level` VALUES ('4', '三星会员', '7', '30', '20', '10', '1', '20000', '3', '0', '0', '0', '0', '1446781220', '1446781220');
INSERT INTO `think_user_level` VALUES ('5', '四星会员', '7', '35', '25', '10', '2', '25000', '3', '0', '0', '0', '0', '1446781255', '1446781255');

-- ----------------------------
-- Table structure for think_user_status_log
-- ----------------------------
DROP TABLE IF EXISTS `think_user_status_log`;
CREATE TABLE `think_user_status_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `status` int(1) NOT NULL COMMENT '状态 1-正常 0-禁用 2-冻结 3-拉黑',
  `desc` varchar(255) DEFAULT NULL COMMENT '操作描述',
  `create_time` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_user_status_log
-- ----------------------------
