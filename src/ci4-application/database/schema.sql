-- AgriConnect Database Schema
-- CodeIgniter 4 Application
-- MySQL/MariaDB
-- Version: 1.0.0
-- Date: November 20, 2024

-- Set character set
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Drop tables if exist (for clean installation)
DROP TABLE IF EXISTS `forum_comments`;
DROP TABLE IF EXISTS `forum_posts`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `users`;

-- ============================================================
-- Table: users
-- Description: All system users (farmers, buyers, admins)
-- ============================================================
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('farmer','buyer','admin') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `cooperative` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: products
-- Description: Products listed by farmers
-- ============================================================
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `farmer_id` int(11) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL DEFAULT 'kilo',
  `category` enum('vegetables','fruits','grains','other') NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `status` enum('available','out-of-stock','pending','rejected') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `farmer_id` (`farmer_id`),
  KEY `category` (`category`),
  KEY `status` (`status`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: orders
-- Description: Orders placed by buyers
-- ============================================================
CREATE TABLE `orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(50) NOT NULL,
  `buyer_id` int(11) unsigned NOT NULL,
  `farmer_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','processing','completed','cancelled') DEFAULT 'pending',
  `delivery_address` text,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `buyer_id` (`buyer_id`),
  KEY `farmer_id` (`farmer_id`),
  KEY `product_id` (`product_id`),
  KEY `status` (`status`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: messages
-- Description: Direct messages between users
-- ============================================================
CREATE TABLE `messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) unsigned NOT NULL,
  `receiver_id` int(11) unsigned NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `is_read` (`is_read`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: announcements
-- Description: System announcements by admin
-- ============================================================
CREATE TABLE `announcements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` enum('weather','government','market','general') NOT NULL,
  `priority` enum('low','medium','high') DEFAULT 'medium',
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `priority` (`priority`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: forum_posts
-- Description: Community forum posts
-- ============================================================
CREATE TABLE `forum_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `likes` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category` (`category`),
  CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Table: forum_comments
-- Description: Comments on forum posts
-- ============================================================
CREATE TABLE `forum_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- Insert sample data
-- ============================================================

-- Sample users (passwords are hashed 'password123')
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `location`, `cooperative`, `status`) VALUES
('Juan Santos', 'juan.santos@example.com', '0917-123-4567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer', 'Brgy. Aga, Nasugbu', 'Nasugbu Farmers Coop', 'active'),
('Maria Cruz', 'maria.cruz@example.com', '0918-234-5678', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer', 'Brgy. Wawa, Nasugbu', 'Green Valley Coop', 'active'),
('Pedro Reyes', 'pedro.reyes@example.com', '0919-345-6789', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer', 'Brgy. Lumbangan, Nasugbu', 'Batangas Corn Growers', 'active'),
('Rosa Garcia', 'rosa.garcia@example.com', '0920-456-7890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer', 'Brgy. Poblacion, Nasugbu', 'Nasugbu Farmers Coop', 'active'),
('Ana Bautista', 'ana.bautista@example.com', '0921-567-8901', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'farmer', 'Brgy. Mataas na Pulo, Nasugbu', 'Green Valley Coop', 'active'),
('Miguel Buyer', 'miguel.buyer@example.com', '0922-678-9012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'buyer', 'Nasugbu Town Center', NULL, 'active'),
('Carmen Buyer', 'carmen.buyer@example.com', '0923-789-0123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'buyer', 'Brgy. Poblacion, Nasugbu', NULL, 'active'),
('Admin User', 'admin@agriconnect.ph', '0943-123-4567', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Nasugbu', NULL, 'active');

-- Sample products
INSERT INTO `products` (`farmer_id`, `name`, `description`, `price`, `unit`, `category`, `stock_quantity`, `location`, `image_url`, `status`) VALUES
(1, 'Fresh Tomatoes', 'Farm-fresh tomatoes, perfect for cooking. Harvested daily.', 80.00, 'kilo', 'vegetables', 50, 'Brgy. Aga, Nasugbu', '/uploads/products/tomatoes.jpg', 'available'),
(2, 'Organic Lettuce', 'Crispy organic lettuce grown without pesticides.', 60.00, 'kilo', 'vegetables', 30, 'Brgy. Wawa, Nasugbu', '/uploads/products/lettuce.jpg', 'available'),
(3, 'Native Corn', 'Sweet native corn, freshly harvested.', 45.00, 'kilo', 'grains', 100, 'Brgy. Lumbangan, Nasugbu', '/uploads/products/corn.jpg', 'available'),
(4, 'Banana Lakatan', 'Premium lakatan bananas, naturally ripened.', 70.00, 'kilo', 'fruits', 80, 'Brgy. Poblacion, Nasugbu', '/uploads/products/banana.jpg', 'available'),
(1, 'Eggplant', 'Fresh eggplants for your favorite dishes.', 55.00, 'kilo', 'vegetables', 40, 'Brgy. Aga, Nasugbu', '/uploads/products/eggplant.jpg', 'available'),
(5, 'Pineapple', 'Sweet and juicy pineapples from local farms.', 90.00, 'piece', 'fruits', 25, 'Brgy. Mataas na Pulo, Nasugbu', '/uploads/products/pineapple.jpg', 'available'),
(2, 'Cabbage', 'Fresh cabbage, perfect for salads and cooking.', 50.00, 'kilo', 'vegetables', 35, 'Brgy. Wawa, Nasugbu', '/uploads/products/cabbage.jpg', 'available'),
(3, 'Sweet Potato', 'Organic sweet potatoes, rich in nutrients.', 65.00, 'kilo', 'vegetables', 60, 'Brgy. Lumbangan, Nasugbu', '/uploads/products/sweetpotato.jpg', 'available');

-- Sample announcements
INSERT INTO `announcements` (`title`, `content`, `category`, `priority`, `created_by`) VALUES
('Weather Alert: Heavy Rain Expected', 'PAGASA warns of heavy rain this weekend (Nov 23-24). Please secure your crops and prepare drainage systems. Flash floods possible in low-lying areas.', 'weather', 'high', 8),
('New Government Subsidy Program', 'DA announces new subsidy program for small-scale farmers. Registration starts next week at the Municipal Agriculture Office. Requirements: Valid ID, farm documents, cooperative membership.', 'government', 'medium', 8),
('Market Price Update - November 2024', 'Current market prices remain stable: Tomatoes: ₱70-85/kg, Lettuce: ₱55-65/kg, Eggplant: ₱50-60/kg, Corn: ₱40-50/kg. Direct selling through AgriConnect ensures better prices for both farmers and buyers.', 'market', 'low', 8),
('AgriConnect Platform Updates', 'New features now available: Product image upload, order tracking, and enhanced messaging. Thank you for being part of our growing community!', 'general', 'low', 8);

-- Sample forum posts
INSERT INTO `forum_posts` (`user_id`, `title`, `content`, `category`, `likes`) VALUES
(1, 'Best Practices for Tomato Growing in Nasugbu', 'Hello fellow farmers! I would like to share my experience growing tomatoes here in Nasugbu. The key is proper irrigation and pest control. Anyone else has tips?', 'farming tips', 12),
(2, 'Organic Farming Techniques', 'I have been doing organic farming for 3 years now. Happy to share techniques and answer questions about pesticide-free farming.', 'farming tips', 18),
(3, 'Where to Buy Quality Seeds?', 'Can anyone recommend good suppliers for vegetable seeds in Batangas? Looking for quality seeds at reasonable prices.', 'general', 5),
(6, 'Thank You Farmers!', 'As a buyer, I just want to thank all the farmers here for providing fresh produce. The quality is amazing and prices are fair. Salamat po!', 'general', 25);

-- Sample forum comments
INSERT INTO `forum_comments` (`post_id`, `user_id`, `comment`) VALUES
(1, 2, 'Great tips Juan! I also use mulching to retain moisture. Works very well.'),
(1, 4, 'Thank you for sharing. Do you use any organic pesticides?'),
(2, 1, 'Maria, can you share your composting process? I want to try organic farming too.'),
(3, 1, 'You can try the DA office in Nasugbu. They have good quality seeds.'),
(4, 1, 'Salamat po sa suporta! This platform really helps us connect with buyers directly.');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- End of schema
-- ============================================================
