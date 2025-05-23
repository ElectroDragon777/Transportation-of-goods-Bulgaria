-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8111
-- Generation Time: Apr 24, 2025 at 08:06 PM
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
  `created_at` bigint(20) DEFAULT unix_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pallets`
--

INSERT INTO `pallets` (`id`, `name`, `description`, `price`, `size_x_cm`, `size_y_cm`, `size_z_cm`, `weight_kg`, `created_at`) VALUES
(1, 'Testing pallet', NULL, 0.00, 12, 23, 33, 1.2, 1745178104);

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
(2, 'date_format', 'd/m/Y'),
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
(1, 'Chara Dreemurr', 'chara@abv.bg', '0882872569', '$2y$10$SzepIhkBjgGu7USFhcIajOf6INf1T9tFMvKxgrCkBzZRIt0IUWUWy', 1743619906, 'root', '', '', 'web/upload/profile_1_67fb7114f4035.jpg'),
(2, 'Hakane', 'hakane.hoshino@yahoo.com', '0886777106', '$2y$10$ziUyifPVgfFHNBiN/hT5MOOzOsAbsFO400vRw0u2d2qmW1OJw/cfW', 1743620055, 'user', '', '', 'web/upload/profile_2_67ed885239962.png'),
(3, 'Monika', 'monika@gmail.com', '0883878982', '$2y$10$Xl7uKdPNbXLRbDgJQeTxCuO532QZLoPcCU5LzIFje/fMef9qSn/aK', 1743620068, 'courier', '', '', 'web/upload/profile_3_67ed8803c86f5.jpg'),
(4, 'Shinano', 'shinano.azurship@gmail.com', '0889876728', '$2y$10$3J9U.m9zNCad8vPH4w5IH./Hq5psbKs7EN3NTEtvCgYscxqyibD3K', 1744996451, 'courier', NULL, NULL, NULL),
(5, 'Ran Yakumo', 'ran-yakumo.the_bulgarianfoxie@abv.bg', '0881928372', '$2y$10$d3irtl06XZN8YGOoGfGI4u6FmkNIUtDVI20zrijE24CbN/vXzscoK', 1744997473, 'admin', NULL, NULL, NULL);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
