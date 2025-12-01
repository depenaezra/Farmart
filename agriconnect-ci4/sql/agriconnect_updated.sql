-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 03:27 AM
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
-- Database: `agriconnect`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('weather','government','market','general') NOT NULL,
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `category`, `priority`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Weather Alert: Heavy Rain Expected', 'PAGASA warns of heavy rain this weekend (Nov 23-24). Please secure your crops and prepare drainage systems. Flash floods possible in low-lying areas.', 'weather', 'high', 8, '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(2, 'New Government Subsidy Program', 'DA announces new subsidy program for small-scale farmers. Registration starts next week at the Municipal Agriculture Office. Requirements: Valid ID, farm documents, cooperative membership.', 'government', 'medium', 8, '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(3, 'Market Price Update - November 2024', 'Current market prices remain stable: Tomatoes: ₱70-85/kg, Lettuce: ₱55-65/kg, Eggplant: ₱50-60/kg, Corn: ₱40-50/kg. Direct selling through AgriConnect ensures better prices for both farmers and buyers.', 'market', 'low', 8, '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(4, 'AgriConnect Platform Updates', 'New features now available: Product image upload, order tracking, and enhanced messaging. Thank you for being part of our growing community!', 'general', 'low', 8, '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(5, 'Sample', 'Php 500.00 for Noche Buena.', 'government', 'high', 8, '2025-11-30 06:24:46', '2025-11-30 06:24:46');

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forum_comments`
--

INSERT INTO `forum_comments` (`id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 1, 2, 'Great tips Juan! I also use mulching to retain moisture. Works very well.', '2025-11-29 07:35:33'),
(2, 1, 4, 'Thank you for sharing. Do you use any organic pesticides?', '2025-11-29 07:35:33'),
(3, 2, 1, 'Maria, can you share your composting process? I want to try organic farming too.', '2025-11-29 07:35:33'),
(4, 3, 1, 'You can try the DA office in Nasugbu. They have good quality seeds.', '2025-11-29 07:35:33'),
(5, 4, 1, 'Salamat po sa suporta! This platform really helps us connect with buyers directly.', '2025-11-29 07:35:33');

-- --------------------------------------------------------

--
-- Table structure for table `forum_likes`
--

CREATE TABLE `forum_likes` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_likes`
--

INSERT INTO `forum_likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(1, 4, 9, '2025-11-30 14:46:34'),
(2, 3, 9, '2025-11-30 14:46:38');

-- --------------------------------------------------------

--
-- Table structure for table `forum_mentions`
--

CREATE TABLE `forum_mentions` (
  `id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) UNSIGNED NOT NULL,
  `mentioned_user_id` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_url` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `user_id`, `title`, `content`, `category`, `likes`, `created_at`, `updated_at`, `image_url`) VALUES
(1, 1, 'Best Practices for Tomato Growing in Nasugbu', 'Hello fellow farmers! I would like to share my experience growing tomatoes here in Nasugbu. The key is proper irrigation and pest control. Anyone else has tips?', 'farming tips', 12, '2025-11-29 03:01:43', '2025-11-29 03:01:43', NULL),
(2, 2, 'Organic Farming Techniques', 'I have been doing organic farming for 3 years now. Happy to share techniques and answer questions about pesticide-free farming.', 'farming tips', 18, '2025-11-29 03:01:43', '2025-11-29 03:01:43', NULL),
(3, 3, 'Where to Buy Quality Seeds?', 'Can anyone recommend good suppliers for vegetable seeds in Batangas? Looking for quality seeds at reasonable prices.', 'general', 5, '2025-11-29 03:01:43', '2025-11-29 03:01:43', NULL),
(4, 6, 'Thank You Farmers!', 'As a buyer, I just want to thank all the farmers here for providing fresh produce. The quality is amazing and prices are fair. Salamat po!', 'general', 25, '2025-11-29 03:01:43', '2025-11-29 03:01:43', NULL),
(5, 9, 'Sample', 'Sampleeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 'market prices', 0, '2025-11-30 06:47:16', '2025-11-30 06:47:16', NULL),
(6, 9, 'Sample #2', 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA', 'general', 0, '2025-11-30 06:56:41', '2025-11-30 06:56:41', 'uploads/forum/1764485801_b8ffe875707d967f497f.png'),
(7, 9, 'Sample #3', 'Sampleeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee', 'general', 0, '2025-11-30 07:00:48', '2025-11-30 07:00:48', '[\"uploads\\/forum\\/1764486048_0bdc6d9d8a208d13eb25.png\",\"uploads\\/forum\\/1764486048_cd4f73607af5f5bc8b4b.png\",\"uploads\\/forum\\/1764486048_d9a887131fdb1799abd8.png\"]');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `subject`, `message`, `is_read`, `created_at`) VALUES
(2, 8, 1, NULL, 'Good afternoon!', 0, '2025-11-29 04:34:17'),
(3, 11, 7, NULL, 'hello po!', 0, '2025-11-29 08:14:10'),
(4, 9, 8, NULL, 'hello po', 1, '2025-11-30 01:10:51'),
(5, 9, 8, NULL, 'areh po', 1, '2025-11-30 07:17:31'),
(6, 9, 11, NULL, 'hi', 0, '2025-11-30 07:54:53'),
(7, 9, 11, NULL, 'hello', 0, '2025-11-30 07:55:00'),
(8, 11, 7, NULL, 'l', 0, '2025-11-30 08:03:44'),
(9, 11, 7, NULL, 'lllll', 0, '2025-11-30 08:10:26'),
(10, 1, 9, NULL, 'hi', 0, '2025-11-30 08:11:51'),
(11, 1, 9, NULL, ' ', 0, '2025-11-30 08:52:58'),
(12, 8, 9, NULL, ' ', 0, '2025-11-30 09:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE `message_attachments` (
  `id` int(11) UNSIGNED NOT NULL,
  `message_id` int(11) UNSIGNED NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int(11) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message_attachments`
--

INSERT INTO `message_attachments` (`id`, `message_id`, `file_path`, `file_name`, `file_type`, `file_size`, `created_at`) VALUES
(1, 11, 'uploads/messages/1764492778_8f9d6b82da4390ac4767.png', 'USER MANUAL.png', 'image/png', 72647, '2025-11-30 16:52:58'),
(2, 12, 'uploads/messages/1764494670_1531324355bc6af18ba8.png', 'USER MANUAL.png', 'image/png', 72647, '2025-11-30 17:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-11-30-070935', 'App\\Database\\Migrations\\AddMessageAttachments', 'default', 'App', 1764486673, 1),
(2, '2025-11-30-061626', 'App\\Database\\Migrations\\AddProfilePictureToUsers', 'default', 'App', 1764488190, 2),
(3, '2025-11-30-070935', '\\AddMessageAttachments', 'default', 'App', 1764489161, 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `buyer_id` int(11) UNSIGNED NOT NULL,
  `farmer_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  `delivery_address` text DEFAULT NULL,
  `payment_method` enum('in_person','gcash') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `buyer_id`, `farmer_id`, `product_id`, `quantity`, `unit`, `total_price`, `status`, `delivery_address`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'ORD-20251201-17600A', 9, 3, 8, 1, 'kilo', 65.00, 'completed', 'Sample Address\nContact: 09999999999', 'Sample Note', '2025-12-01 00:35:54', '2025-12-01 00:38:58'),
(2, 'ORD-20251201-B8FC5B', 9, 3, 3, 8, 'kilo', 360.00, 'pending', 'Brgy. Putat, Nasugbu\nContact: 0999-999-9999', NULL, '2025-12-01 01:10:17', '2025-12-01 01:10:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `farmer_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'kilo',
  `category` enum('vegetables','fruits','grains','other') NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `status` enum('available','out-of-stock','pending','rejected') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `farmer_id`, `name`, `description`, `price`, `unit`, `category`, `stock_quantity`, `location`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(3, 3, 'Native Corn', 'Sweet native corn, freshly harvested.', 45.00, 'kilo', 'grains', 92, 'Brgy. Lumbangan, Nasugbu', '/uploads/products/corn.jpg', 'available', '2025-11-29 03:01:43', '2025-12-01 01:10:17'),
(4, 4, 'Banana Lakatan', 'Premium lakatan bananas, naturally ripened.', 70.00, 'kilo', 'fruits', 80, 'Brgy. Poblacion, Nasugbu', '/uploads/products/banana.jpg', 'available', '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(5, 1, 'Eggplant', 'Fresh eggplants for your favorite dishes.', 55.00, 'kilo', 'vegetables', 40, 'Brgy. Aga, Nasugbu', '/uploads/products/eggplant.jpg', 'available', '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(7, 2, 'Cabbage', 'Fresh cabbage, perfect for salads and cooking.', 50.00, 'kilo', 'vegetables', 35, 'Brgy. Wawa, Nasugbu', '/uploads/products/cabbage.jpg', 'available', '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(8, 3, 'Sweet Potato', 'Organic sweet potatoes, rich in nutrients.', 65.00, 'kilo', 'vegetables', 59, 'Brgy. Lumbangan, Nasugbu', '/uploads/products/sweetpotato.jpg', 'available', '2025-11-29 03:01:43', '2025-12-01 00:35:54'),
(9, 1, 'Bayabas', 'basta bayabas', 50.00, 'kilo', 'fruits', 10, 'Brgy. Putat, Nasugbu', '/uploads/products/1764491167_1d47ac1a263cc86bf66e.png', 'available', '2025-11-30 08:26:07', '2025-11-30 08:26:07');

-- --------------------------------------------------------
--
-- Table structure for table `cart`
--
-- NOTE: Shopping cart items are now stored in the 'cart' table in the database.
-- This allows cart persistence across sessions and devices.
-- Only completed orders are saved to the 'orders' table.
--

CREATE TABLE `cart` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `cooperative` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `location`, `cooperative`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Juan Santos', 'juan.santos@example.com', '0917-123-4567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Brgy. Aga, Nasugbu', 'Nasugbu Farmers Coop', 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(2, 'Maria Cruz', 'maria.cruz@example.com', '0918-234-5678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Brgy. Wawa, Nasugbu', 'Green Valley Coop', 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(3, 'Pedro Reyes', 'pedro.reyes@example.com', '0919-345-6789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Brgy. Lumbangan, Nasugbu', 'Batangas Corn Growers', 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(4, 'Rosa Garcia', 'rosa.garcia@example.com', '0920-456-7890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Brgy. Poblacion, Nasugbu', 'Nasugbu Farmers Coop', 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(5, 'Ana Bautista', 'ana.bautista@example.com', '0921-567-8901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Brgy. Mataas na Pulo, Nasugbu', 'Green Valley Coop', 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(6, 'Miguel Buyer', 'miguel.buyer@example.com', '0922-678-9012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Nasugbu Town Center', NULL, 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(7, 'Carmen Buyer', 'carmen.buyer@example.com', '0923-789-0123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'Brgy. Poblacion, Nasugbu', NULL, 'active', '2025-11-29 03:01:43', '2025-11-29 06:52:55'),
(8, 'Admin User', 'admin@agriconnect.ph', '0943-123-4567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Nasugbu', NULL, 'active', '2025-11-29 03:01:43', '2025-11-29 06:54:29'),
(9, 'Ezra Desacola', 'ezra1234@gmail.com', '0999-999-9999', '$2y$10$eEEG2kDhEcCufw4csWduhe.00QG.wAfVP8lgyfB9eDlOkIzEfZ8UG', 'user', 'Brgy. Putat, Nasugbu', NULL, 'active', '2025-11-29 07:00:10', '2025-11-30 06:18:18'),
(10, 'Carmela Montecarlos', 'montecarlos@gmail.com', '0999-999-9999', '$2y$10$4RzBC/fHSLDVtrclr1dIf.HNbGCEYsRI5bAz52AncvXVO3b4rE1um', 'user', 'Brgy. Putat, Nasugbu', NULL, 'active', '2025-11-29 07:10:09', '2025-11-30 06:18:24'),
(11, 'Ace Craige', 'craige123@gmail.com', '0999-999-9999', '$2y$10$wgtp7sWOpjjS0xqB9GfkxuweAf2OWvC5OSEMRHx1ev8UScROAWkQO', 'user', 'Brgy. Putat, Nasugbu', NULL, 'active', '2025-11-29 08:09:37', '2025-11-30 06:18:32'),
(12, 'Aea Sy', 'aea12345@gmail.com', '0999-999-9999', '$2y$10$X/LTlV7FW/h4WsU8/YBxhOc/w9ir1wldSZIQXHOKFp71Eb7O7GFxa', 'user', 'Brgy. Putat, Nasugbu', NULL, 'active', '2025-11-30 06:16:40', '2025-11-30 06:18:46'),
(13, 'User', 'user1234@gmail.com', '0999-999-9999', '$2y$10$OHoDzkGzEEPLv3o.7ECz3ejmgG0JIQVMfgCLloP0ky8hqBif7Mcqy', 'user', 'Brgy. Putat, Nasugbu', NULL, 'active', '2025-11-30 06:19:25', '2025-11-30 06:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` int(11) UNSIGNED NOT NULL,
  `reporter_id` int(11) UNSIGNED NOT NULL,
  `reported_type` enum('forum_post','forum_comment','product','user') NOT NULL,
  `reported_id` int(11) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','reviewed','resolved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `reporter_id`, `reported_type`, `reported_id`, `reason`, `description`, `status`, `created_at`, `reviewed_at`, `reviewed_by`) VALUES
(2, 9, 'product', 2, 'spam', '', 'resolved', '2025-11-30 05:37:12', '2025-11-30 06:06:59', 8),
(3, 11, 'product', 6, 'false_information', 'sample', 'resolved', '2025-11-30 06:10:53', '2025-11-30 06:11:51', 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `priority` (`priority`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_likes`
--
ALTER TABLE `forum_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id_user_id` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_mentions`
--
ALTER TABLE `forum_mentions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `mentioned_user_id` (`mentioned_user_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `is_read` (`is_read`);

--
-- Indexes for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `message_id` (`message_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `category` (`category`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role` (`role`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `reported_type` (`reported_type`),
  ADD KEY `reported_id` (`reported_id`),
  ADD KEY `status` (`status`),
  ADD KEY `violations_ibfk_2` (`reviewed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_mentions`
--
ALTER TABLE `forum_mentions`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `message_attachments`
--
ALTER TABLE `message_attachments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_mentions`
--
ALTER TABLE `forum_mentions`
  ADD CONSTRAINT `forum_mentions_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_mentions_ibfk_2` FOREIGN KEY (`mentioned_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD CONSTRAINT `message_attachments_message_fk` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `violations_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
