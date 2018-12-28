-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2018 at 09:25 AM
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

--
-- Dumping data for table `bus_driving_log`
--

INSERT INTO `bus_driving_log` (`id`, `reg_date_time`, `bus_id`, `latitude`, `longitude`, `speed`, `distance`) VALUES
('a27a94b3-f927-11e8-b6bd-5494390a7c15', '2018-12-01 01:00:00', 1, -6.245158, 106.820872, 40, 30),
('a27ab9f4-f927-11e8-b6bd-5494390a7c15', '2018-12-02 02:00:00', 1, -6.245258, 106.820972, 34, 10),
('c192040a-f927-11e8-b6bd-5494390a7c15', '2018-12-03 03:00:00', 1, -6.246158, 106.878787, 32, 39),
('c19219e8-f927-11e8-b6bd-5494390a7c15', '2018-12-04 04:00:00', 1, -6.246258, 106.830972, 36, 28);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
