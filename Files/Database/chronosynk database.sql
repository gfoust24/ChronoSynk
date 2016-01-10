-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2015 at 01:23 AM
-- Server version: 5.6.17-log
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chronosynk`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE IF NOT EXISTS `activity` (
  `activityID` int(11) NOT NULL AUTO_INCREMENT,
  `description` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` text NOT NULL,
  PRIMARY KEY (`activityID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `parentID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` varchar(1000) NOT NULL,
  `reply` int(11) DEFAULT NULL,
  `commentID` int(11) NOT NULL AUTO_INCREMENT,
  `type` text NOT NULL,
  `userID` int(11) DEFAULT NULL,
  PRIMARY KEY (`commentID`),
  KEY `parentID` (`parentID`),
  KEY `reply` (`reply`),
  KEY `parentID_2` (`parentID`),
  KEY `userID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`parentID`, `timestamp`, `text`, `reply`, `commentID`, `type`, `userID`) VALUES
(6, '2015-10-04 22:40:33', 'first test comment', NULL, 1, 'session', 1),
(1, '2015-10-05 00:45:54', 'test comment', NULL, 2, 'user', 1),
(1, '2015-10-05 00:46:15', 'test comment 2', NULL, 3, 'user', 1),
(2, '2015-10-05 00:50:21', 'HELLO ASDF!', NULL, 5, 'user', 1),
(2, '2015-10-05 11:11:52', 'test4', NULL, 11, 'session', 1),
(1, '2015-10-05 11:48:21', 'test comment 3', NULL, 12, 'user', 1),
(6, '2015-10-05 21:15:52', 'Hi! Welcome to my awesome senior project!', NULL, 17, 'user', 1),
(1, '2015-10-05 21:21:47', 'andrew was here', NULL, 18, 'user', 6),
(2, '2015-10-05 22:38:26', 'comment', NULL, 19, 'session', 6),
(7, '2015-10-06 14:49:57', 'welcome new member', NULL, 21, 'user', 7),
(13, '2015-10-16 13:29:15', 'great idea', NULL, 23, 'session', 1),
(47, '2015-10-20 14:23:42', 'time was changed to october 30 to 31', NULL, 24, 'session', 17),
(9, '2015-10-20 14:27:22', 'hi', NULL, 26, 'user', 17),
(17, '2015-10-21 18:06:14', 'Hello chestnut. I like dogs.', NULL, 27, 'user', 6),
(7, '2015-10-22 01:59:29', 'asdf!', NULL, 28, 'user', 2),
(6, '2015-10-26 20:27:51', 'another comment', NULL, 29, 'user', 1),
(20, '2015-11-02 19:12:28', 'hello', NULL, 30, 'user', 1),
(17, '2015-11-04 09:38:33', 'it is pretty obvious', NULL, 31, 'session', 1),
(18, '2015-11-04 19:06:24', 'hi kyle', NULL, 32, 'user', 2),
(18, '2015-11-04 19:09:30', '1', NULL, 33, 'user', 2),
(18, '2015-11-04 19:09:32', '2', NULL, 34, 'user', 2),
(18, '2015-11-04 19:09:33', '3', NULL, 35, 'user', 2),
(18, '2015-11-04 19:09:35', '4', NULL, 36, 'user', 2),
(18, '2015-11-04 19:09:36', '5', NULL, 37, 'user', 2),
(18, '2015-11-04 19:09:37', '6', NULL, 38, 'user', 2),
(18, '2015-11-04 19:09:39', '7', NULL, 39, 'user', 2),
(18, '2015-11-04 19:09:40', '8', NULL, 40, 'user', 2),
(18, '2015-11-04 19:09:41', '9', NULL, 41, 'user', 2),
(18, '2015-11-04 19:09:42', '0', NULL, 42, 'user', 2),
(18, '2015-11-04 19:09:44', '10', NULL, 43, 'user', 2),
(18, '2015-11-04 19:09:46', '11', NULL, 44, 'user', 2),
(18, '2015-11-04 19:09:47', '12', NULL, 45, 'user', 2),
(18, '2015-11-04 19:09:49', '13', NULL, 46, 'user', 2),
(18, '2015-11-04 19:09:50', '14', NULL, 47, 'user', 2),
(18, '2015-11-04 19:09:51', '15', NULL, 48, 'user', 2),
(18, '2015-11-04 19:09:53', '16', NULL, 49, 'user', 2);

-- --------------------------------------------------------

--
-- Table structure for table `friend`
--

CREATE TABLE IF NOT EXISTS `friend` (
  `fromID` int(11) NOT NULL COMMENT 'User that sent the request',
  `toID` int(11) NOT NULL COMMENT 'User that the request was sent to',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time',
  `status` int(11) NOT NULL COMMENT '0 means that request has not been accepted, 1 means accepted, 2 means ignored(?)',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  KEY `fromID` (`fromID`,`toID`),
  KEY `toID` (`toID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `friend`
--

INSERT INTO `friend` (`fromID`, `toID`, `timestamp`, `status`, `id`) VALUES
(1, 6, '2015-10-21 10:28:03', 1, 1),
(6, 2, '2015-10-21 12:14:28', 1, 3),
(17, 1, '2015-10-21 12:15:01', 0, 4),
(6, 17, '2015-10-21 18:18:56', 0, 6),
(16, 1, '2015-10-22 01:06:33', 1, 7),
(1, 2, '2015-11-03 18:28:49', 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

CREATE TABLE IF NOT EXISTS `participant` (
  `userID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userID`,`sessionID`),
  KEY `sessionID` (`sessionID`),
  KEY `userID` (`userID`),
  KEY `userID_2` (`userID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `participant`
--

INSERT INTO `participant` (`userID`, `sessionID`, `timestamp`) VALUES
(1, 1, '2015-10-15 15:53:34'),
(1, 2, '2015-10-05 21:12:11'),
(1, 12, '2015-10-12 10:35:08'),
(1, 13, '2015-10-16 13:29:22'),
(1, 16, '2015-10-23 10:28:43'),
(2, 13, '2015-11-03 20:37:26'),
(6, 2, '2015-10-05 22:21:09'),
(6, 12, '2015-10-12 10:35:22'),
(6, 16, '2015-11-03 13:35:46'),
(7, 12, '2015-10-06 14:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `sessionID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `password` text NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `visibility` int(11) NOT NULL DEFAULT '0',
  `leader` int(11) NOT NULL,
  `cap` int(11) NOT NULL DEFAULT '0',
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`sessionID`),
  KEY `start` (`start`,`end`),
  KEY `visibility` (`visibility`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`sessionID`, `title`, `description`, `password`, `start`, `end`, `visibility`, `leader`, `cap`, `timestamp`, `updated`) VALUES
(1, 'First session', 'updated description <img src="http://www.menucool.com/slider/jsImgSlider/images/image-slider-2.jpg">', '', '2015-10-20 02:23:00', '2015-10-21 00:00:00', 0, 1, 1, '2015-10-13 02:06:41', '2015-10-23 13:12:21'),
(2, 'Second', 'second session2', 'password', '2015-10-14 00:00:00', '2015-10-22 00:00:00', 0, 1, 0, '2015-10-13 02:06:41', '2015-10-15 15:28:17'),
(3, 'session form test', 'testing session form', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1, 0, '2015-10-13 02:06:41', '2015-10-15 15:28:17'),
(4, 'test ses', 'test', '', '2015-10-05 02:33:00', '2015-10-05 05:00:00', 0, 1, 0, '2015-10-13 02:06:41', '2015-10-23 13:12:39'),
(5, 'test', 'test1', '', '2015-10-05 02:35:20', '2015-10-06 02:35:20', 0, 1, 0, '2015-10-13 02:06:41', '2015-10-15 15:28:17'),
(6, 'add session from session page', 'asdfasdf', 'password', '2015-10-04 00:02:00', '2015-10-05 00:16:00', 0, 1, 0, '2015-10-13 02:06:41', '2015-10-23 11:06:25'),
(11, 'andrew', 'test', '', '2015-10-06 20:43:54', '2015-10-08 20:43:54', 0, 6, 0, '2015-10-13 02:06:41', '2015-10-15 15:28:17'),
(12, 'interim5', 'report', '', '2015-10-13 14:48:15', '2015-10-16 14:48:15', 0, 7, 0, '2015-10-13 02:06:41', '2015-10-15 15:28:17'),
(13, 'Battlefield group', 'for people that like battlefied\r\n\r\nLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTIONLONG DESCRIPTION', '', '2016-03-10 22:00:00', '2016-03-24 23:00:00', 1, 1, 10, '2015-10-15 17:51:07', '2015-11-03 18:14:02'),
(14, 'asdf1', 'asdf', '', '2015-10-11 01:00:00', '2015-10-11 01:00:00', 0, 1, 0, '2015-10-15 18:03:41', '2015-10-15 18:03:41'),
(15, 'FOOBALL', 'FOR PEOPLE REALLY INTO FOOBALL', '', '2015-12-06 15:14:00', '2015-12-06 22:18:00', 0, 1, 0, '2015-10-15 18:04:43', '2015-10-15 18:04:43'),
(16, 'CARS<3', 'Interest group for people that like cars and gasoline!', '', '2015-12-22 04:16:00', '2015-12-22 17:18:00', 0, 6, 5, '2015-10-15 18:44:41', '2015-11-03 13:35:40'),
(17, 'Christmas Party', 'pretty obvious', '', '2015-12-25 18:00:00', '2015-12-25 00:00:00', 0, 6, 0, '2015-10-15 18:45:47', '2015-10-15 18:45:47'),
(18, '2015-10-13 13:11:12', 'testing what happens if empty string is given for cap', '', '2015-10-13 13:11:12', '2015-10-13 23:11:12', 0, 1, 0, '2015-10-15 23:33:12', '2015-10-15 23:33:12'),
(45, 'asdf123', 'fdsa', '', '2015-10-27 01:00:00', '2015-10-27 01:00:00', 0, 6, 0, '2015-10-16 00:28:19', '2015-10-16 00:28:19'),
(46, 'asdf15', 'sdflkjsdflk', '', '2015-10-11 08:00:00', '2015-10-13 11:00:00', 0, 1, 0, '2015-10-16 13:30:41', '2015-10-16 13:30:41'),
(47, 'catplay', 'come bring your cats to play together', '', '2015-10-29 21:00:00', '2015-10-31 12:00:00', 1, 17, 10, '2015-10-20 14:23:01', '2015-10-20 14:23:01'),
(48, 'my first session', 'register1', '', '2015-12-15 01:00:00', '2015-12-23 01:00:00', 0, 16, 0, '2015-10-22 20:37:17', '2015-10-22 20:37:17'),
(49, 'last id', '', '', '2015-10-01 01:00:00', '2015-10-02 01:00:00', 1, 1, 0, '2015-10-31 15:52:03', '2015-10-31 15:52:03'),
(50, 'resource monitor', 'asd;flkjasdf', '', '2015-11-26 01:00:00', '2015-11-19 01:00:00', 0, 1, 0, '2015-11-04 09:39:07', '2015-11-04 09:39:07'),
(51, 'network stuff', 'idk what im doing', '', '2015-11-24 01:09:00', '2015-11-26 01:00:00', 0, 1, 0, '2015-11-04 17:57:10', '2015-11-04 17:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `activityID` int(11) NOT NULL,
  `sessionID` int(11) NOT NULL,
  PRIMARY KEY (`activityID`,`sessionID`),
  KEY `sessionID` (`sessionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bio` varchar(1000) NOT NULL DEFAULT 'This is my bio!',
  `privacy` int(11) NOT NULL DEFAULT '0',
  `recovery` text,
  `permission` int(11) NOT NULL DEFAULT '0' COMMENT '0 = default, 10 = mod, 20 = admin',
  `emailVerify` text,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `email`, `password`, `timestamp`, `bio`, `privacy`, `recovery`, `permission`, `emailVerify`) VALUES
(1, 'gaf3', 'gaf3@pct.edu', '˜ÇW%ƒT…S	î1\Z‘S9a7c0bbb91878bb60508848bf0baea3131bef518556dd7be8364e60dd48613a2', '2015-09-30 14:22:02', 'This is my bio4\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat quam ipsum, at . Curabitur libero turpis, vehicula a sollicitudin imperdiet, auctor at risus. Fusce iaculis tempus imperdiet. Sed porttitor eros a justo bibendum, id blandit ligula finibus. Proin varius id velit id viverra. Quisque vel diam quis magna finibus dapibus sed sit amet urna. Mauris consequat hendrerit purus.\r\n\r\nIn condimentum pulvinar ex at semper. Praesent consectetur justo nec tincidunt hendrerit. Sed tincidunt est varius nunc tincidunt, sed rhoncus est auctor. Aenean id volutpat massa, eget auctor nibh. Aenean tempus velit suscipit, varius leo ut, congue dui. Praesent tincidunt leo diam, ut sollicitudin elit sagittis sed. Proin luctus semper odio ac dictum massa nunc.', 0, '6b6629bb3985c3a236d0fc345795a4b8', 20, NULL),
(2, 'asdf', 'h607755@trbvm.com', 'W7E,<ÅP´Ë1-ØéÕäa5cd6e7e355ceb0b348c56d268d9f9a766a2e06055197dab3f2a5e6b0f25d8e4', '2015-10-01 18:35:24', 'ASDF bio Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat quam ipsum, at ultricies tortor tempor a. In sem diam, consequat pulvinar augue sit amet, vulputate consectetur nisl. Nam eleifend consectetur enim et vehicula. Curabitur libero turpis, vehicula a sollicitudin imperdiet, auctor at risus. Fusce iaculis tempus imperdiet. Sed porttitor eros a justo bibendum, id blandit ligula finibus. Proin varius id velit id viverra. Quisque vel diam quis magna finibus dapibus sed sit amet urna. Mauris consequat hendrerit purus.\n\nIn condimentum pulvinar ex at semper. Praesent consectetur justo nec tincidunt hendrerit. Sed tincidunt est varius nunc tincidunt, sed rhoncus est auctor. Aenean id volutpat massa, eget auctor nibh. Aenean tempus velit suscipit, varius leo ut, congue dui. Praesent tincidunt leo diam, ut sollicitudin elit sagittis sed. Proin luctus semper odio ac dictum massa nunc.', 0, '2fdecf46f8be73dbd9f776722d6ac7fb', 0, NULL),
(3, 'test1', 'test1@gmail.com', ',	™a[ð±q‚¡mqV!4ed7c38adb97eb0548c91d76d31345582a06a0ba30cc33551e540f98e1baf84e', '2015-10-03 06:39:06', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat quam ipsum, at ultricies tortor tempor a. In sem diam, consequat pulvinar augue sit amet, vulputate consectetur nisl. Nam eleifend consectetur enim et vehicula. Curabitur libero turpis, vehicula a sollicitudin imperdiet, auctor at risus. Fusce iaculis tempus imperdiet. Sed porttitor eros a justo bibendum, id blandit ligula finibus. Proin varius id velit id viverra. Quisque vel diam quis magna finibus dapibus sed sit amet urna. Mauris consequat hendrerit purus.\n\nIn condimentum pulvinar ex at semper. Praesent consectetur justo nec tincidunt hendrerit. Sed tincidunt est varius nunc tincidunt, sed rhoncus est auctor. Aenean id volutpat massa, eget auctor nibh. Aenean tempus velit suscipit, varius leo ut, congue dui. Praesent tincidunt leo diam, ut sollicitudin elit sagittis sed. Proin luctus semper odio ac dictum massa nunc.', 0, 'e56b28b816e432e752403b5dae228834', 0, NULL),
(6, 'amw20', 'amw20@pct.edu', 'h‚žgŒuÀyÙ†„¸w·41a35fa9f4ac2c131d6c254fba6922125e1e626f8ba9960672524848251da420', '2015-10-06 01:15:15', 'I like cars and EDM and Steph', 0, NULL, 0, NULL),
(7, 'report5', 'report5', 'É_ÊŠv‚v˜$a†êHƒ65ebf2744207a965105b673cbd7bf2f309227bd6db7c266f14abde5ba51c03c2', '2015-10-06 18:48:12', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat quam ipsum, at ultricies tortor tempor a. In sem diam, consequat pulvinar augue sit amet, vulputate consectetur nisl. Nam eleifend consectetur enim et vehicula. Curabitur libero turpis, vehicula a sollicitudin imperdiet, auctor at risus. Fusce iaculis tempus imperdiet. Sed porttitor eros a justo bibendum, id blandit ligula finibus. Proin varius id velit id viverra. Quisque vel diam quis magna finibus dapibus sed sit amet urna. Mauris consequat hendrerit purus.\n\nIn condimentum pulvinar ex at semper. Praesent consectetur justo nec tincidunt hendrerit. Sed tincidunt est varius nunc tincidunt, sed rhoncus est auctor. Aenean id volutpat massa, eget auctor nibh. Aenean tempus velit suscipit, varius leo ut, congue dui. Praesent tincidunt leo diam, ut sollicitudin elit sagittis sed. Proin luctus semper odio ac dictum massa nunc.', 0, NULL, 0, NULL),
(9, 'abc123', 'abc123@gmail.com', 'ˆÕ¶—J†W„ èæZäd5cf5c849443df0962444326c0ddad8d64491008b54f48f86c685850996bfdef', '2015-10-16 22:59:02', 'This is my bio!', 0, NULL, 0, NULL),
(13, 'abc1234', 'abc1234@gmail.com', '¤KÙ&¦*g¡lØ‰ØÞ¥1d3b6c255f277c993a3219cff6c9aea7435b472d82e99f4b86d8950be62b1a79', '2015-10-16 23:09:02', 'This is my bio!', 0, NULL, 0, NULL),
(15, 'abc12345', 'abc12345@gmail.com', 'Ûð„¹»ÎB•Ä[žœ91e537fe1b8dc40f99f38ec4ab8989c64ecd1fb14d2960e51890a1315e22da65', '2015-10-16 23:23:36', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam volutpat quam ipsum, at ultricies tortor tempor a. In sem diam, consequat pulvinar augue sit amet, vulputate consectetur nisl. Nam eleifend consectetur enim et vehicula. Curabitur libero turpis, vehicula a sollicitudin imperdiet, auctor at risus. Fusce iaculis tempus imperdiet. Sed porttitor eros a justo bibendum, id blandit ligula finibus. Proin varius id velit id viverra. Quisque vel diam quis magna finibus dapibus sed sit amet urna. Mauris consequat hendrerit purus.\n\nIn condimentum pulvinar ex at semper. Praesent consectetur justo nec tincidunt hendrerit. Sed tincidunt est varius nunc tincidunt, sed rhoncus est auctor. Aenean id volutpat massa, eget auctor nibh. Aenean tempus velit suscipit, varius leo ut, congue dui. Praesent tincidunt leo diam, ut sollicitudin elit sagittis sed. Proin luctus semper odio ac dictum massa nunc.', 0, NULL, 0, NULL),
(16, 'register1', 'register1@gmail.com', '‰Ü¨]Ùÿµ<Ãþºô¶ðæb7d727ed7e83ce7e52db4d2c69d9cb3649c5f54b183fdd6e827b94efb03e2533', '2015-10-20 17:14:34', 'This is my bio!', 0, NULL, 0, NULL),
(17, 'chestnut', 'abc@gmail.com', 'Ùº˜sð''6Ãù§Å#ae5b6bcfc25a1cf5d46874eaba7cdc517e80925f2e28a057edd026c23de2473ce', '2015-10-20 18:17:52', 'I like cats', 0, NULL, 0, NULL),
(18, 'kylerosales', 'nerdykid@imaloser.com', '94È*œGã¶ïT^1Tefbd5764b5a1edfd6901e411bf7a54f7a7a14ca335683fc3c849cf44e275d472', '2015-10-27 19:14:02', 'This is my bio!', 0, NULL, 0, NULL),
(19, 'h1937868', 'h1937868@trbvm.com', 'å¯Ð¸F.MÏS³E¸ZS²f39b88b9b724e4b0a54d0e0248e81f1015622afdfe3af9e8622a920a17129599', '2015-10-30 04:52:59', 'This is my bio!', 0, NULL, 0, '¬MrwtèÌ,ë­j'),
(20, 'h1937921', 'h1937921@trbvm.com', 'MR/ ÿ5:ˆIû—}›0b6492ee51565e735c4814baa11157c86f79625a11e12ae56b5ef33bde62da6e', '2015-10-30 04:55:45', 'This is my bio!', 0, NULL, 0, 'e6cfe7dde1f6c9ae7874f003f255581d'),
(21, 'CrAzYcAsE', 'crazy@yolo.com', '¿{•»æ‹Ûó´ºþ…o73d5a897d9a81b4803be5e0f949225d65b293911010e6a84a2c2aa88b75272f0', '2015-11-03 01:31:50', 'This is my bio!', 0, NULL, 0, '9e12f4ae66d0bb022965c17be36f5b59');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`reply`) REFERENCES `comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `friend`
--
ALTER TABLE `friend`
  ADD CONSTRAINT `friend_ibfk_1` FOREIGN KEY (`fromID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friend_ibfk_2` FOREIGN KEY (`toID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `participant_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `participant_ibfk_2` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `tag_ibfk_1` FOREIGN KEY (`activityID`) REFERENCES `activity` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tag_ibfk_2` FOREIGN KEY (`sessionID`) REFERENCES `session` (`sessionID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
