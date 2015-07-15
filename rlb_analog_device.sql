-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 15, 2015 at 11:27 AM
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
-- Table structure for table `rlb_analog_device`
--

CREATE TABLE IF NOT EXISTS `rlb_analog_device` (
  `analog_id` int(10) NOT NULL AUTO_INCREMENT,
  `analog_input` int(10) NOT NULL,
  `analog_name` varchar(150) NOT NULL,
  `analog_device` varchar(100) NOT NULL,
  `analog_device_type` varchar(100) NOT NULL,
  `analog_device_modified_date` datetime NOT NULL,
  PRIMARY KEY (`analog_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
