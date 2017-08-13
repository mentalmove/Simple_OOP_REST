CREATE DATABASE IF NOT EXISTS `simple_rest` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


USE `simple_rest`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(31) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `colour` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pet` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender` int(11) unsigned NOT NULL,
  `recipient` int(11) unsigned NOT NULL,
  `msg` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
