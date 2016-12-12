SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for shuipfcms_alipay_type
-- ----------------------------
DROP TABLE IF EXISTS `shuipfcms_alipay_type`;
CREATE TABLE `shuipfcms_alipay_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '支付类型',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `price` float(10,2) NOT NULL DEFAULT '0' COMMENT '金额',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `callback` varchar(255) NOT NULL COMMENT '回调处理程序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for shuipfcms_alipay_log
-- ----------------------------
DROP TABLE IF EXISTS `shuipfcms_alipay_log`;
CREATE TABLE `shuipfcms_alipay_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长ID',
  `orderid` varchar(20) NOT NULL COMMENT '订单号',
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否系统操作',
  `subject` varchar(160) NOT NULL COMMENT '日志简述标题',
  `log` mediumtext NOT NULL COMMENT '日志记录',
  `createtime` int(10) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付宝相关操作日志';

-- ----------------------------
-- Table structure for shuipfcms_alipay
-- ----------------------------
DROP TABLE IF EXISTS `shuipfcms_alipay`;
CREATE TABLE `shuipfcms_alipay` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增长ID',
  `alipay_type` int(11) NOT NULL DEFAULT '0' COMMENT '支付类型',
  `userid` mediumint(8) NOT NULL COMMENT '用户ID',
  `username` char(20) NOT NULL COMMENT '用户名',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `tradeno` varchar(20) NOT NULL COMMENT '支付宝交易号',
  `tradestatus` tinyint(2) NOT NULL DEFAULT '0' COMMENT '交易状态',
  `price` decimal(8,2) NOT NULL COMMENT '金额',
  `quantity` int(11) NOT NULL DEFAULT '0' COMMENT '商品数量',
  `logisticsfee` decimal(8,2) NOT NULL COMMENT '物流费用',
  `logisticstype` tinyint(1) NOT NULL COMMENT '物流类型',
  `logisticspayment` tinyint(1) NOT NULL COMMENT '物流费用支付方式',
  `subject` varchar(160) NOT NULL COMMENT '订单标题',
  `other` mediumtext NOT NULL COMMENT '其他数据',
  `lasttime` int(10) NOT NULL COMMENT '最后操作时间',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支付宝交易记录';