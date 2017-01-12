-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `bravery`;
CREATE TABLE `bravery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `founded` varchar(4) COLLATE utf8_czech_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `coaster`;
CREATE TABLE `coaster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bravery_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `front_image` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `back_image` varchar(120) COLLATE utf8_czech_ci NOT NULL,
  `amount` tinyint(4) NOT NULL,
  `date_create` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(75) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(75) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(75) COLLATE utf8_czech_ci NOT NULL,
  `role` varchar(25) COLLATE utf8_czech_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;


-- 2017-01-12 19:31:22