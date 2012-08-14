Techniart Event Project
=======================

written by [Jeremy Greer](http://reergymerej.com)


DB Setup
--------
CREATE DATABASE `techniart` /*!40100 DEFAULT CHARACTER SET utf8 */;


CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `date_modified` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `date_start` int(10) unsigned NOT NULL,
  `date_end` int(10) unsigned DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `contact_name` varchar(45) DEFAULT NULL,
  `contact_phone` varchar(45) DEFAULT NULL,
  `contact_email` varchar(45) DEFAULT NULL,
  `notes` longblob,
  `finished` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `employees` int(10) DEFAULT NULL,
  `pm` varchar(100) DEFAULT NULL,
  `apm` varchar(100) DEFAULT NULL,
  `site_visit` int(10) unsigned DEFAULT NULL,
  `display_date` varchar(45) DEFAULT NULL,
  `am` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `event_day` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `date` int(10) unsigned NOT NULL,
  `ts_created` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `event_product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(10) unsigned NOT NULL,
  `id_product` int(10) unsigned NOT NULL,
  `count_start` int(10) unsigned NOT NULL,
  `count_end` int(10) unsigned DEFAULT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `date_modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `event_summary` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `date_closed` int(11) NOT NULL,
  `notes` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `event_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(10) unsigned NOT NULL,
  `id_user` int(10) unsigned NOT NULL,
  `date_modified` int(10) unsigned NOT NULL,
  `id_user_modified` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_event` int(10) unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `product` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(45) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(6,2) unsigned NOT NULL,
  `model` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8



CREATE TABLE `sales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_event_day` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8



CREATE TABLE `user` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `admin` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8

