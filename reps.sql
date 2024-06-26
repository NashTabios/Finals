-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2024 at 04:17 PM
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
(1, 'repsadmin@reps.com', 'reps2023', 'REPS-ADMIN'),
(2, 'kenn@reps.com', 'kenken', 'KENN - ADMIN');

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
  `status` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`listing_id`, `listing_name`, `listing_price`, `listing_desc`, `listing_image`, `status`, `user_name`) VALUES
(2, 'Tender Juicy', 200, 'Hotdog', 'uploads/66475ccc0fa13.jpg', 'sold', 'kmocorro24'),
(5, 'Toyota Fortuner', 1000000, '2013 Toyota Fortuner G Variant\r\nUsed but not abused\r\n\r\n- Automatic Transmission\r\n- 20k Odometer\r\n- Ridemax Suspension\r\n- DriftXaust Muffler\r\n- Pioneer Stereo with Apple Carplay & Android Auto\r\n- JB Subwoofer\r\n- Focal Speakers & Twitters\r\n- Rockford Fosgate Amplifiers', 'uploads/438246491_411587435090579_496364047626530027_n.jpg', '', 'kmocorro24'),
(6, 'LeBron James #23 Lakers Jersey', 3500, '- Brand New\r\n- Used but not abused', 'uploads/10195196_fpx.jpg', '', 'ninjawarrior2024'),
(7, 'Yamaha Aerox S 2022 Model', 130000, 'Yamaha Aerox V2 2022 Model\r\n- Keyless\r\n- 4500k+ odometer\r\n- Hindi pa nabangga', 'uploads/Screenshot 2024-05-13 210552.png', '', 'kmocorro24'),
(8, 'Diwata', 200, 'Hello', 'uploads/OIP.jpg', 'available', 'kmocorro24');

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
  `user_add` text NOT NULL,
  `contact_num` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email_address`, `user_name`, `first_name`, `last_name`, `dob`, `pass`, `profile_picture`, `user_add`, `contact_num`) VALUES
(10, 'kennmarton.mocorro.cics@ust.edu.ph', 'kmocorro24', 'Kenn Marton', 'Mocorro', '2003-06-24', 'kenken', 'uploads/440488877_318802917732416_6602748213895101957_n.jpg', 'Eastwood City, Brgy. Bagumbayan Quezon City', 0),
(12, 'lebron@gmail.com', 'lbj', 'LeBron Jr.', 'James', '2023-12-31', 'lebronjames', 'uploads/Screenshot 2024-05-13 211855.png', 'Los Angeles, CA, US', 0),
(13, 'steph.curry@gmail.com', 'stephcurry30', 'Stephen', 'Curry', '2016-01-04', 'stephcurry', 'uploads/steph.jpg', 'San Francisco, CA, US', 0),
(14, 'ninjawarrior@gmail.com', 'ninjawarrior2024', 'John', 'Doe', '2024-04-28', 'ninjawarrior', 'uploads/Screenshot 2024-05-13 205320.png', 'SM Marikina, Marcos Highway, Marikina City', 0),
(15, 'joedoe2@gmail.com', 'jonathandunkit', 'Jonathan', 'Doe', '2024-04-28', 'joedoe', 'uploads/66475c689ecbc.jpg', 'HAHAHAHA', 0);

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
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `listing`
--
ALTER TABLE `listing`
  MODIFY `listing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
