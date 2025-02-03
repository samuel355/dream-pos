-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 02, 2025 at 11:17 PM
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
-- Database: `popsy_shop`
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
  `user_id` int(11) NOT NULL,
  `arrange` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `date_created`, `created_by`, `image`, `user_id`, `arrange`) VALUES
(1, 'Fruit Tea Series', '2025-01-17 17:35:56', 'Samuel Osei Adu', 'uploads/categories/678a94fc25e6b.png', 5, 2),
(2, 'Poppin Boba Series', '2025-01-18 08:35:05', 'Samuel Osei Adu', 'uploads/categories/678b67b995ff7.png', 5, 4),
(3, 'Milk Tea Series', '2025-01-18 08:34:45', 'Samuel Osei Adu', 'uploads/categories/678b67a5bb296.png', 5, 1),
(4, 'Extra Toppings', '2025-01-18 08:35:17', 'Samuel Osei Adu', 'uploads/categories/678b67c5a1c28.png', 5, 3);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(21, '1', 'Fruit Tea Series', 'Cocktail', '60', 'Medium', '2025-01-21 10:59:25', NULL, 'uploads/products/6791955b628cf.png'),
(22, '1', 'Fruit Tea Series', 'Mango Fruity', '60', 'Medium', '2025-01-21 11:03:13', NULL, 'uploads/products/6791957060134.png'),
(24, '1', 'Fruit Tea Series', 'Pineapple Fruity', '80', 'Large', '2025-01-21 11:23:02', NULL, 'uploads/products/6795081836e2a.png'),
(25, '1', 'Fruit Tea Series', 'Rose Fruity', '80', 'Large', '2025-01-21 11:24:42', NULL, NULL),
(26, '1', 'Fruit Tea Series', 'Cantaloupe', '60', 'Medium', '2025-01-21 11:41:09', NULL, NULL),
(31, '2', 'Poppin Boba Series', 'Strawberry Popping', '10', 'Medium', '2025-01-21 11:47:23', NULL, NULL),
(32, '2', 'Poppin Boba Series', 'Blueberry Popping', '10', 'Medium', '2025-01-21 11:47:58', NULL, NULL),
(33, '2', 'Poppin Boba Series', 'Orange Popping', '10', 'Medium', '2025-01-21 11:48:13', NULL, NULL),
(34, '2', 'Poppin Boba Series', 'Yogurt Popping', '10', 'Medium', '2025-01-21 11:48:41', NULL, NULL),
(36, '3', 'Milk Tea Series', 'Taro Milk Tea', '60', 'Medium', '2025-01-22 16:26:58', NULL, NULL),
(37, '3', 'Milk Tea Series', 'Blueberry Milk Tea', '60', 'Medium', '2025-01-22 16:27:58', NULL, NULL),
(40, '3', 'Milk Tea Series', 'Strawberry Milk Tea', '60', 'Medium', '2025-01-22 16:45:42', NULL, NULL),
(41, '3', 'Milk Tea Series', 'Chocolate Milk Tea', '60', 'Medium', '2025-01-22 16:46:00', NULL, NULL),
(42, '3', 'Milk Tea Series', 'Pineapple Milk Tea', '60', 'Medium', '2025-01-22 16:47:05', NULL, NULL),
(48, '3', 'Milk Tea Series', 'Oreo Milk Tea', '60', 'Medium', '2025-01-22 16:50:21', NULL, NULL),
(49, '3', 'Milk Tea Series', 'Caramel Delight', '80', 'Large', '2025-01-22 16:50:41', NULL, NULL),
(51, '4', 'Toppings', 'Cheese foam', '10', 'Medium', '2025-01-22 16:54:15', NULL, NULL),
(53, '4', 'Toppings', 'Boba Pearls (Brown sugar)', '10', 'Medium', '2025-01-22 16:55:44', NULL, NULL),
(68, '3', 'Milk Tea Series', 'Classic Milk Tea', '60', 'Medium', '2025-01-27 20:18:22', NULL, NULL),
(69, '3', 'Milk Tea Series', 'Raspberry Milk Tea', '80', 'Large', '2025-01-27 20:20:55', NULL, NULL),
(70, '3', 'Milk Tea Series', 'Banana Milk Tea', '60', 'Medium', '2025-01-27 20:21:24', NULL, NULL),
(71, '3', 'Milk Tea Series', 'Caramel Lotus', '80', 'Large', '2025-01-27 20:28:24', NULL, NULL),
(72, '1', 'Fruit Tea Series', 'Lemon Fruity', '60', 'Medium', '2025-01-27 20:30:27', NULL, NULL),
(73, '1', 'Fruit Tea Series', 'Passion Fruity', '80', 'Large', '2025-01-27 20:30:46', NULL, NULL),
(74, '1', 'Fruit Tea Series', 'Strawberry Fruity', '80', 'Large', '2025-01-27 20:32:03', NULL, NULL),
(75, '4', 'Extra Toppings', 'Lotus Crumbs & Sauce', '10', 'Medium', '2025-01-27 20:44:55', NULL, NULL),
(76, '4', 'Extra Toppings', 'Whipped Cream', '10', 'Medium', '2025-01-27 20:45:21', NULL, NULL),
(77, '4', 'Extra Toppings', 'Hersheys Caramel', '10', 'Medium', '2025-01-27 20:45:37', NULL, NULL),
(78, '3', 'Milk Tea Series', 'Matcha Milk Tea', '60', 'Medium', '2025-01-28 08:31:10', NULL, NULL),
(79, '3', 'Milk Tea Series', 'Matcha Flash Milk Tea', '80', 'Large', '2025-01-28 08:31:45', NULL, NULL);

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
  `image` varchar(255) DEFAULT NULL,
  `is_sysadmin` tinyint(1) DEFAULT 0,
  `software` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `username`, `password`, `fullname`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `image`, `is_sysadmin`, `software`) VALUES
(5, 'addsamuel355@gmail.com', '0246562377', 'samuelos', '$2y$10$lELYL5GH8TZonjHqGmhfpuXp5FB0gzPyHgA4v9/JwLghtKnYTrzxe', 'Samuel Osei Adu', 'admin', 'active', '2025-02-02 22:15:54', '2025-01-17 10:37:32', '2025-01-22', 'uploads/profile_images/678a32eca0f48.jpg', 1, 'developer'),
(13, 'prince@gmail.com', '0246562377', 'princeak', '$2y$10$X.OMwsknzgcxzMHA7yurCumtiozVrEGRBa5etA0K0oSMtdvyD/l4y', 'PRINCE AKOTO', 'cashier', 'active', '2025-02-02 22:12:59', '2025-02-02 22:12:34', '2025-02-02', 'uploads/profile_images/679fedd25bcc3.jpg', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_ibfk_1` (`product_id`);

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
  ADD KEY `notifications_ibfk_1` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_ibfk_1` (`user_id`);

--
-- Indexes for table `order_history`
--
ALTER TABLE `order_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_history_ibfk_2` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `product_pricing`
--
ALTER TABLE `product_pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
