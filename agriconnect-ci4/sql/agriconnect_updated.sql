-- Updates for forum: add image support and mentions table

-- Add image_url column to forum_posts
ALTER TABLE `forum_posts`
  ADD COLUMN `image_url` VARCHAR(500) DEFAULT NULL;

-- Create table to store mentions extracted from posts
CREATE TABLE IF NOT EXISTS `forum_mentions` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` INT(11) UNSIGNED NOT NULL,
  `mentioned_user_id` INT(11) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `mentioned_user_id` (`mentioned_user_id`),
  CONSTRAINT `forum_mentions_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `forum_mentions_ibfk_2` FOREIGN KEY (`mentioned_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional: populate forum_mentions by scanning content for @username
-- This requires mapping usernames to user ids; below is an example statement you can run
-- after adjusting for your username column (if you store username separately).
-- Example (pseudo):
-- INSERT INTO forum_mentions (post_id, mentioned_user_id)
-- SELECT p.id, u.id
-- FROM forum_posts p
-- JOIN users u ON INSTR(p.content, CONCAT('@', u.name)) > 0;

-- Note: If your users have a separate "username" column, replace u.name with u.username.

-- Update all existing users to have role 'buyer' to focus on buyer side
UPDATE users SET role = 'buyer' WHERE role IN ('user', 'farmer');

COMMIT;
