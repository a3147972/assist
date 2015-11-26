/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : assist

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2015-11-26 15:22:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for think_about
-- ----------------------------
DROP TABLE IF EXISTS `think_about`;
CREATE TABLE `think_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '标题',
  `info` varchar(400) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_about
-- ----------------------------
INSERT INTO `think_about` VALUES ('1', '习近平就我国公民被恐怖组织杀害事件发表讲话', '习近平就我国公民被恐怖组织杀害事件发表讲话', '　　新华网马尼拉１１月１９日电  １１月１９日，正在菲律宾马尼拉出席亚太经合组织第二十三次领导人非正式会议的国家主席习近平就我国公民被恐怖组织杀害事件发表讲话。  　　习近平表示，中国强烈谴责“伊斯兰国”极端组织残忍杀害中国公民这一暴行。我向遇害者家属表示深切慰问。恐怖主义是人类的公敌，中国坚决反对一切形式的恐怖主义，坚决打击任何挑战人类文明底线的暴恐犯罪活动。', '1447914692', '1447914692');

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
  `surplus_money` int(11) NOT NULL DEFAULT '0' COMMENT '剩余金额',
  `status` int(11) DEFAULT '0' COMMENT '状态 0-正在匹配 1-匹配完成',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='舍列表';

-- ----------------------------
-- Records of think_assist
-- ----------------------------
INSERT INTO `think_assist` VALUES ('6', '2', '1000', '0', '1', '1448433311', '1448433311');
INSERT INTO `think_assist` VALUES ('7', '3', '1000', '0', '1', '1448433396', '1448433396');
INSERT INTO `think_assist` VALUES ('8', '4', '2000', '2000', '0', '1448433408', '1448433408');

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_c_log
-- ----------------------------
INSERT INTO `think_c_log` VALUES ('1', '4', '1', '100', '3', '充值', '1447913588');
INSERT INTO `think_c_log` VALUES ('2', '4', '1', '10000', '3', '充值\r\n', '1447913596');
INSERT INTO `think_c_log` VALUES ('3', '2', '1', '100000', '6', '充值', '1447914907');
INSERT INTO `think_c_log` VALUES ('4', '2', '1', '100', '1', '会员test13注册推荐奖励', '1447926387');
INSERT INTO `think_c_log` VALUES ('5', '3', '1', '100000', '6', '后台充值', '1448420201');
INSERT INTO `think_c_log` VALUES ('6', '5', '1', '100000', '6', '后台充值', '1448424284');
INSERT INTO `think_c_log` VALUES ('7', '6', '1', '100000', '6', '后台充值', '1448424288');
INSERT INTO `think_c_log` VALUES ('8', '7', '1', '100000', '6', '后台充值', '1448424290');
INSERT INTO `think_c_log` VALUES ('9', '8', '1', '100000', '6', '后台充值', '1448424291');
INSERT INTO `think_c_log` VALUES ('10', '9', '1', '100000', '6', '后台充值', '1448424293');
INSERT INTO `think_c_log` VALUES ('11', '10', '1', '100000', '6', '后台充值', '1448424295');
INSERT INTO `think_c_log` VALUES ('12', '11', '1', '100000', '6', '后台充值', '1448424296');
INSERT INTO `think_c_log` VALUES ('13', '2', '2', '100', '5', '获得收益扣除', '1448433445');
INSERT INTO `think_c_log` VALUES ('14', '3', '2', '200', '5', '获得收益扣除', '1448433455');
INSERT INTO `think_c_log` VALUES ('15', '4', '2', '300', '5', '获得收益扣除', '1448433461');
INSERT INTO `think_c_log` VALUES ('16', '5', '2', '400', '5', '获得收益扣除', '1448433466');
INSERT INTO `think_c_log` VALUES ('17', '6', '2', '1000', '5', '获得收益扣除', '1448433472');
INSERT INTO `think_c_log` VALUES ('28', '2', '2', '500', '4', '账号惩罚扣除', '1448507041');

-- ----------------------------
-- Table structure for think_earn
-- ----------------------------
DROP TABLE IF EXISTS `think_earn`;
CREATE TABLE `think_earn` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `money` int(11) NOT NULL COMMENT '收益金额',
  `surplus_money` int(11) NOT NULL DEFAULT '0' COMMENT '剩余金额',
  `money_type` int(11) NOT NULL COMMENT '钱包类型 1-奖金钱包 2-收益钱包',
  `status` int(11) NOT NULL COMMENT '0:等待匹配 1:部分匹配 2:匹配完成',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='得列表';

-- ----------------------------
-- Records of think_earn
-- ----------------------------
INSERT INTO `think_earn` VALUES ('2', '2', '100', '0', '1', '1', '1448433445', '1448433445');
INSERT INTO `think_earn` VALUES ('3', '3', '200', '0', '1', '1', '1448433455', '1448433455');
INSERT INTO `think_earn` VALUES ('4', '4', '300', '0', '1', '1', '1448433461', '1448433461');
INSERT INTO `think_earn` VALUES ('5', '5', '400', '0', '1', '1', '1448433466', '1448433466');
INSERT INTO `think_earn` VALUES ('6', '6', '1000', '0', '1', '1', '1448433472', '1448433472');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_letter
-- ----------------------------
INSERT INTO `think_letter` VALUES ('1', '2', '0', '111', '1111', '1', '1447988266');
INSERT INTO `think_letter` VALUES ('2', '0', '2', '回复:111', '2222\r\n                        ', '0', '1447991050');
INSERT INTO `think_letter` VALUES ('3', '2', '0', '回复:111', '33333', '0', '1447993301');

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
  `status` int(11) NOT NULL COMMENT '订单状态 1-完成 0-等待确认 2-确认付款 3-拒绝付款',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_order
-- ----------------------------
INSERT INTO `think_order` VALUES ('2', '15112514381782987', '7', '2', '100', '', '0', '1448433497', '1448433497');
INSERT INTO `think_order` VALUES ('3', '15112514381737375', '6', '3', '200', '', '0', '1448433497', '1448433497');
INSERT INTO `think_order` VALUES ('4', '15112514381725457', '6', '4', '300', '', '0', '1448433497', '1448433497');
INSERT INTO `think_order` VALUES ('5', '15112514381769427', '6', '5', '400', '', '3', '1448433497', '1448433497');
INSERT INTO `think_order` VALUES ('6', '15112514381745755', '6', '6', '100', './Uploads/20151125/5655582c194bb.jpg', '1', '1448433497', '1448433497');
INSERT INTO `think_order` VALUES ('7', '15112514381757139', '7', '6', '900', '', '1', '1448433497', '1448433497');

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_pin_log
-- ----------------------------
INSERT INTO `think_pin_log` VALUES ('1', '4', '1', '3', '100', '充值', '1447913609');
INSERT INTO `think_pin_log` VALUES ('2', '2', '1', '3', '100', '充值', '1447914926');
INSERT INTO `think_pin_log` VALUES ('3', '2', '2', '2', '1', '排队扣除', '1447914940');
INSERT INTO `think_pin_log` VALUES ('4', '2', '2', '2', '1', '排队扣除', '1447921658');
INSERT INTO `think_pin_log` VALUES ('5', '2', '2', '2', '1', '排队扣除', '1447921716');
INSERT INTO `think_pin_log` VALUES ('6', '2', '2', '2', '1', '排队扣除', '1447921721');
INSERT INTO `think_pin_log` VALUES ('7', '2', '2', '2', '1', '排队扣除', '1447921754');
INSERT INTO `think_pin_log` VALUES ('8', '3', '1', '3', '10000', '后台充值', '1448420223');
INSERT INTO `think_pin_log` VALUES ('9', '5', '1', '3', '1000', '后台充值', '1448424402');
INSERT INTO `think_pin_log` VALUES ('10', '6', '1', '3', '1000', '后台充值', '1448424408');
INSERT INTO `think_pin_log` VALUES ('11', '7', '1', '3', '1000', '后台充值', '1448424409');
INSERT INTO `think_pin_log` VALUES ('12', '8', '1', '3', '1000', '后台充值', '1448424409');
INSERT INTO `think_pin_log` VALUES ('13', '9', '1', '3', '1000', '后台充值', '1448424412');
INSERT INTO `think_pin_log` VALUES ('14', '10', '1', '3', '1000', '后台充值', '1448424413');
INSERT INTO `think_pin_log` VALUES ('15', '2', '2', '2', '1', '排队扣除', '1448433311');
INSERT INTO `think_pin_log` VALUES ('16', '3', '2', '2', '1', '排队扣除', '1448433396');
INSERT INTO `think_pin_log` VALUES ('17', '4', '2', '2', '2', '排队扣除', '1448433408');

-- ----------------------------
-- Table structure for think_report
-- ----------------------------
DROP TABLE IF EXISTS `think_report`;
CREATE TABLE `think_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '举报人id',
  `to_user_id` int(11) NOT NULL COMMENT '被举报人',
  `reson_type` int(11) NOT NULL COMMENT '1-对方未打款 2-对方未确认 3-对方账号有误 4-联系方式有误 0-其他',
  `reson` text COMMENT '举报原因',
  `pic` varchar(500) DEFAULT NULL COMMENT '举报图片',
  `status` int(11) NOT NULL COMMENT '状态 0-等待 1-审核通过 -1:审核不通过',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='举报列表';

-- ----------------------------
-- Records of think_report
-- ----------------------------
INSERT INTO `think_report` VALUES ('1', '15112316130510074', '2', '2', '3', '对方账号有误', './Uploads/20151124/56540f7a36ae4.jpg', '-1', '1448349570', '1448349570');

-- ----------------------------
-- Table structure for think_r_log
-- ----------------------------
DROP TABLE IF EXISTS `think_r_log`;
CREATE TABLE `think_r_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `money` int(11) NOT NULL,
  `type` int(1) NOT NULL COMMENT '收益类型 1-增加 2-减少',
  `source` int(1) NOT NULL COMMENT '来源 1-收益 2-后台充值 3-拉黑扣除 4-冻结扣除',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_r_log
-- ----------------------------
INSERT INTO `think_r_log` VALUES ('1', '4', '100000', '1', '2', '充值', '1447913603');
INSERT INTO `think_r_log` VALUES ('2', '2', '1000000', '1', '2', '充值', '1447914919');
INSERT INTO `think_r_log` VALUES ('3', '2', '100', '2', '5', '获得收益扣除', '1447922009');
INSERT INTO `think_r_log` VALUES ('4', '3', '100000', '1', '2', '后台充值', '1448420213');
INSERT INTO `think_r_log` VALUES ('5', '5', '100000', '1', '2', '后台充值', '1448424337');
INSERT INTO `think_r_log` VALUES ('6', '6', '100000', '1', '2', '后台充值', '1448424339');
INSERT INTO `think_r_log` VALUES ('7', '7', '100000', '1', '2', '后台充值', '1448424341');
INSERT INTO `think_r_log` VALUES ('8', '8', '100000', '1', '2', '后台充值', '1448424342');
INSERT INTO `think_r_log` VALUES ('9', '9', '100000', '1', '2', '后台充值', '1448424344');
INSERT INTO `think_r_log` VALUES ('10', '10', '100000', '1', '2', '后台充值', '1448424345');
INSERT INTO `think_r_log` VALUES ('11', '3', '1100', '1', '1', '提供帮助收益,订单号:15112514381745755', '1448436522');
INSERT INTO `think_r_log` VALUES ('12', '3', '1100', '1', '1', '提供帮助收益,订单号:15112514381757139', '1448436717');
INSERT INTO `think_r_log` VALUES ('23', '2', '500', '2', '3', '账号惩罚扣除', '1448507041');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_user
-- ----------------------------
INSERT INTO `think_user` VALUES ('2', '0', '1', 'test1', '4297f44b13955235245b2497399d7a93', 'e10adc3949ba59abbe56e057f20f883e', '99500', '999400', '94', '测试一', '18888888888', '18888888888@qq.com', '北京', '北京市', '18888888888', '建设银行', '北京支行', '622202020000000000', '测试', null, '1', '1446782283', '1446782412');
INSERT INTO `think_user` VALUES ('3', '0', '1', 'test2', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '99800', '102200', '9999', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447913514', '1447913514');
INSERT INTO `think_user` VALUES ('4', '2', '1', 'test3', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '9800', '100000', '98', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447913542', '1447913542');
INSERT INTO `think_user` VALUES ('5', '4', '1', 'test4', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '99600', '100000', '1000', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914025', '1447914025');
INSERT INTO `think_user` VALUES ('6', '0', '1', 'test5', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '99000', '100000', '1000', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914040', '1447914040');
INSERT INTO `think_user` VALUES ('7', '2', '1', 'test6', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '100000', '100000', '1000', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914049', '1447914049');
INSERT INTO `think_user` VALUES ('8', '2', '1', 'test7', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '100000', '100000', '1000', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914053', '1447914053');
INSERT INTO `think_user` VALUES ('9', '2', '1', 'test8', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '100000', '100000', '1000', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914056', '1447914056');
INSERT INTO `think_user` VALUES ('10', '2', '1', 'test9', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '100000', '100000', '1000', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914072', '1447914072');
INSERT INTO `think_user` VALUES ('11', '2', '1', 'test10', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '100000', '0', '0', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914074', '1447914074');
INSERT INTO `think_user` VALUES ('12', '2', '1', 'test11', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '0', '0', '0', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914076', '1447914076');
INSERT INTO `think_user` VALUES ('13', '2', '1', 'test12', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '0', '0', '0', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914082', '1447914082');
INSERT INTO `think_user` VALUES ('14', '2', '1', 'test18', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '0', '0', '0', '张三', '18888888888', '18888888888@163.com', '北京', '北京市', '18888888888@163.com', '北京银行', '北京支行', '622738401843664', '张三', null, '1', '1447914083', '1447914083');
INSERT INTO `think_user` VALUES ('15', '0', '1', 'test13', '4297f44b13955235245b2497399d7a93', '4297f44b13955235245b2497399d7a93', '0', '0', '0', '测试12', '18802938988', '18802938988@qq.com', '南京', '南京', '18802938988@qq.com', '南京银行', '南京支行', '12378734468950', '测试账号', '', '1', '1447926387', '1447926387');
INSERT INTO `think_user` VALUES ('16', '0', '1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'e10adc3949ba59abbe56e057f20f883e', '0', '0', '0', 'admin', '18500000000', '18500000000@qq.com', '北京', '北京市', '18500000000@qq.com', '中国银行', '北京支行', '62202020200001', '测试人', '', '1', '1448522384', '1448522384');

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
  `freeze_c_penalty` int(11) NOT NULL COMMENT '冻结奖金钱包罚金',
  `freeze_r_penalty` int(11) NOT NULL COMMENT '冻结收益钱包罚金',
  `create_time` int(11) NOT NULL,
  `modify_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_user_level
-- ----------------------------
INSERT INTO `think_user_level` VALUES ('1', '特殊组', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `think_user_level` VALUES ('2', '普通会员', '4', '8', '2', '10', '0', '4000', '5', '1000', '1000', '500', '500', '1446780283', '1447913344');
INSERT INTO `think_user_level` VALUES ('3', '一星会员', '5', '15', '8', '10', '5', '10000', '5', '2000', '2000', '1000', '1000', '1446781136', '1446781136');
INSERT INTO `think_user_level` VALUES ('4', '二星会员', '7', '20', '15', '10', '3', '15000', '3', '2000', '2000', '1000', '1000', '1446781184', '1446781184');
INSERT INTO `think_user_level` VALUES ('5', '三星会员', '7', '30', '20', '10', '1', '20000', '3', '2000', '2000', '1000', '1000', '1446781220', '1446781220');
INSERT INTO `think_user_level` VALUES ('6', '四星会员', '7', '35', '25', '10', '2', '25000', '3', '2000', '2000', '1000', '1000', '1446781255', '1446781255');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of think_user_status_log
-- ----------------------------
INSERT INTO `think_user_status_log` VALUES ('1', '2', '0', '拒绝付款冻结', '1448437617');
INSERT INTO `think_user_status_log` VALUES ('2', '2', '0', '接受惩罚恢复正常', '1448507041');
