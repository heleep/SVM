-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 14, 2025 at 07:24 AM
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
(6, '6800b3370982f_one_piece_luffy_superpower-wallpaper-3554x1999.jpg', '2025-04-17 07:52:23'),
(7, '6800b94c4bdac_space-wallpaper-3554x1999.jpg', '2025-04-17 08:18:20'),
(9, '680b2aad491a0_bugatti_tourbillon_hybrid_hyper_sports_car-wallpaper-3554x1999.jpg', '2025-04-25 06:24:45'),
(10, '680b2ac1c6bb8_minecraft-forest-4k-wallpaper-uhdpaper.com-619@5@e.jpg', '2025-04-25 06:25:05'),
(12, '681c28e86a722_login.png', '2025-05-08 03:45:44');

-- --------------------------------------------------------

--
-- Table structure for table `SVM_Events`
--

CREATE TABLE `SVM_Events` (
  `SVM_Event` text NOT NULL,
  `Event_description` text DEFAULT NULL,
  `Event_date` date NOT NULL,
  `Event_image` varchar(255) DEFAULT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `SVM_Events`
--

INSERT INTO `SVM_Events` (`SVM_Event`, `Event_description`, `Event_date`, `Event_image`, `id`) VALUES
('freshers', 'this is the first events', '2025-10-19', '6849132ea9d89_takeattendance.png', 2),
('sdf', 'adsfasdfhs  daffasdf asdf ', '0652-05-31', '684c071f17c18_logo.png', 3),
('dlkfja asdfklajs;lfja dslf', 'fgdffffffffdgg', '3492-08-09', '684c079748682_home.png', 4);

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
('mayur@gmail.com', 'mayur'),
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
-- Indexes for table `SVM_Events`
--
ALTER TABLE `SVM_Events`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `SVM_Events`
--
ALTER TABLE `SVM_Events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
