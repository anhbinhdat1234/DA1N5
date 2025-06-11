-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2025 at 04:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `da1_n5`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `product_variant_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Áo thun'),
(2, 'Áo sơ mi'),
(3, 'Quần jeans'),
(4, 'Quần short'),
(5, 'Váy'),
(6, 'Áo khoác');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percent','amount') NOT NULL COMMENT 'percent = %, amount = fixed ₫',
  `value` decimal(10,2) NOT NULL,
  `min_order` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'đơn tối thiểu để áp dụng',
  `usage_limit` int UNSIGNED NOT NULL DEFAULT '0' COMMENT '0 = không giới hạn',
  `used` int UNSIGNED NOT NULL DEFAULT '0',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active,0=inactive',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order`, `usage_limit`, `used`, `start_at`, `end_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 'NEWUSER10', 'percent', '10.00', '0.00', 1, 1, NULL, NULL, 1, '2025-06-09 13:05:58', '2025-06-09 13:06:14'),
(2, 'SUMMER20', 'percent', '20.00', '200000.00', 1000, 3, '2025-06-01 00:00:00', '2025-08-31 00:00:00', 1, '2025-06-09 13:05:58', '2025-06-09 13:35:40'),
(3, 'WELCOME50', 'amount', '50000.00', '300000.00', 0, 0, NULL, NULL, 1, '2025-06-09 13:05:58', '2025-06-09 13:05:58'),
(4, 'FIVECLOTH100', 'amount', '100000.00', '1000000.00', 100, 1, '2025-05-01 00:00:00', '2025-07-31 00:00:00', 1, '2025-06-09 13:05:58', '2025-06-09 13:06:44'),
(5, 'BLACKFRIDAY', 'percent', '50.00', '0.00', 0, 0, '2025-11-27 00:00:00', '2025-11-27 23:59:59', 1, '2025-06-09 13:05:58', '2025-06-09 13:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `coupon_code` varchar(50) DEFAULT NULL,
  `discount_amount` int UNSIGNED NOT NULL DEFAULT '0',
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `coupon_code`, `discount_amount`, `status`, `created_at`, `updated_at`) VALUES
(5, 1, '480000.00', NULL, 0, 'cancelled', '2025-06-09 13:33:30', '2025-06-10 15:44:25'),
(6, 1, '360000.00', 'SUMMER20', 90000, 'pending', '2025-06-09 13:35:45', '2025-06-10 15:44:25'),
(7, 1, '5040000.00', 'SUMMER20', 1260000, 'pending', '2025-06-09 14:22:07', '2025-06-10 15:44:25'),
(8, 1, '300000.00', NULL, 0, 'cancelled', '2025-06-09 14:36:53', '2025-06-10 15:44:25'),
(9, 13, '320000.00', NULL, 0, 'cancelled', '2025-06-09 14:51:58', '2025-06-10 15:44:25'),
(10, 13, '320000.00', NULL, 0, 'cancelled', '2025-06-09 15:02:31', '2025-06-10 15:44:25'),
(12, 1, '2890000.00', NULL, 0, 'pending', '2025-06-09 17:33:10', '2025-06-10 15:44:25'),
(13, 1, '2890000.00', NULL, 0, 'pending', '2025-06-09 17:35:02', '2025-06-10 15:44:25'),
(14, 13, '768000.00', 'SUMMER20', 192000, 'pending', '2025-06-09 19:23:21', '2025-06-10 15:44:25'),
(15, 1, '320000.00', NULL, 0, 'pending', '2025-06-11 11:55:52', '2025-06-11 11:55:52'),
(16, 1, '320000.00', NULL, 0, 'pending', '2025-06-11 11:57:57', '2025-06-11 11:57:57'),
(17, 1, '320000.00', NULL, 0, 'pending', '2025-06-11 11:58:30', '2025-06-11 11:58:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `product_variant_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_variant_id`, `quantity`, `price`) VALUES
(7, 5, 1, 4, '150000.00'),
(8, 6, 1, 3, '150000.00'),
(9, 7, 3, 15, '320000.00'),
(10, 7, 1, 10, '150000.00'),
(11, 8, 1, 2, '150000.00'),
(12, 9, 3, 1, '320000.00'),
(13, 10, 3, 1, '320000.00'),
(14, 13, 5, 5, '450000.00'),
(15, 13, 3, 2, '320000.00'),
(16, 14, 3, 3, '320000.00'),
(17, 17, 3, 1, '320000.00');

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT NULL,
  `changed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('unpaid','paid') DEFAULT 'unpaid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `category_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `category_id`, `created_at`) VALUES
(1, 'Áo thun trắng basic', '15000000.00', 'Áo thun cotton 100%, thoáng mát', 1, '2025-05-12 20:04:24'),
(2, 'Quần jeans xanh', '320000.00', 'Chất liệu denim co giãn nhẹ', 3, '2025-05-12 20:04:24'),
(3, 'Váy xòe hoa nhí', '280000.00', 'Phong cách nữ tính, dễ thương', 5, '2025-05-12 20:04:24'),
(4, 'Áo khoác bomber', '450000.00', 'Chống gió, thời trang', 6, '2025-05-12 20:04:24'),
(5, 'Áo sơ mi caro', '250000.00', 'Chất liệu kate, không nhăn', 2, '2025-05-12 20:04:24'),
(6, 'Quần short kaki', '190000.00', 'Mặc thoải mái, dễ phối đồ', 4, '2025-05-12 20:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`) VALUES
(1, 1, '/assets/client/assets/img/shop/fashion/01.png'),
(2, 2, '/assets/client/assets/img/shop/fashion/02.png'),
(3, 3, '/assets/client/assets/img/shop/fashion/03.png'),
(4, 4, '/assets/client/assets/img/shop/fashion/04.png'),
(5, 5, '/assets/client/assets/img/shop/fashion/05.png'),
(6, 6, '/assets/client/assets/img/shop/fashion/06.png');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `stock` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `color`, `stock`) VALUES
(1, 1, 'M', 'Trắng', 48),
(2, 1, 'L', 'Trắng', 7),
(3, 2, '32', 'Xanh', 42),
(4, 3, 'S', 'Hồng', 5),
(5, 4, 'Free', 'Đen', 3);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` tinyint NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `content`, `created_at`) VALUES
(1, 1, 1, 5, 'Good', '2025-06-08 23:47:34'),
(2, 1, 1, 5, '1', '2025-06-08 23:48:54'),
(3, 1, 1, 5, 'test', '2025-06-08 23:49:34');

-- --------------------------------------------------------

--
-- Table structure for table `shippings`
--

CREATE TABLE `shippings` (
  `id` int NOT NULL,
  `order_id` int DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `note` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','shipping','delivered') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shippings`
--

INSERT INTO `shippings` (`id`, `order_id`, `address`, `phone`, `note`, `created_at`, `status`) VALUES
(5, 5, '46 Đông Tác Đông Thọ, TP. Thanh Hóa, Thanh Hóa', '0853243091', NULL, '2025-06-09 17:34:52', 'pending'),
(6, 6, '46 Đông Tác Đông Thọ, TP. Thanh Hóa, Thanh Hóaaaa', '0853243091', NULL, '2025-06-09 17:34:52', 'pending'),
(7, 7, 'a', '1', NULL, '2025-06-09 17:34:52', 'pending'),
(8, 8, '46 dong tac phuong dong tho tp thanh hoaa', '0853243091', 'aaa', '2025-06-09 17:34:52', 'pending'),
(9, 9, '46 dong tac phuong dong tho', '0853243091', '1', '2025-06-09 17:34:52', 'pending'),
(10, 10, '46 dong tac th', '0853243092', 'aaa', '2025-06-09 17:34:52', 'pending'),
(11, 13, '46 dong tac', '0853243091', 'a', '2025-06-09 17:35:02', 'pending'),
(12, 14, '46 dong tacaaaa', '0853243091', 'aaaa', '2025-06-09 19:23:21', 'pending'),
(13, 15, '46 dong  tac', '0853243091', '', '2025-06-11 11:55:52', 'pending'),
(14, 16, '46 dong  tac', '0853243091', '', '2025-06-11 11:57:57', 'pending'),
(15, 17, '46 dong  tac', '0853243091', '', '2025-06-11 11:58:30', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `image_url`, `title`, `subtitle`, `link`, `sort_order`) VALUES
(1, 'assets/client/assets/img/home/fashion/v1/hero-slider/01.png', 'Mùa hè rực rỡ', 'Giảm giá đến 50%', 'shop.php?category=summer', 1),
(2, 'assets/client/assets/img/home/fashion/v1/hero-slider/02.png', 'BST Thu Đông', 'Sẵn sàng cho tiết trời se lạnh', 'shop.php?category=autumn', 2),
(3, 'assets/client/assets/img/home/fashion/v1/hero-slider/03.png', 'Ưu đãi cuối năm', 'Mua 1 tặng 1', 'shop.php?category=winter', 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'binh', 'fatitss12@gmail.com', '0853243091', '46 Đông Tác Đông Thọ, TP. Thanh Hóa, Thanh Hóa', '$2y$10$x0JpjbbpI5m2qzhUPpKs5.AQnNMYTyMdM9fptnbVjjeC8MyDyOrAe', 'admin', '2025-05-13 18:55:32', '2025-06-10 15:55:55'),
(13, 'CAO THANH BÌNH', 'binhctph527200@gmail.com', NULL, NULL, '$2y$10$SjsDAW5HlkqP4k72acRq6uTr9oSRU3Uh187Z8lGKPuw3hhDH.qdnO', 'user', '2025-06-09 14:51:26', '2025-06-10 08:56:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_variant_id` (`product_variant_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `shippings`
--
ALTER TABLE `shippings`
  ADD CONSTRAINT `shippings_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
