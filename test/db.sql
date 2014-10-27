-- Adminer 4.0.3 MySQL dump

SET NAMES utf8;

DROP TABLE IF EXISTS `buy`;
CREATE TABLE `buy` (
  `buy_Id` int(11) NOT NULL AUTO_INCREMENT,
  `buy_Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `buy_Name` varchar(20) NOT NULL,
  `buy_Price` int(11) NOT NULL,
  `buy_Oprice` int(11) NOT NULL,
  `buy_Com` tinytext NOT NULL,
  `buy_Det` text NOT NULL,
  `buy_Owner` varchar(36) NOT NULL,
  `buy_Class` varchar(15) NOT NULL,
  `buy_Area` varchar(4) NOT NULL,
  `buy_End` varchar(10) NOT NULL DEFAULT 'open',
  `buy_Q` int(11) NOT NULL,
  `buy_Num` int(11) NOT NULL DEFAULT '0',
  `buy_ConRun` int(11) NOT NULL,
  `buy_ConJoin` int(11) NOT NULL,
  `buy_Methor` varchar(20) NOT NULL,
  `buy_Gname` varchar(30) DEFAULT NULL,
  `buy_Gacc` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`buy_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `join`;
CREATE TABLE `join` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `handle` varchar(40) NOT NULL DEFAULT '啾團中',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `img`;
CREATE TABLE `img` (
  `path` varchar(128) NOT NULL,
  `mime` varchar(128) NOT NULL,
  `location` varchar(128) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`path`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `imgplus`;
CREATE TABLE `imgplus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `jid` int(11) DEFAULT NULL,
  `gid` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `birth` varchar(10) NOT NULL,
  `power` varchar(5) NOT NULL DEFAULT '正常',
  `address` tinytext NOT NULL,
  `counties` varchar(4) NOT NULL,
  `run` int(11) NOT NULL DEFAULT '0',
  `join` int(11) NOT NULL DEFAULT '0',
  `main` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `post`;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `create_time` (`create_time`),
  KEY `update_time` (`update_time`),
  KEY `tid` (`tid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `thread`;
CREATE TABLE `thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `threadplus`;
CREATE TABLE `threadplus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `bid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2014-10-21 17:50:00