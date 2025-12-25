-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2025 at 07:04 PM
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
(20, 'kjhasdbkjads', '1766601748_compressed-pexels-tranmautritam-326514 (1).webp', 10, '2025-12-25 17:25:22', 50.00);

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
(8, 'jyjhf', 4444.00, '1766601546_pexels-karola-g-5632402.jpg', '2025-12-24 18:39:06'),
(9, 'pushpa', 10000.00, '1766601663_pexels-karola-g-5632402.jpg', '2025-12-24 18:41:03'),
(10, 'kjhasdbkjads', 50.00, '1766601748_compressed-pexels-tranmautritam-326514 (1).webp', '2025-12-24 18:42:28'),
(11, 'dadad', 600.00, '1766601813_pexels-cottonbro-3584998_optimized_100.webp', '2025-12-24 18:43:33'),
(12, 'hfhf', 600.00, '1766601869_pexels-olly-920381_optimized_100.webp', '2025-12-24 18:44:29'),
(13, 'hgchgf', 300.00, '1766602024_pexels-olia-danilevich-4974915.webp', '2025-12-24 18:47:04'),
(14, 'jhgjh', 666.00, '1766602116_pexels-karola-g-5632402.jpg', '2025-12-24 18:48:36'),
(15, 'aykhads', 90000.00, '1766602244_pexels-divinetechygirl-1181248_optimized_100.webp', '2025-12-24 18:50:44'),
(16, 'yyyy', 8098.00, '1766602265_pexels-olly-842548_optimized_100.webp', '2025-12-24 18:51:05'),
(17, 'hgdfgf', 8098.00, '1766602332_pexels-olly-842548_optimized_100.webp', '2025-12-24 18:52:12'),
(18, 'kgf', 6666.00, '1766602429_pexels-olly-935743.jpg', '2025-12-24 18:53:49'),
(19, 'anil', 66464.00, '1766602519_pexels-tranmautritam-326514.webp', '2025-12-24 18:55:19'),
(20, 'herooo', 10000.00, '1766604475_pexels-cottonbro-3584998.webp', '2025-12-24 19:27:55'),
(21, 'krisha', 10000.00, '1766680939_pexels-silverkblack-23496705.webp', '2025-12-25 16:42:19');

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
(34, 'harilal', 'apple11@gmail.com', '$2y$10$GTpjUJYFre3pj2S3PVtHZeZdaWDpEeNOoh.uRmdgoybHrj4/1Nyii', '2025-12-24 02:21:25');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `register_user`
--
ALTER TABLE `register_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
