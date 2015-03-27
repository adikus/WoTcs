-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 23, 2014 at 07:33 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wotstats`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_server`
--

CREATE TABLE IF NOT EXISTS `api_server` (
  `key` varchar(6) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clans`
--

CREATE TABLE IF NOT EXISTS `clans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(10) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `motto` text NOT NULL,
  `wid` varchar(11) NOT NULL,
  `region` int(11) NOT NULL DEFAULT '0',
  `EFR` float NOT NULL,
  `WN7` float NOT NULL,
  `WN8` float NOT NULL,
  `WR` float NOT NULL,
  `SC3` float NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clan_uniqueness` (`tag`,`wid`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=72 ;

-- --------------------------------------------------------

--
-- Table structure for table `clan_stats`
--

CREATE TABLE IF NOT EXISTS `clan_stats` (
  `key` varchar(5) NOT NULL,
  `average` float NOT NULL,
  `stdev` float NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `clan_stats_data`
--

CREATE TABLE IF NOT EXISTS `clan_stats_data` (
  `key` varchar(5) NOT NULL,
  `value` float NOT NULL,
  `count` int(11) NOT NULL,
  `percentile` float NOT NULL,
  UNIQUE KEY `key_value` (`key`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player_stats`
--

CREATE TABLE IF NOT EXISTS `player_stats` (
  `key` varchar(3) NOT NULL,
  `average` float NOT NULL,
  `stdev` float NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `player_stats_data`
--

CREATE TABLE IF NOT EXISTS `player_stats_data` (
  `key` varchar(3) NOT NULL,
  `value` float NOT NULL,
  `count` int(11) NOT NULL,
  `percentile` float NOT NULL,
  UNIQUE KEY `key_value` (`key`,`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `veh_stats`
--

CREATE TABLE IF NOT EXISTS `veh_stats` (
  `key` text NOT NULL,
  `average` float NOT NULL,
  `stdev` float NOT NULL,
  PRIMARY KEY (`key`(40))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `veh_stats_data`
--

CREATE TABLE IF NOT EXISTS `veh_stats_data` (
  `key` text NOT NULL,
  `value` float NOT NULL,
  `count` int(11) NOT NULL,
  `percentile` float NOT NULL,
  PRIMARY KEY (`key`(40),`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
