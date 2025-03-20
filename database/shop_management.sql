-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 20, 2025 at 07:09 AM
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
-- Database: `shop_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `unique_id`, `user_id`, `product_id`, `quantity`, `status`, `created_at`, `deleted_at`) VALUES
(1, 'ORD_67db9e4351bfb', '6', 1, 20, 'Pending', '2025-03-20 00:19:07', 0),
(2, 'ORD_67db9f9581773', '6', 2, 10, 'Pending', '2025-03-20 00:24:45', 0),
(3, 'ORD_67db9fced022a', 'godwin@gmail.com', 1, 20, 'Pending', '2025-03-20 00:25:42', 0),
(4, 'ORD_67dba7ad7cea8', '2', 1, 10, 'Delivered', '2025-03-20 00:59:17', 0),
(5, 'ORD_67dba7b6624f7', '2', 2, 10, 'Delivered', '2025-03-20 00:59:26', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `unique_id`, `name`, `description`, `price`, `stock`, `created_at`, `deleted_at`) VALUES
(1, '67db86601b45e', 'Examples', 'xyz', 20.00, 50, '2025-03-19 22:37:12', 0),
(2, '67db86916f979', 'Example', 'xyz', 30.00, 50, '2025-03-19 22:38:01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `unique_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `otp` int(6) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `unique_id`, `name`, `email`, `password`, `role`, `otp`, `created_at`, `deleted_at`) VALUES
(1, 'sdf786sf87ss6d', 'Godwin', 'nesarajgodwin80@gmail.com', '$2y$10$ho3RACHkoYXO7ENYivPALeRpW8mctJHAiyX4O7R6x4oSgh4dYTH/m', 'admin', NULL, '2025-03-19 19:07:26', 0),
(2, '4266eeb6-04f7-11f0-b97c-d493902bab26', 'Karthik', 'karthik@gmail.com', '$2y$10$BZA66LZbSmTH3gyD55HFFOydlBv7C/Aiy5KTpBK40ub22q80YbKba', 'user', NULL, '2025-03-19 19:20:46', 0),
(4, '51b8fc64-04f8-11f0-b97c-d493902bab26', 'Surya', 'surya@gmail.com', '$2y$10$c7YCn/9gNI9R/BEvSnOQFet0Paz.NDTUMVvn7LE3WUX0NrNfxKOui', 'user', NULL, '2025-03-19 19:28:21', 0),
(5, NULL, 'Gopi', 'gopi@gmail.com', '$2y$10$vLeU1NvR7KcC3WbvA5TMi.DpWwA1hw6V53Vqfrh31Mhcnrdxan5GO', 'user', NULL, '2025-03-20 02:50:17', 0),
(6, '67db8314bdab9', 'Nesaraj', 'godwin@gmail.com', '$2y$10$aGF5Nv7XB/wqsZYZsycC4OUiXylysm8YtcT6XB.92q1k4al9PaU/y', 'user', NULL, '2025-03-20 02:53:08', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_id` (`unique_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `unique_id` (`unique_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
