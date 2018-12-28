-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2018 at 02:55 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `len_fms`
--

-- --------------------------------------------------------

--
-- Table structure for table `ad_screen`
--

DROP TABLE IF EXISTS `ad_screen`;
CREATE TABLE `ad_screen` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `alarm_status`
--

DROP TABLE IF EXISTS `alarm_status`;
CREATE TABLE `alarm_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `alarm_status`
--

INSERT INTO `alarm_status` (`id`, `name`) VALUES
(1, 'Good'),
(2, 'Warning');

-- --------------------------------------------------------

--
-- Table structure for table `authority`
--

DROP TABLE IF EXISTS `authority`;
CREATE TABLE `authority` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `authority`
--

INSERT INTO `authority` (`id`, `name`) VALUES
(2, 'Admin'),
(1, 'Operator');

-- --------------------------------------------------------

--
-- Table structure for table `bus`
--

DROP TABLE IF EXISTS `bus`;
CREATE TABLE `bus` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `car_number` varchar(12) NOT NULL,
  `reg_date` date DEFAULT NULL,
  `made_date` date DEFAULT NULL,
  `model_no` varchar(20) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `max_speed` int(11) DEFAULT NULL,
  `software_version` varchar(10) DEFAULT NULL,
  `bus_device_id` int(11) DEFAULT NULL,
  `service_group_id` int(11) DEFAULT NULL,
  `route_line_id` int(11) DEFAULT NULL,
  `manufacture_company_id` int(11) DEFAULT NULL,
  `transport_company_id` int(11) DEFAULT NULL,
  `service_status_id` int(11) DEFAULT NULL,
  `operation_status_id` int(11) DEFAULT NULL,
  `connection_status_id` int(11) DEFAULT NULL,
  `alarm_status_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `last_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bus`
--

INSERT INTO `bus` (`id`, `code`, `name`, `car_number`, `reg_date`, `made_date`, `model_no`, `icon`, `remark`, `latitude`, `longitude`, `speed`, `max_speed`, `software_version`, `bus_device_id`, `service_group_id`, `route_line_id`, `manufacture_company_id`, `transport_company_id`, `service_status_id`, `operation_status_id`, `connection_status_id`, `alarm_status_id`, `start_time`, `last_time`) VALUES
(1, 'BID-0001', '3006', 'B 1234 BKS', '2018-11-28', '1990-03-08', NULL, 'car_b.gif', NULL, -6.245158, 106.878787, NULL, NULL, '10.01', 1, 1, 2, 1, 1, 1, 3, 2, NULL, NULL, NULL),
(2, 'BID-0002', '3005', 'B 4321 JKT', '2018-11-28', '2000-07-05', NULL, 'car_r.gif', NULL, -6.245162, 106.878335, NULL, NULL, '10.11', 1, 1, 1, 2, 1, 1, 3, 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bus_device`
--

DROP TABLE IF EXISTS `bus_device`;
CREATE TABLE `bus_device` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bus_device`
--

INSERT INTO `bus_device` (`id`, `code`, `name`) VALUES
(1, '10001', 'CINO_S10');

-- --------------------------------------------------------

--
-- Table structure for table `bus_driving_log`
--

DROP TABLE IF EXISTS `bus_driving_log`;
CREATE TABLE `bus_driving_log` (
  `id` varchar(36) NOT NULL,
  `reg_date_time` datetime DEFAULT NULL,
  `bus_id` int(11) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `speed` int(11) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `val` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config_email`
--

DROP TABLE IF EXISTS `config_email`;
CREATE TABLE `config_email` (
  `smtp_host` varchar(255) DEFAULT NULL,
  `smtp_port` varchar(255) DEFAULT NULL,
  `smtp_user` varchar(255) DEFAULT NULL,
  `smtp_account` varchar(255) DEFAULT NULL,
  `smtp_pass` varchar(255) DEFAULT NULL,
  `smtp_timeout` tinyint(4) NOT NULL DEFAULT '10',
  `mailtype` varchar(255) DEFAULT NULL,
  `wordwrap` tinyint(1) NOT NULL DEFAULT '1',
  `charset` varchar(255) DEFAULT 'iso-8859-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `connection_status`
--

DROP TABLE IF EXISTS `connection_status`;
CREATE TABLE `connection_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `connection_status`
--

INSERT INTO `connection_status` (`id`, `name`) VALUES
(2, 'Off'),
(1, 'On');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

DROP TABLE IF EXISTS `driver`;
CREATE TABLE `driver` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `part_name` varchar(255) DEFAULT NULL,
  `telp` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `fax` varchar(16) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `transport_company_id` int(11) DEFAULT NULL,
  `service_group_id` int(11) DEFAULT NULL,
  `service_status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `garage`
--

DROP TABLE IF EXISTS `garage`;
CREATE TABLE `garage` (
  `id` int(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(13, '2', '793ef67f3f524985adbb2df619e5c0e6', 2, 0, 0, '127.0.0.1', 1543206863),
(21, '2', 'a04b6b9dad864d29872f8f22df4a3803', 2, 0, 0, '127.0.0.1', 1543297716);

-- --------------------------------------------------------

--
-- Table structure for table `line`
--

DROP TABLE IF EXISTS `line`;
CREATE TABLE `line` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lati_long` text,
  `color` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `line_type`
--

DROP TABLE IF EXISTS `line_type`;
CREATE TABLE `line_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `line_type`
--

INSERT INTO `line_type` (`id`, `name`) VALUES
(1, 'Back <--> Forth'),
(2, 'Circulation');

-- --------------------------------------------------------

--
-- Table structure for table `manufacture_company`
--

DROP TABLE IF EXISTS `manufacture_company`;
CREATE TABLE `manufacture_company` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `manufacture_company`
--

INSERT INTO `manufacture_company` (`id`, `code`, `name`) VALUES
(1, '10001', 'Mitsubishi'),
(2, '10002', 'Hyundai');

-- --------------------------------------------------------

--
-- Table structure for table `operation_distance`
--

DROP TABLE IF EXISTS `operation_distance`;
CREATE TABLE `operation_distance` (
  `id` varchar(36) NOT NULL,
  `reg_date` date DEFAULT NULL,
  `bus_id` int(11) NOT NULL,
  `distance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `operation_status`
--

DROP TABLE IF EXISTS `operation_status`;
CREATE TABLE `operation_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `operation_status`
--

INSERT INTO `operation_status` (`id`, `name`) VALUES
(1, 'Drive'),
(3, 'Off-Line'),
(2, 'Stop');

-- --------------------------------------------------------

--
-- Table structure for table `route_deviation_log`
--

DROP TABLE IF EXISTS `route_deviation_log`;
CREATE TABLE `route_deviation_log` (
  `id` varchar(36) NOT NULL,
  `reg_date_time` datetime NOT NULL,
  `bus_id` int(11) NOT NULL,
  `distance` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `route_line`
--

DROP TABLE IF EXISTS `route_line`;
CREATE TABLE `route_line` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `line_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `route_line`
--

INSERT INTO `route_line` (`id`, `code`, `name`, `line_type_id`) VALUES
(1, '12-1', 'Senayan - Bekasi', 1),
(2, '15-1', 'Bandara - Bekasi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `route_line_route_map`
--

DROP TABLE IF EXISTS `route_line_route_map`;
CREATE TABLE `route_line_route_map` (
  `id` int(11) NOT NULL,
  `route_line_id` int(11) DEFAULT NULL,
  `route_map_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `route_map`
--

DROP TABLE IF EXISTS `route_map`;
CREATE TABLE `route_map` (
  `id` int(11) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `map_name` varchar(255) DEFAULT NULL,
  `map_address` varchar(255) DEFAULT NULL,
  `map_no` varchar(10) DEFAULT NULL,
  `display_no` varchar(3) DEFAULT NULL,
  `thick_id` int(11) DEFAULT NULL,
  `transparency_id` int(11) DEFAULT NULL,
  `service_group_id` int(11) DEFAULT NULL,
  `service_status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `route_map_line`
--

DROP TABLE IF EXISTS `route_map_line`;
CREATE TABLE `route_map_line` (
  `id` int(11) NOT NULL,
  `route_map_id` int(11) DEFAULT NULL,
  `line_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `service_group`
--

DROP TABLE IF EXISTS `service_group`;
CREATE TABLE `service_group` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_group`
--

INSERT INTO `service_group` (`id`, `code`, `name`) VALUES
(1, '10001', 'PPD_Jakarta');

-- --------------------------------------------------------

--
-- Table structure for table `service_status`
--

DROP TABLE IF EXISTS `service_status`;
CREATE TABLE `service_status` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `service_status`
--

INSERT INTO `service_status` (`id`, `name`) VALUES
(2, 'Disable'),
(1, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `speed_violation_log`
--

DROP TABLE IF EXISTS `speed_violation_log`;
CREATE TABLE `speed_violation_log` (
  `id` varchar(36) NOT NULL,
  `reg_date_time` datetime NOT NULL,
  `bus_id` int(11) NOT NULL,
  `speed` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
CREATE TABLE `station` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `reg_date` date DEFAULT NULL,
  `made_date` date DEFAULT NULL,
  `install_date` date DEFAULT NULL,
  `install_address` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `line_order_no` int(11) DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `route_line_id` int(11) DEFAULT NULL,
  `station_direct_id` int(11) DEFAULT NULL,
  `station_type_id` int(11) DEFAULT NULL,
  `ad_screen_id` int(11) DEFAULT NULL,
  `service_group_id` int(11) DEFAULT NULL,
  `service_status_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station`
--

INSERT INTO `station` (`id`, `code`, `name`, `short_name`, `reg_date`, `made_date`, `install_date`, `install_address`, `remark`, `line_order_no`, `latitude`, `longitude`, `route_line_id`, `station_direct_id`, `station_type_id`, `ad_screen_id`, `service_group_id`, `service_status_id`) VALUES
(1, 'SID-0001', 'Bandara Terminal 1', 'B2', '2018-11-27', '2018-10-29', '2018-11-26', 'SoeHat Airport', 'Cengkareng', 1, -6.125907216962478, 106.65487818700126, 2, 1, 1, NULL, 1, 1),
(2, 'SID-0002', 'Bekasi', 'A1', '2018-11-28', '2018-10-30', '2018-11-13', 'Cikarang Utama', 'Cikarang', 1, -6.253865123743101, 106.99044743845161, 2, 2, 1, NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `station_direct`
--

DROP TABLE IF EXISTS `station_direct`;
CREATE TABLE `station_direct` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_direct`
--

INSERT INTO `station_direct` (`id`, `name`) VALUES
(2, 'DOWN'),
(1, 'UP');

-- --------------------------------------------------------

--
-- Table structure for table `station_pass_log`
--

DROP TABLE IF EXISTS `station_pass_log`;
CREATE TABLE `station_pass_log` (
  `id` varchar(36) NOT NULL,
  `reg_date_time` datetime DEFAULT NULL,
  `station_id` int(11) DEFAULT NULL,
  `bus_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `station_type`
--

DROP TABLE IF EXISTS `station_type`;
CREATE TABLE `station_type` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `station_type`
--

INSERT INTO `station_type` (`id`, `name`) VALUES
(2, 'Major Point'),
(1, 'Real Station');

-- --------------------------------------------------------

--
-- Table structure for table `thick`
--

DROP TABLE IF EXISTS `thick`;
CREATE TABLE `thick` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transparency`
--

DROP TABLE IF EXISTS `transparency`;
CREATE TABLE `transparency` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `transport_company`
--

DROP TABLE IF EXISTS `transport_company`;
CREATE TABLE `transport_company` (
  `id` int(11) NOT NULL,
  `code` varchar(5) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `transport_company`
--

INSERT INTO `transport_company` (`id`, `code`, `name`) VALUES
(1, '10001', 'PPD_Trans'),
(2, '10002', 'Primajasa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `active` char(1) DEFAULT 'Y'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `foto`, `phone`, `active`) VALUES
('1', 'operator', '4b583376b2767b923c3e1da60d10de59', 'Operator', NULL, NULL, NULL, 'Y'),
('2', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', NULL, NULL, NULL, 'Y'),
('72ed78bd-c650-4385-9661-ea60e51b7c52', 'test', '098f6bcd4621d373cade4e832627b4f6', 'tester', NULL, NULL, NULL, 'Y');

-- --------------------------------------------------------

--
-- Table structure for table `users_authority`
--

DROP TABLE IF EXISTS `users_authority`;
CREATE TABLE `users_authority` (
  `users_id` varchar(36) NOT NULL,
  `authority_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_authority`
--

INSERT INTO `users_authority` (`users_id`, `authority_id`) VALUES
('1', 1),
('2', 2),
('72ed78bd-c650-4385-9661-ea60e51b7c52', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ad_screen`
--
ALTER TABLE `ad_screen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `alarm_status`
--
ALTER TABLE `alarm_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `authority`
--
ALTER TABLE `authority`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `bus`
--
ALTER TABLE `bus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `bus_device_id` (`bus_device_id`),
  ADD KEY `service_group_id` (`service_group_id`),
  ADD KEY `route_line_id` (`route_line_id`),
  ADD KEY `manufacture_company_id` (`manufacture_company_id`),
  ADD KEY `transport_company_id` (`transport_company_id`),
  ADD KEY `service_status_id` (`service_status_id`),
  ADD KEY `operation_status_id` (`operation_status_id`),
  ADD KEY `connection_status_id` (`connection_status_id`),
  ADD KEY `alarm_status_id` (`alarm_status_id`);

--
-- Indexes for table `bus_device`
--
ALTER TABLE `bus_device`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `bus_driving_log`
--
ALTER TABLE `bus_driving_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `connection_status`
--
ALTER TABLE `connection_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `transport_company_id` (`transport_company_id`),
  ADD KEY `service_group_id` (`service_group_id`),
  ADD KEY `service_status_id` (`service_status_id`);

--
-- Indexes for table `garage`
--
ALTER TABLE `garage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `place` (`place`);

--
-- Indexes for table `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `line`
--
ALTER TABLE `line`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `line_type`
--
ALTER TABLE `line_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `manufacture_company`
--
ALTER TABLE `manufacture_company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `operation_distance`
--
ALTER TABLE `operation_distance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `operation_status`
--
ALTER TABLE `operation_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `route_deviation_log`
--
ALTER TABLE `route_deviation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `route_line`
--
ALTER TABLE `route_line`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `line_type_id` (`line_type_id`);

--
-- Indexes for table `route_line_route_map`
--
ALTER TABLE `route_line_route_map`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `route_line_id` (`route_line_id`,`route_map_id`),
  ADD KEY `route_map_id` (`route_map_id`);

--
-- Indexes for table `route_map`
--
ALTER TABLE `route_map`
  ADD PRIMARY KEY (`id`),
  ADD KEY `thick_id` (`thick_id`),
  ADD KEY `transparency_id` (`transparency_id`),
  ADD KEY `service_group_id` (`service_group_id`),
  ADD KEY `service_status_id` (`service_status_id`);

--
-- Indexes for table `route_map_line`
--
ALTER TABLE `route_map_line`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `route_map_id` (`route_map_id`,`line_id`),
  ADD KEY `line_id` (`line_id`);

--
-- Indexes for table `service_group`
--
ALTER TABLE `service_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `service_status`
--
ALTER TABLE `service_status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `speed_violation_log`
--
ALTER TABLE `speed_violation_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `route_line_id` (`route_line_id`),
  ADD KEY `station_direct_id` (`station_direct_id`),
  ADD KEY `station_type_id` (`station_type_id`),
  ADD KEY `ad_screen_id` (`ad_screen_id`),
  ADD KEY `service_group_id` (`service_group_id`),
  ADD KEY `service_status_id` (`service_status_id`);

--
-- Indexes for table `station_direct`
--
ALTER TABLE `station_direct`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `station_pass_log`
--
ALTER TABLE `station_pass_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `station_id` (`station_id`),
  ADD KEY `bus_id` (`bus_id`);

--
-- Indexes for table `station_type`
--
ALTER TABLE `station_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `thick`
--
ALTER TABLE `thick`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `transparency`
--
ALTER TABLE `transparency`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `transport_company`
--
ALTER TABLE `transport_company`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `users_authority`
--
ALTER TABLE `users_authority`
  ADD KEY `users_id` (`users_id`),
  ADD KEY `authority_id` (`authority_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ad_screen`
--
ALTER TABLE `ad_screen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `alarm_status`
--
ALTER TABLE `alarm_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `authority`
--
ALTER TABLE `authority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bus`
--
ALTER TABLE `bus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `bus_device`
--
ALTER TABLE `bus_device`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `connection_status`
--
ALTER TABLE `connection_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `garage`
--
ALTER TABLE `garage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `line`
--
ALTER TABLE `line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `line_type`
--
ALTER TABLE `line_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `manufacture_company`
--
ALTER TABLE `manufacture_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `operation_status`
--
ALTER TABLE `operation_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `route_line`
--
ALTER TABLE `route_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `route_line_route_map`
--
ALTER TABLE `route_line_route_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `route_map`
--
ALTER TABLE `route_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `route_map_line`
--
ALTER TABLE `route_map_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `service_group`
--
ALTER TABLE `service_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `service_status`
--
ALTER TABLE `service_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `station`
--
ALTER TABLE `station`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `station_direct`
--
ALTER TABLE `station_direct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `station_type`
--
ALTER TABLE `station_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `thick`
--
ALTER TABLE `thick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transparency`
--
ALTER TABLE `transparency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transport_company`
--
ALTER TABLE `transport_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bus`
--
ALTER TABLE `bus`
  ADD CONSTRAINT `bus_ibfk_1` FOREIGN KEY (`bus_device_id`) REFERENCES `bus_device` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_2` FOREIGN KEY (`service_group_id`) REFERENCES `service_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_3` FOREIGN KEY (`route_line_id`) REFERENCES `route_line` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_4` FOREIGN KEY (`manufacture_company_id`) REFERENCES `manufacture_company` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_5` FOREIGN KEY (`transport_company_id`) REFERENCES `transport_company` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_6` FOREIGN KEY (`service_status_id`) REFERENCES `service_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_7` FOREIGN KEY (`operation_status_id`) REFERENCES `operation_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_8` FOREIGN KEY (`connection_status_id`) REFERENCES `connection_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `bus_ibfk_9` FOREIGN KEY (`alarm_status_id`) REFERENCES `alarm_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `bus_driving_log`
--
ALTER TABLE `bus_driving_log`
  ADD CONSTRAINT `bus_driving_log_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `driver`
--
ALTER TABLE `driver`
  ADD CONSTRAINT `driver_ibfk_1` FOREIGN KEY (`transport_company_id`) REFERENCES `transport_company` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `driver_ibfk_2` FOREIGN KEY (`service_group_id`) REFERENCES `service_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `driver_ibfk_3` FOREIGN KEY (`service_status_id`) REFERENCES `service_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `keys`
--
ALTER TABLE `keys`
  ADD CONSTRAINT `keys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `operation_distance`
--
ALTER TABLE `operation_distance`
  ADD CONSTRAINT `operation_distance_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `route_deviation_log`
--
ALTER TABLE `route_deviation_log`
  ADD CONSTRAINT `route_deviation_log_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `route_line`
--
ALTER TABLE `route_line`
  ADD CONSTRAINT `route_line_ibfk_1` FOREIGN KEY (`line_type_id`) REFERENCES `line_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `route_line_route_map`
--
ALTER TABLE `route_line_route_map`
  ADD CONSTRAINT `route_line_route_map_ibfk_1` FOREIGN KEY (`route_line_id`) REFERENCES `route_line` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_line_route_map_ibfk_2` FOREIGN KEY (`route_map_id`) REFERENCES `route_map` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `route_map`
--
ALTER TABLE `route_map`
  ADD CONSTRAINT `route_map_ibfk_1` FOREIGN KEY (`thick_id`) REFERENCES `thick` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `route_map_ibfk_2` FOREIGN KEY (`transparency_id`) REFERENCES `transparency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `route_map_ibfk_3` FOREIGN KEY (`service_group_id`) REFERENCES `service_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `route_map_ibfk_4` FOREIGN KEY (`service_status_id`) REFERENCES `service_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `route_map_line`
--
ALTER TABLE `route_map_line`
  ADD CONSTRAINT `route_map_line_ibfk_1` FOREIGN KEY (`route_map_id`) REFERENCES `route_map` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `route_map_line_ibfk_2` FOREIGN KEY (`line_id`) REFERENCES `line` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `speed_violation_log`
--
ALTER TABLE `speed_violation_log`
  ADD CONSTRAINT `speed_violation_log_ibfk_1` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `station`
--
ALTER TABLE `station`
  ADD CONSTRAINT `station_ibfk_1` FOREIGN KEY (`route_line_id`) REFERENCES `route_line` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `station_ibfk_2` FOREIGN KEY (`station_direct_id`) REFERENCES `station_direct` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `station_ibfk_3` FOREIGN KEY (`station_type_id`) REFERENCES `station_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `station_ibfk_4` FOREIGN KEY (`ad_screen_id`) REFERENCES `ad_screen` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `station_ibfk_5` FOREIGN KEY (`service_group_id`) REFERENCES `service_group` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `station_ibfk_6` FOREIGN KEY (`service_status_id`) REFERENCES `service_status` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `station_pass_log`
--
ALTER TABLE `station_pass_log`
  ADD CONSTRAINT `station_pass_log_ibfk_1` FOREIGN KEY (`station_id`) REFERENCES `station` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `station_pass_log_ibfk_2` FOREIGN KEY (`bus_id`) REFERENCES `bus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_authority`
--
ALTER TABLE `users_authority`
  ADD CONSTRAINT `users_authority_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_authority_ibfk_2` FOREIGN KEY (`authority_id`) REFERENCES `authority` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
