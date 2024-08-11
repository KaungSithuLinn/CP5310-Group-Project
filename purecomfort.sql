-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2024 at 08:43 AM
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
-- Database: `purecomfort`
--

-- Drop existing tables
DROP TABLE IF EXISTS `userreview`;
DROP TABLE IF EXISTS `booking_addservice`;
DROP TABLE IF EXISTS `cart`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `rooms`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `addservice`;

-- --------------------------------------------------------

--
-- Table structure for table `addservice`
--

CREATE TABLE `addservice` (
  `addSerID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`addSerID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `room_id` int(11) NOT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_addservice`
--

CREATE TABLE `booking_addservice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `addservice_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'Luxury Suite', 'Experience ultimate comfort in our spacious Luxury Suite.', 300.00, 'room1.jpg'),
(2, 'Deluxe Room', 'Enjoy a comfortable stay in our well-appointed Deluxe Room.', 200.00, 'room2.jpg'),
(3, 'Family Suite', 'Perfect for families, our spacious Family Suite offers comfort for all.', 400.00, 'room3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `fName` varchar(60) NOT NULL,
  `lName` varchar(60) NOT NULL,
  `email` varchar(60) NOT NULL,
  `username` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` varchar(60) NOT NULL,
  `passport` varchar(60) NOT NULL,
  `address` varchar(60) NOT NULL,
  `country` varchar(60) NOT NULL,
  `postcode` varchar(60) NOT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `fName`, `lName`, `email`, `username`, `password`, `date_of_birth`, `gender`, `passport`, `address`, `country`, `postcode`, `last_login`) VALUES
(1, 'KaungSithu', 'Linn', 'kaungsithulinn1@gmail.com', 'KaungSithuLinn', '$2y$10$BH3bSolGh2Uu16euqkvMaumGLw2No.h74YRvCkaf/DnXBLczHszRa', '2001-12-03', 'Male', 'A12345678', '81 Road, Between 12 & 13 Street, Mandalay', 'Myanmar', '123456', '2024-08-04 06:19:54'),
(3, 'ZHANG', 'YUCHENG', '853708331@qq.com', 'zhangyucheng', '$2y$10$8TDrcmjvL2s8kD./kmDUCu2R2yWWl10.sKCxDNOKOboSJ6HKxZk8G', '1985-05-15', 'Male', 'B98765432', '456 Avenue', 'China', '654321', NULL),
(4, 'Worapas', 'P', 'worapas.nwp@gmail.com', 'worapasp', '$2y$10$z0tWpBjFulz4sSO62rZrMuYdxiX6x6T/t7BTn0FfBKZhniiBx91EG', '1992-07-07', 'Female', 'C11223344', '789 Boulevard', 'Thailand', '789123', NULL),
(18, 'John', 'Doe', 'john.doe@example.com', 'johndoe', '$2y$10$yo49wsEQSKgX.58lkTOaV.m9yq/6O29IWn0yUl0FrdRN/2eCjHVxq', '1990-01-01', 'Male', 'AB1234567', '123 Main St', 'United States', '12345', '2024-08-04 06:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `userreview`
--

CREATE TABLE `userreview` (
  `userReID` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `rating` float NOT NULL,
  `comment` text DEFAULT NULL,
  PRIMARY KEY (`userReID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `booking_addservice`
--
ALTER TABLE `booking_addservice`
  ADD CONSTRAINT `booking_addservice_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_addservice_ibfk_2` FOREIGN KEY (`addservice_id`) REFERENCES `addservice` (`addSerID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `userreview`
--
ALTER TABLE `userreview`
  ADD CONSTRAINT `user_userreviewID` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userreview_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;