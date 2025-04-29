-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:8111
-- Generation Time: Apr 14, 2025 at 09:00 PM
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
  `start_point` varchar(255) NOT NULL,
  `end_destination` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
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
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pallets`
--

CREATE TABLE `pallets` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL, -- Document, Package, etc.
  `description` text DEFAULT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `size_x_cm` int(3) NOT NULL,
  `size_y_cm` int(3) NOT NULL,
  `size_z_cm` int(3) NOT NULL,
  `weight_kg` decimal(3,1) NOT NULL,
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
  `photo_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` bigint(20) DEFAULT unix_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `couriers`
--
CREATE TABLE `couriers` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `is_busy` TINYINT(1) DEFAULT 0,  -- Added is_busy status (0=false, 1=true)
  `allowed_tracking` TINYINT(1) DEFAULT 1,  -- Added allowed_tracking status (0=false, 1=true)
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `password_hash`, `created_at`, `role`, `photo_path`) VALUES
(1, 'Chara Dreemurr', 'chara@abv.bg', '0882872569', '$2y$10$SzepIhkBjgGu7USFhcIajOf6INf1T9tFMvKxgrCkBzZRIt0IUWUWy', 1743619906, 'root', 'web/upload/profile_1_67fb7114f4035.jpg'),
(2, 'Hakane', 'hakane.hoshino@yahoo.com', '0886777106', '$2y$10$ziUyifPVgfFHNBiN/hT5MOOzOsAbsFO400vRw0u2d2qmW1OJw/cfW', 1743620055, 'user', 'web/upload/profile_2_67ed885239962.png'),
(3, 'Monika', 'monika@gmail.com', '0883878982', '$2y$10$Xl7uKdPNbXLRbDgJQeTxCuO532QZLoPcCU5LzIFje/fMef9qSn/aK', 1743620068, 'courier', 'web/upload/profile_3_67ed8803c86f5.jpg'),
(4, 'Shinano', 'shinano.azurship@gmail.com', '0889876728', '$2y$10$3J9U.m9zNCad8vPH4w5IH./Hq5psbKs7EN3NTEtvCgYscxqyibD3K', 1744996451, 'courier', NULL),
(5, 'Ran Yakumo', 'ran-yakumo.the_bulgarianfoxie@abv.bg', '0881928372', '$2y$10$d3irtl06XZN8YGOoGfGI4u6FmkNIUtDVI20zrijE24CbN/vXzscoK', 1744997473, 'admin', NULL);
--
-- Indexes for dumped tables
--

--
-- Dumping data for table `couriers`
--

INSERT INTO `couriers` (`id`, `name`, `phone_number`, `email`, `is_busy`, `allowed_tracking`) VALUES
(1, 'Monika', '0883878982', 'monika@gmail.com', 0, 1),
(2, 'Shinano', '0889876728', 'shinano.azurship@gmail.com', 0, 1);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

--
-- AUTO_INCREMENT for table `couriers`
--
ALTER TABLE `couriers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
