-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 29, 2025 at 10:00 AM
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
-- Database: `SVM_local`
--

-- --------------------------------------------------------

--
-- Table structure for table `SVM_Events`
--

CREATE TABLE `SVM_Events` (
  `SVM_Event` text NOT NULL,
  `Event_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `SVM_Events`
--

INSERT INTO `SVM_Events` (`SVM_Event`, `Event_date`) VALUES
('mayur makwana', '0008-08-05'),
('second event example', '0001-02-03');

-- --------------------------------------------------------

--
-- Table structure for table `SVM_Principal`
--

CREATE TABLE `SVM_Principal` (
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `SVM_Principal`
--

INSERT INTO `SVM_Principal` (`user_name`, `user_password`) VALUES
('admin@gmail.com', '@admin_');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `SVM_Principal`
--
ALTER TABLE `SVM_Principal`
  ADD UNIQUE KEY `user_name` (`user_name`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
