-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 22, 2025 at 05:21 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dream_pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL,
  `size` text NOT NULL,
  `category_id` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `date_created`, `created_by`, `image`, `user_id`) VALUES
(1, 'Fruit Tea Series', '2025-01-17 17:35:56', 'Samuel Osei Adu', 'uploads/categories/678a94fc25e6b.png', 5),
(2, 'Poppin Boba Series', '2025-01-18 08:35:05', 'Samuel Osei Adu', 'uploads/categories/678b67b995ff7.png', 5),
(3, 'Milk Tea Series', '2025-01-18 08:34:45', 'Samuel Osei Adu', 'uploads/categories/678b67a5bb296.png', 5),
(4, 'Toppings', '2025-01-18 08:35:17', 'Samuel Osei Adu', 'uploads/categories/678b67c5a1c28.png', 5),
(5, 'Boba Pearls', '2025-01-20 16:54:59', 'Samuel Osei Adu', 'uploads/categories/678e7fe345fa9.png', 5);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `items` text DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `contact`, `items`, `total`, `created_at`) VALUES
(1, 'Eugene', '024637389', '1x Cocktail Boba, 1x Blueberry Popping', 70.00, '2025-01-21 22:30:42'),
(3, 'Mr Akoto', '024829879', '1x Cocktail Boba, 1x Orange Popping', 70.00, '2025-01-22 09:03:27'),
(4, 'Francis', '0247929343', '1x Cocktail Boba, 1x Blueberry Popping', 70.00, '2025-01-22 12:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `order_id`, `is_read`, `created_at`) VALUES
(16, 35, 0, '2025-01-21 22:30:42'),
(17, 36, 0, '2025-01-21 23:11:13'),
(18, 37, 0, '2025-01-22 09:03:27'),
(19, 38, 0, '2025-01-22 12:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `tax_amount` decimal(10,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` enum('cash','card','mobile_money') DEFAULT 'cash',
  `payment_status` enum('paid','pending','failed') DEFAULT 'paid',
  `order_status` enum('completed','cancelled','refunded') DEFAULT 'completed',
  `receipt_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_phone`, `total_amount`, `tax_amount`, `discount_amount`, `payment_method`, `payment_status`, `order_status`, `receipt_number`, `notes`, `created_at`) VALUES
(35, 5, 'Eugene', '024637389', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-EU88691', NULL, '2025-01-16 22:30:42'),
(36, 5, 'Joan', '025363838', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-JO37710', NULL, '2025-01-21 23:11:13'),
(37, 5, 'Mr Akoto', '024829879', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-MRAK27966', NULL, '2025-01-22 09:03:27'),
(38, 5, 'Francis', '0247929343', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-FR45521', NULL, '2025-01-22 12:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_history`
--

CREATE TABLE `order_history` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `previous_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `order_id`, `user_id`, `action`, `previous_status`, `new_status`, `notes`, `created_at`) VALUES
(26, 35, 5, 'order_created', NULL, 'completed', NULL, '2025-01-21 22:30:42'),
(27, 36, 5, 'order_created', NULL, 'completed', NULL, '2025-01-21 23:11:13'),
(28, 37, 5, 'order_created', NULL, 'completed', NULL, '2025-01-22 09:03:27'),
(29, 38, 5, 'order_created', NULL, 'completed', NULL, '2025-01-22 12:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`) VALUES
(60, 35, 21, 1, 60.00, 60.00, '2025-01-21 22:30:42'),
(61, 35, 32, 1, 10.00, 10.00, '2025-01-21 22:30:42'),
(62, 36, 21, 1, 60.00, 60.00, '2025-01-21 23:11:13'),
(63, 37, 21, 1, 60.00, 60.00, '2025-01-22 09:03:27'),
(64, 37, 33, 1, 10.00, 10.00, '2025-01-22 09:03:27'),
(65, 38, 21, 1, 60.00, 60.00, '2025-01-22 12:07:17'),
(66, 38, 32, 1, 10.00, 10.00, '2025-01-22 12:07:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` varchar(255) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `price` text DEFAULT NULL,
  `size` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text DEFAULT NULL,
  `image` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `category_name`, `name`, `price`, `size`, `created_at`, `created_by`, `image`) VALUES
(21, '1', 'Fruit Tea Series', 'Cocktail Boba', '60', 'Medium', '2025-01-21 10:59:25', NULL, 'uploads/products/678f7e0d37c35.png'),
(22, '1', 'Fruit Tea Series', 'Mango', '60', 'Medium', '2025-01-21 11:03:13', NULL, 'uploads/products/678f7ef11e33c.png'),
(23, '1', 'Fruit Tea Series', 'Iced Tea', '80', 'Large', '2025-01-21 11:22:10', NULL, 'uploads/products/678f8362b03b8.png'),
(24, '1', 'Fruit Tea Series', 'Pineapple Boba', '80', 'Large', '2025-01-21 11:23:02', NULL, 'uploads/products/678f8396a902d.png'),
(25, '1', 'Fruit Tea Series', 'Rose', '80', 'Large', '2025-01-21 11:24:42', NULL, NULL),
(26, '1', 'Fruit Tea Series', 'Cantaloupe', '60', 'Medium', '2025-01-21 11:41:09', NULL, NULL),
(27, '1', 'Fruit Tea Series', 'Lemon', '60', 'Medium', '2025-01-21 11:41:36', NULL, NULL),
(28, '1', 'Fruit Tea Series', 'Blue Orange', '60', 'Medium', '2025-01-21 11:42:00', NULL, NULL),
(29, '1', 'Fruit Tea Series', 'Vanilla', '60', 'Medium', '2025-01-21 11:42:25', NULL, NULL),
(30, '1', 'Fruit Tea Series', 'Mint', '60', 'Medium', '2025-01-21 11:42:45', NULL, NULL),
(31, '2', 'Poppin Boba Series', 'Strawberry Popping', '10', 'Medium', '2025-01-21 11:47:23', NULL, NULL),
(32, '2', 'Poppin Boba Series', 'Blueberry Popping', '10', 'Medium', '2025-01-21 11:47:58', NULL, NULL),
(33, '2', 'Poppin Boba Series', 'Orange Popping', '10', 'Medium', '2025-01-21 11:48:13', NULL, NULL),
(34, '2', 'Poppin Boba Series', 'Yogurt Popping', '10', 'Medium', '2025-01-21 11:48:41', NULL, NULL),
(35, '2', 'Poppin Boba Series', 'Passion Fruit Popping', '10', 'Medium', '2025-01-21 11:49:03', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_pricing`
--

CREATE TABLE `product_pricing` (
  `id` int(11) NOT NULL,
  `size_name` text NOT NULL,
  `category_id` text NOT NULL,
  `price` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_pricing`
--

INSERT INTO `product_pricing` (`id`, `size_name`, `category_id`, `price`) VALUES
(10, 'Medium', '1', '60'),
(11, 'Large', '1', '80');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `role` enum('admin','cashier') NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` date NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `username`, `password`, `fullname`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `image`) VALUES
(5, 'addsamuel355@gmail.com', '0246562377', 'samuelos', '$2y$10$lELYL5GH8TZonjHqGmhfpuXp5FB0gzPyHgA4v9/JwLghtKnYTrzxe', 'Samuel Osei Adu', 'admin', 'active', '2025-01-21 10:05:27', '2025-01-17 10:37:32', '2025-01-22', 'uploads/profile_images/678a32eca0f48.jpg'),
(7, 'tater@gmail.com', '0246578988', 'taterjon', '$2y$10$2gbK2DxWCJYaEvJnMazc6uRcU/NJ.OWuY14Z9Hx/Vxj2e58x1jTd.', 'Tater Jonathan', 'admin', 'active', NULL, '2025-01-22 12:06:04', '2025-01-22', 'uploads/profile_images/6790df2c09fd0.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receipt_number` (`receipt_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_pricing`
--
ALTER TABLE `product_pricing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=256;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `product_pricing`
--
ALTER TABLE `product_pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_history`
--
ALTER TABLE `order_history`
  ADD CONSTRAINT `order_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
