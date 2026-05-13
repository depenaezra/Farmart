-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 13, 2026 (schema updated: login whitelist tables aligned with app)
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
-- Schema note: Login whitelist uses `application_settings` + `login_whitelist_emails`
-- (matches the CodeIgniter app). The old `whitelisted_emails` table was removed from this dump.
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
-- Table structure for table `blocked_emails`
--

CREATE TABLE `blocked_emails` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `blocked_by` int(11) UNSIGNED NOT NULL,
  `blocked_at` datetime NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blocked_emails`
--

INSERT INTO `blocked_emails` (`id`, `email`, `blocked_by`, `blocked_at`, `reason`) VALUES
(1, 'yurippe.naoi@gmail.com', 3, '2026-04-13 15:19:27', 'spam account');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
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
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 3, '2026-04-05 07:56:48', '2026-04-05 07:57:09');

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
(5, 4, 1, 'Salamat po sa suporta! This platform really helps us connect with buyers directly.', '2025-11-29 07:35:33'),
(6, 3, 4, 'im not cute anymore', '2026-04-05 07:56:16');

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
(2, 3, 9, '2025-11-30 14:46:38'),
(4, 1, 2, '2026-04-03 13:59:40');

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
(0, '2026-04-05-000001', 'App\\Database\\Migrations\\AddLoginSuspensionToUsers', 'default', 'App', 1778214003, 5),
(1, '2025-11-30-070935', 'App\\Database\\Migrations\\AddMessageAttachments', 'default', 'App', 1764486673, 1),
(2, '2025-11-30-061626', 'App\\Database\\Migrations\\AddProfilePictureToUsers', 'default', 'App', 1764488190, 2),
(3, '2025-11-30-070935', '\\AddMessageAttachments', 'default', 'App', 1764489161, 3),
(4, '2026-05-13-000001', 'App\\Database\\Migrations\\LoginWhitelistAndSettings', 'default', 'App', 1747689600, 6);

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

INSERT INTO `orders` (`id`, `order_number`, `buyer_id`, `farmer_id`, `product_id`, `quantity`, `unit`, `total_price`, `status`, `delivery_address`, `payment_method`, `notes`, `created_at`, `updated_at`) VALUES
(0, 'ORD-20260511-5EA02E', 2, 1, 5, 1, 'kilo', 55.00, 'pending', 'Rillo Tuy, Batangas\nContact: 0906403754111', 'in_person', NULL, '2026-05-11 00:33:24', '2026-05-11 00:33:24'),
(1, 'ORD-20251201-17600A', 9, 3, 8, 1, 'kilo', 65.00, 'completed', 'Sample Address\nContact: 09999999999', NULL, 'Sample Note', '2025-12-01 00:35:54', '2025-12-01 00:38:58'),
(2, 'ORD-20251201-B8FC5B', 9, 3, 3, 8, 'kilo', 360.00, 'pending', 'Brgy. Putat, Nasugbu\nContact: 0999-999-9999', NULL, NULL, '2025-12-01 01:10:17', '2025-12-01 01:10:17');

-- --------------------------------------------------------

--
-- Table structure for table `otp_token`
--

CREATE TABLE `otp_token` (
  `id` int(11) UNSIGNED NOT NULL,
  `userID` int(11) UNSIGNED NOT NULL,
  `token` varchar(10) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `otp_token`
--

INSERT INTO `otp_token` (`id`, `userID`, `token`, `expires_at`, `created_at`) VALUES
(9, 5, '830364', '2026-05-12 16:44:35', '2026-05-12 16:34:35'),
(23, 3, '835079', '2026-05-13 00:10:28', '2026-05-13 00:00:28'),
(25, 7, '153043', '2026-05-13 00:42:08', '2026-05-13 00:32:08');

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
(5, 1, 'Eggplant', 'Fresh eggplants for your favorite dishes.', 55.00, 'kilo', 'vegetables', 40, 'Brgy. Aga, Nasugbu', '/uploads/products/eggplant.jpg', 'available', '2025-11-29 03:01:43', '2025-11-29 03:01:43'),
(8, 3, 'Sweet Potato', 'Organic sweet potatoes, rich in nutrients.', 65.00, 'kilo', 'vegetables', 59, 'Brgy. Lumbangan, Nasugbu', '/uploads/products/sweetpotato.jpg', 'available', '2025-11-29 03:01:43', '2025-12-01 00:35:54'),
(9, 1, 'Bayabas', 'basta bayabas', 50.00, 'kilo', 'fruits', 10, 'Brgy. Putat, Nasugbu', '/uploads/products/1764491167_1d47ac1a263cc86bf66e.png', 'available', '2025-11-30 08:26:07', '2025-11-30 08:26:07');

-- --------------------------------------------------------

--
-- Table structure for table `twofa_attempts`
--

CREATE TABLE `twofa_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `code_entered` varchar(20) DEFAULT NULL,
  `success` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `role` enum('farmer','buyer','admin','user') NOT NULL DEFAULT 'buyer',
  `location` varchar(255) DEFAULT NULL,
  `cooperative` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `login_suspended_until` datetime DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `total_failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `lockout_until` datetime DEFAULT NULL,
  `security_email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `twofa_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `twofa_secret` varchar(255) DEFAULT NULL,
  `twofa_backup_codes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `location`, `cooperative`, `status`, `login_suspended_until`, `failed_login_attempts`, `total_failed_login_attempts`, `last_failed_login`, `lockout_until`, `security_email_sent`, `twofa_enabled`, `twofa_secret`, `twofa_backup_codes`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@agriconnect.ph', '09000000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Nasugbu', NULL, 'active', NULL, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '2026-04-02 10:18:45', '2026-04-02 10:18:45'),
(2, 'Margarette', 'artofrette@gmail.com', '0906403754111', '$2y$10$zN2VNoCLan5n.uTThkdm4em7H1no6Z.QhjwZy4a8Gzj1bZInPaFvS', 'user', 'Tuy', NULL, 'inactive', NULL, 5, 20, '2026-05-08 12:01:05', '2026-05-08 12:06:05', 1, 0, NULL, NULL, '2026-04-02 11:02:52', '2026-05-12 10:08:35'),
(3, 'Maria', 'margerette73@gmail.com', '0906403754', '$2y$10$hE1BgFpQ6YWP5yOqM9AClemC3LEVfX4gNnCYPCo87NevDcd2ev.S6', 'admin', 'Rillo, Tuy Batangas', NULL, 'active', NULL, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '2026-04-02 11:16:15', '2026-05-08 03:52:58'),
(4, 'Jolo Atie', 'atiejolo@gmail.com', '09936126727', '$2y$10$WfAOLDcu4CpNU7We54FUAuxc.mkh0AHyB12GSeqs2mG6ThefsQVnG', 'buyer', 'Sitio Bulihan Brgy. Munting Indang Nasugbu Batangas', NULL, 'inactive', NULL, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '2026-04-05 07:53:35', '2026-05-12 10:08:35'),
(5, 'margarette m. perez', 'margaretteperez73@gmail.com', '0906403754', '$2y$10$CCL/yAc3R7MmBlnjq/f19eeeZ87ncg57/RXelGUeKdoMk8O24Vbm2', 'buyer', '456 Rillo, Tuy, Batangas', NULL, 'inactive', NULL, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '2026-04-13 06:40:25', '2026-05-12 10:08:35'),
(6, 'Marga', '23-72068@g.batstate-u.edu.ph', '09936126727', '$2y$10$59cUgA8n0NzC9TMypwRKcuUDV2l0ZDczuIvsDLN5NCkkQGhr2ZBZ.', 'buyer', '456 Rillo, Tuy, Batangas', NULL, 'inactive', NULL, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '2026-05-12 09:18:30', '2026-05-12 10:08:35'),
(7, 'Admin Account', 'forfarmart@gmail.com', '0906403754', '$2y$10$12DNPxKsUjQCj2SZR8Lm..G.qMjRSu3gJncT5fZaS2R5cqY2zRipa', 'admin', '456 Rillo, Tuy, Batangas', NULL, 'active', NULL, 0, 0, NULL, NULL, 0, 0, NULL, NULL, '2026-05-12 10:00:20', '2026-05-12 10:01:05');

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

-- --------------------------------------------------------

--
-- Table structure for table `application_settings`
-- Key/value settings (e.g. login_whitelist_enabled). Used by Admin login whitelist UI.
--

CREATE TABLE `application_settings` (
  `setting_key` varchar(64) NOT NULL,
  `setting_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_settings`
--

INSERT INTO `application_settings` (`setting_key`, `setting_value`) VALUES
('login_whitelist_enabled', '0');

-- --------------------------------------------------------

--
-- Table structure for table `login_whitelist_emails`
-- When login whitelist mode is on, these emails may sign in (in addition to all admin roles).
--

CREATE TABLE `login_whitelist_emails` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `application_settings`
--
ALTER TABLE `application_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `priority` (`priority`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `blocked_emails`
--
ALTER TABLE `blocked_emails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `blocked_emails_blocked_by_foreign` (`blocked_by`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

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
-- Indexes for table `otp_token`
--
ALTER TABLE `otp_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `otp_user_idx` (`userID`),
  ADD KEY `otp_token_idx` (`token`),
  ADD KEY `otp_expires_idx` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `category` (`category`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `twofa_attempts`
--
ALTER TABLE `twofa_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_idx` (`role`),
  ADD KEY `users_status_idx` (`status`);

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
-- Indexes for table `login_whitelist_emails`
--
ALTER TABLE `login_whitelist_emails`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_whitelist_emails_email_unique` (`email`),
  ADD KEY `login_whitelist_emails_created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `blocked_emails`
--
ALTER TABLE `blocked_emails`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `forum_likes`
--
ALTER TABLE `forum_likes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- AUTO_INCREMENT for table `otp_token`
--
ALTER TABLE `otp_token`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `twofa_attempts`
--
ALTER TABLE `twofa_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `login_whitelist_emails`
--
ALTER TABLE `login_whitelist_emails`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked_emails`
--
ALTER TABLE `blocked_emails`
  ADD CONSTRAINT `blocked_emails_blocked_by_foreign` FOREIGN KEY (`blocked_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `otp_token`
--
ALTER TABLE `otp_token`
  ADD CONSTRAINT `otp_token_user_fk` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `twofa_attempts`
--
ALTER TABLE `twofa_attempts`
  ADD CONSTRAINT `twofa_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_whitelist_emails`
--
ALTER TABLE `login_whitelist_emails`
  ADD CONSTRAINT `login_whitelist_emails_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
