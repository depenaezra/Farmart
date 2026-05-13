-- Manual DB patch for Farmart (MySQL/MariaDB)
-- Fixes: missing `system_settings`, `whitelisted_emails`,
-- and adds suspension columns for forum moderation.
--
-- Run this on your target database (example: agriconnect).

-- ----------------------------
-- 1) Core settings tables
-- ----------------------------
CREATE TABLE IF NOT EXISTS `system_settings` (
  `key` VARCHAR(100) NOT NULL,
  `value` TEXT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `whitelisted_emails` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `created_by` INT(11) UNSIGNED NULL,
  `created_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_whitelisted_emails_email` (`email`),
  KEY `idx_whitelisted_emails_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional foreign key (safe to skip if it fails)
-- ALTER TABLE `whitelisted_emails`
--   ADD CONSTRAINT `whitelisted_emails_created_by_fk`
--   FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
--   ON DELETE SET NULL;

INSERT INTO `system_settings` (`key`, `value`, `updated_at`)
VALUES ('email_whitelist_enabled', '0', NOW())
ON DUPLICATE KEY UPDATE `updated_at` = VALUES(`updated_at`);

-- ----------------------------
-- 2) Forum moderation fields
-- ----------------------------
-- MySQL 8+ supports ADD COLUMN IF NOT EXISTS.
-- If you're on older MySQL/MariaDB and this fails, add the columns manually.

ALTER TABLE `forum_posts`
  ADD COLUMN IF NOT EXISTS `is_suspended` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `suspended_at` DATETIME NULL,
  ADD COLUMN IF NOT EXISTS `suspended_reason` VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS `suspended_by` INT(11) UNSIGNED NULL;

ALTER TABLE `forum_comments`
  ADD COLUMN IF NOT EXISTS `is_suspended` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `suspended_at` DATETIME NULL,
  ADD COLUMN IF NOT EXISTS `suspended_reason` VARCHAR(255) NULL,
  ADD COLUMN IF NOT EXISTS `suspended_by` INT(11) UNSIGNED NULL;

