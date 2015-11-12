CREATE TABLE `think_assist` (
`id` int(11) NOT NULL,
`user_id` int(11) NOT NULL COMMENT '会员id',
`money` int(11) NOT NULL COMMENT '提供援助的金额',
`match_money` int(11) NOT NULL DEFAULT 0 COMMENT '已匹配金额',
`status` int(11) NULL COMMENT '状态 0-等待匹配 1- 部分匹配 2-完全匹配',
`create_time` int(11) NOT NULL,
`modify_time` int(11) NOT NULL,
PRIMARY KEY (`id`) 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='援助记录表';

CREATE TABLE `think_earn` (
`id` int(11) NOT NULL,
`money` int(11) NOT NULL COMMENT '收益金额',
`match_money` int(11) NOT NULL COMMENT '已匹配金额',
`money_type` int(11) NOT NULL COMMENT '钱包类型 1-奖金钱包 2-收益钱包',
`status` int(11) NOT NULL COMMENT '0:等待匹配 1:部分匹配 2:匹配完成',
`create_time` int(11) NOT NULL,
`modify_time` int(11) NOT NULL,
PRIMARY KEY (`id`) 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='收益提交记录';

CREATE TABLE `think_order` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`order_id` int(11) NOT NULL COMMENT '订单号',
`assist_id` int(11) NOT NULL COMMENT '援助记录id',
`earn_id` int(11) NOT NULL COMMENT '收益记录id',
`money` int(11) NOT NULL COMMENT '匹配金额',
`status` int(11) NOT NULL COMMENT '订单状态 1-完成 0-等待确认',
`create_time` int(11) NOT NULL,
`modify_time` int(11) NOT NULL,
PRIMARY KEY (`id`) 
);

