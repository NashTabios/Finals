-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2024 at 11:02 AM
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
-- Database: `reps`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_pass` varchar(255) NOT NULL,
  `admin_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_email`, `admin_pass`, `admin_name`) VALUES
(1, 'repsadmin@reps.com', 'reps2023', 'REPS-ADMIN');

-- --------------------------------------------------------

--
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `listing_id` int(11) NOT NULL,
  `listing_name` varchar(255) NOT NULL,
  `listing_price` int(11) NOT NULL,
  `listing_desc` text NOT NULL,
  `listing_image` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`listing_id`, `listing_name`, `listing_price`, `listing_desc`, `listing_image`, `user_name`) VALUES
(2, 'Tender Juicy', 200, 'haha', 'uploads/haha.jpg', 'kmocorro24'),
(4, 'steph curry', 2001, 'im steph curry testing', 'uploads/steph.jpg', 'stephcurry30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `pass` varchar(255) NOT NULL,
  `profile_picture` varchar(255) NOT NULL,
  `user_add` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `user_name`, `first_name`, `last_name`, `dob`, `pass`, `profile_picture`, `user_add`) VALUES
(10, 'kennmarton.mocorro.cics@ust.edu.ph', 'kmocorro24', 'Kenn Marton', 'Mocorro', '2003-06-24', 'kenken', 'uploads/440488877_318802917732416_6602748213895101957_n.jpg', 'Eastwood City, Brgy. Bagumbayan Quezon City'),
(12, 'lebron@gmail.com', 'lbj', 'LeBron Jr.', 'James', '2023-12-31', 'lebronjames', '', ''),
(13, 'steph.curry@gmail.com', 'stephcurry30', 'Stephen', 'Curry', '2016-01-04', 'stephcurry', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `listing`
--
ALTER TABLE `listing`
  ADD PRIMARY KEY (`listing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `listing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
