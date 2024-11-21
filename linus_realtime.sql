-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 18, 2024 at 09:28 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `linus_realtime`
--

-- --------------------------------------------------------

--
-- Table structure for table `bus_route`
--

CREATE TABLE `bus_route` (
  `id` int(11) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `sequence` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bus_route`
--

INSERT INTO `bus_route` (`id`, `latitude`, `longitude`, `sequence`) VALUES
(1, 3.5661253, 98.6600667, 1),
(2, 3.5665446, 98.6600436, 2),
(3, 3.5665478, 98.6601728, 3),
(4, 3.5626565, 98.6603916, 4),
(5, 3.5592630, 98.6604540, 5),
(6, 3.5562021, 98.6605685, 6),
(7, 3.5560161, 98.6605245, 7),
(8, 3.5560191, 98.6604411, 8),
(9, 3.5563223, 98.6604120, 9),
(10, 3.5563180, 98.6577221, 10),
(11, 3.5563505, 98.6561159, 11),
(12, 3.5574176, 98.6561051, 12),
(13, 3.5590408, 98.6561385, 13),
(14, 3.5590090, 98.6555590, 14),
(15, 3.5589980, 98.6528885, 15),
(16, 3.5594377, 98.6528921, 16),
(17, 3.5607190, 98.6528930, 17),
(18, 3.5666520, 98.6531422, 18),
(19, 3.5668220, 98.6531433, 19),
(20, 3.5668030, 98.6532490, 20),
(21, 3.5606290, 98.6530620, 21),
(22, 3.5594515, 98.6530292, 22),
(23, 3.5590288, 98.6530176, 23),
(24, 3.5590666, 98.6555478, 24),
(25, 3.5590690, 98.6561599, 25),
(26, 3.5574464, 98.6561507, 26),
(27, 3.5563676, 98.6561415, 27),
(28, 3.5563558, 98.6576004, 28),
(29, 3.5563454, 98.6603466, 29),
(30, 3.5563465, 98.6604199, 30),
(31, 3.5591868, 98.6602957, 31),
(32, 3.5635686, 98.6601725, 32);

-- --------------------------------------------------------

--
-- Table structure for table `bus_stops`
--

CREATE TABLE `bus_stops` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_stops`
--

INSERT INTO `bus_stops` (`id`, `name`, `latitude`, `longitude`) VALUES
(1, 'Halte Pintu 1', 3.56612530, 98.66006670),
(2, 'Halte Gelanggang', 3.56356860, 98.66017250),
(3, 'Halte Pendopo', 3.56265650, 98.66039160),
(4, 'Halte Hukum 1', 3.55926300, 98.66045400),
(5, 'Halte Pintu Doraemon', 3.55624100, 98.66038400),
(6, 'Halte FISIP 1', 3.55631800, 98.65772210),
(7, 'Halte FEB 1', 3.55741760, 98.65610510),
(8, 'Halte FMIPA 1', 3.55900900, 98.65555900),
(9, 'Jebol 1', 3.55943770, 98.65289210),
(10, 'Halte Farmasi 1', 3.56071900, 98.65289300),
(11, 'Halte Pintu 4', 3.56665200, 98.65314220),
(12, 'Farmasi 2', 3.56062900, 98.65306200),
(13, 'Jebol 2', 3.55945150, 98.65302920),
(14, 'FMIPA 2', 3.55906660, 98.65554780),
(15, 'FEB 2', 3.55744640, 98.65615070),
(16, 'FISIP 2', 3.55635580, 98.65760040),
(17, 'Doraemon 2', 3.55634540, 98.66034660),
(18, 'Hukum 2', 3.55918680, 98.66029570),
(19, 'Halte Pintu 1', 3.56612530, 98.66006670),
(20, 'Halte Gelanggang', 3.56356860, 98.66017250),
(21, 'Halte Pendopo', 3.56265650, 98.66039160),
(22, 'Halte Hukum 1', 3.55926300, 98.66045400),
(23, 'Halte Pintu Doraemon', 3.55624100, 98.66038400),
(24, 'Halte FISIP 1', 3.55631800, 98.65772210),
(25, 'Halte FEB 1', 3.55741760, 98.65610510),
(26, 'Halte FMIPA 1', 3.55900900, 98.65555900),
(27, 'Jebol 1', 3.55943770, 98.65289210),
(28, 'Halte Farmasi 1', 3.56071900, 98.65289300),
(29, 'Halte Pintu 4', 3.56665200, 98.65314220),
(30, 'Farmasi 2', 3.56062900, 98.65306200),
(31, 'Jebol 2', 3.55945150, 98.65302920),
(32, 'FMIPA 2', 3.55906660, 98.65554780),
(33, 'FEB 2', 3.55744640, 98.65615070),
(34, 'FISIP 2', 3.55635580, 98.65760040),
(35, 'Doraemon 2', 3.55634540, 98.66034660),
(36, 'Hukum 2', 3.55918680, 98.66029570);

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `plate_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `plate_number`, `password`, `latitude`, `longitude`) VALUES
(1, 'BK 2012 YT', '$2y$10$Ksfj9Nwt3XdGpkXbemtqK.D/9ilHx6AV2Y6t/714Iyv3MSqmRN6NW', NULL, NULL),
(2, 'BK 2024AH', '$2y$10$LUXIGiB.esSK9RU6eGkEDOnFdaJRuo8fer2U6u6/5EH0nw46eQ7py', NULL, NULL),
(3, 'BK232', '$2y$10$2EPeVXrgS9bBbCph9dhwa.UxfudWg2zWYWZJyLUjZYmuYfLYeA3p6', NULL, NULL),
(4, 'BK1087 YT', '$2y$10$CbjpEjg461N4rr8Otzxg2ucWtU7jHVzRSdqK1OlVvyzWgHtHNfUHO', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bus_route`
--
ALTER TABLE `bus_route`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bus_stops`
--
ALTER TABLE `bus_stops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plate_number` (`plate_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bus_route`
--
ALTER TABLE `bus_route`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `bus_stops`
--
ALTER TABLE `bus_stops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
