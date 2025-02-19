-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 18, 2025 at 03:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `travel_buddy`
--

-- --------------------------------------------------------

--
-- Table structure for table `connection_requests`
--

CREATE TABLE `connection_requests` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_user`
--

CREATE TABLE `new_user` (
  `id` int(11) NOT NULL,
  `auth_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

CREATE TABLE `registered_users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `travel_partners`
--

CREATE TABLE `travel_partners` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `destination` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `budget` decimal(10,2) NOT NULL,
  `preferences` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `connection_requests`
--
ALTER TABLE `connection_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `new_user`
--
ALTER TABLE `new_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_id` (`auth_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `travel_partners`
--
ALTER TABLE `travel_partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `connection_requests`
--
ALTER TABLE `connection_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_user`
--
ALTER TABLE `new_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registered_users`
--
ALTER TABLE `registered_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `travel_partners`
--
ALTER TABLE `travel_partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `connection_requests`
--
ALTER TABLE `connection_requests`
  ADD CONSTRAINT `connection_requests_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `connection_requests_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `new_user`
--
ALTER TABLE `new_user`
  ADD CONSTRAINT `new_user_ibfk_1` FOREIGN KEY (`auth_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `travel_partners`
--
ALTER TABLE `travel_partners`
  ADD CONSTRAINT `travel_partners_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_partners_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `travel_partners_ibfk_3` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`trip_id`) ON DELETE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `registered_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
