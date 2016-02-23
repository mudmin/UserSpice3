-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2016 at 07:10 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `us330`
--

-- --------------------------------------------------------

--
-- Table structure for table `uc_audit`
--

CREATE TABLE IF NOT EXISTS `uc_audit` (
  `audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `audit_userid` int(11) NOT NULL,
  `audit_userip` varchar(16) NOT NULL,
  `audit_othus` int(11) NOT NULL,
  `audit_eventcode` int(11) NOT NULL,
  `audit_action` varchar(128) NOT NULL,
  `audit_itemid` int(11) NOT NULL,
  `audit_timestamp` int(11) NOT NULL,
  PRIMARY KEY (`audit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `uc_audit`
--

INSERT INTO `uc_audit` (`audit_id`, `audit_userid`, `audit_userip`, `audit_othus`, `audit_eventcode`, `audit_action`, `audit_itemid`, `audit_timestamp`) VALUES
(1, 1, '::1', 1, 5, 'Registered', 0, 1455996327),
(2, 1, '::1', 1, 1, 'Logged In', 0, 1455996343),
(3, 1, '::1', 1, 2, 'Logged Out', 0, 1455996562);

-- --------------------------------------------------------

--
-- Table structure for table `uc_configuration`
--

CREATE TABLE IF NOT EXISTS `uc_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `uc_configuration`
--

INSERT INTO `uc_configuration` (`id`, `name`, `value`) VALUES
(1, 'website_name', 'UserSpice'),
(2, 'website_url', 'localhost/'),
(3, 'email', 'noreply@UserSpice.com'),
(4, 'activation', 'false'),
(5, 'resend_activation_threshold', '0'),
(6, 'language', 'models/languages/en.php'),
(7, 'template', 'css/bootstrap.min.css');

-- --------------------------------------------------------

--
-- Table structure for table `uc_pages`
--

CREATE TABLE IF NOT EXISTS `uc_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `uc_pages`
--

INSERT INTO `uc_pages` (`id`, `page`, `private`) VALUES
(18, 'account.php', 1),
(19, 'activate-account.php', 0),
(20, 'admin_configuration.php', 1),
(21, 'admin_dashboard.php', 1),
(22, 'admin_page.php', 1),
(23, 'admin_pages.php', 1),
(24, 'admin_permission.php', 1),
(25, 'admin_permissions.php', 1),
(26, 'admin_user.php', 1),
(27, 'admin_users.php', 1),
(28, 'forgot-password.php', 0),
(29, 'index.php', 0),
(30, 'json_chart.php', 1),
(31, 'login.php', 0),
(32, 'logout.php', 0),
(33, 'public.php', 0),
(34, 'register.php', 0),
(35, 'resend-activation.php', 0),
(36, 'user_settings.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `uc_permissions`
--

CREATE TABLE IF NOT EXISTS `uc_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `uc_permissions`
--

INSERT INTO `uc_permissions` (`id`, `name`) VALUES
(1, 'New Member'),
(2, 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `uc_permission_page_matches`
--

CREATE TABLE IF NOT EXISTS `uc_permission_page_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `uc_permission_page_matches`
--

INSERT INTO `uc_permission_page_matches` (`id`, `permission_id`, `page_id`) VALUES
(1, 1, 18),
(2, 2, 18),
(3, 2, 20),
(4, 2, 21),
(5, 2, 22),
(6, 2, 23),
(7, 2, 24),
(8, 2, 25),
(9, 2, 26),
(10, 2, 27),
(11, 1, 36),
(12, 2, 36),
(13, 2, 30);

-- --------------------------------------------------------

--
-- Table structure for table `uc_users`
--

CREATE TABLE IF NOT EXISTS `uc_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` int(11) NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `title` varchar(150) NOT NULL,
  `sign_up_stamp` int(11) NOT NULL,
  `last_sign_in_stamp` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `uc_users`
--

INSERT INTO `uc_users` (`id`, `user_name`, `display_name`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `active`, `title`, `sign_up_stamp`, `last_sign_in_stamp`) VALUES
(1, 'admin', 'admin', '$2y$12$cfuTM6E2DOta50V4eukA3uis46p/6FbdA1VVUTQcwlYk5JFVhAKby', 'admin@userspice.com', 'ea11920e2106ae515d96910fe5a348ca', 1455996327, 0, 1, 'Site Administrator', 1455996327, 1455996343);

-- --------------------------------------------------------

--
-- Table structure for table `uc_user_permission_matches`
--

CREATE TABLE IF NOT EXISTS `uc_user_permission_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `uc_user_permission_matches`
--

INSERT INTO `uc_user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
(1, 1, 2),
(2, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
