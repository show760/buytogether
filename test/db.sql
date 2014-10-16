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
  `buy_End` varchar(5) NOT NULL DEFAULT 'open',
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


DROP TABLE IF EXISTS `buyimg`;
CREATE TABLE `buyimg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL,
  `gid` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `members`;
CREATE TABLE `members` (
  `members_Id` int(11) NOT NULL AUTO_INCREMENT,
  `members_EMAIL` varchar(30) NOT NULL,
  `members_PASSWORD` varchar(20) NOT NULL,
  `members_NAME` varchar(20) NOT NULL,
  `members_BIRTH` varchar(10) NOT NULL,
  `members_POWER` varchar(5) NOT NULL DEFAULT '正常',
  `members_ADDRESS` tinytext NOT NULL,
  `members_COUNTIES` varchar(4) NOT NULL,
  `members_RUN` int(11) NOT NULL DEFAULT '0',
  `members_JOIN` int(11) NOT NULL DEFAULT '0',
  `members_MAIN` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`members_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `userimg`;
CREATE TABLE `userimg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `gid` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2014-10-07 14:25:42