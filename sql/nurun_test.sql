/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50541
Source Host           : 127.0.0.1:3306
Source Database       : nurun_test

Target Server Type    : MYSQL
Target Server Version : 50541
File Encoding         : 65001

Date: 2015-04-29 17:23:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cifras
-- ----------------------------
DROP TABLE IF EXISTS `cifras`;
CREATE TABLE `cifras` (
  `id_cifra` int(200) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(25) NOT NULL,
  `cifra` int(10) unsigned NOT NULL,
  `fecha_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cifra`),
  KEY `fk_cifra_usuario` (`id_usuario`),
  CONSTRAINT `fk_cifra_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for usuarios
-- ----------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario` int(25) NOT NULL AUTO_INCREMENT,
  `visible_id` varchar(10) NOT NULL,
  `username` varchar(35) NOT NULL,
  `id_facebook` varchar(55) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
