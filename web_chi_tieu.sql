-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2026 at 12:25 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_chi_tieu`
--

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE `budget` (
  `id` int NOT NULL,
  `amount` int NOT NULL DEFAULT '0',
  `month` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `budget`
--

INSERT INTO `budget` (`id`, `amount`, `month`, `year`, `updated_at`) VALUES
(1, 10000000, 4, 2026, '2026-04-21 10:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `session_id`, `message`, `sender`, `created_at`) VALUES
(1, 'User_1070', 'hello', 'user', '2026-04-12 09:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int NOT NULL,
  `user_alias` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `amount` int NOT NULL,
  `type` enum('Thu','Chi') COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `amount`, `type`, `category`, `note`, `date`, `created_at`) VALUES
(1, 50000, 'Chi', 'Ăn uống', 'Ăn sáng', '2026-04-10', '2026-04-10 11:05:49'),
(2, 200000, 'Chi', 'Mua sắm', 'Mua áo', '2026-04-10', '2026-04-10 11:05:49'),
(3, 1000000, 'Thu', 'Lương', 'Lương tháng', '2026-04-10', '2026-04-10 11:05:49'),
(4, 35000, 'Chi', 'Ăn uống', '', '2026-04-10', '2026-04-10 11:35:22'),
(5, 400000, 'Chi', 'Giải trí', '', '2026-04-10', '2026-04-10 11:36:21'),
(6, 5000000, 'Thu', 'Lương', 'Lương tháng 1', '2026-01-05', '2026-04-10 12:30:57'),
(7, 1200000, 'Chi', 'Ăn uống', 'Ăn uống', '2026-01-10', '2026-04-10 12:30:57'),
(8, 800000, 'Chi', 'Mua sắm', 'Quần áo', '2026-01-15', '2026-04-10 12:30:57'),
(9, 5000000, 'Thu', 'Lương', 'Lương tháng 2', '2026-02-05', '2026-04-10 12:30:57'),
(10, 1500000, 'Chi', 'Ăn uống', 'Ăn uống', '2026-02-08', '2026-04-10 12:30:57'),
(11, 500000, 'Chi', 'Giải trí', 'Xem phim', '2026-02-20', '2026-04-10 12:30:57'),
(12, 5000000, 'Thu', 'Lương', 'Lương tháng 3', '2026-03-05', '2026-04-10 12:30:57'),
(13, 2000000, 'Chi', 'Học tập', 'Đóng học phí', '2026-03-12', '2026-04-10 12:30:57'),
(14, 5000000, 'Thu', 'Lương', 'Lương tháng 4', '2026-04-05', '2026-04-10 12:30:57'),
(15, 1000000, 'Chi', 'Ăn uống', 'Ăn uống', '2026-04-09', '2026-04-10 12:30:57'),
(16, 700000, 'Chi', 'Di chuyển', 'Xăng xe', '2026-04-18', '2026-04-10 12:30:57'),
(17, 5000000, 'Thu', 'Lương', 'Lương tháng 5', '2026-05-05', '2026-04-10 12:30:57'),
(18, 1800000, 'Chi', 'Mua sắm', 'Shopping', '2026-05-14', '2026-04-10 12:30:57'),
(19, 5000000, 'Thu', 'Lương', 'Lương tháng 6', '2026-06-05', '2026-04-10 12:30:57'),
(20, 1300000, 'Chi', 'Ăn uống', 'Ăn uống', '2026-06-11', '2026-04-10 12:30:57'),
(21, 5000000, 'Thu', 'Lương', 'Lương tháng 7', '2026-07-05', '2026-04-10 12:30:57'),
(22, 900000, 'Chi', 'Giải trí', 'Du lịch', '2026-07-22', '2026-04-10 12:30:57'),
(23, 5000000, 'Thu', 'Lương', 'Lương tháng 8', '2026-08-05', '2026-04-10 12:30:57'),
(24, 1100000, 'Chi', 'Ăn uống', 'Ăn uống', '2026-08-10', '2026-04-10 12:30:57'),
(25, 5000000, 'Thu', 'Lương', 'Lương tháng 9', '2026-09-05', '2026-04-10 12:30:57'),
(26, 2000000, 'Chi', 'Học tập', 'Mua sách', '2026-09-18', '2026-04-10 12:30:57'),
(27, 5000000, 'Thu', 'Lương', 'Lương tháng 10', '2026-10-05', '2026-04-10 12:30:57'),
(28, 1700000, 'Chi', 'Mua sắm', 'Đồ điện tử', '2026-10-12', '2026-04-10 12:30:57'),
(29, 5000000, 'Thu', 'Lương', 'Lương tháng 11', '2026-11-05', '2026-04-10 12:30:57'),
(30, 1400000, 'Chi', 'Ăn uống', 'Ăn uống', '2026-11-20', '2026-04-10 12:30:57'),
(31, 5000000, 'Thu', 'Lương', 'Lương tháng 12', '2026-12-05', '2026-04-10 12:30:57'),
(32, 3000000, 'Chi', 'Giải trí', 'Noel', '2026-12-25', '2026-04-10 12:30:57'),
(33, 80000, 'Chi', 'Ăn uống', '', '2026-04-21', '2026-04-21 09:53:11'),
(34, 3000000, 'Thu', 'Thu nhập', '', '2026-04-21', '2026-04-21 10:24:18'),
(35, 3000000, 'Chi', 'Ăn uống', '', '2026-04-21', '2026-04-21 10:24:30'),
(36, 250000, 'Chi', 'Mua sắm', '', '2026-04-21', '2026-04-21 10:27:09'),
(37, 1000000, 'Chi', 'Ăn uống', '', '2026-04-21', '2026-04-21 10:27:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_idx` (`session_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
