/*
 Navicat Premium Data Transfer

 Source Server         : my_aliyun
 Source Server Type    : MySQL
 Source Server Version : 50640
 Source Host           : 47.244.2.208:3306
 Source Schema         : stock

 Target Server Type    : MySQL
 Target Server Version : 50640
 File Encoding         : 65001

 Date: 20/08/2019 10:37:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ave_testing
-- ----------------------------
DROP TABLE IF EXISTS `ave_testing`;
CREATE TABLE `ave_testing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17062 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for buying_point
-- ----------------------------
DROP TABLE IF EXISTS `buying_point`;
CREATE TABLE `buying_point` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `is_kdj_lowest` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `highest` varchar(32) DEFAULT NULL COMMENT '之后两个月内的最高价',
  `close` varchar(32) DEFAULT NULL COMMENT '当前价',
  `growth_rate` varchar(32) DEFAULT NULL COMMENT '涨幅',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cross
-- ----------------------------
DROP TABLE IF EXISTS `cross`;
CREATE TABLE `cross` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5635 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for five_ave_rise
-- ----------------------------
DROP TABLE IF EXISTS `five_ave_rise`;
CREATE TABLE `five_ave_rise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=InnoDB AUTO_INCREMENT=7778 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for five_sixty
-- ----------------------------
DROP TABLE IF EXISTS `five_sixty`;
CREATE TABLE `five_sixty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for golden_above
-- ----------------------------
DROP TABLE IF EXISTS `golden_above`;
CREATE TABLE `golden_above` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`code`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=31412 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for lirun
-- ----------------------------
DROP TABLE IF EXISTS `lirun`;
CREATE TABLE `lirun` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `report_date` date NOT NULL,
  `operatereve` varchar(255) DEFAULT NULL COMMENT '总营收',
  `operatereve_tb` float(10,0) DEFAULT NULL COMMENT '总营收同比',
  `net_profit` varchar(255) DEFAULT NULL COMMENT '净利润',
  `net_profit_tb` float(10,0) DEFAULT NULL COMMENT '净利润同比',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`,`report_date`)
) ENGINE=InnoDB AUTO_INCREMENT=7528 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for macd_testing
-- ----------------------------
DROP TABLE IF EXISTS `macd_testing`;
CREATE TABLE `macd_testing` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `close` varchar(32) DEFAULT '0',
  `highest` varchar(32) DEFAULT NULL,
  `growth_rate` float(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=47259 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for macd_twice_gold
-- ----------------------------
DROP TABLE IF EXISTS `macd_twice_gold`;
CREATE TABLE `macd_twice_gold` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6245 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for publish_record
-- ----------------------------
DROP TABLE IF EXISTS `publish_record`;
CREATE TABLE `publish_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `quantity` varchar(64) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`code`,`date`)
) ENGINE=InnoDB AUTO_INCREMENT=68596 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock
-- ----------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `outer_code` varchar(32) DEFAULT NULL,
  `jys` varchar(32) DEFAULT NULL,
  `market_type` varchar(32) DEFAULT NULL,
  `mkt_num` int(11) DEFAULT NULL,
  `security_type` tinyint(3) DEFAULT NULL,
  `net_interest` decimal(10,4) DEFAULT NULL COMMENT '净利率',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3906 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for stock_flow
-- ----------------------------
DROP TABLE IF EXISTS `stock_flow`;
CREATE TABLE `stock_flow` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL COMMENT '股票代码',
  `open` varchar(32) NOT NULL COMMENT '开盘价',
  `close` varchar(32) NOT NULL COMMENT '收盘价',
  `highest` varchar(32) NOT NULL COMMENT '最高价',
  `lowest` varchar(32) NOT NULL COMMENT '最低价',
  `vol` int(255) NOT NULL COMMENT '成交量',
  `date` date NOT NULL COMMENT '日期',
  `turnover` bigint(10) NOT NULL COMMENT '成交额',
  `amplitude` varchar(32) NOT NULL COMMENT '振幅',
  `turnover_rate` varchar(32) DEFAULT NULL COMMENT '换手率',
  `five_ave` varchar(32) DEFAULT NULL COMMENT '五日均价',
  `ten_ave` varchar(32) DEFAULT NULL COMMENT '十日均价',
  `twenty_ave` varchar(32) DEFAULT NULL COMMENT '二十日均价',
  `sixty_ave` varchar(32) DEFAULT NULL COMMENT '六十日均价',
  `kdj_k` varchar(32) DEFAULT NULL,
  `kdj_d` varchar(32) DEFAULT NULL,
  `kdj_j` varchar(32) DEFAULT NULL,
  `ema12` varchar(32) DEFAULT NULL,
  `ema26` varchar(32) DEFAULT NULL,
  `diff` varchar(32) DEFAULT NULL,
  `dea` varchar(32) DEFAULT NULL,
  `macd` varchar(32) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `code` (`code`,`date`,`close`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10082586 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tape
-- ----------------------------
DROP TABLE IF EXISTS `tape`;
CREATE TABLE `tape` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `tape_z` decimal(10,4) NOT NULL COMMENT '看多',
  `tape_d` decimal(10,4) NOT NULL COMMENT '看空',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=190013 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for vol_rise
-- ----------------------------
DROP TABLE IF EXISTS `vol_rise`;
CREATE TABLE `vol_rise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=7885 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for zhuli
-- ----------------------------
DROP TABLE IF EXISTS `zhuli`;
CREATE TABLE `zhuli` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `code` varchar(32) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `date` (`date`,`code`)
) ENGINE=InnoDB AUTO_INCREMENT=628 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
