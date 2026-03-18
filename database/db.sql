-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 04:32 AM
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
-- Database: `e-commerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `created_at`, `role`) VALUES
(1, 'Anil Shrestha', 'hello@anil.com', 'password123', '2025-12-24 19:07:05', 'admin'),
(13, 'Nirmal', 'nirmal@gmail.com', '123456789', '2026-03-17 06:29:11', 'editor');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `register_user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `register_user_id`, `created_at`, `updated_at`) VALUES
(3, 41, '2026-02-25 10:23:06', NULL),
(18, 43, '2026-03-17 07:19:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `register_user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','cancelled','delivered') NOT NULL DEFAULT 'pending',
  `street` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `zip_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'Nepal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `register_user_id`, `total_amount`, `status`, `street`, `city`, `state`, `zip_code`, `country`, `created_at`, `updated_at`) VALUES
(14, 41, 15000.00, 'confirmed', NULL, NULL, NULL, NULL, 'Nepal', '2026-02-25 10:23:44', NULL),
(15, 41, 135000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-02-25 10:24:08', NULL),
(16, 41, 215000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-02-25 16:58:09', NULL),
(17, 41, 5000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-02-26 05:51:57', NULL),
(18, 41, 5000.00, 'confirmed', NULL, NULL, NULL, NULL, 'Nepal', '2026-02-26 05:52:43', NULL),
(19, 41, 270000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-11 15:22:29', NULL),
(20, 41, 240000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-16 14:48:42', NULL),
(21, 41, 80000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-17 07:18:36', NULL),
(22, 43, 275000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-17 07:41:08', NULL),
(23, 41, 5000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-17 07:55:52', NULL),
(24, 41, 80000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-17 07:56:52', NULL),
(25, 41, 5000.00, 'pending', 'vkjzsjs', 'ksjdks', 'Bagmati', 'ggg', 'Nepal', '2026-03-17 08:00:51', NULL),
(26, 43, 55000.00, 'pending', 'Jadibuttte', 'Kathmandu', 'Bagmati', '4450', 'Nepal', '2026-03-17 08:04:51', NULL),
(27, 41, 5000.00, 'pending', '11', '11', 'Bagmati', '', 'Nepal', '2026-03-17 08:11:50', NULL),
(28, 41, 5000.00, 'confirmed', '', '', '', '', 'Nepal', '2026-03-17 08:19:37', NULL),
(29, 41, 5000.00, 'confirmed', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 08:41:33', NULL),
(30, 41, 55000.00, 'pending', 'khurkot', 'sindhuli', 'Bagmati', '', 'Nepal', '2026-03-17 08:54:19', NULL),
(31, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 09:04:00', NULL),
(32, 41, 5000.00, 'pending', 'sungure', 'sindhuli', 'Bagmati', '', 'Nepal', '2026-03-17 09:06:12', NULL),
(33, 41, 55000.00, 'pending', 'darbar', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 09:07:43', NULL),
(34, 41, 85000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 09:12:47', NULL),
(35, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 09:21:56', NULL),
(36, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 17:46:10', NULL),
(37, 41, 5000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 17:48:04', NULL),
(38, 43, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 18:02:11', NULL),
(39, 43, 55000.00, 'pending', 'baneshwor', 'kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 18:15:43', NULL),
(40, 41, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:09:22', NULL),
(41, 43, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:10:34', NULL),
(42, 41, 75000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:21:23', NULL),
(43, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:25:52', NULL),
(44, 41, 40000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:26:50', NULL),
(45, 41, 40000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:27:52', NULL),
(46, 41, 75000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:29:15', NULL),
(47, 41, 100000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:30:07', NULL),
(48, 41, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:34:59', NULL),
(49, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:43:40', NULL),
(50, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:44:18', NULL),
(51, 41, 140000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:48:01', NULL),
(52, 41, 135000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:48:22', NULL),
(53, 41, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 19:51:17', NULL),
(54, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 20:38:54', NULL),
(55, 41, 55000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 20:52:22', NULL),
(56, 41, 155000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-17 20:58:50', NULL),
(57, 41, 55000.00, 'pending', '', '', '', '', 'Nepal', '2026-03-17 21:26:59', NULL),
(58, 41, 5000.00, 'pending', '', '', '', '', 'Nepal', '2026-03-17 21:29:14', NULL),
(59, 41, 135000.00, 'pending', '', '', '', '', 'Nepal', '2026-03-18 01:44:33', NULL),
(60, 41, 175000.00, 'pending', '', '', '', '', 'Nepal', '2026-03-18 01:45:45', NULL),
(61, 41, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 01:51:58', NULL),
(62, 41, 120000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 01:55:10', NULL),
(63, 41, 100000.00, 'pending', 'sungure', 'sindhuli', 'Bagmati', '', 'Nepal', '2026-03-18 01:58:49', NULL),
(64, 41, 75000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 02:01:17', NULL),
(65, 41, 135000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-18 02:08:10', NULL),
(66, 41, 55000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-18 02:15:44', NULL),
(67, 41, 80000.00, 'pending', NULL, NULL, NULL, NULL, 'Nepal', '2026-03-18 02:35:00', NULL),
(68, 41, 135000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 02:37:35', NULL),
(69, 41, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 02:55:06', NULL),
(70, 41, 135000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 03:01:53', NULL),
(71, 41, 160000.00, 'pending', 'sungure', 'sindhuli', 'Bagmati', '', 'Nepal', '2026-03-18 03:02:35', NULL),
(72, 41, 5000.00, 'confirmed', 'khurkot', 'sindhuli', 'Bagmati', '', 'Nepal', '2026-03-18 03:06:18', NULL),
(73, 43, 155000.00, 'pending', 'madhutar', 'sindhuli', 'Bagmati', '', 'Nepal', '2026-03-18 03:15:30', NULL),
(74, 43, 60000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 03:16:26', NULL),
(75, 43, 80000.00, 'pending', 'Koteswor', 'Kathmandu', 'Bagmati', '', 'Nepal', '2026-03-18 03:16:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(15, 14, 23, 3, 5000.00, '2026-02-25 10:23:44', NULL),
(16, 15, 25, 1, 80000.00, '2026-02-25 10:24:08', NULL),
(17, 15, 24, 1, 55000.00, '2026-02-25 10:24:08', NULL),
(18, 16, 28, 1, 75000.00, '2026-02-25 16:58:09', NULL),
(19, 16, 23, 1, 5000.00, '2026-02-25 16:58:09', NULL),
(20, 16, 24, 1, 55000.00, '2026-02-25 16:58:09', NULL),
(21, 16, 25, 1, 80000.00, '2026-02-25 16:58:09', NULL),
(22, 17, 23, 1, 5000.00, '2026-02-26 05:51:57', NULL),
(23, 19, 24, 2, 55000.00, '2026-03-11 15:22:29', NULL),
(24, 19, 25, 2, 80000.00, '2026-03-11 15:22:29', NULL),
(25, 20, 25, 2, 80000.00, '2026-03-16 14:48:42', NULL),
(26, 20, 29, 1, 80000.00, '2026-03-16 14:48:42', NULL),
(27, 21, 23, 1, 5000.00, '2026-03-17 07:18:36', NULL),
(28, 21, 28, 1, 75000.00, '2026-03-17 07:18:36', NULL),
(29, 22, 23, 1, 5000.00, '2026-03-17 07:41:08', NULL),
(30, 22, 24, 2, 55000.00, '2026-03-17 07:41:08', NULL),
(31, 22, 29, 1, 80000.00, '2026-03-17 07:41:08', NULL),
(32, 22, 25, 1, 80000.00, '2026-03-17 07:41:08', NULL),
(33, 23, 23, 1, 5000.00, '2026-03-17 07:55:52', NULL),
(34, 24, 25, 1, 80000.00, '2026-03-17 07:56:52', NULL),
(35, 25, 23, 1, 5000.00, '2026-03-17 08:00:51', NULL),
(36, 26, 24, 1, 55000.00, '2026-03-17 08:04:51', NULL),
(37, 27, 23, 1, 5000.00, '2026-03-17 08:11:50', NULL),
(38, 28, 23, 1, 5000.00, '2026-03-17 08:19:37', NULL),
(39, 29, 23, 1, 5000.00, '2026-03-17 08:41:33', NULL),
(40, 30, 24, 1, 55000.00, '2026-03-17 08:54:19', NULL),
(41, 31, 24, 1, 55000.00, '2026-03-17 09:04:00', NULL),
(42, 32, 23, 1, 5000.00, '2026-03-17 09:06:12', NULL),
(43, 33, 24, 1, 55000.00, '2026-03-17 09:07:43', NULL),
(44, 34, 27, 1, 85000.00, '2026-03-17 09:12:47', NULL),
(45, 35, 24, 1, 55000.00, '2026-03-17 09:21:56', NULL),
(46, 36, 24, 1, 55000.00, '2026-03-17 17:46:10', NULL),
(47, 37, 23, 1, 5000.00, '2026-03-17 17:48:04', NULL),
(48, 38, 24, 1, 55000.00, '2026-03-17 18:02:11', NULL),
(49, 39, 24, 1, 55000.00, '2026-03-17 18:15:44', NULL),
(50, 40, 25, 1, 80000.00, '2026-03-17 19:09:22', NULL),
(51, 41, 25, 1, 80000.00, '2026-03-17 19:10:34', NULL),
(52, 42, 28, 1, 75000.00, '2026-03-17 19:21:23', NULL),
(53, 43, 24, 1, 55000.00, '2026-03-17 19:25:52', NULL),
(54, 44, 26, 1, 40000.00, '2026-03-17 19:26:51', NULL),
(55, 45, 26, 1, 40000.00, '2026-03-17 19:27:52', NULL),
(56, 46, 28, 1, 75000.00, '2026-03-17 19:29:15', NULL),
(57, 47, 30, 1, 100000.00, '2026-03-17 19:30:08', NULL),
(58, 48, 25, 1, 80000.00, '2026-03-17 19:34:59', NULL),
(59, 49, 24, 1, 55000.00, '2026-03-17 19:43:40', NULL),
(60, 50, 24, 1, 55000.00, '2026-03-17 19:44:18', NULL),
(61, 51, 25, 1, 80000.00, '2026-03-17 19:48:01', NULL),
(62, 51, 23, 1, 5000.00, '2026-03-17 19:48:01', NULL),
(63, 51, 24, 1, 55000.00, '2026-03-17 19:48:01', NULL),
(64, 52, 24, 1, 55000.00, '2026-03-17 19:48:22', NULL),
(65, 52, 25, 1, 80000.00, '2026-03-17 19:48:22', NULL),
(66, 53, 29, 1, 80000.00, '2026-03-17 19:51:17', NULL),
(67, 54, 24, 1, 55000.00, '2026-03-17 20:38:54', NULL),
(68, 55, 24, 1, 55000.00, '2026-03-17 20:52:22', NULL),
(69, 56, 28, 1, 75000.00, '2026-03-17 20:58:50', NULL),
(70, 56, 29, 1, 80000.00, '2026-03-17 20:58:50', NULL),
(71, 57, 24, 1, 55000.00, '2026-03-17 21:26:59', NULL),
(72, 58, 23, 1, 5000.00, '2026-03-17 21:29:14', NULL),
(73, 59, 24, 1, 55000.00, '2026-03-18 01:44:33', NULL),
(74, 59, 29, 1, 80000.00, '2026-03-18 01:44:33', NULL),
(75, 60, 28, 1, 75000.00, '2026-03-18 01:45:45', NULL),
(76, 60, 30, 1, 100000.00, '2026-03-18 01:45:45', NULL),
(77, 61, 29, 1, 80000.00, '2026-03-18 01:51:58', NULL),
(78, 62, 25, 1, 80000.00, '2026-03-18 01:55:10', NULL),
(79, 62, 26, 1, 40000.00, '2026-03-18 01:55:10', NULL),
(80, 63, 30, 1, 100000.00, '2026-03-18 01:58:49', NULL),
(81, 64, 28, 1, 75000.00, '2026-03-18 02:01:17', NULL),
(82, 65, 24, 1, 55000.00, '2026-03-18 02:08:10', NULL),
(83, 65, 29, 1, 80000.00, '2026-03-18 02:08:10', NULL),
(84, 66, 24, 1, 55000.00, '2026-03-18 02:15:44', NULL),
(85, 67, 29, 1, 80000.00, '2026-03-18 02:35:00', NULL),
(86, 68, 24, 1, 55000.00, '2026-03-18 02:37:35', NULL),
(87, 68, 25, 1, 80000.00, '2026-03-18 02:37:35', NULL),
(88, 69, 29, 1, 80000.00, '2026-03-18 02:55:06', NULL),
(89, 70, 24, 1, 55000.00, '2026-03-18 03:01:53', NULL),
(90, 70, 29, 1, 80000.00, '2026-03-18 03:01:53', NULL),
(91, 71, 28, 1, 75000.00, '2026-03-18 03:02:35', NULL),
(92, 71, 25, 1, 80000.00, '2026-03-18 03:02:35', NULL),
(93, 71, 23, 1, 5000.00, '2026-03-18 03:02:35', NULL),
(94, 72, 23, 1, 5000.00, '2026-03-18 03:06:18', NULL),
(95, 73, 25, 1, 80000.00, '2026-03-18 03:15:30', NULL),
(96, 73, 28, 1, 75000.00, '2026-03-18 03:15:30', NULL),
(97, 74, 24, 1, 55000.00, '2026-03-18 03:16:26', NULL),
(98, 74, 23, 1, 5000.00, '2026-03-18 03:16:26', NULL),
(99, 75, 29, 1, 80000.00, '2026-03-18 03:16:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `created_at`) VALUES
(23, 'Dell Inspiron 15 3530 i5 13th Gen 16GB 512GB', 5000.00, '1766779890_Dell Inspiron 15 3530 i5 13th Gen 16GB 512GB.jpg', '2025-12-26 20:11:30'),
(24, 'DELL INSPIRON 15 RYZEN 5 5500U 8GB 512GB', 55000.00, '1766779928_DELL INSPIRON 15 RYZEN 5 5500U 8GB 512GB.jpg', '2025-12-26 20:12:08'),
(25, 'Dell Inspiron 15 5559', 80000.00, '1766779952_Dell Inspiron 15 5559.jpg', '2025-12-26 20:12:32'),
(26, 'Dell Inspiron 3501 ', 40000.00, '1766779988_Dell Inspiron 3501 Price in Nepal.jpg', '2025-12-26 20:13:08'),
(27, 'Buy Dell G15 5530 (13th Gen i7-13650HX, 16GB RAM', 85000.00, '1766780086_Buy Dell G15 5530 (13th Gen i7-13650HX, 16GB RAM.jpg', '2025-12-26 20:14:46'),
(28, 'Dell Inspiron 7430 i7 13th Generation (1355U)', 75000.00, '1766780133_Dell Inspiron 7430 i7 13th Generation (1355U.jpg', '2025-12-26 20:15:33'),
(29, 'Dell Inspiron 3520 best office laptop', 80000.00, '1766780173_Dell Inspiron 3520 best office laptop.jpg', '2025-12-26 20:16:13'),
(30, 'DELL LATITUDE 3320 INTEL CORE™ 17-11TH GEN', 100000.00, '1766780210_DELL LATITUDE 3320 INTEL CORE™ 17-11TH GEN.jpg', '2025-12-26 20:16:50'),
(31, 'Dell Inspiron 7430 i7 13th Generation (1355U', 85000.00, '1766780283_Dell Inspiron 7430 i7 13th Generation (1355U.jpg', '2025-12-26 20:18:03'),
(32, 'dell laptop i5', 66000.00, '1766780313_dell laptop i5.jpg', '2025-12-26 20:18:33'),
(33, 'Dell latitude 3440 i5 13th Laptop', 95000.00, '1766780361_Dell latitude 3440 i5 13th Laptop.jpg', '2025-12-26 20:19:21'),
(34, 'Dell Pro 14 Core 5 (PC14250)', 55000.00, '1766780394_Dell Pro 14 Core 5 (PC14250).jpg', '2025-12-26 20:19:54'),
(35, 'Dell Vosteo 15 3520(i7)', 88000.00, '1766780421_Dell Vosteo 15 3520(i7).jpg', '2025-12-26 20:20:21'),
(36, 'Buy Latest Dell 13th Gen Models', 77000.00, '1766780702_Buy Latest Dell 13th Gen Models.jpg', '2025-12-26 20:25:02'),
(37, 'Dell 16 Plus (2-in-1) Laptop With Ultra 7, 2k Touch', 110000.00, '1766780720_Dell 16 Plus (2-in-1) Laptop With Ultra 7, 2k Touch.jpg', '2025-12-26 20:25:20'),
(38, 'Dell Inspiron 15 3542 ', 45000.00, '1766780746_Dell Inspiron 15 3542 - Mero Laptop.jpg', '2025-12-26 20:25:46'),
(39, 'Dell Latitude 5450 Core Ultra 7 165U laptop', 88000.00, '1766780767_Dell Latitude 5450 Core Ultra 7 165U laptop.jpg', '2025-12-26 20:26:07'),
(40, 'Dell Latitude 7430', 99999.00, '1766780786_Dell Latitude 7430.jpg', '2025-12-26 20:26:26'),
(41, 'Dell XPS 13 7390', 500000.00, '1766780801_Dell XPS 13 7390.jpg', '2025-12-26 20:26:41'),
(42, 'Dell XPS 15 9560', 56000.00, '1766780830_Dell XPS 15 9560.jpg', '2025-12-26 20:27:10'),
(43, 'Dell 4s', 50000.00, '1766931982_Dell Inspiron 15 5559.jpg', '2025-12-28 14:26:22'),
(44, 'Dell dell', 60000.00, '1767277722_Dell 16 Plus (2-in-1) Laptop With Ultra 7, 2k Touch.jpg', '2026-01-01 14:28:42'),
(47, 'kj', 5000.00, '1772040315_coverlette.jpeg', '2026-02-25 17:25:15'),
(49, 'hmgcfh', 10000.00, '1773773330_WhatsApp Image 2026-03-15 at 21.33.11.jpeg', '2026-03-17 18:48:50');

-- --------------------------------------------------------

--
-- Table structure for table `register_user`
--

CREATE TABLE `register_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_user`
--

INSERT INTO `register_user` (`id`, `username`, `email`, `phone`, `password`, `created_at`) VALUES
(41, 'nben', 'hello@nben.com.np', '9742894313', '$2y$10$QO4GqomkKyx8KTFaR6PtE.WEQgQ4wWcKwd/LFB6rQz8XhvTOnXl6C', '2026-02-25 10:22:51'),
(43, 'pushpa', 'pushpa@gmail.com', '9873774744', '$2y$10$TJeM9Ixri16jITlfpZ2/HeKyRpoEOcX0mbVWqKun6zCbZq.xWBHI.', '2026-03-17 07:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `identifier` varchar(120) NOT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `identifier`, `status`, `note`, `created_at`, `updated_at`) VALUES
(11, 14, 'bc774214-b6eb-4613-ab88-054ba4606cf8', 'success', '{\"transaction_code\":\"000EB8X\",\"status\":\"COMPLETE\",\"total_amount\":\"15000.0\",\"transaction_uuid\":\"bc774214-b6eb-4613-ab88-054ba4606cf8\",\"product_code\":\"EPAYTEST\",\"signed_field_names\":\"transaction_code,status,total_amount,transaction_uuid,product_code,signed_field_names\",\"signature\":\"MBCCHvm5SEXCd\\/RzsN7UW9rDGByI9xNpGTh0\\/rMMEYk=\"}', '2026-02-25 10:23:44', NULL),
(12, 15, '132ed229-ba8c-47bb-9fe3-9e1b8ed4fc1f', 'pending', 'COD order', '2026-02-25 10:24:08', NULL),
(13, 16, '7e03b1d7-3f94-42c7-bd5b-b063e3a25896', 'pending', 'COD order', '2026-02-25 16:58:09', NULL),
(14, 17, '522d3057-9d5c-4c6f-b8fd-133b452aa0bc', 'pending', 'COD order', '2026-02-26 05:51:57', NULL),
(15, 18, '237d3cb4-685d-4fd6-a820-b78d2a27d983', 'success', '{\"transaction_code\":\"000EBLH\",\"status\":\"COMPLETE\",\"total_amount\":\"5000.0\",\"transaction_uuid\":\"237d3cb4-685d-4fd6-a820-b78d2a27d983\",\"product_code\":\"EPAYTEST\",\"signed_field_names\":\"transaction_code,status,total_amount,transaction_uuid,product_code,signed_field_names\",\"signature\":\"2ccIQf3UE7\\/MLIgy8WEBjI5rzR0vVLTGR8BCE2CSoD4=\"}', '2026-02-26 05:52:43', NULL),
(16, 19, '3133ec0e-753d-4bdc-8d57-e3a749fd957d', 'pending', 'COD order', '2026-03-11 15:22:29', NULL),
(17, 20, '8fe99a5e-97ad-457d-9b50-31eda81dc8f9', 'pending', 'COD order', '2026-03-16 14:48:42', NULL),
(18, 21, '06a7a61c-ca07-4cc5-ab21-b6c7b41a20f1', 'pending', 'COD order', '2026-03-17 07:18:36', NULL),
(19, 22, '70faeb4e-d2be-4cb0-b4d7-3a4c0aef6498', 'pending', 'COD order', '2026-03-17 07:41:08', NULL),
(20, 23, '10fdd82a-39f7-40a1-897c-f10069395d5d', 'pending', 'COD order', '2026-03-17 07:55:52', NULL),
(21, 24, '7c6b011b-e132-4ae5-ab32-8935782d7b1a', 'pending', 'COD order', '2026-03-17 07:56:52', NULL),
(22, 25, 'b724532a-3539-4d95-aea8-02ec08170454', 'pending', 'COD order', '2026-03-17 08:00:51', NULL),
(23, 26, 'cb8ca718-8254-407a-9a6a-fd3d65e6cce7', 'pending', 'COD order', '2026-03-17 08:04:51', NULL),
(24, 27, 'a4768daa-357f-48a8-89f7-703180307636', 'pending', 'COD order', '2026-03-17 08:11:50', NULL),
(25, 28, '93b0d589-f18f-431b-abdf-367bef1f0593', 'success', '{\"transaction_code\":\"000EH7U\",\"status\":\"COMPLETE\",\"total_amount\":\"5000.0\",\"transaction_uuid\":\"93b0d589-f18f-431b-abdf-367bef1f0593\",\"product_code\":\"EPAYTEST\",\"signed_field_names\":\"transaction_code,status,total_amount,transaction_uuid,product_code,signed_field_names\",\"signature\":\"QclFb5bTmF0uYHnUVZolvAR0zMOtsU2oPxZxllzu+Co=\"}', '2026-03-17 08:19:37', NULL),
(26, 29, '6edd44fd-5293-4754-9c3a-fd2c51d9ca53', 'success', '{\"transaction_code\":\"000EH7V\",\"status\":\"COMPLETE\",\"total_amount\":\"5000.0\",\"transaction_uuid\":\"6edd44fd-5293-4754-9c3a-fd2c51d9ca53\",\"product_code\":\"EPAYTEST\",\"signed_field_names\":\"transaction_code,status,total_amount,transaction_uuid,product_code,signed_field_names\",\"signature\":\"Vp9dhLd1ClJq8p22OnxEIRLSL5B10u1W6V7bIMy4jgo=\"}', '2026-03-17 08:41:33', NULL),
(27, 30, '1aa7deba-e694-4a64-984a-56ad600fdd66', 'pending', 'COD order', '2026-03-17 08:54:19', NULL),
(28, 31, 'd8191d6a-894e-48c2-bbf7-962862d2fa02', 'pending', 'COD order', '2026-03-17 09:04:00', NULL),
(29, 32, '522f3f2f-d3c0-4715-b74e-8f0fc6a71dbe', 'pending', 'COD order', '2026-03-17 09:06:12', NULL),
(30, 33, 'd4b9f1b6-6e89-4ced-a75b-568d9836aa0a', 'pending', 'COD order', '2026-03-17 09:07:43', NULL),
(31, 34, '4f1cfb8d-9d29-4af8-8bf4-b06eecf956d9', 'pending', 'COD order', '2026-03-17 09:12:47', NULL),
(32, 35, '75981ae3-9ede-48c1-bb28-816fbefbb165', 'pending', 'COD order', '2026-03-17 09:21:56', NULL),
(33, 36, 'e68e9f8e-e7f0-47b4-81a1-1c103a1c25f9', 'pending', 'COD order', '2026-03-17 17:46:10', NULL),
(34, 37, '9828d661-46f2-44be-91fc-18d0b359fa9d', 'pending', 'COD order', '2026-03-17 17:48:04', NULL),
(35, 38, '5218c27e-2093-4d1c-accc-8ce0ff7579f1', 'pending', 'COD order', '2026-03-17 18:02:11', NULL),
(36, 39, '5da94bf6-e807-4e7d-b5ba-33686538480e', 'pending', 'COD order', '2026-03-17 18:15:44', NULL),
(37, 40, '560cf8d5-743d-467a-86bf-663c0864ecea', 'pending', 'COD order', '2026-03-17 19:09:22', NULL),
(38, 41, '9f2f4f2e-a3c3-42eb-a05d-3829ed5941b8', 'pending', 'COD order', '2026-03-17 19:10:34', NULL),
(39, 42, '13f2b2fa-9139-4dd9-aa3c-1f040c192e7d', 'pending', 'COD order', '2026-03-17 19:21:23', NULL),
(40, 43, 'ca06be87-e947-4ee3-bc19-210dbb07b701', 'pending', 'COD order', '2026-03-17 19:25:52', NULL),
(41, 44, 'ca72c9a6-8524-46e6-b091-9245fa21406d', 'pending', 'COD order', '2026-03-17 19:26:51', NULL),
(42, 45, 'b3b91362-557e-42d9-8932-76589cf384c2', 'pending', 'COD order', '2026-03-17 19:27:52', NULL),
(43, 46, 'e2bbb6d2-6db5-427c-bff9-6c2a5418763f', 'pending', 'COD order', '2026-03-17 19:29:15', NULL),
(44, 47, '70acd9ce-1c0c-412b-988e-810036682b41', 'pending', 'COD order', '2026-03-17 19:30:08', NULL),
(45, 48, '50f88bf8-91de-4e5b-bbe8-3d89943af82b', 'pending', 'COD order', '2026-03-17 19:34:59', NULL),
(46, 49, '29141c30-f18f-4e12-b695-6f4fbca2e53b', 'pending', 'COD order', '2026-03-17 19:43:40', NULL),
(47, 50, '9c1bed4e-51b5-475a-9820-e475f3c2c094', 'pending', 'COD order', '2026-03-17 19:44:18', NULL),
(48, 51, 'd08bf1d9-2ded-4608-8473-cdb65ccdaee5', 'pending', 'COD order', '2026-03-17 19:48:01', NULL),
(49, 52, '92ae5949-45f4-4287-819d-b50a4c629cb2', 'pending', 'COD order', '2026-03-17 19:48:22', NULL),
(50, 53, '2e4236a4-376e-42ca-9c20-86a7793b32ad', 'pending', 'COD order', '2026-03-17 19:51:17', NULL),
(51, 54, 'f5b1e5bd-f00e-4d35-adee-8392409e6412', 'pending', 'COD order', '2026-03-17 20:38:54', NULL),
(52, 55, '989db3a2-8d29-4a8d-91c8-2bc963e8ac44', 'pending', 'COD order', '2026-03-17 20:52:22', NULL),
(53, 56, '7216a367-d2de-4cb9-93fa-7e55d9ba13a9', 'pending', 'COD order', '2026-03-17 20:58:50', NULL),
(54, 57, '2b75610e-2f8e-4733-b9dc-c78d6f53462f', 'pending', 'COD order', '2026-03-17 21:26:59', NULL),
(55, 58, '13aa9cbf-c1e7-4722-b08c-814ea3198a7c', 'pending', 'COD order', '2026-03-17 21:29:15', NULL),
(56, 59, '6155d3ca-c67d-4e5a-aa74-4d7b2c98c125', 'pending', 'COD order', '2026-03-18 01:44:33', NULL),
(57, 60, 'cd53764d-b8aa-426c-af4d-6b9328f9ebb5', 'pending', 'COD order', '2026-03-18 01:45:45', NULL),
(58, 61, '69eb5505-d3bd-4963-9d9a-8075a863e7df', 'pending', 'COD order', '2026-03-18 01:51:58', NULL),
(59, 62, '1bdd0479-d0fd-459e-96c7-210d1d2e9fa0', 'pending', 'COD order', '2026-03-18 01:55:10', NULL),
(60, 63, 'da0b3412-07a6-4f93-ac5b-cc5f8075d2bf', 'pending', 'COD order', '2026-03-18 01:58:49', NULL),
(61, 64, '37e998b0-e876-48df-836d-f4e49c7c8c60', 'pending', 'COD order', '2026-03-18 02:01:17', NULL),
(62, 65, 'ab39e340-394f-4f67-b7b8-aecb5cf25389', 'pending', 'COD order', '2026-03-18 02:08:10', NULL),
(63, 66, 'b0c53603-c534-468a-935c-7623fc9c6ec8', 'pending', 'COD order', '2026-03-18 02:15:44', NULL),
(64, 67, 'bee17a38-79e3-4a06-b9cc-e315218556ee', 'pending', 'COD order', '2026-03-18 02:35:00', NULL),
(65, 68, '5227a9aa-b581-47aa-b12d-ac285adf8301', 'pending', 'COD order', '2026-03-18 02:37:35', NULL),
(66, 69, 'e895e1ac-f754-48f9-af46-221f71353c0d', 'pending', 'COD order', '2026-03-18 02:55:06', NULL),
(67, 70, '38ffe177-5ae9-4bd7-b2e3-c24209c1b40e', 'pending', 'COD order', '2026-03-18 03:01:53', NULL),
(68, 71, '4c88219b-8050-43e2-b16a-a363b84754de', 'pending', 'COD order', '2026-03-18 03:02:35', NULL),
(69, 72, '5696c01e-0a08-4b1a-bde7-3062661a6c50', 'success', '{\"transaction_code\":\"000EIAC\",\"status\":\"COMPLETE\",\"total_amount\":\"5000.0\",\"transaction_uuid\":\"5696c01e-0a08-4b1a-bde7-3062661a6c50\",\"product_code\":\"EPAYTEST\",\"signed_field_names\":\"transaction_code,status,total_amount,transaction_uuid,product_code,signed_field_names\",\"signature\":\"CQLjxK2IOpe5sYLKDEgPp53fo1+R7gqvsz+1thTtatM=\"}', '2026-03-18 03:06:18', NULL),
(70, 73, '762a8d53-6770-4f5a-a233-74293f0c6a5e', 'pending', 'COD order', '2026-03-18 03:15:30', NULL),
(71, 74, 'ddbf1a54-184e-49e2-8b9c-cfa28f37af10', 'pending', 'COD order', '2026-03-18 03:16:26', NULL),
(72, 75, 'd424c56c-5d5b-438a-8108-b8bbe4e23ffb', 'pending', 'COD order', '2026-03-18 03:16:51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_cart` (`register_user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart_product` (`cart_id`,`product_id`),
  ADD KEY `fk_cart_items_product` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_register_user` (`register_user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `register_user`
--
ALTER TABLE `register_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_identifier` (`identifier`),
  ADD KEY `fk_transactions_order` (`order_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `register_user`
--
ALTER TABLE `register_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_register_user` FOREIGN KEY (`register_user_id`) REFERENCES `register_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_register_user` FOREIGN KEY (`register_user_id`) REFERENCES `register_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
