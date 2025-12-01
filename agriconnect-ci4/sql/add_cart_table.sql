-- Add Cart Table to Existing Database
-- Run this SQL script to add the cart functionality to your existing database

-- Create cart table with primary key
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add unique constraint for user-product combination
ALTER TABLE `cart`
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`);

-- Add indexes for foreign keys
ALTER TABLE `cart`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

-- Add foreign key constraints
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

COMMIT;