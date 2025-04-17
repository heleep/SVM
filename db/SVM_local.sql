-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 17, 2025 at 09:59 AM
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
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `filename`, `uploaded_at`) VALUES
(2, '6800b2c6d98ec_one_piece_ace-wallpaper-1920x1080.jpg', '2025-04-16 07:11:50'),
(6, '6800b3370982f_one_piece_luffy_superpower-wallpaper-3554x1999.jpg', '2025-04-17 07:52:23');

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
('freshers', '3344-02-12'),
('makwana', '0445-03-12'),
('dfsf', '4567-03-12'),
('patel princ', '0099-09-09'),
('dfsf', '0323-02-23'),
('mayur', '2001-01-01'),
('mayur', '9999-09-09'),
('princ', '2025-04-16');

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
('admin@gmail.com', '@admin_'),
('patel@gmail.com', '2635');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `SVM_Principal`
--
ALTER TABLE `SVM_Principal`
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
