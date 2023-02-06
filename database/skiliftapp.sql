-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-server
-- Generation Time: Feb 05, 2023 at 05:16 AM
-- Server version: 8.0.19
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ski_lift_app`
--
CREATE DATABASE `ski_lift_app`;

USE `ski_lift_app`;

-- --------------------------------------------------------

--
-- Table structure for table `app_hills`
--

CREATE TABLE `app_hills` (
  `hill_id` int NOT NULL,
  `hill_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `app_hills`
--

INSERT INTO `app_hills` (`hill_id`, `hill_name`) VALUES
(1, 'Cape Smokey'),
(2, 'Ben Eoin'),
(3, 'Martok'),
(4, 'Wentworth'),
(5, 'Sugarloaf');

-- --------------------------------------------------------

--
-- Table structure for table `app_members`
--

CREATE TABLE `app_members` (
  `mem_pass_number` varchar(50) NOT NULL,
  `mem_first_name` varchar(50) NOT NULL,
  `mem_last_name` varchar(50) NOT NULL,
  `mem_status` enum('Cancelled','Active') NOT NULL,
  `mem_payment_status` enum('Paid','Pending','Refunded') NOT NULL,
  `mem_bar_code` varchar(50) NOT NULL,
  `mem_last_updated` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_users`
--

CREATE TABLE `app_users` (
  `user_id` int NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_pwd` varchar(100) NOT NULL,
  `hill_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `app_users`
--

INSERT INTO `app_users` (`user_id`, `user_name`, `user_pwd`, `hill_id`) VALUES
(1, 'registrar', '123456', NULL),
(2, 'wentworth', '123456', 4),
(3, 'sugarloaf', '123456', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mem_visits`
--

CREATE TABLE `mem_visits` (
  `mem_pass_number` varchar(50) NOT NULL,
  `hill_id` int NOT NULL,
  `visited_on` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_hills`
--
ALTER TABLE `app_hills`
  ADD PRIMARY KEY (`hill_id`);

--
-- Indexes for table `app_users`
--
ALTER TABLE `app_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_hills`
--
ALTER TABLE `app_hills`
  MODIFY `hill_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `app_users`
--
ALTER TABLE `app_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
