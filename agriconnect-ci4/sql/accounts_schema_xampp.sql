-- Farmart/AgriConnect account schema for XAMPP (MySQL/MariaDB)
-- Import this file in phpMyAdmin to restore account storage.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `agriconnect` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `agriconnect`;

-- Drop only account-related tables (safe for account reset workflows)
DROP TABLE IF EXISTS `otp_token`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('farmer','buyer','admin','user') NOT NULL DEFAULT 'buyer',
  `location` VARCHAR(255) DEFAULT NULL,
  `cooperative` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_idx` (`role`),
  KEY `users_status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `otp_token` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userID` INT(11) UNSIGNED NOT NULL,
  `token` VARCHAR(10) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `otp_user_idx` (`userID`),
  KEY `otp_token_idx` (`token`),
  KEY `otp_expires_idx` (`expires_at`),
  CONSTRAINT `otp_token_user_fk`
    FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional starter admin account
-- Email: admin@agriconnect.ph
-- Password: password123
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`, `location`, `cooperative`, `status`)
VALUES
('Admin User', 'admin@agriconnect.ph', '09000000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Nasugbu', NULL, 'active');

COMMIT;
