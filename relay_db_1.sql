-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2015 at 01:28 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `relay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `rlb_admin_users`
--

CREATE TABLE IF NOT EXISTS `rlb_admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `user_type` enum('SA','A') DEFAULT 'SA' COMMENT 'SA: Super Admin,A: Admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rlb_admin_users`
--

INSERT INTO `rlb_admin_users` (`id`, `username`, `email`, `password`, `block`, `user_type`) VALUES
(1, 'admin', 'dhiraj.netwin@yahoo.com', '0192023a7bbd73250516f069df18b500', 0, 'SA');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_analog_device`
--

CREATE TABLE IF NOT EXISTS `rlb_analog_device` (
  `analog_id` int(10) NOT NULL AUTO_INCREMENT,
  `analog_input` int(10) NOT NULL,
  `analog_name` varchar(150) NOT NULL,
  `analog_device` varchar(100) NOT NULL,
  `analog_device_type` varchar(100) NOT NULL,
  `device_direction` int(5) NOT NULL,
  `analog_device_modified_date` datetime NOT NULL,
  PRIMARY KEY (`analog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rlb_analog_device`
--

INSERT INTO `rlb_analog_device` (`analog_id`, `analog_input`, `analog_name`, `analog_device`, `analog_device_type`, `device_direction`, `analog_device_modified_date`) VALUES
(1, 0, 'AP0', '0', 'R', 0, '2015-07-17 14:19:51'),
(2, 1, 'AP1', '2', 'V', 1, '2015-07-17 14:19:51'),
(3, 2, 'AP2', '2', 'P', 0, '2015-07-17 14:19:51'),
(4, 3, 'AP3', '0', 'V', 2, '2015-07-17 14:19:51');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_device`
--

CREATE TABLE IF NOT EXISTS `rlb_device` (
  `device_id` int(10) NOT NULL AUTO_INCREMENT,
  `device_number` int(10) NOT NULL,
  `device_name` varchar(150) NOT NULL,
  `device_type` varchar(100) NOT NULL,
  `last_updated_date` datetime NOT NULL,
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rlb_device`
--

INSERT INTO `rlb_device` (`device_id`, `device_number`, `device_name`, `device_type`, `last_updated_date`) VALUES
(1, 0, 'PowerCenter1', 'P', '2015-07-09 09:04:10'),
(2, 0, 'RelayName0', 'R', '2015-07-17 08:07:09'),
(3, 1, 'PowerCenter2', 'P', '2015-07-09 09:04:19'),
(4, 1, 'Test Relay 2', 'R', '2015-07-09 09:03:34'),
(5, 3, 'Valve Name Save', 'V', '2015-07-09 11:30:11'),
(6, 0, 'PumpName1', 'PS', '2015-07-17 08:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_modes`
--

CREATE TABLE IF NOT EXISTS `rlb_modes` (
  `mode_id` int(11) NOT NULL AUTO_INCREMENT,
  `mode_name` varchar(255) NOT NULL,
  `mode_status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mode_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `rlb_modes`
--

INSERT INTO `rlb_modes` (`mode_id`, `mode_name`, `mode_status`) VALUES
(1, 'Auto', 0),
(2, 'Manual', 1),
(3, 'Time-Out', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rlb_powercenters`
--

CREATE TABLE IF NOT EXISTS `rlb_powercenters` (
  `powercenter_id` int(11) NOT NULL AUTO_INCREMENT,
  `powercenter_number` int(11) NOT NULL,
  `powercenter_name` varchar(100) NOT NULL,
  PRIMARY KEY (`powercenter_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rlb_powercenters`
--

INSERT INTO `rlb_powercenters` (`powercenter_id`, `powercenter_number`, `powercenter_name`) VALUES
(1, 0, 'Test powercenter0'),
(2, 4, 'pc4 edit'),
(3, 2, 'Testing'),
(4, 1, 'PowerCenter1');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_pump_device`
--

CREATE TABLE IF NOT EXISTS `rlb_pump_device` (
  `pump_id` int(10) NOT NULL AUTO_INCREMENT,
  `pump_number` int(5) NOT NULL,
  `pump_type` varchar(150) NOT NULL COMMENT '''1'' = VS Pump, ''2'' = VF Pump',
  `pump_speed` varchar(150) NOT NULL,
  `pump_flow` varchar(250) NOT NULL,
  `pump_closure` varchar(150) NOT NULL,
  `pump_modified_date` datetime NOT NULL,
  PRIMARY KEY (`pump_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `rlb_pump_device`
--

INSERT INTO `rlb_pump_device` (`pump_id`, `pump_number`, `pump_type`, `pump_speed`, `pump_flow`, `pump_closure`, `pump_modified_date`) VALUES
(1, 0, '2', '2', '', '2', '2015-07-17 11:02:30'),
(2, 1, '3', '', '200', '0', '2015-07-17 11:02:41');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_relays`
--

CREATE TABLE IF NOT EXISTS `rlb_relays` (
  `relay_id` int(11) NOT NULL AUTO_INCREMENT,
  `relay_number` int(11) NOT NULL,
  `relay_name` varchar(100) NOT NULL,
  PRIMARY KEY (`relay_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `rlb_relays`
--

INSERT INTO `rlb_relays` (`relay_id`, `relay_number`, `relay_name`) VALUES
(1, 0, 'Test Realy 1'),
(2, 2, 'Test for relay 2'),
(3, 3, 'Test for relay3 editedaf'),
(4, 5, 'rl5'),
(5, 10, 'relay 10'),
(6, 1, 'Test Relay 11111');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_relay_prog`
--

CREATE TABLE IF NOT EXISTS `rlb_relay_prog` (
  `relay_prog_id` int(11) NOT NULL AUTO_INCREMENT,
  `relay_prog_name` varchar(255) NOT NULL,
  `relay_number` varchar(8) NOT NULL,
  `relay_prog_type` int(2) NOT NULL COMMENT '1-Daily, 2-Weekly',
  `relay_prog_days` varchar(255) NOT NULL COMMENT '0-All, 1-Mon, 2-Tue...7-Sun',
  `relay_start_time` varchar(255) NOT NULL,
  `relay_end_time` varchar(255) NOT NULL,
  `relay_prog_created_date` datetime NOT NULL,
  `relay_prog_modified_date` datetime NOT NULL,
  `relay_prog_delete` int(1) NOT NULL DEFAULT '0',
  `relay_prog_active` int(1) NOT NULL DEFAULT '0',
  `relay_prog_absolute` enum('0','1') NOT NULL DEFAULT '0',
  `relay_prog_absolute_start_time` varchar(100) DEFAULT NULL,
  `relay_prog_absolute_end_time` varchar(100) DEFAULT NULL,
  `relay_prog_absolute_total_time` varchar(100) DEFAULT NULL,
  `relay_prog_absolute_run_time` varchar(100) DEFAULT NULL,
  `relay_prog_absolute_start_date` date DEFAULT NULL,
  `relay_prog_absolute_run` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`relay_prog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `rlb_relay_prog`
--

INSERT INTO `rlb_relay_prog` (`relay_prog_id`, `relay_prog_name`, `relay_number`, `relay_prog_type`, `relay_prog_days`, `relay_start_time`, `relay_end_time`, `relay_prog_created_date`, `relay_prog_modified_date`, `relay_prog_delete`, `relay_prog_active`, `relay_prog_absolute`, `relay_prog_absolute_start_time`, `relay_prog_absolute_end_time`, `relay_prog_absolute_total_time`, `relay_prog_absolute_run_time`, `relay_prog_absolute_start_date`, `relay_prog_absolute_run`) VALUES
(1, 'test', '0', 1, '0', '23:00:00', '23:30:00', '2015-04-03 15:40:46', '2015-07-10 12:37:12', 0, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(2, 'newtest1', '0', 2, '2,3,6', '14:00:00', '20:00:00', '2015-04-07 00:00:00', '2015-04-07 00:00:00', 0, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(3, 'testrelay0', '0', 2, '1,4,5', '22:00:00', '23:30:00', '2015-04-07 00:00:00', '2015-04-07 00:00:00', 0, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(4, 'testrelay1', '1', 1, '0', '10:00:00', '13:00:00', '2015-04-08 00:00:00', '2015-04-08 00:00:00', 0, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(5, 'Weeklytest', '0', 2, '2,6', '01:00:00', '01:30:00', '2015-04-20 00:00:00', '2015-04-20 00:00:00', 1, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(6, '</>', '0', 1, '0', '</>', '</>', '2015-04-24 00:00:00', '2015-04-24 00:00:00', 1, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(7, 'dhiraj Test', '0', 1, '0', '02:00:00', '02:30:00', '2015-07-06 00:00:00', '2015-07-13 12:50:40', 0, 0, '1', NULL, NULL, '00:30:00', '', NULL, '0'),
(8, 'Test', '0', 2, '2,4,6', '00:00:00', '01:00:00', '2015-07-09 07:31:37', '0000-00-00 00:00:00', 1, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(9, 'Test Relay 1', '1', 2, '2,3,4', '00:30:00', '01:00:00', '2015-07-09 08:38:46', '2015-07-09 08:40:21', 1, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(10, 'Program2', '1', 2, '2,3', '02:00:00', '04:00:00', '2015-07-09 09:05:11', '2015-07-09 09:05:23', 0, 0, '0', NULL, NULL, NULL, NULL, NULL, '0'),
(11, 'Test Relay Number', '0', 2, '3', '01:00:00', '02:00:00', '2015-07-09 11:41:53', '2015-07-10 14:56:21', 0, 0, '1', NULL, NULL, '01:00:00', NULL, NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_setting`
--

CREATE TABLE IF NOT EXISTS `rlb_setting` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(100) DEFAULT NULL,
  `port_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rlb_setting`
--

INSERT INTO `rlb_setting` (`id`, `ip_address`, `port_no`) VALUES
(1, '68.229.35.153', '13330');

-- --------------------------------------------------------

--
-- Table structure for table `rlb_valves`
--

CREATE TABLE IF NOT EXISTS `rlb_valves` (
  `valve_id` int(11) NOT NULL AUTO_INCREMENT,
  `valve_number` int(11) NOT NULL,
  `valve_name` varchar(100) NOT NULL,
  PRIMARY KEY (`valve_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
