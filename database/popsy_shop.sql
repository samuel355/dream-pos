-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 25, 2025 at 05:56 PM
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

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `session_id`, `product_id`, `quantity`, `price`, `size`, `category_id`, `created_at`) VALUES
(257, '1lotq4ij736s0j3i2uop3tei9l', 61, 1, 80.00, 'Large', '5', '2025-01-22 17:16:29'),
(264, 'nd6er1rb8kumlaam3hq02hihhc', 21, 1, 60.00, 'Medium', '1', '2025-01-23 14:59:46'),
(265, 'nd6er1rb8kumlaam3hq02hihhc', 32, 1, 10.00, 'Medium', '2', '2025-01-23 14:59:49'),
(277, 'joi43tu1ipv0a4hhi97frv7995', 23, 1, 80.00, 'Large', '1', '2025-01-24 21:43:05'),
(278, 'joi43tu1ipv0a4hhi97frv7995', 32, 2, 10.00, 'Medium', '2', '2025-01-24 21:43:44'),
(287, 'l3rip9p78nf55vussflkbdeb16', 32, 1, 10.00, 'Medium', '2', '2025-01-25 15:32:46');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `contact`, `items`, `total`, `created_at`, `created_by`) VALUES
(1, 'Eugene', '024637389', '1x Cocktail Boba, 1x Blueberry Popping', 70.00, '2025-01-21 22:30:42', ''),
(3, 'Mr Akoto', '024829879', '1x Cocktail Boba, 1x Orange Popping', 70.00, '2025-01-22 09:03:27', ''),
(4, 'Francis', '0247929343', '1x Cocktail Boba, 1x Blueberry Popping', 70.00, '2025-01-22 12:07:17', ''),
(5, 'Dark Side', '0246577221', '1x Boba Pearls', 60.00, '2025-01-23 09:07:57', ''),
(6, 'new cus', '064676798', '1x Cocktail Boba', 60.00, '2025-01-23 11:14:24', ''),
(7, 'Emmanuel', '0253098944', '1x Cocktail Boba, 1x Brown sugar syrup', 70.00, '2025-01-23 15:07:24', 'Tater Jonathan'),
(8, 'Listowell', '0253098944', '1x Boba Pearls', 60.00, '2025-01-23 15:11:14', 'Tater Jonathan'),
(9, 'Jahmon', '024988345', '1x Boba Pearls', 60.00, '2025-01-23 16:06:20', 'Samuel Osei Adu'),
(10, 'Sam', '02498905445', '1x Boba Pearls', 60.00, '2025-01-23 16:07:09', 'Samuel Osei Adu'),
(11, 'Zara', '02984908245', '1x Boba Pearls, 1x Nutella', 70.00, '2025-01-23 16:08:55', 'Samuel Osei Adu'),
(12, 'Jacobina', '0248903453', '1x Mint, 1x Brown sugar syrup', 70.00, '2025-01-24 19:25:39', 'Samuel Osei Adu'),
(13, 'Gambi', '043872985', '1x Iced Tea, 1x Blueberry Popping', 90.00, '2025-01-24 21:49:01', 'Samuel Osei Adu'),
(14, 'Loving', '0398289435', '1x Iced Tea, 1x Blueberry Popping, 1x Brown sugar syrup', 100.00, '2025-01-24 21:57:51', 'Samuel Osei Adu'),
(15, 'Blessing', '0398478934', '1x Strawberry Popping, 1x Brown sugar syrup', 20.00, '2025-01-25 15:30:55', 'Grace Gambi'),
(16, 'Emman', '03398740934', '1x Blueberry Popping', 10.00, '2025-01-25 15:32:57', 'Grace Gambi'),
(17, 'Kofi', '0245693784', '1x Blueberry Popping, 1x Hersheys Caramel Syrup, 1x Boba Pearls', 80.00, '2025-01-25 15:36:42', 'Grace Gambs'),
(18, 'Kwame', '03984094', '1x Blueberry Popping, 1x Hersheys Caramel Syrup, 1x Boba Pearls', 80.00, '2025-01-25 15:37:11', 'Grace Gambs'),
(19, 'Anas', '025309844', '1x Cocktail Boba, 1x Blueberry Popping', 70.00, '2025-01-25 15:57:53', 'Samuel Osei Adu');

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
(16, 35, 1, '2025-01-21 22:30:42'),
(17, 36, 1, '2025-01-21 23:11:13'),
(18, 37, 1, '2025-01-22 09:03:27'),
(19, 38, 1, '2025-01-22 12:07:17'),
(20, 39, 1, '2025-01-23 09:07:57'),
(21, 40, 1, '2025-01-23 11:14:24'),
(22, 46, 1, '2025-01-23 15:07:24'),
(23, 47, 1, '2025-01-23 15:11:14'),
(24, 48, 1, '2025-01-23 16:06:20'),
(25, 49, 1, '2025-01-23 16:07:09'),
(26, 50, 1, '2025-01-23 16:08:55'),
(27, 51, 1, '2025-01-24 19:25:39'),
(28, 56, 1, '2025-01-24 21:49:01'),
(29, 57, 1, '2025-01-24 21:57:51'),
(30, 58, 0, '2025-01-25 15:30:55'),
(31, 59, 0, '2025-01-25 15:32:57'),
(32, 60, 0, '2025-01-25 15:36:42'),
(33, 61, 0, '2025-01-25 15:37:11'),
(34, 62, 0, '2025-01-25 15:57:53');

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

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_phone`, `total_amount`, `tax_amount`, `discount_amount`, `payment_method`, `payment_status`, `order_status`, `receipt_number`, `notes`, `created_at`, `created_by`) VALUES
(35, 5, 'Eugene', '024637389', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-EU88691', NULL, '2025-01-16 22:30:42', ''),
(36, 5, 'Joan', '025363838', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-JO37710', NULL, '2025-01-21 23:11:13', ''),
(37, 5, 'Mr Akoto', '024829879', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-MRAK27966', NULL, '2025-01-22 09:03:27', ''),
(38, 5, 'Francis', '0247929343', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-FR45521', NULL, '2025-01-22 12:07:17', ''),
(39, 5, 'Dark Side', '0246577221', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-DASI64775', NULL, '2025-01-23 09:07:57', ''),
(40, 5, 'new cus', '064676798', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-NECU43282', NULL, '2025-01-23 11:14:24', ''),
(46, 7, 'Emmanuel', '0253098944', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-EM93674', NULL, '2025-01-23 15:07:24', 'Tater Jonathan'),
(47, 7, 'Listowell', '0253098944', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-LI60820', NULL, '2025-01-23 15:11:14', 'Tater Jonathan'),
(48, 5, 'Jahmon', '024988345', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-JA66032', NULL, '2025-01-23 16:06:20', 'Samuel Osei Adu'),
(49, 5, 'Sam', '02498905445', 60.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-SA66258', NULL, '2025-01-23 16:07:09', 'Samuel Osei Adu'),
(50, 5, 'Zara', '02984908245', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-ZA75582', NULL, '2025-01-23 16:08:55', 'Samuel Osei Adu'),
(51, 5, 'Jacobina', '0248903453', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-JA93237', NULL, '2025-01-24 19:25:39', 'Samuel Osei Adu'),
(56, 5, 'Gambi', '043872985', 90.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-GA12027', NULL, '2025-01-24 21:49:01', 'Samuel Osei Adu'),
(57, 5, 'Loving', '0398289435', 100.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-LO33762', NULL, '2025-01-24 21:57:51', 'Samuel Osei Adu'),
(58, 8, 'Blessing', '0398478934', 20.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-BL18913', NULL, '2025-01-25 15:30:55', 'Grace Gambi'),
(59, 8, 'Emman', '03398740934', 10.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-EM24289', NULL, '2025-01-25 15:32:57', 'Grace Gambi'),
(60, 8, 'Kofi', '0245693784', 80.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-KO95174', NULL, '2025-01-25 15:36:42', 'Grace Gambs'),
(61, 8, 'Kwame', '03984094', 80.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-KW46881', NULL, '2025-01-25 15:37:11', 'Grace Gambs'),
(62, 5, 'Anas', '025309844', 70.00, NULL, 0.00, 'cash', 'paid', 'completed', 'INV-AN24965', NULL, '2025-01-25 15:57:53', 'Samuel Osei Adu');

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

--
-- Dumping data for table `order_history`
--

INSERT INTO `order_history` (`id`, `order_id`, `user_id`, `action`, `previous_status`, `new_status`, `notes`, `created_at`, `created_by`) VALUES
(26, 35, 5, 'order_created', NULL, 'completed', NULL, '2025-01-21 22:30:42', ''),
(27, 36, 5, 'order_created', NULL, 'completed', NULL, '2025-01-21 23:11:13', ''),
(28, 37, 5, 'order_created', NULL, 'completed', NULL, '2025-01-22 09:03:27', ''),
(29, 38, 5, 'order_created', NULL, 'completed', NULL, '2025-01-22 12:07:17', ''),
(30, 39, 5, 'order_created', NULL, 'completed', NULL, '2025-01-23 09:07:57', ''),
(31, 40, 5, 'order_created', NULL, 'completed', NULL, '2025-01-23 11:14:24', ''),
(32, 46, 7, 'order_created', NULL, 'completed', NULL, '2025-01-23 15:07:24', 'Tater Jonathan'),
(33, 47, 7, 'order_created', NULL, 'completed', NULL, '2025-01-23 15:11:14', 'Tater Jonathan'),
(34, 48, 5, 'order_created', NULL, 'completed', NULL, '2025-01-23 16:06:20', 'Samuel Osei Adu'),
(35, 49, 5, 'order_created', NULL, 'completed', NULL, '2025-01-23 16:07:09', 'Samuel Osei Adu'),
(36, 50, 5, 'order_created', NULL, 'completed', NULL, '2025-01-23 16:08:55', 'Samuel Osei Adu'),
(37, 51, 5, 'order_created', NULL, 'completed', NULL, '2025-01-24 19:25:39', 'Samuel Osei Adu'),
(38, 56, 5, 'order_created', NULL, 'completed', NULL, '2025-01-24 21:49:01', 'Samuel Osei Adu'),
(39, 57, 5, 'order_created', NULL, 'completed', NULL, '2025-01-24 21:57:51', 'Samuel Osei Adu'),
(40, 58, 8, 'order_created', NULL, 'completed', NULL, '2025-01-25 15:30:55', 'Grace Gambi'),
(41, 59, 8, 'order_created', NULL, 'completed', NULL, '2025-01-25 15:32:57', 'Grace Gambi'),
(42, 60, 8, 'order_created', NULL, 'completed', NULL, '2025-01-25 15:36:42', 'Grace Gambs'),
(43, 61, 8, 'order_created', NULL, 'completed', NULL, '2025-01-25 15:37:11', 'Grace Gambs'),
(44, 62, 5, 'order_created', NULL, 'completed', NULL, '2025-01-25 15:57:53', 'Samuel Osei Adu');

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

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `created_by`) VALUES
(60, 35, 21, 1, 60.00, 60.00, '2025-01-21 22:30:42', ''),
(61, 35, 32, 1, 10.00, 10.00, '2025-01-21 22:30:42', ''),
(62, 36, 21, 1, 60.00, 60.00, '2025-01-21 23:11:13', ''),
(63, 37, 21, 1, 60.00, 60.00, '2025-01-22 09:03:27', ''),
(64, 37, 33, 1, 10.00, 10.00, '2025-01-22 09:03:27', ''),
(65, 38, 21, 1, 60.00, 60.00, '2025-01-22 12:07:17', ''),
(66, 38, 32, 1, 10.00, 10.00, '2025-01-22 12:07:17', ''),
(67, 39, 61, 1, 60.00, 60.00, '2025-01-23 09:07:57', ''),
(68, 40, 21, 1, 60.00, 60.00, '2025-01-23 11:14:24', ''),
(69, 46, 21, 1, 60.00, 60.00, '2025-01-23 15:07:24', '0'),
(70, 46, 53, 1, 10.00, 10.00, '2025-01-23 15:07:24', '0'),
(71, 47, 61, 1, 60.00, 60.00, '2025-01-23 15:11:14', 'Tater Jonathan'),
(72, 48, 61, 1, 60.00, 60.00, '2025-01-23 16:06:20', 'Samuel Osei Adu'),
(73, 49, 61, 1, 60.00, 60.00, '2025-01-23 16:07:09', 'Samuel Osei Adu'),
(74, 50, 61, 1, 60.00, 60.00, '2025-01-23 16:08:55', 'Samuel Osei Adu'),
(75, 50, 54, 1, 10.00, 10.00, '2025-01-23 16:08:55', 'Samuel Osei Adu'),
(76, 51, 30, 1, 60.00, 60.00, '2025-01-24 19:25:39', 'Samuel Osei Adu'),
(77, 51, 53, 1, 10.00, 10.00, '2025-01-24 19:25:39', 'Samuel Osei Adu'),
(78, 56, 23, 1, 80.00, 80.00, '2025-01-24 21:49:01', 'Samuel Osei Adu'),
(79, 56, 32, 1, 10.00, 10.00, '2025-01-24 21:49:01', 'Samuel Osei Adu'),
(80, 57, 23, 1, 80.00, 80.00, '2025-01-24 21:57:51', 'Samuel Osei Adu'),
(81, 57, 32, 1, 10.00, 10.00, '2025-01-24 21:57:51', 'Samuel Osei Adu'),
(82, 57, 53, 1, 10.00, 10.00, '2025-01-24 21:57:51', 'Samuel Osei Adu'),
(83, 58, 31, 1, 10.00, 10.00, '2025-01-25 15:30:55', 'Grace Gambi'),
(84, 58, 53, 1, 10.00, 10.00, '2025-01-25 15:30:55', 'Grace Gambi'),
(85, 59, 32, 1, 10.00, 10.00, '2025-01-25 15:32:57', 'Grace Gambi'),
(86, 60, 32, 1, 10.00, 10.00, '2025-01-25 15:36:42', 'Grace Gambs'),
(87, 60, 52, 1, 10.00, 10.00, '2025-01-25 15:36:42', 'Grace Gambs'),
(88, 60, 61, 1, 60.00, 60.00, '2025-01-25 15:36:42', 'Grace Gambs'),
(89, 61, 32, 1, 10.00, 10.00, '2025-01-25 15:37:11', 'Grace Gambs'),
(90, 61, 52, 1, 10.00, 10.00, '2025-01-25 15:37:11', 'Grace Gambs'),
(91, 61, 61, 1, 60.00, 60.00, '2025-01-25 15:37:11', 'Grace Gambs'),
(92, 62, 21, 1, 60.00, 60.00, '2025-01-25 15:57:53', 'Samuel Osei Adu'),
(93, 62, 32, 1, 10.00, 10.00, '2025-01-25 15:57:53', 'Samuel Osei Adu');

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
(21, '1', 'Fruit Tea Series', 'Cocktail Boba', '60', 'Medium', '2025-01-21 10:59:25', NULL, 'uploads/products/6791955b628cf.png'),
(22, '1', 'Fruit Tea Series', 'Mango', '60', 'Medium', '2025-01-21 11:03:13', NULL, 'uploads/products/6791957060134.png'),
(23, '1', 'Fruit Tea Series', 'Iced Tea', '80', 'Large', '2025-01-21 11:22:10', NULL, 'uploads/products/67920b11d2635.png'),
(24, '1', 'Fruit Tea Series', 'Pineapple Boba', '80', 'Large', '2025-01-21 11:23:02', NULL, 'uploads/products/6795081836e2a.png'),
(25, '1', 'Fruit Tea Series', 'Rose', '80', 'Large', '2025-01-21 11:24:42', NULL, NULL),
(26, '1', 'Fruit Tea Series', 'Cantaloupe', '60', 'Medium', '2025-01-21 11:41:09', NULL, NULL),
(27, '1', 'Fruit Tea Series', 'Lemon', '60', 'Medium', '2025-01-21 11:41:36', NULL, 'uploads/products/6795083f7c1fd.png'),
(28, '1', 'Fruit Tea Series', 'Blue Orange', '60', 'Medium', '2025-01-21 11:42:00', NULL, NULL),
(29, '1', 'Fruit Tea Series', 'Vanilla', '60', 'Medium', '2025-01-21 11:42:25', NULL, NULL),
(30, '1', 'Fruit Tea Series', 'Mint', '60', 'Medium', '2025-01-21 11:42:45', NULL, NULL),
(31, '2', 'Poppin Boba Series', 'Strawberry Popping', '10', 'Medium', '2025-01-21 11:47:23', NULL, NULL),
(32, '2', 'Poppin Boba Series', 'Blueberry Popping', '10', 'Medium', '2025-01-21 11:47:58', NULL, NULL),
(33, '2', 'Poppin Boba Series', 'Orange Popping', '10', 'Medium', '2025-01-21 11:48:13', NULL, NULL),
(34, '2', 'Poppin Boba Series', 'Yogurt Popping', '10', 'Medium', '2025-01-21 11:48:41', NULL, NULL),
(35, '2', 'Poppin Boba Series', 'Passion Fruit Popping', '10', 'Medium', '2025-01-21 11:49:03', NULL, NULL),
(36, '3', 'Milk Tea Series', 'Taro Milk', '60', 'Medium', '2025-01-22 16:26:58', NULL, NULL),
(37, '3', 'Milk Tea Series', 'Blue Berry Milk', '60', 'Medium', '2025-01-22 16:27:58', NULL, NULL),
(38, '3', 'Milk Tea Series', 'Vanilla Milk', '80', 'Large', '2025-01-22 16:42:58', NULL, NULL),
(40, '3', 'Milk Tea Series', 'Strawberry Milk', '60', 'Medium', '2025-01-22 16:45:42', NULL, NULL),
(41, '3', 'Milk Tea Series', 'Chocolate Milk', '60', 'Medium', '2025-01-22 16:46:00', NULL, NULL),
(42, '3', 'Milk Tea Series', 'Pineapple Milk', '60', 'Medium', '2025-01-22 16:47:05', NULL, NULL),
(43, '3', 'Milk Tea Series', 'Coconut Milk', '80', 'Large', '2025-01-22 16:47:23', NULL, NULL),
(44, '3', 'Milk Tea Series', 'Original Milk Tea', '80', 'Large', '2025-01-22 16:48:26', NULL, NULL),
(45, '3', 'Milk Tea Series', 'Banana Milk', '60', 'Medium', '2025-01-22 16:48:52', NULL, NULL),
(46, '3', 'Milk Tea Series', 'Matcha Milk', '60', 'Medium', '2025-01-22 16:49:18', NULL, NULL),
(47, '3', 'Milk Tea Series', 'Melon Milk', '60', 'Medium', '2025-01-22 16:49:47', NULL, NULL),
(48, '3', 'Milk Tea Series', 'Oreo Milk', '60', 'Medium', '2025-01-22 16:50:21', NULL, NULL),
(49, '3', 'Milk Tea Series', 'Lotus Biscoff Caramel', '80', 'Large', '2025-01-22 16:50:41', NULL, NULL),
(50, '3', 'Milk Tea Series', 'Malta Flash Milk', '60', 'Medium', '2025-01-22 16:51:04', NULL, NULL),
(51, '4', 'Toppings', 'Cheese Bomb', '10', 'Medium', '2025-01-22 16:54:15', NULL, NULL),
(52, '4', 'Toppings', 'Hersheys Caramel Syrup', '10', 'Medium', '2025-01-22 16:55:09', NULL, NULL),
(53, '4', 'Toppings', 'Brown sugar syrup', '10', 'Medium', '2025-01-22 16:55:44', NULL, NULL),
(54, '4', 'Toppings', 'Nutella', '10', 'Medium', '2025-01-22 16:56:03', NULL, NULL),
(55, '4', 'Toppings', 'Mango Syrup', '10', 'Medium', '2025-01-22 16:56:27', NULL, NULL),
(56, '4', 'Toppings', 'Passion Fruit', '10', 'Medium', '2025-01-22 16:56:54', NULL, NULL),
(57, '4', 'Toppings', 'Mango', '10', 'Medium', '2025-01-22 17:00:32', NULL, NULL),
(58, '4', 'Toppings', 'Strawberry', '10', 'Medium', '2025-01-22 17:01:36', NULL, NULL),
(59, '4', 'Toppings', 'Brown Chocolate', '10', 'Medium', '2025-01-22 17:02:13', NULL, NULL),
(60, '4', 'Toppings', 'Brown Sugar Boba', '80', 'Large', '2025-01-22 17:02:30', NULL, NULL),
(61, '5', 'Boba Pearls', 'Boba Pearls', '60', 'Medium', '2025-01-22 17:03:21', NULL, NULL);

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
(11, 'Large', '1', '80'),
(12, 'Large', '23', '50');

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
  `is_sysadmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `phone`, `username`, `password`, `fullname`, `role`, `status`, `last_login`, `created_at`, `updated_at`, `image`, `is_sysadmin`) VALUES
(5, 'addsamuel355@gmail.com', '0246562377', 'samuelos', '$2y$10$lELYL5GH8TZonjHqGmhfpuXp5FB0gzPyHgA4v9/JwLghtKnYTrzxe', 'Samuel Osei Adu', 'admin', 'active', '2025-01-25 15:57:26', '2025-01-17 10:37:32', '2025-01-22', 'uploads/profile_images/678a32eca0f48.jpg', 1),
(7, 'tater@gmail.com', '0246578988', 'taterjon', '$2y$10$2gbK2DxWCJYaEvJnMazc6uRcU/NJ.OWuY14Z9Hx/Vxj2e58x1jTd.', 'Tater Jonathan', 'cashier', 'active', '2025-01-25 12:44:55', '2025-01-22 12:06:04', '2025-01-23', 'uploads/profile_images/6790df2c09fd0.jpg', 0),
(8, 'grace@gmail.com', '034983987943', 'gracegam', '$2y$10$M4hDABM8pW1vnkUdoPTQRuGFb6hBRbsCO/HvBqTgLIjvu5GWIuP.u', 'Grace Gambs', 'admin', 'active', '2025-01-25 15:34:12', '2025-01-25 11:52:12', '2025-01-25', NULL, 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=297;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `order_history`
--
ALTER TABLE `order_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `product_pricing`
--
ALTER TABLE `product_pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
