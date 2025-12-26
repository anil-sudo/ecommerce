-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 10:15 PM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `name`, `image`, `quantity`, `added_on`, `price`) VALUES
(27, 'Dell Inspiron 15 3530 i5 13th Gen 16GB 512GB', '1766779890_Dell Inspiron 15 3530 i5 13th Gen 16GB 512GB.jpg', 1, '2025-12-26 20:21:07', 50000.00),
(28, 'DELL INSPIRON 15 RYZEN 5 5500U 8GB 512GB', '1766779928_DELL INSPIRON 15 RYZEN 5 5500U 8GB 512GB.jpg', 2, '2025-12-26 20:21:08', 550000.00),
(29, 'Dell Inspiron 15 5559', '1766779952_Dell Inspiron 15 5559.jpg', 1, '2025-12-26 20:33:18', 80000.00),
(30, 'Dell Inspiron 7430 i7 13th Generation (1355U)', '1766780133_Dell Inspiron 7430 i7 13th Generation (1355U.jpg', 1, '2025-12-26 20:33:21', 75000.00),
(31, 'Dell latitude 3440 i5 13th Laptop', '1766780361_Dell latitude 3440 i5 13th Laptop.jpg', 1, '2025-12-26 20:33:23', 95000.00);

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
(23, 'Dell Inspiron 15 3530 i5 13th Gen 16GB 512GB', 50000.00, '1766779890_Dell Inspiron 15 3530 i5 13th Gen 16GB 512GB.jpg', '2025-12-26 20:11:30'),
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
(41, 'Dell XPS 13 7390', 66000.00, '1766780801_Dell XPS 13 7390.jpg', '2025-12-26 20:26:41'),
(42, 'Dell XPS 15 9560', 56000.00, '1766780830_Dell XPS 15 9560.jpg', '2025-12-26 20:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `register_user`
--

CREATE TABLE `register_user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register_user`
--

INSERT INTO `register_user` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'anil', 'anilstha662@gmail.com', '$2y$10$tvpgspGLZoDzlcCErx8kMeVw5TnNe.6.gTJllD9713v63YgrE5r0i', '2025-12-23 22:45:33'),
(2, 'ram', 'example@gmail.com', '$2y$10$dnH5xkfMVRC9cOxaoDbrB.mfn3q2aCddI02gK8sHDB9Gwzk5Ciqq.', '2025-12-23 22:48:11'),
(3, '123456', 'apple@gmail.com', '$2y$10$ol62pTtBitUrpQDx/46EPeg211K5zpZuA0mP1XIywhvBdZP1CvjIm', '2025-12-23 22:48:36'),
(22, 'anilstha', 'example1@gmail.com', '$2y$10$Je9PXl39eyUazUJgNGLh3.PgVSMqAsJdfppC7CH94WcCwxh6uO6zS', '2025-12-23 23:13:38'),
(33, 'krish', 'ani33l@example.com', '$2y$10$jB93N9zBoJ112As4mb2t3OjEpAkjZ7.iEbh/c.KXaP6WzqxMoxzx6', '2025-12-23 23:25:02'),
(34, 'harilal', 'apple11@gmail.com', '$2y$10$GTpjUJYFre3pj2S3PVtHZeZdaWDpEeNOoh.uRmdgoybHrj4/1Nyii', '2025-12-24 02:21:25'),
(35, 'krishna', 'anilstha663@gmail.com', '$2y$10$55CQ4iSntIzYYshQzNh07eI7WFenzjQYRyYv4ygHj/QbYTRhhZzR.', '2025-12-26 19:12:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) NOT NULL DEFAULT 'admin',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `role`, `status`) VALUES
(1, 'Anil Shrestha', 'hello@anil.com', 'password123', '2025-12-24 19:07:05', 'admin', 'active'),
(2, 'Nirmal', 'hello@nirmal.com', 'password123', '2025-12-24 19:07:05', 'editor', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `register_user`
--
ALTER TABLE `register_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
