/*
 Navicat Premium Data Transfer

 Source Server         : local_mysql_db
 Source Server Type    : MySQL
 Source Server Version : 50622
 Source Host           : localhost
 Source Database       : scenery-project

 Target Server Type    : MySQL
 Target Server Version : 50622
 File Encoding         : utf-8

 Date: 05/25/2015 10:33:50 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `place`
-- ----------------------------
DROP TABLE IF EXISTS `place`;
CREATE TABLE `place` (
  `id` double NOT NULL,
  `placeNum` double DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `scenery`
-- ----------------------------
DROP TABLE IF EXISTS `scenery`;
CREATE TABLE `scenery` (
  `id` double NOT NULL,
  `sceneryNum` double DEFAULT NULL,
  `placeNum` double DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `imgUrl` varchar(100) DEFAULT NULL,
  `audioUrl` varchar(100) DEFAULT NULL,
  `articleText` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
