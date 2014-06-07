-- phpMyAdmin SQL Dump
-- version 2.11.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 15, 2011 at 10:04 AM
-- Server version: 5.1.50
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `adsmysql`
--

-- --------------------------------------------------------

ALTER DATABASE
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci
	DEFAULT COLLATE utf8_general_ci
	;

--
-- Table structure for table `active_guests`
--

DROP TABLE IF EXISTS `active_guests`;
CREATE TABLE IF NOT EXISTS `active_guests` (
  `ip` varchar(15) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci
	DEFAULT COLLATE utf8_general_ci
	;

--
-- Dumping data for table `active_guests`
--


-- --------------------------------------------------------

--
-- Table structure for table `active_users`
--

DROP TABLE IF EXISTS `active_users`;
CREATE TABLE IF NOT EXISTS `active_users` (
  `username` varchar(30) NOT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci
	DEFAULT COLLATE utf8_general_ci
	;

--
-- Dumping data for table `active_users`
--


-- --------------------------------------------------------

--
-- Table structure for table `adsmysql`
--

DROP TABLE IF EXISTS `adsmysql`;
CREATE TABLE IF NOT EXISTS `adsmysql` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL,
  `text` text NOT NULL,
  `flags` text NOT NULL,
  `game` text NOT NULL,
  `gamesrvid` text NOT NULL,
  `name` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci
	DEFAULT COLLATE utf8_general_ci
	;

--
-- Dumping data for table `adsmysql`
--

INSERT INTO `adsmysql` VALUES(7, 'S', 'ditto', 'none', 'All', 'All', 'Administrator', '2009-02-06 14:44:12');
INSERT INTO `adsmysql` VALUES(1, 'S', 'Another test', 'none', 'ageofchivalry', 'All', 'Administrator', '2009-02-06 12:59:04');
INSERT INTO `adsmysql` VALUES(6, 'S', 'check3', 'none', 'All', 'All', 'Administrator', '2009-02-06 12:59:12');
INSERT INTO `adsmysql` VALUES(5, 'S', 'test2', 'none', 'All', 'All', 'Administrator', '2009-02-05 22:19:40');
INSERT INTO `adsmysql` VALUES(10, 'S', '1234567890!@#$%^&*()', 'none', 'All', 'All', 'Administrator', '2009-03-01 23:43:28');
INSERT INTO `adsmysql` VALUES(4, 'H', 'This is a new ad', '', 'zps', 'All', 'Administrator', '2009-02-05 22:19:24');
INSERT INTO `adsmysql` VALUES(8, 'T', '{YELLOW} Next map is {SM_NEXTMAP}  ', 'none', 'All', 'All', 'Administrator', '2009-02-06 14:44:15');
INSERT INTO `adsmysql` VALUES(3, 'S', 'Test', 'none', 'hl2mp', 'All', 'Administrator', '2009-02-05 09:31:37');
INSERT INTO `adsmysql` VALUES(12, 'S', 'test3', 'none', 'All', 'All', 'Administrator', '2009-02-06 12:59:23');
INSERT INTO `adsmysql` VALUES(2, 'S', 'sfdfsdf', 'none', 'hl2mp', 'All', 'Administrator', '2009-02-06 12:59:04');
INSERT INTO `adsmysql` VALUES(11, 'S', 'OK new', 'none', 'pvkii', 'All', 'Administrator', '2009-03-01 23:43:26');
INSERT INTO `adsmysql` VALUES(9, 'S', 'new2', 'none', 'insurgency', 'All', 'Administrator', '2009-03-01 23:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(32) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `userlevel` tinyint(1) unsigned NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `timestamp` int(11) unsigned NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM
	CHARACTER SET utf8
	DEFAULT CHARACTER SET utf8
	COLLATE utf8_general_ci
	DEFAULT COLLATE utf8_general_ci
	;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES('Administrator', '7b7bc2512ee1fedcd76bdc68926d4f7b', 'd8590953f39d50d4f8d4e8cd22c5f454', 9, 'admin@yourdomain.com', 1235969018);
