DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `pallets`; -- Updated from products
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `order_pallets`; -- Updated from order_products
DROP TABLE IF EXISTS `couriers`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `messages`;

-- Create the `users` table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(20),
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` BIGINT DEFAULT UNIX_TIMESTAMP(),
  `role` VARCHAR(20) NOT NULL,
  `photo_path` VARCHAR(255),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `pallets` table (Updated from products)
CREATE TABLE IF NOT EXISTS `pallets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `size_x_cm` INT(3) NOT NULL,
  `size_y_cm` INT(3) NOT NULL,
  `size_z_cm` INT(3) NOT NULL,
  `weight_kg` DECIMAL(3, 1) NOT NULL,
  `created_at` BIGINT DEFAULT UNIX_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `orders` table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `start_point` VARCHAR(255) NOT NULL,
  `end_destination` VARCHAR(255) NOT NULL,
  `status` VARCHAR(50) NOT NULL,
  `product_price` DECIMAL(10, 2) NOT NULL,
  `total_amount` DECIMAL(10, 2) NOT NULL,
  `created_at` BIGINT DEFAULT UNIX_TIMESTAMP(),
  `last_processed` BIGINT DEFAULT UNIX_TIMESTAMP(),
  `courier_id` INT(11) NOT NULL,
  `tracking_number` VARCHAR(255) DEFAULT NULL,
  `delivery_date` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `order_pallets` table (Updated from order_products)
CREATE TABLE IF NOT EXISTS `order_pallets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `pallet_id` INT(11) NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  `subtotal` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `couriers` table (NOT USED)
CREATE TABLE IF NOT EXISTS `couriers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `phone_number` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `is_busy` TINYINT(1) DEFAULT 0,  -- Added is_busy status (0=false, 1=true)
  `allowed_tracking` TINYINT(1) DEFAULT 1,  -- Added allowed_tracking status (0=false, 1=true)
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `settings` table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `key` VARCHAR(255) NOT NULL,
  `value` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `notifications` table
CREATE TABLE `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `link` VARCHAR(255) NULL, -- Optional, to open a specific page
  `is_seen` TINYINT(1) DEFAULT 0, -- 0 = unseen, 1 = seen
  `created_at` BIGINT DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create the `messages` table
CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `sender_id` INT(11) NOT NULL,
  `recipient_id` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0, -- 0 = unread, 1 = read
  `created_at` BIGINT DEFAULT UNIX_TIMESTAMP(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`), -- Add foreign key for sender
  FOREIGN KEY (`recipient_id`) REFERENCES `users`(`id`) -- Add foreign key for recipient
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `settings` (`key`, `value`)
VALUES ('email_sending', 'disabled'), -- Updated to match the current settings
       ('date_format', 'dd/mm/Y'),
       ('opening_time', '08:00'),
       ('closing_time', '18:00'),
       ('weekend_operation', '0'),
       ('weekend_opening_time', '10:00'),
       ('weekend_closing_time', '17:00'),
       ('order_cut_off_time', '17:00'),
       ('default_order_status', 'Pending'),
       ('timezone', 'Europe/Sofia'),
       ('currency', 'BGN'); -- Added currency setting