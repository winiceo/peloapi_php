/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100307
 Source Host           : localhost:3306
 Source Schema         : pelo

 Target Server Type    : MySQL
 Target Server Version : 100307
 File Encoding         : 65001

 Date: 29/05/2018 17:25:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for activations
-- ----------------------------
DROP TABLE IF EXISTS `activations`;
CREATE TABLE `activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(191) NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activations_user_id_foreign` (`user_id`),
  CONSTRAINT `activations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of activations
-- ----------------------------
BEGIN;
INSERT INTO `activations` VALUES (1, 1, 's4u7l9pfNGuFDTHWWwGINAC8X4MBntMr', 1, '2018-05-29 08:14:16', '2018-05-29 08:14:16', '2018-05-29 08:14:16');
COMMIT;

-- ----------------------------
-- Table structure for assets
-- ----------------------------
DROP TABLE IF EXISTS `assets`;
CREATE TABLE `assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '创建者uid',
  `coin_id` smallint(4) NOT NULL COMMENT '币种',
  `balance` bigint(10) unsigned DEFAULT 0 COMMENT '总资产',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of assets
-- ----------------------------
BEGIN;
INSERT INTO `assets` VALUES (1, 1, 1, 98335, '2017-12-13 16:27:15', '2018-05-29 09:10:28');
INSERT INTO `assets` VALUES (2, 1, 2, 2000000, '2017-12-13 16:27:15', '2017-12-13 16:27:15');
COMMIT;

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `item_cnt` int(10) unsigned NOT NULL DEFAULT 0,
  `is_active` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'yes',
  `has_pic` enum('yes','no') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  `meta_title` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_description` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `meta_keywords` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for coin_type
-- ----------------------------
DROP TABLE IF EXISTS `coin_type`;
CREATE TABLE `coin_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `symbol` varchar(191) NOT NULL,
  `decimals` int(10) DEFAULT NULL,
  `withdraw_enable` tinyint(1) DEFAULT 0,
  `fee` int(10) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of coin_type
-- ----------------------------
BEGIN;
INSERT INTO `coin_type` VALUES (1, '菠萝', 'Pelo', 18, 1, 10);
INSERT INTO `coin_type` VALUES (2, '菠菜', 'Boc', 18, 0, 2);
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` VALUES ('20171117135548');
INSERT INTO `migrations` VALUES ('20171117140509');
INSERT INTO `migrations` VALUES ('20171120151128');
INSERT INTO `migrations` VALUES ('20171130091220');
INSERT INTO `migrations` VALUES ('20171228211415');
INSERT INTO `migrations` VALUES ('20180101083658');
COMMIT;

-- ----------------------------
-- Table structure for order
-- ----------------------------
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) DEFAULT NULL,
  `coin_id` int(10) NOT NULL DEFAULT 0 COMMENT '提币币种',
  `amount` bigint(10) unsigned NOT NULL COMMENT '提币数量',
  `balance` bigint(10) DEFAULT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单编号',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '提币地址',
  `finish_time` datetime DEFAULT NULL COMMENT '交易结束时间',
  `status` tinyint(3) unsigned DEFAULT 0 COMMENT '状态：0 - 处理中，1 - 已审批，2 - 已完成',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `txid` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(1) DEFAULT 0 COMMENT '1为收入;2为提现',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for permission_role
-- ----------------------------
DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE `permission_role` (
  `permission_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_role_id_foreign` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for persistences
-- ----------------------------
DROP TABLE IF EXISTS `persistences`;
CREATE TABLE `persistences` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `code` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persistences_code_unique` (`code`),
  KEY `persistences_user_id_foreign` (`user_id`),
  CONSTRAINT `persistences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of persistences
-- ----------------------------
BEGIN;
INSERT INTO `persistences` VALUES (1, 1, 'qvK5Fj9TMsDJWF1OO3T5gl1sYRHOgiUJ', '2018-05-29 08:14:20', '2018-05-29 08:14:20');
COMMIT;

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user` (
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users1` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for throttle
-- ----------------------------
DROP TABLE IF EXISTS `throttle`;
CREATE TABLE `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `type` varchar(191) NOT NULL,
  `ip` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_foreign` (`user_id`),
  CONSTRAINT `throttle_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(191) NOT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `first_name` varchar(191) DEFAULT NULL,
  `permissions` text DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_username_unique` (`username`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES (1, 'admin', NULL, '18028721507', '$2y$10$ZIp2DodMwLZ4hQ54c0Aoj.ayqWAEmU1WxuZ2ggsJbZhVV3ypYezRO', NULL, NULL, '{\"user.delete\":0}', '2018-05-29 08:14:20', '2018-05-29 08:14:16', '2018-05-29 08:14:20');
COMMIT;

-- ----------------------------
-- Table structure for user_balances
-- ----------------------------
DROP TABLE IF EXISTS `user_balances`;
CREATE TABLE `user_balances` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '创建者uid',
  `coin_type` smallint(4) NOT NULL COMMENT '币种',
  `coin_name` char(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `block_balance` bigint(20) DEFAULT 0 COMMENT '锁定金额',
  `pending_balance` bigint(10) unsigned DEFAULT 0 COMMENT '可用金额',
  `total_balance` bigint(10) unsigned DEFAULT 0 COMMENT '总资产',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_balances
-- ----------------------------
BEGIN;
INSERT INTO `user_balances` VALUES (1, 1, 1, 'BTC', 702402, 0, 2122823, '2017-12-13 16:27:15', '2017-12-15 08:40:37');
INSERT INTO `user_balances` VALUES (2, 1, 2, 'ETH', 0, 0, 123123123, '2017-12-13 16:27:15', '2017-12-13 16:27:15');
COMMIT;

-- ----------------------------
-- Table structure for user_orders
-- ----------------------------
DROP TABLE IF EXISTS `user_orders`;
CREATE TABLE `user_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '订单编号',
  `ad_id` int(10) unsigned NOT NULL COMMENT '广告ID',
  `ad_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '广告编号',
  `ad_user_id` int(10) unsigned NOT NULL COMMENT '广告用户ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ad_price` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '广告单价价',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '交易金额',
  `qty` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT '交易数量',
  `payment_windowpayment_window_minutes` int(11) NOT NULL DEFAULT 30 COMMENT '交易数量',
  `finish_time` datetime DEFAULT NULL COMMENT '交易结束时间',
  `status` tinyint(3) unsigned DEFAULT 0 COMMENT '状态：0 - 待审批，1 - 已审批，2 - 被拒绝',
  `order_desc` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '交易描述',
  `buyer_estimate` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '买方评价',
  `seller_estimate` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '卖方评价',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Table structure for user_wallet
-- ----------------------------
DROP TABLE IF EXISTS `user_wallet`;
CREATE TABLE `user_wallet` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '创建者uid',
  `coin_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '币种',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '钱包名称',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '钱包地址',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '状态',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of user_wallet
-- ----------------------------
BEGIN;
INSERT INTO `user_wallet` VALUES (1, 2, '1', '我的钱包', 'abdfadfasdfadf33', '2017-12-13 13:48:36', '2017-12-13 13:48:36', 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for users1
-- ----------------------------
DROP TABLE IF EXISTS `users1`;
CREATE TABLE `users1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `has_pic` enum('no','yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
