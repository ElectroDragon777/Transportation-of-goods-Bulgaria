-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8111
-- Generation Time: Apr 10, 2025 at 12:45 PM EEST
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+02:00"; -- Updated to EEST

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chara_transportation`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_seen` tinyint(1) DEFAULT 0,
  `created_at` bigint(20) DEFAULT unix_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `address` varchar(255) NOT NULL,
    `region` varchar(255) NOT NULL,
    `status` varchar(50) NOT NULL,
    `product_price` decimal(10,2) NOT NULL,
    `total_amount` decimal(10,2) NOT NULL,
    `created_at` bigint(20) DEFAULT unix_timestamp(),
    `last_processed` bigint(20) DEFAULT unix_timestamp(),
    `courier_id` int(11) NOT NULL,
    `tracking_number` varchar(255) DEFAULT NULL,
    `delivery_date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_pallets`
--

CREATE TABLE `order_pallets` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `pallet_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `mini_tax` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pallets`
--

CREATE TABLE `pallets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `size_x_cm` int(3) NOT NULL,
  `size_y_cm` int(3) NOT NULL,
  `size_z_cm` int(3) NOT NULL,
  `weight_kg` decimal(3,1) NOT NULL,
  `code_billlanding` int(10) NOT NULL,
  `created_at` bigint(20) DEFAULT unix_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'email_sending', 'disabled'),
(2, 'date_format', 'dd/mm/Y'),
(3, 'opening_time', '08:00'),
(4, 'closing_time', '18:00'),
(5, 'weekend_operation', '0'),
(6, 'weekend_opening_time', '10:00'),
(7, 'weekend_closing_time', '17:00'),
(8, 'order_cut_off_time', '17:00'),
(9, 'default_order_status', 'Pending'),
(10, 'timezone', 'Europe/Sofia'),
(11, 'currency', 'BGN');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` bigint(20) DEFAULT unix_timestamp(),
  `role` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `password_hash`, `created_at`, `role`, `address`, `region`, `photo_path`) VALUES
(1, 'Chara Dreemurr', 'chara@abv.bg', '', '$2y$10$SzepIhkBjgGu7USFhcIajOf6INf1T9tFMvKxgrCkBzZRIt0IUWUWy', 1743619906, 'root', '', '', 'web/upload/profile_1_67ed879ca62ff.jpg'),
(2, 'Hakane', 'hakane.hoshino@yahoo.com', NULL, '$2y$10$ziUyifPVgfFHNBiN/hT5MOOzOsAbsFO400vRw0u2d2qmW1OJw/cfW', 1743620055, 'user', NULL, NULL, 'web/upload/profile_2_67ed885239962.png'),
(3, 'Monika', 'monika@gmail.com', NULL, '$2y$10$Xl7uKdPNbXLRbDgJQeTxCuO532QZLoPcCU5LzIFje/fMef9qSn/aK', 1743620068, 'courier', NULL, NULL, 'web/upload/profile_3_67ed8803c86f5.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_pallets`
--
ALTER TABLE `order_pallets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pallets`
--
ALTER TABLE `pallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code_billlanding` (`code_billlanding`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_pallets`
--
ALTER TABLE `order_pallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pallets`
--
ALTER TABLE `pallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;