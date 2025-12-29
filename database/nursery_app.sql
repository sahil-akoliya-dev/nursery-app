-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 20, 2025 at 11:15 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nursery_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'created', 'App\\Models\\User', 'created', 1, NULL, NULL, '{\"attributes\":{\"name\":\"Super Admin\",\"email\":\"admin@nursery-app.com\",\"role\":\"super_admin\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(2, 'default', 'created', 'App\\Models\\User', 'created', 2, NULL, NULL, '{\"attributes\":{\"name\":\"Admin User\",\"email\":\"admin.user@nursery-app.com\",\"role\":\"admin\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(3, 'default', 'created', 'App\\Models\\User', 'created', 3, NULL, NULL, '{\"attributes\":{\"name\":\"Manager User\",\"email\":\"manager@nursery-app.com\",\"role\":\"manager\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(4, 'default', 'created', 'App\\Models\\User', 'created', 4, NULL, NULL, '{\"attributes\":{\"name\":\"John Doe\",\"email\":\"customer@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(5, 'default', 'created', 'App\\Models\\User', 'created', 5, NULL, NULL, '{\"attributes\":{\"name\":\"Helmer Denesik\",\"email\":\"zlind@example.net\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(6, 'default', 'created', 'App\\Models\\User', 'created', 6, NULL, NULL, '{\"attributes\":{\"name\":\"Clifford McCullough\",\"email\":\"owilliamson@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(7, 'default', 'created', 'App\\Models\\User', 'created', 7, NULL, NULL, '{\"attributes\":{\"name\":\"Elvera Spinka\",\"email\":\"alfredo.rohan@example.org\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(8, 'default', 'created', 'App\\Models\\User', 'created', 8, NULL, NULL, '{\"attributes\":{\"name\":\"Cicero Torp\",\"email\":\"breilly@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(9, 'default', 'created', 'App\\Models\\User', 'created', 9, NULL, NULL, '{\"attributes\":{\"name\":\"Mr. Javier Muller\",\"email\":\"dickinson.maryam@example.org\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(10, 'default', 'created', 'App\\Models\\User', 'created', 10, NULL, NULL, '{\"attributes\":{\"name\":\"Mrs. Lesly Raynor II\",\"email\":\"monahan.verner@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(11, 'default', 'created', 'App\\Models\\User', 'created', 11, NULL, NULL, '{\"attributes\":{\"name\":\"Florence Cormier II\",\"email\":\"vandervort.raphaelle@example.org\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(12, 'default', 'created', 'App\\Models\\User', 'created', 12, NULL, NULL, '{\"attributes\":{\"name\":\"Brook Fritsch\",\"email\":\"sfritsch@example.org\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(13, 'default', 'created', 'App\\Models\\User', 'created', 13, NULL, NULL, '{\"attributes\":{\"name\":\"Heath VonRueden\",\"email\":\"rempel.felicita@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(14, 'default', 'created', 'App\\Models\\User', 'created', 14, NULL, NULL, '{\"attributes\":{\"name\":\"Kaylah Borer\",\"email\":\"cassidy.effertz@example.net\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(15, 'default', 'created', 'App\\Models\\User', 'created', 15, NULL, NULL, '{\"attributes\":{\"name\":\"Dr. Reuben Turner\",\"email\":\"pflatley@example.com\",\"role\":\"admin\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(16, 'default', 'created', 'App\\Models\\User', 'created', 16, NULL, NULL, '{\"attributes\":{\"name\":\"Keenan Frami\",\"email\":\"bergstrom.janessa@example.net\",\"role\":\"admin\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(17, 'default', 'created', 'App\\Models\\User', 'created', 17, NULL, NULL, '{\"attributes\":{\"name\":\"Camryn Sipes IV\",\"email\":\"furman.kuphal@example.org\",\"role\":\"manager\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(18, 'default', 'created', 'App\\Models\\User', 'created', 18, NULL, NULL, '{\"attributes\":{\"name\":\"Else Heidenreich\",\"email\":\"hosinski@example.net\",\"role\":\"manager\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(19, 'default', 'created', 'App\\Models\\User', 'created', 19, NULL, NULL, '{\"attributes\":{\"name\":\"Sahil Akoliya\",\"email\":\"sahil@matrixsoftwares.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-12 07:00:48', '2025-12-12 07:00:48'),
(20, 'default', 'created', 'App\\Models\\User', 'created', 20, NULL, NULL, '{\"attributes\":{\"name\":\"Sahil Akoliya\",\"email\":\"sahilakoliya1604@gmail.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-14 14:06:52', '2025-12-14 14:06:52'),
(21, 'default', 'updated', 'App\\Models\\User', 'updated', 20, 'App\\Models\\User', 20, '{\"attributes\":{\"two_factor_enabled\":0},\"old\":{\"two_factor_enabled\":null}}', NULL, '2025-12-14 14:06:52', '2025-12-14 14:06:52'),
(22, 'default', 'created', 'App\\Models\\User', 'created', 21, 'App\\Models\\User', 20, '{\"attributes\":{\"name\":\"Sahil\",\"email\":\"sahil@matrixsoftwaress.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-14 15:17:13', '2025-12-14 15:17:13'),
(23, 'default', 'created', 'App\\Models\\User', 'created', 22, NULL, NULL, '{\"attributes\":{\"name\":\"John Doe\",\"email\":\"john.doe@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-14 18:50:11', '2025-12-14 18:50:11'),
(24, 'default', 'created', 'App\\Models\\User', 'created', 23, NULL, NULL, '{\"attributes\":{\"name\":\"New User\",\"email\":\"newuser@example.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-14 19:20:28', '2025-12-14 19:20:28'),
(25, 'default', 'updated', 'App\\Models\\User', 'updated', 1, 'App\\Models\\User', 1, '{\"attributes\":{\"name\":\"Updated Name\",\"role\":\"manager\"},\"old\":{\"name\":\"Super Admin\",\"role\":\"super_admin\"}}', NULL, '2025-12-14 19:20:29', '2025-12-14 19:20:29'),
(26, 'default', 'created', 'App\\Models\\User', 'created', 24, NULL, NULL, '{\"attributes\":{\"name\":\"Sahil Akoliya\",\"email\":\"sahil@gmail.com\",\"role\":\"customer\",\"two_factor_enabled\":0}}', NULL, '2025-12-14 19:53:24', '2025-12-14 19:53:24'),
(27, 'default', 'updated', 'App\\Models\\User', 'updated', 4, 'App\\Models\\User', 4, '{\"attributes\":{\"name\":\"Updated Name\"},\"old\":{\"name\":\"John Doe\"}}', NULL, '2025-12-14 23:53:03', '2025-12-14 23:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `address_line_1` varchar(255) NOT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `type`, `first_name`, `last_name`, `company`, `address_line_1`, `address_line_2`, `city`, `state`, `postal_code`, `country`, `phone`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 24, 'delivery', 'Sahil', 'Akoliya', NULL, '275', 'Shiv Shakti, Punagam', 'Suart', 'Gujarat', '395010', 'IN', NULL, 1, '2025-12-15 06:46:57', '2025-12-15 06:46:57');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `item_type`, `item_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(5, 24, 'App\\Models\\Product', 3, 1, 49.99, '2025-12-15 06:47:07', '2025-12-15 06:47:07'),
(6, 24, 'App\\Models\\Product', 4, 28, 12.99, '2025-12-15 06:47:30', '2025-12-15 06:55:58');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Indoor Plants', 'indoor-plants', 'Perfect plants for indoor spaces that thrive in low to medium light conditions.', '/images/categories/indoor_plants.png', NULL, 1, 1, '2025-12-12 05:22:45', '2025-12-14 23:09:17'),
(2, 'Outdoor Plants', 'outdoor-plants', 'Beautiful plants for your garden, patio, and outdoor spaces.', '/images/categories/outdoor_plants.png', NULL, 1, 2, '2025-12-12 05:22:45', '2025-12-14 23:09:17'),
(3, 'Succulents', 'succulents', 'Low-maintenance succulents perfect for beginners and busy plant parents.', '/images/categories/succulents.png', NULL, 1, 3, '2025-12-12 05:22:45', '2025-12-14 23:09:17'),
(4, 'Flowering Plants', 'flowering-plants', 'Colorful flowering plants to brighten up any space.', '/images/categories/flowering_plants.png', NULL, 1, 4, '2025-12-12 05:22:45', '2025-12-14 23:09:17'),
(5, 'Herbs', 'herbs', 'Fresh herbs for cooking and culinary purposes.', '/images/categories/herbs.png', NULL, 1, 5, '2025-12-12 05:22:45', '2025-12-14 23:09:17'),
(6, 'Garden Tools', 'garden-tools', 'Essential tools for plant care and gardening.', 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800&q=80', NULL, 1, 6, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(7, 'Pots & Planters', 'pots-planters', 'Beautiful pots and planters for your plants.', 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=800&q=80', NULL, 1, 7, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(8, 'Fertilizers', 'fertilizers', 'Plant nutrients and fertilizers for healthy growth.', 'https://plus.unsplash.com/premium_photo-1679547202440-27d922aea3a8?w=800&q=80', NULL, 1, 8, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(9, 'Low Light Plants', 'low-light-plants', 'Plants that thrive in low light conditions.', NULL, 1, 1, 1, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(10, 'Air Purifying Plants', 'air-purifying-plants', 'Plants that naturally purify indoor air.', NULL, 1, 1, 2, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(11, 'Perennials', 'perennials', 'Plants that come back year after year.', NULL, 2, 1, 1, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(12, 'Annuals', 'annuals', 'Plants that complete their life cycle in one season.', NULL, 2, 1, 2, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(13, 'Pruning Tools', 'pruning-tools', 'Tools for pruning and trimming plants.', NULL, 6, 1, 1, '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(14, 'Watering Tools', 'watering-tools', 'Tools for watering and irrigation.', NULL, 6, 1, 2, '2025-12-12 05:22:45', '2025-12-12 05:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `icon` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT 'green',
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `features`
--

INSERT INTO `features` (`id`, `icon`, `title`, `description`, `color`, `order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'sprout', 'Sustainability First', 'We source our plants from responsible growers and use eco-friendly packaging for every order.', 'green', 1, 1, '2025-12-12 05:30:38', '2025-12-12 05:30:38'),
(2, 'droplets', 'Expert Care', 'Every plant comes with detailed care instructions, and our team is always here to help you succeed.', 'blue', 2, 1, '2025-12-12 05:30:38', '2025-12-12 05:30:38'),
(3, 'heart', 'Community Driven', 'We\'re building a community of plant lovers. Join us for workshops, swaps, and growing together.', 'purple', 3, 1, '2025-12-12 05:30:38', '2025-12-12 05:30:38');

-- --------------------------------------------------------

--
-- Table structure for table `gift_cards`
--

CREATE TABLE `gift_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `initial_value` decimal(10,2) NOT NULL,
  `current_balance` decimal(10,2) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `gift_card_usages`
--

CREATE TABLE `gift_card_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gift_card_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount_used` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_logs`
--

CREATE TABLE `health_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plant_care_reminder_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `helpful_votes`
--

CREATE TABLE `helpful_votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `voteable_type` varchar(255) NOT NULL,
  `voteable_id` bigint(20) UNSIGNED NOT NULL,
  `is_helpful` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `type` varchar(255) NOT NULL,
  `source` varchar(255) NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `review_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `points_balance` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loyalty_points`
--

INSERT INTO `loyalty_points` (`id`, `user_id`, `points`, `type`, `source`, `order_id`, `review_id`, `description`, `points_balance`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 19, 50, 'signup_bonus', 'bonus', NULL, NULL, 'Welcome Bonus', 50, '2026-12-12 07:00:48', '2025-12-12 07:00:48', '2025-12-12 07:00:48'),
(2, 21, 50, 'signup_bonus', 'bonus', NULL, NULL, 'Welcome Bonus', 50, '2026-12-14 15:17:13', '2025-12-14 15:17:13', '2025-12-14 15:17:13'),
(3, 22, 50, 'signup_bonus', 'bonus', NULL, NULL, 'Welcome Bonus', 50, '2026-12-14 18:50:11', '2025-12-14 18:50:11', '2025-12-14 18:50:11'),
(4, 23, 50, 'signup_bonus', 'bonus', NULL, NULL, 'Welcome Bonus', 50, '2026-12-14 19:20:29', '2025-12-14 19:20:29', '2025-12-14 19:20:29'),
(5, 24, 50, 'signup_bonus', 'bonus', NULL, NULL, 'Welcome Bonus', 50, '2026-12-14 19:53:24', '2025-12-14 19:53:24', '2025-12-14 19:53:24'),
(6, 24, 1987, 'earned', 'purchase', 1, NULL, 'Points from order #1', 1987, '2026-12-15 06:35:07', '2025-12-15 06:35:07', '2025-12-15 06:35:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations1`
--

CREATE TABLE `migrations1` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations1`
--

INSERT INTO `migrations1` (`id`, `migration`, `batch`) VALUES
(36, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(37, '2025_09_17_013106_create_users_table', 1),
(38, '2025_09_17_095702_create_permission_tables', 1),
(39, '2025_09_20_021747_create_categories_table', 1),
(40, '2025_09_20_021753_create_products_table', 1),
(41, '2025_09_20_021803_create_plants_table', 1),
(42, '2025_09_20_021840_create_orders_table', 1),
(43, '2025_09_20_021841_create_order_items_table', 1),
(44, '2025_09_20_021848_create_cart_items_table', 1),
(45, '2025_09_20_021851_create_wishlist_items_table', 1),
(46, '2025_09_20_050041_create_plant_care_guides_table', 1),
(47, '2025_09_20_050058_create_plant_care_reminders_table', 1),
(48, '2025_09_20_050112_create_reviews_table', 1),
(49, '2025_09_20_050126_create_helpful_votes_table', 1),
(50, '2025_09_20_051809_add_2fa_to_users_table', 1),
(51, '2025_09_20_051827_create_audit_logs_table', 1),
(52, '2025_09_20_051843_create_loyalty_points_table', 1),
(53, '2025_09_20_124450_create_sessions_table', 1),
(54, '2025_11_03_065717_create_addresses_table', 1),
(55, '2025_11_23_154926_create_vendors_table', 1),
(56, '2025_11_23_154927_add_vendor_id_to_products_table', 1),
(57, '2025_11_23_155215_add_status_to_order_items_table', 1),
(58, '2025_11_23_155258_create_vendor_transactions_table', 1),
(59, '2025_11_24_100831_create_posts_table', 1),
(60, '2025_11_25_051144_create_features_table', 1),
(61, '2025_11_25_051145_create_testimonials_table', 1),
(62, '2025_11_30_070118_create_price_alerts_table', 1),
(63, '2025_11_30_070715_create_vouchers_and_gift_cards_tables', 1),
(64, '2025_11_30_071041_add_points_to_users_and_create_transactions_table', 1),
(65, '2025_11_30_071425_create_health_logs_table', 1),
(66, '2025_11_30_071800_create_community_tables', 1),
(67, '2025_12_11_104155_create_system_settings_table', 1),
(68, '2025_12_11_111954_create_activity_log_table', 1),
(69, '2025_12_11_111955_add_event_column_to_activity_log_table', 1),
(70, '2025_12_11_111956_add_batch_uuid_column_to_activity_log_table', 1),
(71, '2025_12_14_000000_modify_users_role_to_enum', 2),
(72, '2025_12_14_192456_add_google_login_to_users_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 24),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 15),
(2, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 17),
(3, 'App\\Models\\User', 18),
(4, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(4, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 7),
(4, 'App\\Models\\User', 8),
(4, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 12),
(4, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 14),
(4, 'App\\Models\\User', 19),
(4, 'App\\Models\\User', 21),
(4, 'App\\Models\\User', 22),
(4, 'App\\Models\\User', 23);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_transaction_id` varchar(255) DEFAULT NULL COMMENT 'Payment gateway transaction ID',
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`shipping_address`)),
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`billing_address`)),
  `notes` text DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `subtotal`, `tax_amount`, `shipping_amount`, `total_amount`, `status`, `payment_status`, `payment_method`, `payment_transaction_id`, `shipping_address`, `billing_address`, `notes`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`) VALUES
(1, 'ORD-H1ZTLROH', 24, 1840.31, 147.22, 0.00, 1987.53, 'pending', 'pending', 'cod', NULL, '{\"first_name\":\"Sahil\",\"last_name\":\"Akoliya\",\"email\":\"sahilakoliya.dev@gmail.com\",\"phone\":\"9313170701\",\"address\":\"275\",\"city\":\"Suart\",\"state\":\"Gujarat\",\"zip\":\"395010\",\"country\":\"US\"}', '{\"first_name\":\"Sahil\",\"last_name\":\"Akoliya\",\"email\":\"sahilakoliya.dev@gmail.com\",\"phone\":\"9313170701\",\"address\":\"275\",\"city\":\"Suart\",\"state\":\"Gujarat\",\"zip\":\"395010\",\"country\":\"US\"}', NULL, NULL, NULL, '2025-12-15 06:35:07', '2025-12-15 06:35:07');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL COMMENT 'Price at time of purchase',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_type`, `item_id`, `quantity`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Product', 1, 15, 29.99, 'pending', '2025-12-15 06:35:07', '2025-12-15 06:35:07'),
(2, 1, 'App\\Models\\Product', 2, 25, 16.99, 'pending', '2025-12-15 06:35:07', '2025-12-15 06:35:07'),
(3, 1, 'App\\Models\\Product', 3, 7, 49.99, 'pending', '2025-12-15 06:35:07', '2025-12-15 06:35:07'),
(4, 1, 'App\\Models\\Product', 6, 22, 27.99, 'pending', '2025-12-15 06:35:07', '2025-12-15 06:35:07');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'products.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(2, 'products.create', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(3, 'products.update', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(4, 'products.delete', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(5, 'products.manage', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(6, 'plants.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(7, 'plants.create', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(8, 'plants.update', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(9, 'plants.delete', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(10, 'categories.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(11, 'categories.create', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(12, 'categories.update', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(13, 'categories.delete', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(14, 'orders.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(15, 'orders.update', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(16, 'orders.delete', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(17, 'orders.cancel', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(18, 'users.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(19, 'users.create', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(20, 'users.update', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(21, 'users.delete', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(22, 'users.manage', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(23, 'reviews.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(24, 'reviews.approve', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(25, 'reviews.delete', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(26, 'reviews.manage', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(27, 'analytics.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(28, 'analytics.export', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(29, 'audit.view', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(30, 'system.settings', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(31, 'system.backup', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(32, 'vendor.access', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(33, 'vendor.profile.update', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 19, 'api-token', 'ff67ffd41142a5643e930bbfeed4becf0d86ffaff352a3d9b8bf72253063afa3', '[\"*\"]', '2025-12-12 07:02:12', '2025-12-12 09:00:48', '2025-12-12 07:00:48', '2025-12-12 07:02:12'),
(2, 'App\\Models\\User', 21, 'api-token', '727ebd182ce0c10eb4b0fe98efbd67cea1c5dfc35fdb760460d97611feaa7b49', '[\"*\"]', NULL, '2025-12-14 17:17:13', '2025-12-14 15:17:13', '2025-12-14 15:17:13'),
(3, 'App\\Models\\User', 4, 'api-token', '352b0db0eecf235ab58da9fe5eecb02ba4cfef99cf275fbcbd5c1aea4364a70d', '[\"*\"]', NULL, '2025-12-14 17:19:29', '2025-12-14 15:19:29', '2025-12-14 15:19:29'),
(4, 'App\\Models\\User', 4, 'api-token', 'ad421c8f1fc54e27889afd9eab9df5d98736f4e79f6b465adf3c6fb7b6443e20', '[\"*\"]', NULL, '2025-12-14 17:19:34', '2025-12-14 15:19:34', '2025-12-14 15:19:34'),
(5, 'App\\Models\\User', 1, 'api-token', 'ba36621e9f0f6edf49c942ccbe500b40627964c54570dc5efc0e2bb5d8297478', '[\"*\"]', NULL, '2025-12-14 17:19:39', '2025-12-14 15:19:39', '2025-12-14 15:19:39'),
(6, 'App\\Models\\User', 1, 'api-token', '8d68b9f7c2eea426114b783d550ce58371083b5cd6e9c625ef1f0ec22307f6a0', '[\"*\"]', NULL, '2025-12-14 17:20:40', '2025-12-14 15:20:40', '2025-12-14 15:20:40'),
(7, 'App\\Models\\User', 1, 'api-token', 'b13990067663a460acffb3779f24386095041df48b0d0d63bc50edac47df22f2', '[\"*\"]', NULL, '2025-12-14 17:20:49', '2025-12-14 15:20:49', '2025-12-14 15:20:49'),
(8, 'App\\Models\\User', 1, 'api-token', '92d5febbf317579e20179c7025ea2b605b4a2ca9073e4fd3ccce9a243f4403ab', '[\"*\"]', NULL, '2025-12-14 17:20:52', '2025-12-14 15:20:52', '2025-12-14 15:20:52'),
(9, 'App\\Models\\User', 1, 'api-token', '6a9fc71c6fa80ad0d6d55cc07e12ca4b63d7a7dcec3a68e12486418c57bae974', '[\"*\"]', NULL, '2025-12-14 17:20:53', '2025-12-14 15:20:53', '2025-12-14 15:20:53'),
(10, 'App\\Models\\User', 1, 'api-token', '8387b5ec0d8159a43a46a7434e9ec8dd0931c34ef8c622fe469effe72a1a7c2c', '[\"*\"]', NULL, '2025-12-14 17:20:54', '2025-12-14 15:20:54', '2025-12-14 15:20:54'),
(11, 'App\\Models\\User', 1, 'api-token', 'e4037cff3986ddc5797f140286c778b5c1d5ee7228d7767c361f140b183c0fa4', '[\"*\"]', NULL, '2025-12-14 17:26:25', '2025-12-14 15:26:25', '2025-12-14 15:26:25'),
(12, 'App\\Models\\User', 1, 'api-token', '84d4e784c19a72ab9642e971f50d3290a964c07b54b0ec88f6bbe13f68aa9953', '[\"*\"]', NULL, '2025-12-14 17:26:59', '2025-12-14 15:26:59', '2025-12-14 15:26:59'),
(13, 'App\\Models\\User', 1, 'api-token', '199e112cfa9c39550f2ee0a16140757b475adf2fbd628d2bd53bd7a10ce54e80', '[\"*\"]', '2025-12-14 15:31:45', '2025-12-14 17:31:10', '2025-12-14 15:31:10', '2025-12-14 15:31:45'),
(14, 'App\\Models\\User', 4, 'api-token', 'e38368e7274b7ca3e00943ffb3c26df604fa9282d8e0da3deb21b7540b74c7a6', '[\"*\"]', NULL, '2025-12-14 19:17:49', '2025-12-14 17:17:49', '2025-12-14 17:17:49'),
(15, 'App\\Models\\User', 1, 'api-token', '335b1ede78407d5aa75960e27ab72a07ffc9139f9bcc4f4884719e8d69b327f1', '[\"*\"]', '2025-12-14 18:11:13', '2025-12-14 20:11:11', '2025-12-14 18:11:11', '2025-12-14 18:11:13'),
(17, 'App\\Models\\User', 3, 'api-token', 'b43fcbff8215016fe0bbe0c3961bcd46c2cd8097ec394901ba2f66b5df6b2a96', '[\"*\"]', NULL, '2025-12-14 20:11:11', '2025-12-14 18:11:11', '2025-12-14 18:11:11'),
(18, 'App\\Models\\User', 4, 'api-token', 'fe1540d3a9b8fee691bb05f398d29ffbe49a1f567ba49c9dfb5227a8b5981153', '[\"*\"]', NULL, '2025-12-14 20:11:12', '2025-12-14 18:11:12', '2025-12-14 18:11:12'),
(19, 'App\\Models\\User', 1, 'api-token', 'b2cd58be6435809a8df0f8f526024d7048603a13d1b0e8bce5bc471e931a6800', '[\"*\"]', '2025-12-14 18:24:47', '2025-12-14 20:24:45', '2025-12-14 18:24:45', '2025-12-14 18:24:47'),
(21, 'App\\Models\\User', 3, 'api-token', '7b47216e4b193e0cc734c9ea67e4ac375da348aa7cb77b9decb0feebe7618cf6', '[\"*\"]', NULL, '2025-12-14 20:24:45', '2025-12-14 18:24:45', '2025-12-14 18:24:45'),
(22, 'App\\Models\\User', 4, 'api-token', '2807fdab7b6a73d40683c0809983e49c3c46542298b9bed61e5967691851c931', '[\"*\"]', NULL, '2025-12-14 20:24:46', '2025-12-14 18:24:46', '2025-12-14 18:24:46'),
(23, 'App\\Models\\User', 1, 'api-token', 'a0440f4116f80e84bbbdb7033be8163e13bc695fe651758cd3c47f8e7f5103bf', '[\"*\"]', '2025-12-14 18:28:58', '2025-12-14 20:28:56', '2025-12-14 18:28:56', '2025-12-14 18:28:58'),
(25, 'App\\Models\\User', 3, 'api-token', 'a4378def915bb349f2d643615a6bf1c67f203c39caa6761bea7391821a4830aa', '[\"*\"]', NULL, '2025-12-14 20:28:56', '2025-12-14 18:28:56', '2025-12-14 18:28:56'),
(26, 'App\\Models\\User', 4, 'api-token', 'a03e541ce7c202dfa62cbce19ca7974697ab32c0a302c61fc4cc3dfc94335086', '[\"*\"]', NULL, '2025-12-14 20:28:57', '2025-12-14 18:28:57', '2025-12-14 18:28:57'),
(27, 'App\\Models\\User', 4, 'api-token', '0f16d6924b7f1a870a6e3b4a266d73644990bbca9c255b38096f91fd1626cc79', '[\"*\"]', '2025-12-14 18:30:15', '2025-12-14 20:30:10', '2025-12-14 18:30:10', '2025-12-14 18:30:15'),
(28, 'App\\Models\\User', 1, 'api-token', 'bc069c47ce1db6924316af121fe1d157d7ba348cf4aa60e86c9cfb4fee74d42a', '[\"*\"]', '2025-12-14 18:30:27', '2025-12-14 20:30:19', '2025-12-14 18:30:19', '2025-12-14 18:30:27'),
(29, 'App\\Models\\User', 1, 'api-token', '7c6eb7e46dae9eccd37dedee26b5ac0dd55a53755bf94a4c22a8675509ef8c89', '[\"*\"]', '2025-12-14 18:30:50', '2025-12-14 20:30:49', '2025-12-14 18:30:49', '2025-12-14 18:30:50'),
(31, 'App\\Models\\User', 3, 'api-token', '98378d73262286328a0a6bd58aed59910507e2996374f89cbf1b459f91949a79', '[\"*\"]', NULL, '2025-12-14 20:30:49', '2025-12-14 18:30:49', '2025-12-14 18:30:49'),
(32, 'App\\Models\\User', 4, 'api-token', '36f72537dc5b58fcfab11a893a0e0671afac5dea38490f12e39a49aa97edddb2', '[\"*\"]', '2025-12-14 18:39:53', '2025-12-14 20:39:48', '2025-12-14 18:39:48', '2025-12-14 18:39:53'),
(33, 'App\\Models\\User', 22, 'api-token', 'f5439156c60b7a9a4f31a293a0f94a990df7a7eb5dbbe59e4c1a11017dd3a653', '[\"*\"]', NULL, '2025-12-14 20:50:11', '2025-12-14 18:50:11', '2025-12-14 18:50:11'),
(36, 'App\\Models\\User', 1, 'api-token', 'c41fe7eab942153b7c7f0b3de888f7b248b6cf67b6eebfe6fc8de1fb4f8322a3', '[\"*\"]', '2025-12-14 19:20:29', '2025-12-14 21:20:28', '2025-12-14 19:20:28', '2025-12-14 19:20:29'),
(37, 'App\\Models\\User', 4, 'api-token', '3d47e8309c8058dc1465ea8bb10cf6bfa54f005b0e5f6dabc0365d7beaa091e9', '[\"*\"]', NULL, '2025-12-14 21:20:28', '2025-12-14 19:20:28', '2025-12-14 19:20:28'),
(38, 'App\\Models\\User', 3, 'api-token', 'be260c0ee028d8b19f523f26696c9e7f8aeb35e4ced2ca8ce6faf64fcbab05bb', '[\"*\"]', '2025-12-14 19:20:28', '2025-12-14 21:20:28', '2025-12-14 19:20:28', '2025-12-14 19:20:28'),
(41, 'App\\Models\\User', 24, 'api-token', '7952ce40f7457bc6961d8bfc6f1a4342a92aae1d26b98b90ca3a02368cd2d8d1', '[\"*\"]', '2025-12-14 20:12:47', '2025-12-14 21:53:24', '2025-12-14 19:53:24', '2025-12-14 20:12:47'),
(42, 'App\\Models\\User', 24, 'api-token', '54149797e41311e4bf3f063f3e71a0d2517265bc8a261fbe51f4fe57b86c9d23', '[\"*\"]', NULL, '2025-12-15 23:40:03', '2025-12-14 23:40:03', '2025-12-14 23:40:03'),
(43, 'App\\Models\\User', 1, 'api-token', 'dc371f391ff6cc5bacf09843fe65719a051ce7fc4d661fcad6dd784418333dcc', '[\"*\"]', NULL, '2025-12-15 23:42:48', '2025-12-14 23:42:48', '2025-12-14 23:42:48'),
(44, 'App\\Models\\User', 1, 'api-token', '3a703eda67d2d49ee1ad2d6c3bc051ffcecdfed11259d9dbb4931338bf6bd3ed', '[\"*\"]', NULL, '2025-12-15 23:42:54', '2025-12-14 23:42:54', '2025-12-14 23:42:54'),
(46, 'App\\Models\\User', 1, 'api-token', 'fbb92aa20af635a3afb89c336ce55fc2b243b9fdff2429aef3b8de003f496adf', '[\"*\"]', NULL, '2025-12-15 23:53:02', '2025-12-14 23:53:02', '2025-12-14 23:53:02'),
(47, 'App\\Models\\User', 4, 'api-token', 'fb3775ba784e4fed0e0dab362616d8da0bfc0c5acbed2b8727e663f9efa2a068', '[\"*\"]', '2025-12-14 23:53:04', '2025-12-15 23:53:03', '2025-12-14 23:53:03', '2025-12-14 23:53:04'),
(50, 'App\\Models\\User', 24, 'api-token', 'f64a722015a126a9928d76b063fcd9e4e7340390acbb82c9c268459120fe3e06', '[\"*\"]', NULL, '2025-12-16 00:00:13', '2025-12-15 00:00:13', '2025-12-15 00:00:13'),
(51, 'App\\Models\\User', 24, 'api-token', '7dc515910a991a72e09069ec352eca7b20e72c9c20ac5724ee41a098ba706218', '[\"*\"]', NULL, '2025-12-16 05:49:01', '2025-12-15 05:49:01', '2025-12-15 05:49:01'),
(52, 'App\\Models\\User', 24, 'api-token', 'e222ff319fffd0b7a60b4f1c65e4c12dcc32086def5d082d2d22ebe0d9b70885', '[\"*\"]', NULL, '2025-12-16 06:55:40', '2025-12-15 06:55:40', '2025-12-15 06:55:40'),
(53, 'App\\Models\\User', 24, 'api-token', 'd655a3c97a0330bf5e8efe2e4dd90bdf59ebeec76c042dabfaac5ce5d013e957', '[\"*\"]', NULL, '2025-12-16 06:55:57', '2025-12-15 06:55:57', '2025-12-15 06:55:57'),
(54, 'App\\Models\\User', 24, 'api-token', '90002b26c4bf19a534d98535bb76f683c961aa8abb4b74c5800fd5accec1312a', '[\"*\"]', NULL, '2025-12-16 06:56:16', '2025-12-15 06:56:16', '2025-12-15 06:56:16'),
(55, 'App\\Models\\User', 24, 'api-token', '4c26e01be21290e476de7f67868cca64ad07a8d5afe39099326c67d59fb0a3ce', '[\"*\"]', NULL, '2025-12-16 06:56:56', '2025-12-15 06:56:56', '2025-12-15 06:56:56'),
(56, 'App\\Models\\User', 24, 'api-token', '1381adbf45bd5bfc0f546310698386829252f4bcd15c8b2ff19dfe62bccf074d', '[\"*\"]', NULL, '2025-12-16 06:58:36', '2025-12-15 06:58:36', '2025-12-15 06:58:36'),
(57, 'App\\Models\\User', 24, 'api-token', '9eef828e402053aa777e478c03cf2016974e7e14856b9b0f1d08a6b643762600', '[\"*\"]', NULL, '2025-12-16 06:59:11', '2025-12-15 06:59:11', '2025-12-15 06:59:11'),
(58, 'App\\Models\\User', 24, 'api-token', '5986f99a2973edeaf589e5bad71bacbd002c6247f67d4f189045b1f9e1847f94', '[\"*\"]', NULL, '2025-12-16 07:51:13', '2025-12-15 07:51:13', '2025-12-15 07:51:13'),
(59, 'App\\Models\\User', 24, 'api-token', '47576ceece7d7739e7c99ba0cc657c32935f741aec6011a9b426200284f68b47', '[\"*\"]', NULL, '2025-12-16 07:52:04', '2025-12-15 07:52:04', '2025-12-15 07:52:04'),
(60, 'App\\Models\\User', 24, 'api-token', '6ab2da1a97bc227114ccc068d5eb1ca732d8ea66132b2d4f24fad6355333826e', '[\"*\"]', '2025-12-15 22:10:03', '2025-12-16 07:52:33', '2025-12-15 07:52:33', '2025-12-15 22:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `plants`
--

CREATE TABLE `plants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `scientific_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `in_stock` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sku` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `care_instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`care_instructions`)),
  `plant_characteristics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`plant_characteristics`)),
  `plant_type` varchar(255) NOT NULL DEFAULT 'indoor',
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `plants`
--

INSERT INTO `plants` (`id`, `name`, `slug`, `scientific_name`, `description`, `short_description`, `price`, `sale_price`, `stock_quantity`, `in_stock`, `is_featured`, `is_active`, `sku`, `images`, `care_instructions`, `plant_characteristics`, `plant_type`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Monstera Deliciosa', 'monstera-deliciosa-plant', 'Monstera deliciosa', 'A climbing, flowering plant native to tropical forests of southern Mexico, south to Panama. Famous for its fenestrated leaves that resemble Swiss cheese.', 'The iconic \"Swiss Cheese Plant\" for tropical vibes.', 35.00, NULL, 20, 1, 1, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1614594975525-e45890e2e122?w=800&q=80\"]', '{\"water\":\"Weekly\",\"light\":\"Bright indirect\",\"humidity\":\"High\",\"temperature\":\"65-85\\u00b0F\"}', '{\"height\":\"Small to Large\",\"growth_rate\":\"Fast\",\"toxicity\":\"Toxic to pets\"}', 'indoor', 1, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(2, 'Fiddle Leaf Fig', 'fiddle-leaf-fig-plant', 'Ficus lyrata', 'A species of flowering plant in the mulberry and fig family Moraceae. It is native to western Africa, from Cameroon west to Sierra Leone.', 'Structural tree with large, violin-shaped leaves.', 55.00, 45.00, 15, 1, 1, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1612435889250-5283e22032e7?w=800&q=80\"]', '{\"water\":\"When top inch dries\",\"light\":\"Bright filtered\",\"humidity\":\"Moderate\",\"temperature\":\"60-75\\u00b0F\"}', '{\"height\":\"Large (Tree)\",\"growth_rate\":\"Slow\",\"toxicity\":\"Toxic\"}', 'indoor', 1, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(3, 'Snake Plant', 'snake-plant-plant', 'Dracaena trifasciata', 'A species of flowering plant in the family Asparagaceae, native to tropical West Africa from Nigeria east to the Congo.', 'Indestructible air purifier, perfect for low light.', 25.00, NULL, 50, 1, 0, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1599598425947-70e021653e5c?w=800&q=80\"]', '{\"water\":\"Monthly\",\"light\":\"Low to Bright\",\"humidity\":\"Low\",\"temperature\":\"55-85\\u00b0F\"}', '{\"height\":\"Medium\",\"growth_rate\":\"Medium\",\"toxicity\":\"Toxic to pets\"}', 'indoor', 1, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(4, 'Lavender', 'lavender-plant-outdoor', 'Lavandula', 'A genus of 47 known species of flowering plants in the mint family, Lamiaceae. It is native to the Old World and is found in Cape Verde and the Canary Islands.', 'Fragrant herb beloved for its calming scent.', 12.00, NULL, 40, 1, 0, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1499002238440-d264edd596ec?w=800&q=80\"]', '{\"water\":\"Sparingly\",\"light\":\"Full Sun\",\"humidity\":\"Low\",\"temperature\":\"Hardy\"}', '{\"height\":\"Small shrub\",\"growth_rate\":\"Medium\",\"toxicity\":\"Non-toxic\"}', 'outdoor', 2, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(5, 'Aloe Vera', 'aloe-vera-plant', 'Aloe barbadensis miller', 'A succulent plant species of the genus Aloe. An evergreen perennial, it originates from the Arabian Peninsula, but grows wild in tropical, semi-tropical, and arid climates around the world.', 'Soothing succulent with medicinal gel.', 15.00, NULL, 30, 1, 0, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1596547609652-9cf5d8d76921?w=800&q=80\"]', '{\"water\":\"Bi-weekly\",\"light\":\"Bright direct\",\"humidity\":\"Low\",\"temperature\":\"65-80\\u00b0F\"}', '{\"height\":\"Small\",\"growth_rate\":\"Slow\",\"toxicity\":\"Toxic if eaten\"}', 'succulent', 3, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(6, 'Peace Lily', 'peace-lily-plant', 'Spathiphyllum', 'Adaptive and low-maintenance, the Peace Lily is famous for its white spoon-shaped flowers and air-purifying capabilities.', 'Elegant white blooms for low-light spaces.', 22.00, NULL, 25, 1, 1, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1593482892290-f54927ae1bb6?w=800&q=80\"]', '{\"water\":\"Keep moist\",\"light\":\"Low to partial\",\"humidity\":\"High\",\"temperature\":\"65-80\\u00b0F\"}', '{\"height\":\"Medium\",\"growth_rate\":\"Fast\",\"toxicity\":\"Toxic to pets\"}', 'indoor', 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(7, 'Rosemary', 'rosemary-plant-herb', 'Salvia rosmarinus', 'A woody, perennial herb with fragrant, evergreen, needle-like leaves and white, pink, purple, or blue flowers, native to the Mediterranean region.', 'Aromatic culinary herb.', 10.00, NULL, 60, 1, 0, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1545165375-ce8d5e8b9f8e?w=800&q=80\"]', '{\"water\":\"Allow to dry\",\"light\":\"Full sun\",\"humidity\":\"Low\",\"temperature\":\"60-80\\u00b0F\"}', '{\"height\":\"Small shrub\",\"growth_rate\":\"Medium\",\"toxicity\":\"Non-toxic\"}', 'indoor', 5, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(8, 'Rubber Plant', 'rubber-plant', 'Ficus elastica', 'With its large, glossy, dark green leaves, the Rubber Plant is a bold statement piece. Native to southeast Asia, it can grow into a large tree in the wild but adapts well to indoor living.', 'Glossy, dark leaves for a modern look.', 40.00, NULL, 10, 1, 0, 1, NULL, '[\"https:\\/\\/images.unsplash.com\\/photo-1463936575829-25148e1db1b8?w=800&q=80\"]', '{\"water\":\"Weekly\",\"light\":\"Bright indirect\",\"humidity\":\"Normal\",\"temperature\":\"60-75\\u00b0F\"}', '{\"height\":\"Large\",\"growth_rate\":\"Medium\",\"toxicity\":\"Toxic to pets\"}', 'indoor', 1, '2025-12-12 05:22:46', '2025-12-12 05:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `plant_care_guides`
--

CREATE TABLE `plant_care_guides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `plant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plant_type` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `care_level` enum('beginner','intermediate','expert') NOT NULL DEFAULT 'beginner',
  `light_requirements` varchar(255) DEFAULT NULL,
  `water_needs` varchar(255) DEFAULT NULL,
  `humidity_requirements` varchar(255) DEFAULT NULL,
  `temperature_range` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`temperature_range`)),
  `soil_type` varchar(255) DEFAULT NULL,
  `fertilizer_schedule` text DEFAULT NULL,
  `repotting_frequency` varchar(255) DEFAULT NULL,
  `pruning_instructions` text DEFAULT NULL,
  `common_problems` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`common_problems`)),
  `seasonal_care` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seasonal_care`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plant_care_reminders`
--

CREATE TABLE `plant_care_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `plant_care_guide_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reminder_type` enum('watering','fertilizing','repotting','pruning','general') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `scheduled_date` datetime NOT NULL,
  `frequency` enum('daily','weekly','monthly','seasonal','custom','one_time') NOT NULL DEFAULT 'one_time',
  `frequency_value` int(11) DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notification_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `point_transactions`
--

CREATE TABLE `point_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `point_transactions`
--

INSERT INTO `point_transactions` (`id`, `user_id`, `points`, `type`, `description`, `order_id`, `created_at`, `updated_at`) VALUES
(1, 24, 1987, 'purchase', 'Earned from Order #ORD-H1ZTLROH', 1, '2025-12-15 06:35:07', '2025-12-15 06:35:07');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `excerpt`, `content`, `image`, `user_id`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Top 10 Air Purifying Plants for Your Home', 'top-10-air-purifying-plants-for-your-home', 'Discover the best plants to clean the air in your home and improve your indoor environment. NASA-approved plants that remove toxins and boost oxygen levels.', '\n<p>Indoor air quality is a major concern for many homeowners, especially in urban environments where pollution and sealed buildings can trap harmful toxins. Fortunately, nature has provided us with a simple, beautiful solution: plants! Here are the top 10 air-purifying plants that are easy to care for and look great in any room.</p>\n\n<h3>1. Snake Plant (Sansevieria)</h3>\n<p>Also known as Mother-in-Law\'s Tongue, this plant is one of the best for filtering out formaldehyde, which is commonly found in cleaning products, toilet paper, and personal care products. It\'s also one of the few plants that releases oxygen at night, making it perfect for bedrooms.</p>\n\n<h3>2. Spider Plant (Chlorophytum comosum)</h3>\n<p>Great for beginners, the spider plant battles benzene, formaldehyde, carbon monoxide and xylene. It\'s incredibly resilient and produces baby plants that you can propagate and share with friends.</p>\n\n<h3>3. Peace Lily (Spathiphyllum)</h3>\n<p>A beautiful flowering plant that removes ammonia, benzene, formaldehyde, and trichloroethylene. The Peace Lily will even tell you when it needs water by drooping its leaves.</p>\n\n<h3>4. Pothos (Devil\'s Ivy)</h3>\n<p>This trailing plant is nearly impossible to kill and excels at removing formaldehyde from the air. It\'s perfect for hanging baskets or training up a moss pole.</p>\n\n<h3>5. Rubber Plant (Ficus elastica)</h3>\n<p>With its large, glossy leaves, the rubber plant is excellent at removing toxins from the air while adding a bold architectural statement to your space.</p>\n\n<h3>6. Boston Fern</h3>\n<p>This lush fern is particularly effective at removing formaldehyde and acts as a natural humidifier, adding moisture to dry indoor air.</p>\n\n<h3>7. Aloe Vera</h3>\n<p>Beyond its healing properties, aloe vera removes formaldehyde and benzene from the air. It\'s also incredibly low-maintenance.</p>\n\n<h3>8. Bamboo Palm</h3>\n<p>This elegant palm filters out benzene and trichloroethylene while adding a tropical touch to your home.</p>\n\n<h3>9. English Ivy</h3>\n<p>Studies have shown that English ivy can reduce airborne mold particles, making it ideal for those with allergies.</p>\n\n<h3>10. ZZ Plant</h3>\n<p>This modern, glossy plant removes toxins while tolerating low light and irregular watering.</p>\n\n<p>Adding these plants to your home not only improves air quality but also adds a touch of greenery that can boost your mood, productivity, and overall well-being. Start with 2-3 plants and watch your indoor jungle grow!</p>\n', 'https://images.unsplash.com/photo-1512428908174-cc784f0b0583?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-26 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(2, 'How to Care for Succulents: A Complete Beginner\'s Guide', 'how-to-care-for-succulents-a-complete-beginners-guide', 'Succulents are popular for a reason. Learn the basics of watering, light, and soil to keep them thriving. Master the art of succulent care with our comprehensive guide.', '\n<p>Succulents are known for being low-maintenance, but they still have specific needs. The most common mistake beginners make is overwatering, which can quickly lead to root rot. Follow this guide to keep your succulents healthy and happy.</p>\n\n<h3>Understanding Succulents</h3>\n<p>Succulents are plants that store water in their leaves, stems, or roots. This adaptation allows them to survive in arid environments. Popular types include echeveria, jade plants, aloe vera, and sedums.</p>\n\n<h3>Light Requirements</h3>\n<p>Most succulents love bright, indirect light. A south-facing window is usually ideal, but be careful of intense afternoon sun which can scorch leaves. If your succulent starts stretching (etiolation), it needs more light. Consider using a grow light if natural light is limited.</p>\n\n<h3>The \"Soak and Dry\" Watering Method</h3>\n<p>This is the golden rule of succulent care: water thoroughly, then let the soil dry out completely before watering again. In summer, this might mean watering every 7-10 days. In winter, you might water only once a month. Always check the soil first - if it\'s damp, don\'t water.</p>\n\n<h3>Soil Matters</h3>\n<p>Use a well-draining cactus mix to prevent root rot. You can make your own by mixing regular potting soil with perlite or coarse sand in a 1:1 ratio. The soil should dry quickly after watering.</p>\n\n<h3>Pot Selection</h3>\n<p>Always use pots with drainage holes. Terracotta pots are ideal because they\'re porous and allow excess moisture to evaporate.</p>\n\n<h3>Fertilizing</h3>\n<p>Succulents don\'t need much fertilizer. Feed them with a diluted, balanced fertilizer once during the growing season (spring/summer).</p>\n\n<h3>Common Problems and Solutions</h3>\n<ul>\n<li><strong>Wrinkled leaves:</strong> Needs water</li>\n<li><strong>Soft, mushy leaves:</strong> Overwatered - stop watering immediately</li>\n<li><strong>Stretched growth:</strong> Needs more light</li>\n<li><strong>Brown spots:</strong> Sunburn - move to less intense light</li>\n</ul>\n\n<p>With these simple tips, your succulent collection will flourish! Remember, when in doubt, underwater rather than overwater.</p>\n', 'https://images.unsplash.com/photo-1446071103084-c257b5f70672?q=80&w=1584&auto=format&fit=crop', 1, 1, '2025-11-03 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(3, 'Monstera Care Guide: Growing the Perfect Swiss Cheese Plant', 'monstera-care-guide-growing-the-perfect-swiss-cheese-plant', 'Everything you need to know about caring for Monstera deliciosa, from propagation to troubleshooting common problems.', '\n<p>The Monstera deliciosa has become one of the most popular houseplants, and for good reason. Its stunning split leaves and easy-going nature make it perfect for plant enthusiasts of all levels.</p>\n\n<h3>Light and Location</h3>\n<p>Monsteras thrive in bright, indirect light. East or west-facing windows are ideal. While they can tolerate lower light, growth will slow and new leaves may not develop the characteristic splits and holes (fenestrations).</p>\n\n<h3>Watering Schedule</h3>\n<p>Water when the top 2 inches of soil feel dry. Monsteras prefer consistent moisture but hate soggy soil. In summer, this might mean weekly watering; in winter, every 2-3 weeks.</p>\n\n<h3>Humidity and Temperature</h3>\n<p>Native to tropical rainforests, Monsteras appreciate 60-80% humidity. Mist leaves 2-3 times per week, use a humidifier, or place on a pebble tray. They prefer temperatures between 65-85°F.</p>\n\n<h3>Support and Training</h3>\n<p>As a climbing plant, Monsteras benefit from a moss pole or stake. This encourages larger leaves and more dramatic fenestrations. Secure stems gently with plant ties.</p>\n\n<h3>Fertilizing</h3>\n<p>Feed monthly during spring and summer with a balanced liquid fertilizer diluted to half strength. Reduce feeding in fall and winter.</p>\n\n<h3>Propagation</h3>\n<p>Monsteras are easy to propagate from stem cuttings. Cut below a node (the bump where leaves emerge), ensure the cutting has at least one leaf and aerial root, and place in water or directly in soil.</p>\n\n<h3>Common Issues</h3>\n<ul>\n<li><strong>Yellow leaves:</strong> Overwatering or poor drainage</li>\n<li><strong>Brown leaf edges:</strong> Low humidity or underwatering</li>\n<li><strong>No fenestrations:</strong> Insufficient light or young plant</li>\n<li><strong>Pests:</strong> Check for spider mites or mealybugs</li>\n</ul>\n\n<p>With proper care, your Monstera can grow into a stunning focal point that brings the jungle indoors!</p>\n', 'https://images.unsplash.com/photo-1614594975525-e45890e2e122?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-05 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(4, 'Winter Plant Care: Keeping Your Houseplants Thriving in Cold Months', 'winter-plant-care-keeping-your-houseplants-thriving-in-cold-months', 'Adjust your plant care routine for winter success. Learn how to protect your plants from cold, dry air and reduced light.', '\n<p>Winter presents unique challenges for houseplant care. Shorter days, lower humidity, and indoor heating can stress your green friends. Here\'s how to help them thrive through the cold months.</p>\n\n<h3>Adjust Watering</h3>\n<p>Most plants enter a dormant or slow-growth phase in winter and need less water. Check soil moisture before watering - many plants need 30-50% less water than in summer. Overwatering is the #1 killer of houseplants in winter.</p>\n\n<h3>Increase Humidity</h3>\n<p>Indoor heating drastically reduces humidity. Combat this by:\n<ul>\n<li>Grouping plants together to create a microclimate</li>\n<li>Using a humidifier</li>\n<li>Placing plants on pebble trays filled with water</li>\n<li>Misting tropical plants regularly</li>\n</ul>\n</p>\n\n<h3>Maximize Light</h3>\n<p>With shorter days, move plants closer to windows. Clean windows and dust plant leaves to maximize light absorption. Consider supplementing with grow lights for light-hungry plants.</p>\n\n<h3>Reduce Fertilizing</h3>\n<p>Most plants don\'t need fertilizer during winter dormancy. Resume feeding when you see new growth in spring.</p>\n\n<h3>Watch Temperature</h3>\n<p>Keep plants away from cold drafts, heating vents, and radiators. Most houseplants prefer temperatures between 65-75°F. Avoid placing plants near frequently opened doors.</p>\n\n<h3>Pause Repotting</h3>\n<p>Winter is not the time to repot unless absolutely necessary. Wait until spring when plants enter active growth.</p>\n\n<h3>Monitor for Pests</h3>\n<p>Dry indoor air can encourage spider mites and mealybugs. Inspect plants regularly and treat promptly if you spot pests.</p>\n\n<h3>Prune Wisely</h3>\n<p>Light pruning to remove dead or yellowing leaves is fine, but save major pruning for spring when plants can recover quickly.</p>\n\n<p>Remember, some leaf drop and slower growth are normal in winter. Your plants are just resting, preparing for vigorous spring growth!</p>\n', 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-10-03 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(5, 'Propagation 101: How to Multiply Your Plant Collection for Free', 'propagation-101-how-to-multiply-your-plant-collection-for-free', 'Learn various propagation methods to expand your plant collection without spending money. From water propagation to division.', '\n<p>Propagation is one of the most rewarding aspects of plant parenthood. Not only can you expand your collection for free, but you can also share plants with friends and rescue leggy specimens. Here are the main propagation methods.</p>\n\n<h3>Water Propagation</h3>\n<p>Perfect for: Pothos, Philodendron, Monstera, Spider Plants\n<br>Method: Cut below a node, remove lower leaves, place in water. Change water weekly. Once roots are 2-3 inches long, pot in soil.</p>\n\n<h3>Stem Cuttings in Soil</h3>\n<p>Perfect for: Succulents, Snake Plants, Rubber Plants\n<br>Method: Take a 4-6 inch cutting, let it callus for 24 hours, dip in rooting hormone (optional), plant in moist soil. Keep soil lightly moist until established.</p>\n\n<h3>Leaf Cuttings</h3>\n<p>Perfect for: Succulents, African Violets, Begonias\n<br>Method: Gently remove a healthy leaf, let it callus, place on soil surface. Keep soil lightly moist. New plants will grow from the base.</p>\n\n<h3>Division</h3>\n<p>Perfect for: Snake Plants, Peace Lilies, Ferns, Spider Plants\n<br>Method: Remove plant from pot, gently separate root ball into sections (each with roots and shoots), pot separately.</p>\n\n<h3>Air Layering</h3>\n<p>Perfect for: Rubber Plants, Fiddle Leaf Figs, Monstera\n<br>Method: Make a small cut on a stem, wrap with moist sphagnum moss and plastic wrap. Once roots develop, cut below and pot.</p>\n\n<h3>Offsets/Pups</h3>\n<p>Perfect for: Aloe, Succulents, Bromeliads, Spider Plants\n<br>Method: Wait until offsets are 1/3 the size of the parent, gently separate with roots attached, pot individually.</p>\n\n<h3>Pro Tips for Success</h3>\n<ul>\n<li>Use clean, sharp tools to prevent disease</li>\n<li>Propagate during growing season (spring/summer)</li>\n<li>Be patient - some plants take weeks or months</li>\n<li>Maintain high humidity for cuttings</li>\n<li>Use rooting hormone for difficult-to-root plants</li>\n</ul>\n\n<p>Start with easy plants like Pothos or Spider Plants to build confidence, then experiment with more challenging species. Happy propagating!</p>\n', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-10-15 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(6, 'The Benefits of Biophilic Design: Bringing Nature Indoors', 'the-benefits-of-biophilic-design-bringing-nature-indoors', 'Incorporating nature into your interior design can reduce stress, increase creativity, and improve overall well-being. Discover the science behind biophilic design.', '\n<p>Biophilic design is more than just a trend; it\'s a way of living that connects us to nature. By bringing the outdoors in, we can create spaces that are calming, restorative, and conducive to well-being.</p>\n\n<h3>What is Biophilic Design?</h3>\n<p>Biophilia means \"love of life\" - our innate tendency to seek connections with nature. Biophilic design incorporates natural elements into built environments through plants, natural light, water features, natural materials, and views of nature.</p>\n\n<h3>Proven Health Benefits</h3>\n<p>Studies have shown that biophilic design can:\n<ul>\n<li>Lower blood pressure and heart rate</li>\n<li>Reduce stress and anxiety</li>\n<li>Improve concentration and productivity by up to 15%</li>\n<li>Boost creativity and problem-solving</li>\n<li>Speed up healing and recovery</li>\n<li>Enhance mood and emotional well-being</li>\n</ul>\n</p>\n\n<h3>How to Incorporate Biophilic Design</h3>\n<p><strong>1. Add Plants</strong><br>\nStart with 2-3 plants per room. Mix sizes and heights for visual interest. Consider air-purifying varieties for added benefits.</p>\n\n<p><strong>2. Maximize Natural Light</strong><br>\nKeep windows clean and unobstructed. Use sheer curtains instead of heavy drapes. Position furniture to take advantage of natural light.</p>\n\n<p><strong>3. Use Natural Materials</strong><br>\nIncorporate wood, stone, bamboo, wool, and cotton. These materials have textures and patterns that resonate with our connection to nature.</p>\n\n<p><strong>4. Bring in Water Elements</strong><br>\nA small fountain or aquarium adds the soothing sound of water and increases humidity.</p>\n\n<p><strong>5. Choose Nature-Inspired Colors</strong><br>\nEarth tones, greens, blues, and warm neutrals create a calming, natural atmosphere.</p>\n\n<p><strong>6. Create Views</strong><br>\nPosition seating to face windows with outdoor views. Use nature photography or botanical prints if outdoor views are limited.</p>\n\n<p><strong>7. Add Natural Patterns</strong><br>\nIncorporate patterns found in nature - fractals, spirals, leaf shapes - through textiles, wallpaper, or artwork.</p>\n\n<p>Start small by adding a few potted plants to your workspace or living room. You\'ll be amazed at the difference it makes to your mood, focus, and overall sense of well-being.</p>\n', 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?q=80&w=2072&auto=format&fit=crop', 1, 1, '2025-11-19 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(7, 'Creating a Stunning Indoor Jungle: Design Tips and Plant Combinations', 'creating-a-stunning-indoor-jungle-design-tips-and-plant-combinations', 'Transform your home into a lush indoor jungle with these expert design tips and plant pairing ideas.', '\n<p>Creating an indoor jungle is about more than just adding lots of plants - it\'s about thoughtful design, layering, and choosing the right combinations. Here\'s how to achieve that lush, tropical look.</p>\n\n<h3>Start with a Plan</h3>\n<p>Consider your space\'s light levels, humidity, and temperature. Choose plants that will thrive in your conditions rather than fighting against them.</p>\n\n<h3>Layer Heights</h3>\n<p>Create visual interest by combining:\n<ul>\n<li><strong>Tall plants (5-8 feet):</strong> Fiddle Leaf Fig, Rubber Plant, Bird of Paradise</li>\n<li><strong>Medium plants (2-4 feet):</strong> Monstera, Philodendron, Peace Lily</li>\n<li><strong>Low plants (under 2 feet):</strong> Pothos, Ferns, Calathea</li>\n<li><strong>Trailing plants:</strong> String of Hearts, Pothos, Philodendron Brasil</li>\n</ul>\n</p>\n\n<h3>Perfect Plant Combinations</h3>\n<p><strong>Tropical Paradise Corner:</strong>\n<br>Monstera deliciosa (tall), Bird\'s Nest Fern (medium), Pothos (trailing from shelf)</p>\n\n<p><strong>Low-Light Oasis:</strong>\n<br>Snake Plant (tall), ZZ Plant (medium), Pothos (trailing)</p>\n\n<p><strong>Bright Window Display:</strong>\n<br>Fiddle Leaf Fig (tall), Rubber Plant (medium), String of Pearls (trailing)</p>\n\n<h3>Use Varied Containers</h3>\n<p>Mix pot styles, materials, and sizes for visual interest. Combine ceramic, terracotta, woven baskets, and modern planters. Ensure all have drainage holes.</p>\n\n<h3>Add Vertical Elements</h3>\n<p>Use plant stands, wall-mounted planters, hanging baskets, and shelves to create layers and maximize space.</p>\n\n<h3>Group for Impact</h3>\n<p>Cluster plants in odd numbers (3, 5, 7) for a more natural, abundant look. This also creates beneficial microclimates with increased humidity.</p>\n\n<h3>Include Texture Variety</h3>\n<p>Mix leaf shapes and textures:\n<ul>\n<li>Large, glossy leaves (Monstera, Rubber Plant)</li>\n<li>Delicate, feathery foliage (Ferns, Asparagus Fern)</li>\n<li>Architectural, upright leaves (Snake Plant, Dracaena)</li>\n<li>Trailing vines (Pothos, Philodendron)</li>\n</ul>\n</p>\n\n<h3>Don\'t Forget Maintenance</h3>\n<p>More plants mean more care. Group plants with similar needs together for easier watering and maintenance.</p>\n\n<p>Remember, your indoor jungle should bring you joy, not stress. Start with a few plants and add gradually as you gain confidence!</p>\n', 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-15 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(8, 'Small Space Plant Styling: Maximizing Greenery in Apartments', 'small-space-plant-styling-maximizing-greenery-in-apartments', 'Living in a small apartment doesn\'t mean you can\'t have an impressive plant collection. Learn how to maximize vertical space and choose the right plants.', '\n<p>Limited square footage doesn\'t mean you have to limit your plant collection. With smart styling and space-saving solutions, even the smallest apartment can become a green oasis.</p>\n\n<h3>Think Vertical</h3>\n<p>When floor space is limited, go up!\n<ul>\n<li><strong>Wall-mounted planters:</strong> Perfect for small succulents and air plants</li>\n<li><strong>Hanging planters:</strong> Utilize ceiling space for trailing plants</li>\n<li><strong>Tall plant stands:</strong> Create levels without taking up floor space</li>\n<li><strong>Ladder shelves:</strong> Display multiple plants in a small footprint</li>\n<li><strong>Window shelves:</strong> Add a shelf across your window for sun-loving plants</li>\n</ul>\n</p>\n\n<h3>Multi-Functional Furniture</h3>\n<p>Choose furniture that doubles as plant displays:\n<ul>\n<li>Bookshelves with plants interspersed</li>\n<li>Side tables that can hold plants</li>\n<li>Room dividers with built-in planters</li>\n<li>Window sills as plant shelves</li>\n</ul>\n</p>\n\n<h3>Best Plants for Small Spaces</h3>\n<p><strong>Compact Growers:</strong>\n<br>Succulents, Air Plants, Small Ferns, Peperomia, Pilea</p>\n\n<p><strong>Vertical Growers:</strong>\n<br>Snake Plants, ZZ Plants, Dracaena, Bamboo</p>\n\n<p><strong>Trailing Plants for Hanging:</strong>\n<br>Pothos, String of Hearts, String of Pearls, Philodendron Brasil</p>\n\n<h3>Corner Solutions</h3>\n<p>Corners are often wasted space. Place a tall plant like a Fiddle Leaf Fig or Bird of Paradise in a corner to create a focal point without cluttering the room.</p>\n\n<h3>Bathroom and Kitchen Plants</h3>\n<p>These rooms often have unused space perfect for plants:\n<ul>\n<li><strong>Bathroom:</strong> Ferns, Pothos, Orchids (love humidity)</li>\n<li><strong>Kitchen:</strong> Herbs on windowsill, Pothos on top of cabinets</li>\n</ul>\n</p>\n\n<h3>Styling Tips</h3>\n<ul>\n<li>Use matching or coordinating pots for a cohesive look</li>\n<li>Stick to 2-3 pot colors/materials to avoid visual clutter</li>\n<li>Group small plants together for impact</li>\n<li>Choose plants with similar care needs for easier maintenance</li>\n</ul>\n\n<h3>Avoid Overcrowding</h3>\n<p>While you want plenty of plants, leave breathing room. Overcrowding can make a small space feel claustrophobic and make plant care difficult.</p>\n\n<p>With these strategies, you can create a lush, plant-filled home no matter how small your space!</p>\n', 'https://images.unsplash.com/photo-1512428908174-cc784f0b0583?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-09-21 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(9, 'Spring Plant Care Checklist: Preparing Your Plants for Growing Season', 'spring-plant-care-checklist-preparing-your-plants-for-growing-season', 'Spring is the perfect time to refresh your plant care routine. Follow this checklist to help your plants thrive.', '\n<p>Spring is an exciting time for plant parents! As days lengthen and temperatures warm, your plants will wake from winter dormancy and enter active growth. Here\'s your complete spring plant care checklist.</p>\n\n<h3>1. Assess Plant Health</h3>\n<p>Inspect each plant for:\n<ul>\n<li>Dead or damaged leaves (remove them)</li>\n<li>Pest infestations (treat immediately)</li>\n<li>Root-bound conditions (time to repot)</li>\n<li>Overall vigor and color</li>\n</ul>\n</p>\n\n<h3>2. Resume Fertilizing</h3>\n<p>Start feeding plants as new growth appears. Use a balanced liquid fertilizer at half strength initially, then increase to full strength as growth accelerates. Feed every 2-4 weeks during growing season.</p>\n\n<h3>3. Increase Watering</h3>\n<p>As plants grow more actively, they\'ll need more water. Check soil moisture more frequently and adjust your watering schedule accordingly.</p>\n\n<h3>4. Repot as Needed</h3>\n<p>Spring is the ideal time to repot. Signs your plant needs repotting:\n<ul>\n<li>Roots growing through drainage holes</li>\n<li>Water runs straight through pot</li>\n<li>Plant is top-heavy and tips over</li>\n<li>Growth has slowed despite good care</li>\n</ul>\nChoose a pot 1-2 inches larger and use fresh potting mix.</p>\n\n<h3>5. Prune and Shape</h3>\n<p>Remove dead growth, trim leggy stems, and shape plants to encourage bushier growth. Spring pruning stimulates new growth.</p>\n\n<h3>6. Propagate</h3>\n<p>Spring is the best time to propagate! Take cuttings from healthy growth for best success rates.</p>\n\n<h3>7. Clean Plants</h3>\n<p>Wipe down leaves to remove dust accumulated over winter. This helps plants photosynthesize more efficiently.</p>\n\n<h3>8. Rotate Plants</h3>\n<p>Rotate plants 90 degrees weekly for even growth, especially important as they start growing vigorously.</p>\n\n<h3>9. Move Plants Outdoors (Gradually)</h3>\n<p>If you plan to summer plants outdoors, acclimate them gradually over 7-10 days to prevent shock.</p>\n\n<h3>10. Refresh Top Soil</h3>\n<p>For plants you\'re not repotting, remove the top 1-2 inches of soil and replace with fresh potting mix to replenish nutrients.</p>\n\n<p>Follow this checklist and your plants will reward you with vigorous growth and abundant foliage all season long!</p>\n', 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-30 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(10, 'Summer Plant Care: Keeping Plants Happy in the Heat', 'summer-plant-care-keeping-plants-happy-in-the-heat', 'Hot summer weather requires adjustments to your plant care routine. Learn how to protect plants from heat stress and sunburn.', '\n<p>Summer brings longer days and warmer temperatures - great for plant growth, but also potential challenges. Here\'s how to keep your plants thriving through the heat.</p>\n\n<h3>Adjust Watering</h3>\n<p>Plants need significantly more water in summer:\n<ul>\n<li>Check soil moisture daily during heat waves</li>\n<li>Water deeply rather than frequently</li>\n<li>Water in early morning or evening to minimize evaporation</li>\n<li>Consider bottom-watering for consistent moisture</li>\n</ul>\n</p>\n\n<h3>Watch for Sunburn</h3>\n<p>Even sun-loving plants can burn in intense summer sun. Signs of sunburn:\n<ul>\n<li>Brown, crispy patches on leaves</li>\n<li>Faded or bleached areas</li>\n<li>Curling leaf edges</li>\n</ul>\nSolution: Move plants away from direct afternoon sun or use sheer curtains to filter light.</p>\n\n<h3>Maintain Humidity</h3>\n<p>Air conditioning can dry out indoor air. Maintain humidity by:\n<ul>\n<li>Grouping plants together</li>\n<li>Using pebble trays</li>\n<li>Running a humidifier</li>\n<li>Misting tropical plants</li>\n</ul>\n</p>\n\n<h3>Fertilize Regularly</h3>\n<p>Active growth requires nutrients. Feed every 2-4 weeks with diluted liquid fertilizer.</p>\n\n<h3>Monitor for Pests</h3>\n<p>Warm weather brings increased pest activity. Check regularly for:\n<ul>\n<li>Spider mites (fine webbing, stippled leaves)</li>\n<li>Aphids (clusters on new growth)</li>\n<li>Mealybugs (white, cottony masses)</li>\n<li>Fungus gnats (small flies around soil)</li>\n</ul>\n</p>\n\n<h3>Provide Air Circulation</h3>\n<p>Good airflow prevents fungal issues and helps plants cool down. Use fans to keep air moving, but avoid direct drafts on plants.</p>\n\n<h3>Consider Outdoor Vacation</h3>\n<p>Many houseplants benefit from summering outdoors:\n<ul>\n<li>Acclimate gradually over 7-10 days</li>\n<li>Start in shade, gradually increase light</li>\n<li>Protect from harsh afternoon sun</li>\n<li>Bring indoors before temperatures drop in fall</li>\n</ul>\n</p>\n\n<h3>Vacation Care</h3>\n<p>Going away? Prepare plants:\n<ul>\n<li>Water thoroughly before leaving</li>\n<li>Move away from direct sun</li>\n<li>Group plants to increase humidity</li>\n<li>Consider self-watering stakes or asking a friend to water</li>\n</ul>\n</p>\n\n<p>With these adjustments, your plants will thrive through even the hottest summer!</p>\n', 'https://images.unsplash.com/photo-1518621736915-f3b1c41bfd00?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-09-23 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(11, 'Fall Plant Care: Preparing Your Indoor Garden for Cooler Weather', 'fall-plant-care-preparing-your-indoor-garden-for-cooler-weather', 'As temperatures drop and days shorten, adjust your plant care routine for the changing season.', '\n<p>Fall marks the transition from active growth to dormancy for many plants. Adjusting your care routine now will help plants stay healthy through winter.</p>\n\n<h3>Bring Outdoor Plants Inside</h3>\n<p>Before first frost:\n<ul>\n<li>Inspect thoroughly for pests</li>\n<li>Treat any infestations before bringing indoors</li>\n<li>Acclimate gradually over 7-10 days</li>\n<li>Clean pots and remove dead foliage</li>\n<li>Repot if needed (though spring is better)</li>\n</ul>\n</p>\n\n<h3>Reduce Watering</h3>\n<p>As growth slows, plants need less water. Reduce watering frequency by 25-50%. Always check soil before watering.</p>\n\n<h3>Taper Off Fertilizing</h3>\n<p>Stop fertilizing by late fall. Plants entering dormancy don\'t need nutrients and excess fertilizer can cause problems.</p>\n\n<h3>Adjust Light</h3>\n<p>With shorter days, move plants closer to windows or supplement with grow lights. Clean windows to maximize available light.</p>\n\n<h3>Monitor Temperature</h3>\n<p>As you start heating your home:\n<ul>\n<li>Keep plants away from heating vents and radiators</li>\n<li>Avoid cold drafts from windows and doors</li>\n<li>Maintain consistent temperatures (65-75°F for most plants)</li>\n</ul>\n</p>\n\n<h3>Increase Humidity</h3>\n<p>Heating systems dry indoor air. Combat this with humidifiers, pebble trays, or grouping plants.</p>\n\n<h3>Prune Lightly</h3>\n<p>Remove dead or yellowing leaves, but save major pruning for spring when plants can recover quickly.</p>\n\n<h3>Pest Prevention</h3>\n<p>Inspect plants regularly. Pests brought in from outdoors can spread quickly indoors.</p>\n\n<h3>Prepare for Dormancy</h3>\n<p>Some plants (like Alocasia, Caladium) may die back completely. This is normal! Reduce watering and wait for spring regrowth.</p>\n\n<h3>Clean and Organize</h3>\n<p>Fall is a good time to:\n<ul>\n<li>Clean pots and saucers</li>\n<li>Organize plant care supplies</li>\n<li>Take inventory of what you have</li>\n<li>Plan winter plant purchases</li>\n</ul>\n</p>\n\n<p>With these fall adjustments, your plants will transition smoothly into winter dormancy and emerge strong in spring!</p>\n', 'https://images.unsplash.com/photo-1499002238440-d264edd596ec?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-26 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(12, '5 Rare Plants Every Collector Needs', '5-rare-plants-every-collector-needs', 'Looking for something unique? Check out these rare and stunning plants that will be the envy of your friends.', '\n<p>For the plant enthusiast who has it all, these rare finds are the next level. While they may require more investment and care, their beauty and uniqueness make them worth it.</p>\n\n<h3>1. Philodendron Pink Princess</h3>\n<p>Known for its stunning pink variegation on dark green leaves, this plant has become incredibly sought-after. The pink coloring is unstable, making each plant unique. Requires bright, indirect light to maintain variegation. Prices range from $50-$300 depending on variegation.</p>\n\n<h3>2. Monstera Albo (Monstera deliciosa \'Albo Variegata\')</h3>\n<p>The white and green variegated leaves are absolute showstoppers. This slow-growing plant can command prices of $100-$1000+ for a single cutting. Requires bright light to maintain white variegation and careful watering to prevent rot.</p>\n\n<h3>3. Anthurium Warocqueanum (Queen Anthurium)</h3>\n<p>Also known as the Queen Anthurium, it features long, velvety leaves with prominent white veins that can grow over 3 feet long. Requires high humidity (70%+) and consistent care. A true statement plant for serious collectors.</p>\n\n<h3>4. Philodendron Gloriosum</h3>\n<p>This crawling philodendron produces large, heart-shaped, velvety leaves with striking white veins. Unlike climbing philodendrons, it grows horizontally, making it unique. Requires high humidity and well-draining soil.</p>\n\n<h3>5. Alocasia Azlanii (Red Mambo)</h3>\n<p>Features stunning metallic, iridescent leaves in shades of purple, pink, and green. The underside is deep burgundy. Requires high humidity, warm temperatures, and bright indirect light. Goes dormant in winter.</p>\n\n<h3>Care Tips for Rare Plants</h3>\n<ul>\n<li>Research specific care requirements before purchasing</li>\n<li>Quarantine new plants to prevent pest spread</li>\n<li>Invest in a humidifier for tropical rarities</li>\n<li>Use well-draining, airy soil mixes</li>\n<li>Be patient - rare plants often grow slowly</li>\n<li>Join plant communities for care tips and trading</li>\n</ul>\n\n<h3>Where to Find Rare Plants</h3>\n<ul>\n<li>Specialty online retailers</li>\n<li>Local plant swaps and sales</li>\n<li>Plant collector groups on social media</li>\n<li>Botanical gardens sales</li>\n<li>Specialty nurseries</li>\n</ul>\n\n<p>These plants may require a bit more care and investment, but their beauty and uniqueness make them treasured additions to any collection!</p>\n', 'https://images.unsplash.com/photo-1614594975525-e45190c55d0b?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-17 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(13, 'Best Low-Light Plants for Dark Corners and Offices', 'best-low-light-plants-for-dark-corners-and-offices', 'Don\'t let low light stop you from having plants. These varieties thrive in dim conditions and are perfect for offices and north-facing rooms.', '\n<p>Not all spaces are blessed with bright, sunny windows. Fortunately, many beautiful plants thrive in low-light conditions, making them perfect for offices, bathrooms, and north-facing rooms.</p>\n\n<h3>Understanding Low Light</h3>\n<p>Low light means:\n<ul>\n<li>North-facing windows</li>\n<li>Rooms with no direct sunlight</li>\n<li>Spaces 6-8 feet from windows</li>\n<li>Offices with only fluorescent lighting</li>\n</ul>\nNote: No plant can survive in complete darkness. Even low-light plants need some ambient light.</p>\n\n<h3>Top Low-Light Champions</h3>\n\n<h4>1. Snake Plant (Sansevieria)</h4>\n<p>The ultimate low-light survivor. Tolerates neglect, irregular watering, and dim conditions. Perfect for beginners and busy people.</p>\n\n<h4>2. ZZ Plant (Zamioculcas zamiifolia)</h4>\n<p>Glossy, architectural leaves that look great in modern spaces. Extremely drought-tolerant and pest-resistant.</p>\n\n<h4>3. Pothos</h4>\n<p>Trailing vines that tolerate low light (though growth will be slower). Comes in several varieties including golden, marble queen, and neon.</p>\n\n<h4>4. Peace Lily</h4>\n<p>Produces elegant white flowers even in low light. Communicates its needs by drooping when thirsty.</p>\n\n<h4>5. Cast Iron Plant (Aspidistra)</h4>\n<p>Named for its tough-as-nails nature. Tolerates low light, temperature fluctuations, and neglect.</p>\n\n<h4>6. Chinese Evergreen (Aglaonema)</h4>\n<p>Beautiful variegated foliage in shades of green, pink, and red. Very forgiving of low light and irregular watering.</p>\n\n<h4>7. Dracaena</h4>\n<p>Many varieties available, all tolerant of low light. Grows slowly but can eventually become a tall, tree-like plant.</p>\n\n<h4>8. Philodendron (Heart-leaf)</h4>\n<p>Trailing or climbing plant with heart-shaped leaves. Adapts well to low light though growth slows.</p>\n\n<h3>Care Tips for Low-Light Plants</h3>\n<ul>\n<li><strong>Water less:</strong> Low light = slower growth = less water needed</li>\n<li><strong>Don\'t fertilize as much:</strong> Feed only 2-3 times per year</li>\n<li><strong>Dust leaves regularly:</strong> Clean leaves absorb more light</li>\n<li><strong>Rotate plants:</strong> Ensure even growth on all sides</li>\n<li><strong>Be patient:</strong> Growth will be slower than in bright light</li>\n<li><strong>Watch for stretching:</strong> If stems get leggy, plant needs more light</li>\n</ul>\n\n<h3>Boosting Low Light</h3>\n<p>If your space is very dark, consider:\n<ul>\n<li>LED grow lights (can be decorative)</li>\n<li>Mirrors to reflect available light</li>\n<li>Light-colored walls to brighten space</li>\n<li>Rotating plants to brighter spots periodically</li>\n</ul>\n</p>\n\n<p>Don\'t let low light stop you from enjoying plants! These hardy varieties will thrive even in the dimmest corners.</p>\n', 'https://images.unsplash.com/photo-1509587584298-0f3b3a3a1797?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-10-26 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(14, 'Common Plant Problems and How to Fix Them', 'common-plant-problems-and-how-to-fix-them', 'Troubleshoot the most common houseplant problems with our expert guide. From yellow leaves to pest infestations.', '\n<p>Even experienced plant parents encounter problems. Here\'s how to diagnose and fix the most common issues.</p>\n\n<h3>Yellow Leaves</h3>\n<p><strong>Causes:</strong>\n<ul>\n<li>Overwatering (most common)</li>\n<li>Underwatering</li>\n<li>Nutrient deficiency</li>\n<li>Natural aging (lower leaves)</li>\n</ul>\n<strong>Solutions:</strong> Check soil moisture. If soggy, let dry out and reduce watering. If bone dry, water thoroughly. If care is correct, it may be natural aging or time to fertilize.</p>\n\n<h3>Brown Leaf Tips</h3>\n<p><strong>Causes:</strong>\n<ul>\n<li>Low humidity</li>\n<li>Fluoride/chlorine in water</li>\n<li>Fertilizer burn</li>\n<li>Underwatering</li>\n</ul>\n<strong>Solutions:</strong> Increase humidity, use filtered water, flush soil to remove excess fertilizer, or adjust watering schedule.</p>\n\n<h3>Drooping/Wilting</h3>\n<p><strong>Causes:</strong>\n<ul>\n<li>Underwatering</li>\n<li>Overwatering (root rot)</li>\n<li>Temperature stress</li>\n<li>Transplant shock</li>\n</ul>\n<strong>Solutions:</strong> Check soil moisture. If dry, water thoroughly. If wet, check roots for rot. Ensure plant isn\'t near drafts or heat sources.</p>\n\n<h3>Leggy Growth</h3>\n<p><strong>Cause:</strong> Insufficient light\n<br><strong>Solution:</strong> Move to brighter location or add grow light. Prune leggy stems to encourage bushier growth.</p>\n\n<h3>No New Growth</h3>\n<p><strong>Causes:</strong>\n<ul>\n<li>Dormancy (winter)</li>\n<li>Root-bound</li>\n<li>Insufficient light</li>\n<li>Lack of nutrients</li>\n</ul>\n<strong>Solutions:</strong> If winter, this is normal. Otherwise, check if plant needs repotting, more light, or fertilizer.</p>\n\n<h3>Pest Infestations</h3>\n\n<h4>Spider Mites</h4>\n<p>Tiny pests that create fine webbing. Leaves appear stippled or dusty.\n<br><strong>Treatment:</strong> Spray with water, neem oil, or insecticidal soap. Increase humidity.</p>\n\n<h4>Mealybugs</h4>\n<p>White, cottony masses on stems and leaves.\n<br><strong>Treatment:</strong> Remove with cotton swab dipped in rubbing alcohol. Spray with neem oil.</p>\n\n<h4>Fungus Gnats</h4>\n<p>Small flies around soil surface. Larvae feed on roots.\n<br><strong>Treatment:</strong> Let soil dry out between waterings. Use yellow sticky traps. Add sand layer on top of soil.</p>\n\n<h4>Scale</h4>\n<p>Brown, shell-like bumps on stems and leaves.\n<br><strong>Treatment:</strong> Scrape off manually. Spray with neem oil or insecticidal soap.</p>\n\n<h3>Root Rot</h3>\n<p><strong>Signs:</strong> Mushy stems, yellow leaves, foul smell from soil\n<br><strong>Treatment:</strong> Remove plant from pot, trim away rotted roots, repot in fresh soil. Adjust watering habits.</p>\n\n<h3>Sunburn</h3>\n<p><strong>Signs:</strong> Brown, crispy patches on leaves\n<br><strong>Treatment:</strong> Move away from direct sun. Trim damaged leaves. Gradually acclimate to brighter light if desired.</p>\n\n<h3>Prevention is Best</h3>\n<ul>\n<li>Inspect plants regularly</li>\n<li>Quarantine new plants for 2 weeks</li>\n<li>Provide appropriate light and water</li>\n<li>Maintain good air circulation</li>\n<li>Keep leaves clean</li>\n<li>Use quality potting mix</li>\n</ul>\n\n<p>Remember, most plant problems are fixable if caught early. Regular observation is your best tool!</p>\n', 'https://images.unsplash.com/photo-1466692476868-aef1dfb1e735?q=80&w=2070&auto=format&fit=crop', 1, 1, '2025-11-01 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(15, 'The Ultimate Guide to Repotting Houseplants', 'the-ultimate-guide-to-repotting-houseplants', 'Learn when and how to repot your plants for optimal health and growth. Step-by-step guide with pro tips.', '\n<p>Repotting is essential for plant health, but it can be intimidating for beginners. This comprehensive guide will walk you through everything you need to know.</p>\n\n<h3>When to Repot</h3>\n<p><strong>Signs your plant needs repotting:</strong>\n<ul>\n<li>Roots growing through drainage holes</li>\n<li>Water runs straight through pot without absorbing</li>\n<li>Plant is top-heavy and tips over easily</li>\n<li>Growth has slowed despite good care</li>\n<li>Soil dries out very quickly</li>\n<li>Roots are circling the root ball (root-bound)</li>\n<li>It\'s been 2+ years since last repotting</li>\n</ul>\n</p>\n\n<h3>Best Time to Repot</h3>\n<p>Spring and early summer are ideal when plants are entering active growth. Avoid repotting during winter dormancy or when plant is flowering.</p>\n\n<h3>Choosing the Right Pot</h3>\n<ul>\n<li>Select a pot 1-2 inches larger in diameter</li>\n<li>Must have drainage holes</li>\n<li>Material options: terracotta (breathable), plastic (retains moisture), ceramic (decorative)</li>\n<li>Don\'t size up too much - excess soil retains too much moisture</li>\n</ul>\n\n<h3>Supplies Needed</h3>\n<ul>\n<li>New pot with drainage</li>\n<li>Fresh potting mix (appropriate for plant type)</li>\n<li>Newspaper or tarp</li>\n<li>Watering can</li>\n<li>Scissors or pruning shears</li>\n<li>Optional: rooting hormone, perlite, activated charcoal</li>\n</ul>\n\n<h3>Step-by-Step Repotting</h3>\n\n<p><strong>1. Prepare Your Workspace</strong><br>\nCover surface with newspaper. Have all supplies ready.</p>\n\n<p><strong>2. Water Plant</strong><br>\nWater 1-2 days before repotting. Moist (not soggy) soil is easier to work with.</p>\n\n<p><strong>3. Remove Plant from Pot</strong><br>\nGently squeeze pot sides. Turn upside down, supporting plant. Tap bottom until plant slides out. If stuck, run knife around inside edge.</p>\n\n<p><strong>4. Examine Roots</strong><br>\nHealthy roots are white or tan and firm. Brown, mushy roots indicate rot - trim these away.</p>\n\n<p><strong>5. Loosen Root Ball</strong><br>\nGently tease apart circling roots. Trim any dead or damaged roots. If severely root-bound, make 3-4 vertical cuts in root ball.</p>\n\n<p><strong>6. Prepare New Pot</strong><br>\nAdd 1-2 inches of fresh potting mix to bottom. Don\'t use garden soil - it\'s too dense.</p>\n\n<p><strong>7. Position Plant</strong><br>\nPlace plant in center of pot at same depth as before. Top of root ball should be 1/2-1 inch below pot rim.</p>\n\n<p><strong>8. Fill with Soil</strong><br>\nAdd potting mix around roots, gently firming as you go. Don\'t pack too tightly. Leave space at top for watering.</p>\n\n<p><strong>9. Water Thoroughly</strong><br>\nWater until it drains from bottom. This settles soil and eliminates air pockets.</p>\n\n<p><strong>10. Place in Appropriate Light</strong><br>\nKeep in bright, indirect light for a week while plant adjusts. Resume normal care after.</p>\n\n<h3>Post-Repotting Care</h3>\n<ul>\n<li>Don\'t fertilize for 4-6 weeks (fresh soil has nutrients)</li>\n<li>Monitor moisture - new soil may retain water differently</li>\n<li>Some leaf drop is normal as plant adjusts</li>\n<li>Be patient - it may take a few weeks to see new growth</li>\n</ul>\n\n<h3>Special Considerations</h3>\n\n<p><strong>Cacti and Succulents:</strong> Use cactus mix. Let roots dry for 24 hours before repotting. Wait 1 week before watering.</p>\n\n<p><strong>Orchids:</strong> Use orchid bark mix. Trim dead roots. Soak bark before using.</p>\n\n<p><strong>Large Plants:</strong> Get help! Tip pot on side, slide plant out. May need to break pot if severely root-bound.</p>\n\n<h3>Common Mistakes to Avoid</h3>\n<ul>\n<li>Pot too large (causes overwatering)</li>\n<li>No drainage holes</li>\n<li>Using garden soil</li>\n<li>Repotting during dormancy</li>\n<li>Fertilizing immediately after</li>\n<li>Disturbing roots too much</li>\n</ul>\n\n<p>With practice, repotting becomes routine. Your plants will thank you with vigorous growth and better health!</p>\n', 'https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?q=80&w=2072&auto=format&fit=crop', 1, 1, '2025-10-24 05:22:46', '2025-12-12 05:22:46', '2025-12-12 05:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `price_alerts`
--

CREATE TABLE `price_alerts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `target_price` decimal(10,2) NOT NULL,
  `current_price` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_triggered` tinyint(1) NOT NULL DEFAULT 0,
  `triggered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `short_description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `in_stock` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sku` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `care_instructions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`care_instructions`)),
  `plant_characteristics` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`plant_characteristics`)),
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock_quantity`, `in_stock`, `is_featured`, `is_active`, `sku`, `images`, `care_instructions`, `plant_characteristics`, `category_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Monstera Deliciosa', 'monstera-deliciosa', 'The Monstera Deliciosa, affectionately known as the Swiss Cheese Plant, is a stunning tropical houseplant that brings the lush beauty of the rainforest into your home. With its iconic large, glossy leaves featuring natural fenestrations (holes), this plant makes a bold architectural statement in any interior space. Native to Central American rainforests, the Monstera thrives in bright, indirect light and appreciates regular watering when the top inch of soil becomes dry. As it matures, the leaves develop more dramatic splits and holes, creating an ever-evolving display of natural artistry. Perfect for plant enthusiasts who want to make a statement, this easy-care beauty can grow quite large indoors, reaching heights of 6-8 feet with proper care.', 'Iconic tropical plant with stunning split leaves', 29.99, NULL, 0, 0, 1, 1, 'MON-001', '[\"\\/images\\/products\\/monstera_deliciosa.png\"]', '[\"Water when the top 2 inches of soil feels dry to touch\",\"Place in bright, indirect light near east or west-facing window\",\"Mist leaves 2-3 times per week for humidity\",\"Fertilize monthly during spring and summer with balanced liquid fertilizer\",\"Wipe leaves with damp cloth to remove dust and promote photosynthesis\",\"Provide moss pole or stake for climbing support\"]', '{\"height\":\"6-8 feet indoors\",\"light_requirements\":\"Bright, indirect light\",\"water_needs\":\"Moderate - weekly watering\",\"toxicity\":\"Toxic to pets and humans if ingested\",\"humidity\":\"60-80% preferred\",\"temperature\":\"65-85\\u00b0F (18-29\\u00b0C)\"}', 1, '2025-12-12 05:22:46', '2025-12-15 06:35:07'),
(2, NULL, 'Snake Plant (Sansevieria)', 'snake-plant', 'The Snake Plant, also called Mother-in-Law\'s Tongue or Sansevieria, is the ultimate low-maintenance houseplant and a favorite among beginners and busy plant parents. This architectural wonder features striking upright leaves with beautiful variegated patterns in shades of green and yellow. Renowned for its air-purifying qualities, the Snake Plant removes toxins like formaldehyde and benzene from indoor air, making it an excellent choice for bedrooms and offices. Incredibly resilient, it tolerates low light conditions, irregular watering, and a wide range of temperatures. NASA studies have shown it\'s one of the best plants for improving indoor air quality, even producing oxygen at night. Whether you\'re a forgetful waterer or new to plant care, this hardy beauty will thrive with minimal attention.', 'Nearly indestructible air-purifying plant', 19.99, 16.99, 0, 0, 0, 1, 'SNA-001', '[\"\\/images\\/products\\/snake_plant.png\"]', '[\"Water every 2-3 weeks, allowing soil to dry completely between waterings\",\"Tolerates low to bright indirect light - very adaptable\",\"Use well-draining cactus or succulent soil mix\",\"Fertilize sparingly, only 2-3 times during growing season\",\"Wipe leaves occasionally to remove dust\",\"Avoid overwatering - root rot is the main concern\"]', '{\"height\":\"2-4 feet\",\"light_requirements\":\"Low to bright indirect light\",\"water_needs\":\"Very low - drought tolerant\",\"toxicity\":\"Mildly toxic to pets if ingested\",\"humidity\":\"Average household humidity\",\"temperature\":\"55-85\\u00b0F (13-29\\u00b0C)\"}', 1, '2025-12-12 05:22:46', '2025-12-15 06:35:07'),
(3, NULL, 'Fiddle Leaf Fig', 'fiddle-leaf-fig', 'The Fiddle Leaf Fig is the crown jewel of indoor plants, beloved by interior designers and plant enthusiasts worldwide for its dramatic, violin-shaped leaves and sculptural presence. This stunning tree-like plant features large, deeply veined leaves that can grow up to 15 inches long, creating an impressive focal point in any room. Native to West African rainforests, the Fiddle Leaf Fig has become an Instagram sensation and a must-have for modern homes. While it has a reputation for being finicky, with the right care and consistent conditions, it will reward you with lush, vibrant growth. The key to success is finding the perfect spot with bright, filtered light and maintaining a regular watering schedule. Once established, this majestic plant can grow 6-10 feet tall indoors, transforming your space into a sophisticated urban jungle.', 'Dramatic statement plant with violin-shaped leaves', 49.99, NULL, 1, 1, 1, 1, 'FID-001', '[\"\\/images\\/products\\/fiddle_leaf_fig.png\"]', '[\"Water when top 2 inches of soil is dry - consistency is key\",\"Needs bright, filtered light - avoid direct sun which can scorch leaves\",\"Rotate plant 90 degrees weekly for even growth\",\"Wipe leaves with damp cloth monthly to remove dust\",\"Fertilize monthly during growing season with diluted liquid fertilizer\",\"Avoid moving once established - dislikes change\",\"Maintain consistent temperature and avoid drafts\"]', '{\"height\":\"6-10 feet indoors\",\"light_requirements\":\"Bright, filtered indirect light\",\"water_needs\":\"Moderate - consistent watering schedule\",\"toxicity\":\"Toxic to pets and humans\",\"humidity\":\"30-65% - appreciates occasional misting\",\"temperature\":\"60-75\\u00b0F (15-24\\u00b0C)\"}', 1, '2025-12-12 05:22:46', '2025-12-15 06:35:07'),
(4, NULL, 'Pothos (Devil\'s Ivy)', 'pothos-devils-ivy', 'The Pothos, affectionately called Devil\'s Ivy for its ability to stay green even in the dark, is one of the most forgiving and versatile houseplants available. This trailing beauty features heart-shaped leaves in various shades of green, often with stunning yellow or white variegation. Perfect for beginners, the Pothos thrives on neglect and can adapt to almost any indoor environment. Its cascading vines can grow several feet long, making it ideal for hanging baskets, shelves, or training up a moss pole. Beyond its good looks, Pothos is an excellent air purifier, removing indoor pollutants like formaldehyde and benzene. Whether you want a lush hanging plant or a climbing specimen, this adaptable beauty will flourish with minimal care.', 'Easy-care trailing plant perfect for beginners', 14.99, 12.99, 35, 1, 1, 1, 'POT-001', '[\"\\/images\\/products\\/pothos.png\"]', '[\"Water when soil is dry 1-2 inches down\",\"Thrives in low to bright indirect light\",\"Trim leggy vines to encourage bushier growth\",\"Propagates easily in water from stem cuttings\",\"Fertilize every 2-3 months during growing season\",\"Wipe leaves occasionally to keep them glossy\"]', '{\"height\":\"Vines can grow 6-10 feet long\",\"light_requirements\":\"Low to bright indirect light\",\"water_needs\":\"Low to moderate\",\"toxicity\":\"Toxic to pets and humans\",\"humidity\":\"Average household humidity\",\"temperature\":\"65-85\\u00b0F (18-29\\u00b0C)\"}', 1, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(5, NULL, 'Peace Lily', 'peace-lily', 'The Peace Lily is an elegant flowering houseplant that combines lush green foliage with stunning white blooms, bringing serenity and sophistication to any space. Known for its air-purifying prowess, this plant was featured in NASA\'s Clean Air Study for its ability to remove harmful toxins like ammonia, benzene, and formaldehyde from indoor air. The Peace Lily is remarkably communicative - its leaves will droop when it needs water, then perk right back up after a drink. Thriving in low to medium light, it\'s perfect for offices and rooms without bright windows. The elegant white spathes (often mistaken for flowers) appear throughout the year, adding a touch of grace to your indoor garden. This low-maintenance beauty is ideal for those seeking both aesthetic appeal and health benefits.', 'Elegant flowering plant with air-purifying qualities', 24.99, NULL, 18, 1, 0, 1, 'PEA-001', '[\"\\/images\\/products\\/peace_lily.png\"]', '[\"Water when leaves begin to droop slightly\",\"Prefers low to medium indirect light\",\"Mist leaves regularly for humidity\",\"Remove spent blooms and yellow leaves\",\"Fertilize monthly during growing season\",\"Keep away from cold drafts and heating vents\"]', '{\"height\":\"1-3 feet\",\"light_requirements\":\"Low to medium indirect light\",\"water_needs\":\"Moderate - prefers consistently moist soil\",\"toxicity\":\"Toxic to pets and humans\",\"humidity\":\"50-60% - appreciates misting\",\"temperature\":\"65-80\\u00b0F (18-27\\u00b0C)\"}', 1, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(6, NULL, 'ZZ Plant (Zamioculcas)', 'zz-plant', 'The ZZ Plant is a modern marvel of the plant world, featuring glossy, waxy leaves that look almost artificial in their perfection. This virtually indestructible plant is perfect for those who travel frequently or tend to forget about their plants. Native to East Africa, the ZZ Plant stores water in its thick rhizomes, allowing it to survive weeks without watering. Its upright, architectural growth habit and deep green foliage make it a favorite in contemporary interiors and offices. The ZZ Plant tolerates low light better than almost any other houseplant and is resistant to pests and diseases. Whether you\'re a beginner or an experienced plant parent, this stunning, low-maintenance beauty will thrive with minimal attention while adding a touch of modern elegance to your space.', 'Virtually indestructible plant with glossy leaves', 27.99, NULL, 0, 0, 0, 1, 'ZZ-001', '[\"\\/images\\/products\\/zz_plant.png\"]', '[\"Water every 2-3 weeks - allow soil to dry completely\",\"Tolerates low to bright indirect light\",\"Use well-draining potting mix\",\"Fertilize 2-3 times during growing season\",\"Wipe leaves monthly to maintain glossy appearance\",\"Avoid overwatering - rhizomes store water\"]', '{\"height\":\"2-3 feet\",\"light_requirements\":\"Low to bright indirect light\",\"water_needs\":\"Very low - drought tolerant\",\"toxicity\":\"Toxic to pets and humans\",\"humidity\":\"Average household humidity\",\"temperature\":\"60-75\\u00b0F (15-24\\u00b0C)\"}', 1, '2025-12-12 05:22:46', '2025-12-15 06:35:07'),
(7, NULL, 'Hybrid Tea Rose Bush', 'hybrid-tea-rose-bush', 'The Hybrid Tea Rose is the quintessential garden rose, renowned for its large, perfectly formed blooms and intoxicating fragrance. These classic beauties produce elegant flowers on long stems, making them ideal for cutting and creating stunning bouquets. Each bloom unfolds slowly, revealing layers of velvety petals in rich, vibrant colors. Hybrid Tea Roses are the result of careful breeding to combine the best traits of different rose varieties - large blooms, strong fragrance, and continuous flowering throughout the growing season. While they require more care than some other roses, the reward is spectacular blooms from late spring through fall. Perfect for formal gardens, rose beds, or as a focal point, these roses will transform your outdoor space into a romantic paradise. With proper care including regular watering, feeding, and pruning, your rose bush will provide years of breathtaking beauty.', 'Classic garden rose with large, fragrant blooms', 39.99, 34.99, 12, 1, 1, 1, 'ROS-001', '[\"\\/images\\/products\\/hybrid_tea_rose.png\"]', '[\"Plant in well-draining, nutrient-rich soil\",\"Water deeply 2-3 times per week during growing season\",\"Prune in late winter to encourage new growth\",\"Fertilize every 4-6 weeks during blooming season\",\"Deadhead spent blooms to encourage more flowers\",\"Apply mulch around base to retain moisture\",\"Monitor for pests like aphids and treat promptly\"]', '{\"height\":\"3-5 feet\",\"light_requirements\":\"Full sun (6+ hours daily)\",\"water_needs\":\"Moderate to high\",\"toxicity\":\"Non-toxic\",\"bloom_time\":\"Late spring through fall\",\"hardiness\":\"USDA zones 5-9\"}', 2, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(8, NULL, 'Lavender Plant', 'lavender-plant', 'Lavender is a beloved Mediterranean herb that brings beauty, fragrance, and versatility to any garden. With its silvery-green foliage and stunning purple flower spikes, lavender creates a romantic, cottage-garden atmosphere while attracting beneficial pollinators like bees and butterflies. Beyond its ornamental value, lavender is prized for its aromatic essential oils, used in aromatherapy, cooking, and crafts. The flowers can be harvested and dried for sachets, potpourri, or culinary use. This drought-tolerant perennial thrives in sunny locations with well-draining soil, making it perfect for water-wise gardens. Plant lavender along pathways to release its calming scent when brushed against, or create a stunning lavender hedge. Once established, it requires minimal care and will reward you with fragrant blooms year after year.', 'Fragrant Mediterranean herb with purple blooms', 16.99, NULL, 28, 1, 0, 1, 'LAV-001', '[\"\\/images\\/products\\/lavender.png\"]', '[\"Plant in full sun location\",\"Use well-draining, slightly alkaline soil\",\"Water sparingly once established - drought tolerant\",\"Prune after flowering to maintain shape\",\"Avoid overwatering - prefers dry conditions\",\"Harvest flowers in morning for strongest fragrance\"]', '{\"height\":\"2-3 feet\",\"light_requirements\":\"Full sun\",\"water_needs\":\"Low - drought tolerant\",\"toxicity\":\"Non-toxic, edible flowers\",\"bloom_time\":\"Late spring to summer\",\"hardiness\":\"USDA zones 5-9\"}', 2, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(9, NULL, 'Hydrangea Bush', 'hydrangea-bush', 'Hydrangeas are show-stopping flowering shrubs that produce massive, globe-shaped flower clusters in stunning shades of blue, pink, purple, or white. These garden favorites are beloved for their long-lasting blooms that appear in summer and can be dried for beautiful arrangements. The flower color of some varieties can be influenced by soil pH - acidic soil produces blue flowers, while alkaline soil creates pink blooms. Hydrangeas are perfect for creating dramatic focal points, hedges, or foundation plantings. They thrive in partial shade and appreciate consistent moisture, making them ideal for those shady spots in your garden. With their lush foliage and spectacular blooms, hydrangeas bring a touch of elegance and old-world charm to any landscape.', 'Spectacular flowering shrub with large bloom clusters', 44.99, 39.99, 10, 1, 1, 1, 'HYD-001', '[\"\\/images\\/products\\/hydrangea.png\"]', '[\"Plant in partial shade to morning sun\",\"Keep soil consistently moist but not waterlogged\",\"Mulch around base to retain moisture\",\"Fertilize in spring with balanced fertilizer\",\"Prune after flowering for best results\",\"Adjust soil pH to influence flower color\"]', '{\"height\":\"3-6 feet\",\"light_requirements\":\"Partial shade to morning sun\",\"water_needs\":\"Moderate to high\",\"toxicity\":\"Toxic to pets\",\"bloom_time\":\"Summer to fall\",\"hardiness\":\"USDA zones 3-9\"}', 2, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(10, NULL, 'Aloe Vera', 'aloe-vera', 'Aloe Vera is far more than just a pretty succulent - it\'s a living first-aid kit! This versatile plant has been used for thousands of years for its medicinal properties, particularly for soothing burns, cuts, and skin irritations. The thick, fleshy leaves contain a clear gel rich in vitamins, minerals, and antioxidants. Beyond its healing properties, Aloe Vera is an attractive, low-maintenance plant perfect for sunny windowsills, desks, or outdoor gardens in warm climates. Its architectural rosette of spiky leaves adds a modern, sculptural element to any space. Aloe Vera is incredibly easy to care for, requiring minimal water and thriving on neglect. It also produces offsets (baby plants) that can be separated and shared with friends. Whether you\'re growing it for its beauty or its benefits, Aloe Vera is a must-have plant.', 'Medicinal succulent with healing gel', 12.99, NULL, 30, 1, 0, 1, 'ALO-001', '[\"\\/images\\/products\\/aloe_vera.png\"]', '[\"Water every 2-3 weeks, allowing soil to dry completely\",\"Needs bright, indirect light - can tolerate some direct sun\",\"Use well-draining cactus or succulent soil mix\",\"Fertilize sparingly, 2-3 times during growing season\",\"Remove offsets to propagate new plants\",\"Avoid overwatering - main cause of problems\"]', '{\"height\":\"1-2 feet\",\"light_requirements\":\"Bright, indirect to direct light\",\"water_needs\":\"Very low - drought tolerant\",\"toxicity\":\"Mildly toxic to pets, safe for topical use on humans\",\"humidity\":\"Low - prefers dry conditions\",\"temperature\":\"55-80\\u00b0F (13-27\\u00b0C)\"}', 3, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(11, NULL, 'Jade Plant', 'jade-plant', 'The Jade Plant, also known as the Money Tree or Lucky Plant, is a beloved succulent that symbolizes prosperity and good fortune in many cultures. This charming plant features thick, glossy, oval-shaped leaves that resemble jade stones, growing on sturdy, woody stems that develop a tree-like appearance over time. With proper care, Jade Plants can live for decades, becoming treasured family heirlooms passed down through generations. They\'re incredibly easy to care for, requiring minimal water and thriving in bright light. Mature plants may even produce delicate white or pink star-shaped flowers in winter. The Jade Plant\'s compact size and sculptural form make it perfect for desks, shelves, or as a bonsai specimen. Whether you believe in its lucky properties or simply appreciate its beauty, this timeless succulent is a wonderful addition to any plant collection.', 'Lucky succulent symbolizing prosperity', 18.99, 15.99, 24, 1, 0, 1, 'JAD-001', '[\"\\/images\\/products\\/jade_plant.png\"]', '[\"Water when soil is completely dry - every 2-3 weeks\",\"Needs bright light, can tolerate some direct sun\",\"Use cactus\\/succulent potting mix\",\"Prune to maintain desired shape\",\"Fertilize monthly during growing season\",\"Rotate plant for even growth\"]', '{\"height\":\"1-3 feet\",\"light_requirements\":\"Bright light to full sun\",\"water_needs\":\"Very low\",\"toxicity\":\"Toxic to pets\",\"humidity\":\"Low\",\"temperature\":\"55-75\\u00b0F (13-24\\u00b0C)\"}', 3, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(12, NULL, 'Echeveria Succulent', 'echeveria-succulent', 'Echeveria succulents are nature\'s living sculptures, forming perfect rosettes of fleshy leaves in an stunning array of colors from pale green and blue-gray to pink, purple, and even black. These Mexican natives are among the most popular and collectible succulents, prized for their geometric beauty and low-maintenance care requirements. Each variety has its own unique characteristics, with some featuring ruffled edges, powdery coatings, or vibrant color tips. Echeverias are perfect for dish gardens, fairy gardens, or as standalone specimens in decorative pots. They produce tall flower stalks with bell-shaped blooms in shades of coral, pink, or yellow. These drought-tolerant beauties thrive in bright light and require minimal water, making them ideal for busy plant lovers or those new to succulents.', 'Geometric rosette succulent in stunning colors', 9.99, NULL, 40, 1, 0, 1, 'ECH-001', '[\"\\/images\\/products\\/echeveria.png\"]', '[\"Water every 2-3 weeks when soil is completely dry\",\"Needs bright light - some varieties tolerate full sun\",\"Use well-draining succulent soil\",\"Avoid getting water on leaves to prevent rot\",\"Remove dead lower leaves as plant grows\",\"Propagate from leaf cuttings or offsets\"]', '{\"height\":\"4-8 inches\",\"light_requirements\":\"Bright light to full sun\",\"water_needs\":\"Very low\",\"toxicity\":\"Non-toxic\",\"humidity\":\"Low\",\"temperature\":\"60-80\\u00b0F (15-27\\u00b0C)\"}', 3, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(13, NULL, 'Sweet Basil Plant', 'sweet-basil-plant', 'Sweet Basil is the king of culinary herbs, essential for Italian cuisine and beloved by home cooks worldwide. This aromatic annual produces lush, fragrant leaves perfect for pesto, caprese salad, pasta dishes, and countless other recipes. Growing your own basil ensures you always have fresh, flavorful leaves at your fingertips, far superior to store-bought alternatives. The plant thrives in warm, sunny conditions and grows quickly, providing abundant harvests throughout the growing season. Regular harvesting actually encourages bushier growth and more leaves. Beyond its culinary uses, basil is attractive enough to grow as an ornamental, with some varieties featuring purple leaves or beautiful flowers. Plant basil in your kitchen garden, in containers on a sunny patio, or even on a bright windowsill for year-round fresh herbs.', 'Essential culinary herb for fresh cooking', 8.99, NULL, 20, 1, 0, 1, 'BAS-001', '[\"\\/images\\/products\\/sweet_basil.png\"]', '[\"Water when soil surface feels dry - keep consistently moist\",\"Needs 6-8 hours of direct sunlight daily\",\"Pinch off flower buds to encourage leaf production\",\"Harvest regularly by pinching off top leaves\",\"Fertilize every 2-3 weeks with balanced fertilizer\",\"Protect from cold temperatures - sensitive to frost\"]', '{\"height\":\"1-2 feet\",\"light_requirements\":\"Full sun (6-8 hours)\",\"water_needs\":\"Moderate - keep soil moist\",\"toxicity\":\"Non-toxic, edible\",\"harvest_time\":\"4-6 weeks from planting\",\"growing_season\":\"Spring through fall\"}', 5, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(14, NULL, 'Rosemary Plant', 'rosemary-plant', 'Rosemary is a fragrant, evergreen herb that brings the essence of the Mediterranean to your garden or kitchen. This woody perennial features needle-like leaves packed with aromatic oils, perfect for flavoring roasted meats, vegetables, bread, and infused oils. Beyond its culinary prowess, rosemary is steeped in history and symbolism, representing remembrance and fidelity. The plant produces delicate blue, purple, or white flowers that attract bees and butterflies. Rosemary is remarkably versatile - grow it as a hedge, in containers, or even train it as a topiary. It\'s drought-tolerant once established and thrives in sunny, well-drained locations. The fresh, pine-like scent is invigorating and can be used in aromatherapy. Whether you\'re an avid cook or simply appreciate beautiful, fragrant plants, rosemary is an excellent choice.', 'Aromatic Mediterranean herb for cooking', 11.99, NULL, 16, 1, 0, 1, 'ROS-002', '[\"\\/assets\\/images\\/products\\/monstera.png\"]', '[\"Water sparingly - allow soil to dry between waterings\",\"Needs full sun (6+ hours daily)\",\"Use well-draining soil - avoid waterlogged conditions\",\"Prune regularly to maintain shape and encourage growth\",\"Harvest sprigs as needed, avoiding more than 1\\/3 at once\",\"Protect from harsh winter winds in cold climates\"]', '{\"height\":\"2-4 feet\",\"light_requirements\":\"Full sun\",\"water_needs\":\"Low - drought tolerant\",\"toxicity\":\"Non-toxic, edible\",\"harvest_time\":\"Year-round once established\",\"hardiness\":\"USDA zones 7-10\"}', 5, '2025-12-12 05:22:46', '2025-12-14 21:07:25'),
(15, NULL, 'Mint Plant', 'mint-plant', 'Mint is an vigorous, refreshing herb that\'s perfect for teas, cocktails, desserts, and savory dishes. This fast-growing perennial is incredibly easy to cultivate, spreading enthusiastically through underground runners (which is why many gardeners prefer to grow it in containers). The aromatic leaves release their fresh, cooling scent when brushed or crushed, making mint a delightful sensory experience. There are many varieties to choose from, including peppermint, spearmint, chocolate mint, and apple mint, each with its own unique flavor profile. Mint is not only useful in the kitchen but also beneficial in the garden, attracting pollinators and repelling some pests. Harvest leaves regularly to encourage new growth and prevent flowering. Whether you\'re making mojitos, brewing tea, or garnishing desserts, fresh mint elevates any dish.', 'Refreshing herb for teas and cocktails', 7.99, 6.99, 32, 1, 0, 1, 'MIN-001', '[\"\\/images\\/products\\/mint.png\"]', '[\"Water regularly to keep soil consistently moist\",\"Grows in partial shade to full sun\",\"Contain in pots to prevent aggressive spreading\",\"Harvest frequently to encourage bushy growth\",\"Pinch off flowers to focus energy on leaves\",\"Divide plants every 2-3 years to maintain vigor\"]', '{\"height\":\"1-2 feet\",\"light_requirements\":\"Partial shade to full sun\",\"water_needs\":\"Moderate to high\",\"toxicity\":\"Non-toxic, edible\",\"harvest_time\":\"Continuous throughout growing season\",\"hardiness\":\"USDA zones 3-9\"}', 5, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(16, NULL, 'Orchid (Phalaenopsis)', 'phalaenopsis-orchid', 'Phalaenopsis orchids, commonly known as Moth Orchids, are the most popular and easiest orchids to grow indoors. These elegant plants produce stunning, long-lasting blooms that can persist for months, making them excellent value and a favorite gift. The gracefully arching flower spikes bear multiple blooms in colors ranging from pure white and soft pink to vibrant purple and yellow, often with intricate patterns and markings. Despite their exotic appearance, Phalaenopsis orchids are surprisingly easy to care for, requiring only bright, indirect light and weekly watering. They thrive in typical household temperatures and humidity levels. After the flowers fade, with proper care, the plant will rebloom, providing years of beauty. These sophisticated plants add a touch of luxury to any interior, whether displayed on a windowsill, desk, or as an elegant centerpiece.', 'Elegant orchid with long-lasting blooms', 34.99, NULL, 14, 1, 1, 1, 'ORC-001', '[\"\\/images\\/products\\/phalaenopsis_orchid.png\"]', '[\"Water weekly by soaking roots for 10-15 minutes\",\"Place in bright, indirect light - avoid direct sun\",\"Use orchid-specific potting mix (bark-based)\",\"Fertilize monthly with diluted orchid fertilizer\",\"Maintain 50-70% humidity if possible\",\"Trim spent flower spikes to encourage reblooming\"]', '{\"height\":\"1-2 feet including flower spike\",\"light_requirements\":\"Bright, indirect light\",\"water_needs\":\"Low to moderate\",\"toxicity\":\"Non-toxic\",\"bloom_time\":\"2-6 months per flowering cycle\",\"temperature\":\"65-80\\u00b0F (18-27\\u00b0C)\"}', 4, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(17, NULL, 'African Violet', 'african-violet', 'African Violets are charming, compact flowering houseplants that bring continuous color to indoor spaces. These beloved plants produce clusters of delicate, velvety flowers in shades of purple, pink, white, or blue, set against fuzzy, dark green leaves. What makes African Violets special is their ability to bloom year-round with proper care, providing constant cheerful color on windowsills, desks, or shelves. They\'re perfect for small spaces and make excellent gifts. While they have a reputation for being finicky, success comes down to understanding their simple needs: consistent moisture (but never wet leaves), bright indirect light, and warm temperatures. Many enthusiasts become collectors, drawn to the hundreds of varieties available with different flower forms, colors, and leaf patterns. These compact beauties prove that good things come in small packages.', 'Compact plant with year-round colorful blooms', 13.99, 11.99, 26, 1, 0, 1, 'AFR-001', '[\"\\/images\\/products\\/african_violet.png\"]', '[\"Water from bottom to avoid wetting leaves\",\"Keep soil consistently moist but not soggy\",\"Provide bright, indirect light\",\"Use African Violet-specific potting mix\",\"Fertilize every 2 weeks with African Violet fertilizer\",\"Remove spent flowers to encourage more blooms\"]', '{\"height\":\"6-8 inches\",\"light_requirements\":\"Bright, indirect light\",\"water_needs\":\"Moderate - consistent moisture\",\"toxicity\":\"Non-toxic\",\"bloom_time\":\"Year-round with proper care\",\"temperature\":\"65-75\\u00b0F (18-24\\u00b0C)\"}', 4, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(18, NULL, 'Anthurium', 'anthurium', 'Anthuriums are striking tropical plants known for their glossy, heart-shaped \"flowers\" (actually modified leaves called spathes) in vibrant shades of red, pink, white, or coral. These exotic beauties bring a touch of the tropics to any interior, with their shiny, waxy blooms lasting for weeks or even months. The true flowers are the tiny bumps on the central spadix, while the colorful spathe provides the dramatic display. Native to Central and South American rainforests, Anthuriums appreciate warmth, humidity, and bright, indirect light. They\'re surprisingly easy to care for and bloom repeatedly throughout the year with proper conditions. The glossy, heart-shaped leaves are attractive even when the plant isn\'t flowering. Perfect for adding a bold pop of color to modern interiors, Anthuriums make sophisticated statement plants that never go out of style.', 'Tropical plant with glossy, heart-shaped blooms', 28.99, NULL, 11, 1, 0, 1, 'ANT-001', '[\"\\/images\\/products\\/anthurium.png\"]', '[\"Water when top inch of soil is dry\",\"Needs bright, indirect light\",\"Maintain high humidity (60-80%)\",\"Use well-draining, peat-based potting mix\",\"Fertilize monthly during growing season\",\"Wipe leaves to keep them glossy\"]', '{\"height\":\"1-2 feet\",\"light_requirements\":\"Bright, indirect light\",\"water_needs\":\"Moderate\",\"toxicity\":\"Toxic to pets and humans\",\"bloom_time\":\"Year-round with proper care\",\"temperature\":\"65-80\\u00b0F (18-27\\u00b0C)\"}', 4, '2025-12-12 05:22:46', '2025-12-14 23:20:02'),
(20, NULL, 'TEST', 'test-1765805431', 'AEKJFHDAKJF', NULL, 123.00, NULL, 12, 1, 0, 1, NULL, '[\"data:image\\/jpeg;base64,\\/9j\\/4AAQSkZJRgABAQAAAQABAAD\\/2wCEAAkGBxMSEhUTEhIVFhUVFRYVFRYVFRcVFRUVFRUWFhUWFxUYHSggGBolGxYWIjEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGxAQGi0lHyUtLS0tLS0tLS0tKy0uLS0tLTAtKy0tLS0tKy8rLSstLS0tLS0tLS0rLS0tLS0tLS0tLf\\/AABEIAPsAyQMBIgACEQEDEQH\\/xAAcAAABBQEBAQAAAAAAAAAAAAACAQMEBQYABwj\\/xABBEAABAwEFBQUFBgMJAAMAAAABAAIRAwQFEiExBkFRYXETIoGRoTKxwdHwFCNCUnKSM2LhBxVDU4KistLxFlSD\\/8QAGgEAAwEBAQEAAAAAAAAAAAAAAAECAwQFBv\\/EAC8RAAICAQIEAQsFAAAAAAAAAAABAhEDEiEEMUFRIgUTMmFxgZGhweHwFEJSsfH\\/2gAMAwEAAhEDEQA\\/APWMSXEmg5LiQMNzsslgLz2otNltNSniDmzja14kYX5jCRBA1GsZLe4lg\\/7TbvljK7fapuwn9D8x5OH+4rm4uMvN3F01uJ7Fjd39oNFxivTdSP5h94z0GIeRWssVsZVaH03te072kEdMt\\/JeD0q0jNSrFbKlB+OjUcx3Eb+RGjhyK4cflCcXWRWKz3Gu7RLiWLuDbZtXDTtADH7njJjus+yfTotiHL08eWGRXFlDgKWU2CllaAOBy4FNyulADkpCUMoZQAeJdiQShlADwKWUy1y4OQAdodkhplBXOi5hQA64pMSAldKADxJMSCV0oAKs7JNYktd2Sj9ogAadXJH2iQuCcBCAB7VVm0di7eg9m9zcv1DvN9QrNxHBIXNIgpNJqmB4LUGA6EZwRwP1u1T9GtO\\/5bgtlt5s+AXWimMj\\/FA\\/5gcOI8eKwRaWnly08eC8PNhcJaX\\/AKY7pllOXQ\\/+LbbEbSEEWeq6QcqTjuP5J4cOGnCMBRrSHeHopDHwQscWSWGepGkT3HtDwSivyVJsfff2mh3j95Thr+Y\\/C\\/xg+IKv5C+ghNTipLqUB2yTtkeJI6oACTEBWAhrckLqp1jJY3ava+qxwp2UNBJAxkYjJz7oOWQ3mdQsparxrPA7es98gnN3d5dwZDXgsJZ0nSM3kSPQL02ys1DIuL3flpwf9xIHqquy7e9q\\/CyzlunedUGQP8obrHNeY9oXuyziPXhxPJbrZO6sVVoIyPef+kbj1MDxWSyzlLYjXJs3tgqnBLplxxZ7gYgeQHmpQqpcS6RwXYjYCu6RkhpvTshI9wCAE7RJ2q7tOS4vHBAA9su7ZKXjghlAxarpCjSpPaIMQ4IArLNaC4yNAqW8NshZ6zqVoovDZmnUZBD2HkSMxoYO7RX1goYGDood+2CnWZge0EHzafzN4EKJKVeF7gyGzbqxHWo9v6qb\\/gCrmwXnQr\\/wqrH8muBI6t1C8gvm5KtmPebiYTk8aHh06KNZwCQWnCRmCDB8\\/Bcf6nJF1JEa65nt1rohzSCJBBBHEEQR5Lxq+rvdQqupnce6fzNPskeC0Fz7a1qBwWiarNzj\\/EaOp9vx81p7bY7NeNHExwdGjm+3TPAg59Wn5FPIo8RHw80D8S2PJGVP67vRTqdTJvVFfN1PoVCyoMxm1wzDhuI5f1USi\\/TqvNyR6PmTF1szW7EXj2VqDSe7VmmeEnNnrA8V6gCvDWPMyDBGYPA8V7VYLT2tJlT87Gu\\/c0H4r0PJ8\\/C4djREouyWKv8At9WtWFJpikH4DH4nNBLp6EAea0F\\/23sqRIOeg6nT4nwWVFbBT7Q\\/hqx+5rnfBdWR3sZ5H0KW3VAHN0LxTLo4Fxlx9As+6s6q4ATGYy1PBo5\\/+pW2hxrudmRL2iBJMkhrQN55LW3FcLaLG1KhaXRkBmGz+EHeeJ88shyU5ul7zOiBclzFgBLe9oN4HJvE8TvXpFxXd2LM\\/bdm7lwCYui7yPvKgz\\/C38o3GOPLcreV2Y8aiawj1YQKUFAlBWpYcpusUuJN1jmEAdKKU2UsoGFK5cFxQBySUkrpQBXWm1R3W6oqVEgSU3YbuA7ziZVm1o0SGQqtFj2kOAIIggiQRwIOq892s2YFA9tQBDAe+wGYk6t5HSOfPL000WpmvY2uBBzBEQcwQdQRvCmcFJbiaTPErfamuDY1b3SeIOh9PVBdF61bNUD6b8LhkeDhwcN4W22h2DxS6g6P5T55OOvjHUrBW6x1KVTBUaWujORvG8cdJ8VwTxyi7MtNHpTX0b1ofkrMzjUsJ3j81MxmP6LE3vdFWzn71kA5NqNzY7xG\\/kc01cd4GjUFRpggT\\/2B4gicuML1G7LwZbaLpYxzZLKjDPUdQQWkH5JyUcquW0u4Wnz5nk9B+vRexbKvmyUZ\\/IB5EheZ7SbOPstTEwOdRcRBOZbP4XHxyO\\/qYXpuzNCLJQ50mH9wn4o4ONZH7C4uyBtZVkBvJzp4BmH\\/ALLD31b3EPoM07RjtM8mEab960G31fDUptnMsq+rqf8A1Kj0bNTbUfaakDER2bMi7C1oaO6dHHIwZicxmY3yy3e9fRGc\\/SItxXSWd40g5xkNxZhsnMADJzjvPhECVurrusMh1TvP56N5D5+SduyyMLQ8ODpGRG4cBOin9hzWuOEYpVyKjGt2KSllCKPEpey5rY0FJXSkLBxXGmOKQhQm3nNOMA4rnsBTAbXQl7Pml7HmgYJKWUnZc0vZc0CEJSSuNLmk7LmgYgRKNWtTGe04D3+SrXbU2YHCHEkbgBPkSCs3OK5sLLhp1RFUlPaWzYoLy2fzscB5xHqrWz2unUE03tcOLXB3uQpxfJhY\\/CqL8uKlaWFr29CPaaeLT8EI2kodu+zucWuZhzd7JLmh0TugEaq2ad4MjknalsHM8Uvi6alifgeMQdOB49l7Tqf5SMpB479VN2Ivc0LRgcYbVik6dz2k9mfOW\\/653LZ7d3U6rTDwJ7PES3i10SRzGFeWW2z4Ig5EkznOpOfA5hcOSlNx+BjJUz2PaqhjsdpaNRRe5m\\/vMAqMy\\/UweSt7Paw5rHt9lzWu6BwBB8iFT3NbBabK2odXMLX\\/AKgC13mc+hCzDr1d9io0aZIPZNa8\\/iwsb2bW+IbJPAjiVM8yxLUNPw2Str7dZ31mOYTUqUg9oiMEuLcy7eWwcgIkjhBqLFTkycycyTJJ6k9TloM4hQmRPuVhZNJ4\\/D69V5PFcTPJd7EuVmn2atgZUgmGubHKZGEnzI8VsGlYS53sGLHqRhHCXbvctZdVoxNLSZcw4SeIzwn0PkV6\\/kyMo8OlL2ouD6EmZKMpmm5PBekaCQlBSpAECOCVCilAApVxK6UAIVwSkpEACVy5ySUAVVru4PG\\/PeDn8lkL32XqGTAeOWTh0AzHgtwWuBR4SdVjPFGYOKZ41aLJUpmA4uAyh3tDwOvhmp931JbMwRoN5Xpd4XRSrDvNBManXwcMwsPfGxVQOxUiXD8pMPHQ7x9c1yz4dr1kSh2KerbS50SDlkeKcsW1j6TgMRLZzwmHjKMjoY4O81U3hdVekDjpvDdcTmkYTrrpnyJGeqqqOcE7\\/eudRcXZC2PXbDta0gdqZaQ0h7QQQSYIc3fB4R0WN2vu5tOpiZBoVu\\/Tc3NoJ1AOmp04GFUi2Oa0AaaZ6Yoy8xPlOqsbrvdsGlVBdRf7bN7HfmZwcPVaTnqWmXufYaky22EvgU6dSi46Bz8M6loJIB3yCPBoUQ1OGgyy3RplwVXa7EbLaGQ7HTeJpPGj2PDmg8iCYI3eKfp1948QuDioytJ9Ccir3nWl5Bgb9Oc\\/FW7IENHJoPUgDPmSPNVNFgNQGch3jO6NPWElW2B9pp0vwtqMNSNSQ4HCOgkdSeAWMMXnZxh8SYqy2NoiaoJwlxbQB1cRrUI4DXyG9anY+04jO5zS3qWZ\\/wDZYC8ari9rTAeQAGDSjTObR1IMzqZneFvNlmBhoNbp3gOYDHSfP4L38Sp0uSLjzNQw5p+VX3xbKdnpuq1XYWjzJ3ADeTwXld\\/7Z17TLWuNKluY0w5w\\/ncNegy66rXJljjW5sesV73s9MxUr0mHg6o1p8iUFG\\/LNUOFloouPAVGE+AnNeDBwSdr5Ll\\/Vv8AiB9EPEpF4ddG09ps8CnVOH8j+8zwB9n\\/AEwtVd\\/9oRcQKrA0b3Al08MtR6raPFQfPYD0ZxVNfd+NpZAjHlzgcTCdoXjTrU8VN4JAxRO75c1hLRaH1a7nQMiZ5hpy8FeTJUdikiVUvKtUIONxfixQMmBjZ1Gq091X65wYHhubQXEbj9BY+iS4VKjnDF7OXPcPBTbJXMMaxsFzczOpkwFxKc4yuxUegB0pVT2C1RSxOMRlnxCZ\\/v1vE+S7\\/OxpNhRaFyVj0C5qoYtoqOwnDEwYmYB3SBuXnl+3ra8WGsTTG4MlrDzBHtDxK9ETNooNcIIBB1BEjyWHEYXkjSdCas8us151aZllVw8ZB6g5HxRW51nrtJqUezqa46MNa8\\/zUzlPMQtfeOydJ4lrcB4tyHlp6TzCy1vuKtS\\/DjbxaDP7ePSV5Uo5sPPl8vz5mbTRQ1rvc+OzIc0QHRk5pGhew5jqJGRMqNUeGljvzDvDeCDBy8j4lSqjc8TSQRoQYISWmu2rlUADxmHgZvjWRvPLfu3A6QyalTEo3yHhXx0uxdmMbXsO9hkCph5Fu7i0I6dQFs\\/i\\/FlEHjHA\\/GFXWepBbMHUyNCDoRyzVrftGKVB7Dm9vrSaaTfRvv4qZR1Kn0NPN640M2et2balQ65NbzOo8N\\/QFVV01yy0MdqQ6c85J3njnnG+Eld7sIkENcSQdxIgnPkHN\\/cotnqHtRhcA7EIMgQfzSdI1nctcGPS7JcdOxeWgff1C52LDLqrpzJGbmz+YnLqYVvZL9tLR2xeGloHZgNGFomCI3tILhnJPGYVFZ2Ne4MZ\\/DYZJ\\/O6NY4AaDnzVhfdmcKTQAcTnTERhYARLjuk6D+Uq8mSnSZl1I+0m0VW2PxVDDW5MYPZHExOp9NFS5nPiptO6KrtGkjoY8zCnWW5JPffTaOby8+TAB\\/uWcsiu29zWyknPkETGFxyBPQK\\/qXRRaYxujizs3jzLZCCyXdTc6HF7hMd525NJvcdMo3ZcB1IHpKlWWw1XxhaTO8gho6uiFrDY6Taoa1jQIGnnM6qZghxB3j3oaAi2C6n2ez1Xtrd5zSCAAGAb4nOeeXRV921AQZLtNePVTdobWadB+6eeSqbBaIbqdOHqt4+iPoW1l\\/gkgbz8laUCe5PstaBlr9ZqspVYosbvLgPjnyVkK8A6AemecJUOmLSL31C2fu2xDRx1zVrgHAKkubE5zjijE7TlotN9n\\/mPoplG2WWVJ2QRAqiuG1GqwZ5q4+znivRJHygD+SAUXcV3YnigB8PTVopAiI+XkgdSPFSKNOBmhqwM3etxU6urJPEZOHR2\\/oVgr7uV9ImcwNHRH7huPpzXr3YHco1rujtBBHjvHQ\\/BcOXg16WLZ9un2Jcex4U+Wz9fQVvbLVis9EA95vaNjfLnvcOX4hvWh2p2Z7PvOZDZjG0AAk7nN0Due\\/id2MdScx9Nj4jE10gyC0O70eAOq5m3dSVPqjSL2NzZ7vpGy07PVYHOY8vJDsgc2hsjXuwDnqOQKdFNlId0YRwbDR5AQp1hstP7HZnuBdVrsb2TQTjqOcC4Z7mgZkkQAFjL+a5tX7O55jEcbmiPu2SKmRMjcfCN6xz4M+pa5bf1XqM3CU5F1VvcnKlGmIunHDJAxcNXD+ktmK8AnEGS6ZxPPaOmNQTk0\\/pDVptlLmaKpaGmaDWOrEg516jPu6YO9tNjn5Rq9p1C2DbP4LrXANx2lXfb6lOKWyPJ6rXH2iT1M+SAMXq\\/wBjY7VoPUA+9NVdn7O7Wk3wEf8AGFjLyZPpJfnxM9KPLwxLC31q2MpnNji3\\/cPIwfVU1s2QrN9mHdDB8nR71y5OCzw\\/bfs3+4tJnaToMjVJeN4ljTULZjUjd1HBWn\\/x207qTj6f0US02CqyRUpPaNDiaYIPOIIUwllxO2nXrTHujA7UX72zQ0bzJ+Ck2Ks4MEk5tBOfvXsey9y3Xa6cOsVmFZo77ezaJA\\/G0cPcfCdIzY6wAQLJRjT2AvexqM4Jw5FJo8Yo37Ra0ag9JBy5qDfO1jMBbSPe0OfHf5L3mnslYRpZKA\\/\\/ADb8lMo3JZ2ezQpDpTaPgqWEpzs+etm71tLiGspPdzax5HmAt99ptn\\/1Kn7V6iKAGgA6CF2BWsaROo8m2KqZkLbgrBbDnMrcYskIpi1q+ESTkqype\\/BvmrinYg5pDxM6\\/W5Ul\\/bIvqsLaFfAeDgcxwxtzb5FUISrfzGCXvA8h71CqbeWduhnxPwBlZqvsLbm\\/wCE1\\/NtRpnfJxkE\\/wDirK+y9pYBis9QEHVtNxzOubRAHNZuUuxehdzXP\\/tIZ+GmT0n1mEDf7TmzHZHWM2nPpmsJUsrmzjpubuGJkDXWHH38+UNNIjKDx4nIgDXTOfAc1LmwUEbrafaw1bHUxNa3EwwIJMgy3eRqIXmFe2ucQD+EGDvOLL3KzfaTEES3TDEzlETpmqF7Dh0M6eA+h5rOa1NNoKo9O2Lv7A1jnDHUbRZRpA4gKdJgAIblEuIknl1UH7c37Q\\/GJaajXvG8tp43uaDzDj4gcFjrsvDC5knDhcyRIBIBzMAyckRvUYiSR3nEkE8SIC58sJucey+pcWkep7N7a0KFN3aNealWo+tVc2CC+o7QSdA0NELS3ftrZawJaXiDBDmZ+k5Lw+n2zjIbUcJ0awnLeBhBhTrLdNqqAltmrcAHUKg3DOQ3QrrjOSRLime50r1s7\\/ZqMnrB8ipIcNQfivDrJsjbz3hZaoOe\\/CfEuIzWmufZC9g4OFY0eT6ocPFrcQPRaKbfQjSu56Y2uBrlzUtrVW3bddQUwLRUbUfvLGYGngIkz138FaUGwIO7L5LQhsdp0xvROoN5LmhOBMAWUwNAnAFwRBIBIXQnAxI5qAAISQjKRAHjeyVjfSnGIW0sJDndBPich8VkaF7\\/AJgtRcNQObiGhPuWcWayWxdUwnUDE4FoZihEkCVAHJp9nYdWNPUAp4J+jRB+h80AVpu+l\\/lU\\/wBjfkk\\/u+j\\/AJVP9jfkp1VsFNooLI393Uf8qn+xvyT1KztaIa0AcAAB6JxEEUKzgwcEYZyXBGExHBqMNXAIgEAJhXBqNcNUAA1GFXU7UQ4tO5xHkVPpvlAIdCIIQlCQxwOSOKFcgDikXFDKAPCwVudkT9yBzd7yoNGwU26NCt7qESsovc1kti7YjCaYU4CtDMNdKGV0oAWUuJASklMA8S5AlCBBhGEDUYTEG1ONTbU41ABgJwNRUohG5yAGkiVcgCiqu+8f+p3vUuz1IVRRrSSeJJ8yrGi5MktqT5ToKgUXqW16RSY4ulDKQuSGc4pvGo15W0UmFx3ep4LIf\\/JKnAIbSBKxop25qZY50vLg6CJ3RO9Qm2luok9GlO0LQQ4d12upiOawRs0ammU6ColB+SkNK1MxwFKgldKYhSkldKRACoghCIJgGEYQBGEyRxqcam2pxqAHAUUpsIggAlEvi09nRe7g0gdTkPUqWFjf7SLc9tOnSpQS5xe6THdYMh4uI\\/agBqw1lcUKiw13XjUEY6Th0IcPer2hfbAJOIf6HfAITFRqqT1JpvWXpbR0ZjEZ5tcPgp1mvyk4wHyeA18kCNBiTVasGiSYAVe28J0afHJNV6Zf7Z8BopbLSKG+rW6s7L2RoOPNV\\/2fkVqRZWjQBd2I4BQWjLC7WiMMtj8riPSYRU7IR\\/iv13wfgl+1O3UndSWgZ+KaFS0GQGU28C55dv4AfFZ7muxfXfUyg6j15qxY5ZuxGq1wL3tIgyGtI6GSTzV7RqytIszkiYCulNNcilUSEulDK6UwDBRhNAowUCHQnGpppTjVQhxqcCaanAgkcCUIAjCACJXmm0NZ9e0Oe0jCO6w5zhGp13mT4rYbU2\\/BSLGuwveCARmWje7P6zWHdSrtzDqbhu7pafEgn3KZMpDdOy1TkKkdGCfUqXZ7A\\/LFVfzAgD0GSSz2itGVNgO+Haa6ZeqSrarRl92Doe68DrqM4UjHxdIGZfUJ1ze6DHIKRY6NMOLmtAJiTGfmolG3Onv0qozGcB7TP6CfcpVC1UCQRUa3cQXQZneHZ6oAu7MVLDlFo1AR3SD4yjxJDHS5BiCSVyBmObbLQDDrNPOnUaRzydhKJt7ge3RrMzylhdP7JVm1Nm0U972jhLgM1nZqQ2X5Zpg1mA8HODSJ4hys7vtrHey9rh\\/K4H3KvrXjZhIdWpaSQXN0G+JUb+9LIHAtqUy\\/QFgxOyH8onRCdCZr2PRhyqLsvBtVstJMGDLXNM9HAFWTXLRMhoexJcSaBS4lVkjwcja5Rw5ONKYEhpTjSmGlOtKZLHgUYKaCcamSOtSV6wY0uOgErphZK\\/r4oVJbVxCmw546dRrDEiScMEaxu9ENjIV73gxzy6pUY3dDnAQBuzKGjeFISMU5T3Wl2WuUDNLd5sn+CaGcnuFmp1OW9WlOzuOgWZRSOtLHQaYeQch92\\/5ZdV32ip+Gz1XwSMg1vq8jJaVliA1z+af7MaBA6KSxsrgH7gCMxiqjPlkDCW2UazxBoUSCM8byQD0wZq7Y2NUlRIZmaWz\\/AH2vLmtw6MpUw1pnjMyfqFeU8suHw5pwtXFkoAJglOxzKjtMRHqjxc\\/+PzRYGVbcdHLEwu3Evc58\\/qxHvHqnmXZRGlGmN2TG6eSGx35Zqv8ADtFJx3gPbI6tJnzEqwaOHz+gstzXYZZZWCIY3iAAPPrzRBgGnplmdSnw1Dg5JDEbEjPp\\/RPsrRkcue49Cua3olFIabuEfP4oVoNmSGvRSojbLGhI5Tl5FF2dQfiaTukEeZB9wV6iNJKBTjSoLe0n2W\\/vPuwo2Pqz\\/Dbr+fnr7KpSE4liwp1qrW1KuXcZz+8Ph+DNPA1YGdMGM8nOAO7eJHkq1IjSyyaurWljPaMcBqT0AzKr20nmMVVx3w0Bg9BMePinKNFjNGjPXeTG8nU9U9QtIFau+pkAQ3hvPX5IW2QnXRS+0SYkrHRAq3NZ3e3Rpv8A1MaY9ErbqoAy2m1pme5LOP5YUitXa0EucABqSQB5lV1XaKytMfaKZJkwx2N2QE91snePNIY7\\/dTASWvrAuj\\/ABqjgIMwA5xA8lwsbw4ObaKoaJlhwOBkZEOLcQjqo9W++8WsoWh5gaUsAg7w6qWjcmadttb2gizspnMEVaskZwCAwEFsQfankUASxZ7T+G0Uznljo7uEsePNI91pEfd0XiBJFRzDPJpa7LqVDpU7cT3q9AZ+y2k4gN\\/1OknmYHJTKlK1mMNWjrnjpuMDiC1w5ZRlJzKYhl141BOKy1hBIEGk7EBHeEPn3JaN8U5Ac2q05e3RqNAni7Dh9UlehbACQ+zkjOCyo3TniPnCCnY7SQMdpaDv7GkG6jcahdvIzQBNo2ulVB7Oox0a4HNdHloiwN+nBVti2epU3Bzi+o4OL2ueRk58yQGgDPmIyVnP1i\\/okNFVXsdOoCH02OBIJDmgiRoTO\\/Ieih1Lhs5JcKQYeNMupGRv7hEHIZ8ApVqqkDI\\/iaPPDPvKx1p2gtLS6KmlR7R3GaNa4gezxAUJGjNE64gB3LTaWHee1NSRvBFQOGhiRoiqWerS732wCmJL+2ZTJwxlDxgAjiQVnLitlS0saatWoSaeI4Xup5yzdTIjUrV2e56BwF1JrzAzqTUOcTm+TvQxJlM3aoioWipQrNyE0m1GyXEACe82MJ45wY4LTXfa+0ph5Y5hI9l4LXNM5yPrcjwhoAAAAMQBAjPcijPwKBjgf9eGaLF\\/VMO\\/Dzj3hG0pAO40YqJnj4e9c0+75oAkY0WNR2DMcxn6Lg7IKiR+nUMCY6DMeGXyRPq8MzrHFMtH15IqeY8T70xGStdvr1Kr2V7UbI0E4A2mQXgNBM1HAtcRxa45HMAqVZ7go1zifa6tZsYQ1loqCnmfacMZJd4xyC0ZOnQ+4KPVu6i5xc6lTc490ksaSRmYkjmU7FRWUdjbEwR2AdniLnlznEiM5J9FdUabWANY0NA0DQABnuA0VcbjoN7Qta9pLfw1arfw7sLhBy1CytsvGtQpVDTq1Ja9sF7zUjFTa4\\/xCcp8tyfMDfF319c0yXR9eCz2zF51atFrqj5cTBMNGUkbgrtzlLGhxjlPonjoPrNQKWo55KUT6AkcoBjLwQgZIf8AXL6+Ch1HZkHWfRSSchzGfp8ymagmeQkdcM+9Nkoaa\\/cfH6+fFOfWqjA+6fGSgxHifMpWVR\\/\\/2Q==\"]', NULL, NULL, 12, '2025-12-15 08:00:31', '2025-12-15 08:00:31');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_verified_purchase` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `helpful_count` int(11) NOT NULL DEFAULT 0,
  `not_helpful_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `reviewable_type`, `reviewable_id`, `rating`, `title`, `content`, `images`, `is_verified_purchase`, `is_featured`, `is_approved`, `helpful_count`, `not_helpful_count`, `created_at`, `updated_at`) VALUES
(1, 8, 'App\\Models\\Product', 1, 5, 'Accusantium numquam molestiae aspernatur.', 'Veniam optio tenetur inventore eos. Incidunt et sequi sint non. Non quia adipisci id quasi id voluptates tempore.', NULL, 0, 0, 1, 18, 3, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(2, 6, 'App\\Models\\Product', 1, 2, 'Illum a suscipit.', 'Inventore dicta sunt officia ea maiores soluta. Esse et aut nam. Ducimus quibusdam ratione ut nihil vitae. Placeat non quas ab ducimus iure provident vero.', NULL, 1, 0, 1, 44, 5, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(3, 5, 'App\\Models\\Product', 2, 3, 'Tenetur sed quia adipisci quaerat.', 'Tempore illum illum qui quia. Laudantium illo maxime cum excepturi et et et. Voluptas optio velit qui assumenda amet accusantium aut. Omnis omnis et ad iusto occaecati et.', NULL, 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(4, 8, 'App\\Models\\Product', 3, 1, 'Doloremque vero vel repellendus.', 'Atque sapiente accusantium sunt necessitatibus fuga autem. Recusandae temporibus nulla perferendis quaerat qui. Ipsam quo autem delectus quidem omnis ipsam. Aut consequatur eum ipsam. Veniam ut illo itaque hic occaecati aliquid.', NULL, 0, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(5, 9, 'App\\Models\\Product', 4, 3, 'Similique et iusto et.', 'Quo eius explicabo aut quae accusantium nihil maxime sequi. Accusamus ut qui molestiae maiores qui. Reiciendis unde iusto in quis hic deleniti rerum.', NULL, 0, 0, 1, 3, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(6, 5, 'App\\Models\\Product', 5, 3, 'Iure sit nisi cumque minus.', 'Voluptatibus voluptas nobis libero. Quidem velit eum esse delectus reprehenderit praesentium quas reprehenderit. Aperiam architecto repudiandae sit dignissimos.', NULL, 1, 0, 1, 1, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(7, 13, 'App\\Models\\Product', 5, 4, 'Earum explicabo corporis libero.', 'Et voluptatem amet nisi nihil vel cum. Eius officiis eos corrupti enim veritatis qui quam. Qui deserunt aut quibusdam consequuntur.', NULL, 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(8, 8, 'App\\Models\\Product', 6, 4, 'Veritatis quis qui.', 'Et dolor aut nam ullam voluptatem vero. Optio cumque quasi voluptatem in dolores magnam et. Deleniti dolor officiis rerum. Ullam similique sit maiores praesentium.', NULL, 1, 0, 1, 17, 7, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(9, 11, 'App\\Models\\Product', 6, 2, 'Voluptatem non dolor vel impedit.', 'Praesentium non est et numquam perspiciatis commodi. Ipsa incidunt in nisi tempora veniam. Adipisci ut delectus neque veniam id dignissimos dolorem.', NULL, 0, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(10, 9, 'App\\Models\\Product', 7, 3, 'Enim laborum repellat quis.', 'Exercitationem mollitia provident ab doloribus neque repellendus. Praesentium modi sint officia necessitatibus et molestiae. Error voluptatem et qui suscipit architecto deleniti autem et. Unde repellat qui ratione doloribus. Velit voluptatem minima illum dolores.', NULL, 1, 0, 1, 27, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(11, 6, 'App\\Models\\Product', 8, 5, 'Consectetur consequuntur.', 'Temporibus eveniet accusamus repellendus sed et eveniet. Earum minus autem aut voluptatem accusantium. Ut consequuntur praesentium nesciunt qui a unde nulla. Omnis facilis eos ullam.', NULL, 1, 0, 1, 38, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(12, 11, 'App\\Models\\Product', 9, 5, 'Consequatur et at.', 'Et dolore odit et in. Accusantium accusantium velit itaque qui eum iste.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 40, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(13, 14, 'App\\Models\\Product', 10, 4, 'Fugit incidunt dolores maxime.', 'Eveniet quaerat laudantium atque rerum. Quasi et dolor ut corporis. Commodi porro in suscipit eum.', NULL, 1, 1, 1, 6, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(14, 12, 'App\\Models\\Product', 10, 5, 'Illo voluptas praesentium.', 'Quisquam id non quas voluptatem est expedita animi. Excepturi ratione aut libero est qui. Ut accusamus id neque eveniet et corrupti. Nulla quas voluptas et et doloribus quas laborum.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 24, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(15, 5, 'App\\Models\\Product', 10, 5, 'Delectus omnis ducimus.', 'Recusandae doloremque commodi deserunt natus eos. Aut nesciunt fuga sit occaecati. Commodi reiciendis rerum expedita ratione sint.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 48, 5, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(16, 14, 'App\\Models\\Product', 11, 4, 'Eaque pariatur est molestiae.', 'Exercitationem sint temporibus et quis cumque molestias dicta. Eos et ipsum aut et possimus. Asperiores ab magni quaerat porro.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 49, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(17, 10, 'App\\Models\\Product', 11, 3, 'Sequi sed esse nam.', 'Sequi voluptatum dignissimos cupiditate veritatis non ea dolore. Soluta et totam unde nam illum culpa error. Hic unde ut voluptatem veritatis.', NULL, 1, 0, 1, 10, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(18, 4, 'App\\Models\\Product', 11, 1, 'Amet quod sed.', 'Est tenetur aut cum reiciendis voluptas et consequatur labore. Vel amet quia accusantium. Deleniti deleniti consequuntur inventore itaque animi quia expedita. Animi est non et est laudantium eius.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 0, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(19, 12, 'App\\Models\\Product', 12, 4, 'Et harum.', 'Vitae consequatur incidunt hic maiores mollitia quasi. Suscipit veniam in voluptas ut maxime voluptatem.', NULL, 0, 0, 1, 15, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(20, 12, 'App\\Models\\Product', 12, 2, 'Dignissimos velit accusamus.', 'Ut et dolor atque distinctio repellendus molestias. Culpa odit molestiae harum. Dolor quos consequatur accusamus sit dolore perferendis dolorem quisquam.', NULL, 1, 0, 1, 45, 10, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(21, 7, 'App\\Models\\Product', 13, 2, 'Recusandae quidem omnis vero.', 'Iste labore soluta voluptas cumque. Debitis numquam eos eligendi et aut doloremque.', NULL, 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(22, 14, 'App\\Models\\Product', 13, 5, 'Eaque nam neque numquam.', 'Qui architecto consequatur doloribus sunt beatae. Ad molestiae esse eveniet at. Quia explicabo doloribus libero ipsa quia earum laudantium. Mollitia accusamus voluptatum quia eligendi quod sunt.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 0, 5, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(23, 7, 'App\\Models\\Product', 13, 3, 'Distinctio magni deserunt exercitationem.', 'Deserunt hic sapiente perspiciatis soluta quo minima aut. Doloribus vel nostrum non rerum nulla. Odit quia dolorem sunt soluta earum.', NULL, 0, 0, 1, 41, 2, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(24, 4, 'App\\Models\\Product', 14, 4, 'Explicabo repellat dolorem.', 'Omnis totam animi placeat animi ex qui optio. Ipsam impedit commodi praesentium ut dolorem beatae quae. Quod id non neque incidunt minus perferendis sed. Enim aliquam sunt vitae qui quibusdam.', NULL, 0, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(25, 13, 'App\\Models\\Product', 14, 5, 'Ea voluptas optio cum deserunt.', 'In et reiciendis sit ut dolor eius. Eius iure quia ipsa fuga sit velit. Voluptates qui eaque adipisci ut enim.', NULL, 0, 0, 1, 37, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(26, 4, 'App\\Models\\Product', 15, 4, 'Et saepe harum a.', 'Tenetur vero ut voluptate officia tempora et ducimus. Temporibus necessitatibus aspernatur fugit provident asperiores libero. Omnis quo est porro excepturi odio doloremque.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 1, 1, 45, 10, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(27, 6, 'App\\Models\\Product', 15, 4, 'Corrupti ut ab amet.', 'Voluptatem iusto sit quod impedit ut impedit inventore et. Illum voluptatem vel labore nihil rerum. Qui iure non nisi dolores itaque. Doloremque omnis culpa iure vel et excepturi ipsa.', NULL, 0, 0, 1, 50, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(28, 7, 'App\\Models\\Product', 15, 4, 'Enim facilis non.', 'Pariatur optio magnam perspiciatis placeat ipsa maiores. Velit optio ut qui aut aliquid quibusdam aliquid. Commodi quis quo eos ipsum sed.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 0, 0, 1, 30, 10, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(29, 13, 'App\\Models\\Product', 16, 2, 'Repudiandae ea consequatur.', 'Nihil dolorem neque omnis. Est et consequatur in. Voluptatem vitae omnis unde et asperiores. Et consequatur veniam occaecati id consequatur in.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 3, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(30, 12, 'App\\Models\\Product', 16, 5, 'Incidunt est.', 'Beatae distinctio cupiditate ad et illum quis asperiores. Quam soluta repudiandae adipisci est voluptatem similique rerum. Quisquam dolor ut mollitia sit qui ut voluptatem.', NULL, 1, 0, 1, 12, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(31, 8, 'App\\Models\\Product', 17, 2, 'Illum et ab molestiae.', 'Veniam molestiae aut amet non corrupti recusandae magni. Aut dignissimos assumenda dolore itaque ut esse quis. Aperiam illum eligendi ratione. Voluptates dolorem vero ipsa. Ipsa accusamus mollitia debitis quis.', NULL, 1, 0, 1, 35, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(32, 14, 'App\\Models\\Product', 17, 3, 'Et quo qui nisi eaque.', 'Beatae nam laboriosam nobis sunt molestiae beatae autem quia. Ut quia delectus vero maxime sed repellat sunt.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 0, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(33, 4, 'App\\Models\\Product', 17, 1, 'Magni vel et quo.', 'Odit dolor perferendis est qui quia rerum est. Qui quaerat fugiat dolorem ullam omnis amet voluptate. Dolores ea rerum veritatis omnis temporibus doloremque est.', NULL, 1, 0, 1, 24, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(34, 10, 'App\\Models\\Product', 18, 5, 'Culpa voluptatem veniam.', 'Voluptatum inventore aut voluptatem qui cum laudantium. Nesciunt at deleniti vel non. Sequi dignissimos iusto magnam rerum beatae voluptates exercitationem voluptas.', NULL, 0, 0, 1, 50, 2, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(35, 11, 'App\\Models\\Product', 18, 3, 'At sit quos ipsa.', 'Error quibusdam a culpa deleniti rerum dolore molestias. Iure facere veniam maiores molestias unde. Sunt quasi voluptas iure ea fuga quia consequuntur.', NULL, 0, 0, 1, 33, 9, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(36, 6, 'App\\Models\\Plant', 1, 3, 'Qui non beatae fugit officia.', 'Voluptas molestiae dignissimos nostrum nulla. Qui provident aut facilis culpa eius ea. Id dolore sed expedita ut ipsam sapiente tenetur.', NULL, 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(37, 6, 'App\\Models\\Plant', 1, 5, 'Maiores aut et.', 'Quo harum sit tempore qui minus est. Et facilis culpa et cupiditate. Atque laboriosam est accusamus numquam et animi ipsa. Et ipsam voluptate minima veritatis occaecati dolorum nostrum.', NULL, 1, 0, 1, 41, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(38, 13, 'App\\Models\\Plant', 2, 3, 'Possimus beatae architecto enim.', 'Nesciunt vel aliquam nisi consequuntur autem architecto. Voluptate porro omnis magnam officia dicta laborum. Aperiam quod ut atque minima blanditiis.', NULL, 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(39, 14, 'App\\Models\\Plant', 2, 5, 'Aut velit ex officiis.', 'Accusamus est perferendis rerum occaecati delectus quia placeat odit. Aliquid voluptas earum dolorum labore placeat unde eum. Sapiente aut dolores et ipsam adipisci rerum voluptas.', NULL, 1, 0, 1, 26, 9, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(40, 13, 'App\\Models\\Plant', 3, 1, 'Est maxime voluptatem.', 'Voluptas consequatur nihil explicabo beatae. Earum velit perspiciatis vel ad omnis nulla quia. Est aut nostrum eum molestiae quaerat et. Vero sit doloremque placeat odio corrupti.', NULL, 1, 0, 1, 42, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(41, 9, 'App\\Models\\Plant', 4, 2, 'Aut voluptatibus.', 'Rerum provident illum officia laborum perferendis architecto. Doloribus nesciunt impedit eum ullam voluptas qui nisi. Accusantium necessitatibus quia doloribus ut.', NULL, 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(42, 10, 'App\\Models\\Plant', 5, 4, 'Distinctio quam non est.', 'Amet a unde nam perspiciatis nam et aut. Consequatur ex ut et aspernatur.', NULL, 1, 0, 1, 0, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(43, 11, 'App\\Models\\Plant', 5, 5, 'Nulla et qui omnis.', 'Quisquam facere perferendis quis debitis. Blanditiis ut sapiente id dolor ipsam eos soluta repellat. Perferendis at hic nemo debitis optio. Consequatur eveniet nihil voluptates aspernatur.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(44, 8, 'App\\Models\\Plant', 6, 5, 'Et aut cupiditate neque.', 'Sed praesentium illum nulla sunt et. Nam odit sit voluptatem delectus impedit nisi corrupti nesciunt. Aut et rem reprehenderit assumenda sint. Id tempore voluptatem quibusdam consectetur.', NULL, 1, 0, 1, 18, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(45, 10, 'App\\Models\\Plant', 6, 1, 'Iure natus veritatis velit.', 'Rerum voluptas nobis amet aut excepturi earum. Necessitatibus commodi corrupti cupiditate assumenda. Ad ex ipsum sed.', NULL, 1, 0, 1, 7, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(46, 9, 'App\\Models\\Plant', 7, 1, 'Expedita dolor placeat.', 'At quae explicabo ut nam sunt ipsam iure. Quis ut dolor nihil accusantium ipsa autem ipsum. Facere amet hic est veritatis.', NULL, 1, 0, 1, 17, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(47, 4, 'App\\Models\\Plant', 8, 1, 'Dolore voluptatem quod minus.', 'Et ex officia illo quod necessitatibus. Corrupti aperiam quo veritatis quibusdam. Qui eos sequi recusandae at ea ducimus ipsum dolorem.', NULL, 1, 0, 1, 8, 1, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(48, 10, 'App\\Models\\Plant', 8, 5, 'Officia distinctio accusamus adipisci.', 'Ut nobis illum dolorem est natus molestias. Illo explicabo dolorum at earum.', NULL, 0, 0, 1, 15, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(49, 7, 'App\\Models\\Product', 15, 5, 'Sunt qui nulla.', 'Debitis harum quisquam eum commodi nostrum. Omnis numquam voluptatibus recusandae. Perferendis sit dolor debitis exercitationem odio veniam. Est nisi maxime cupiditate corrupti quaerat quis voluptas ducimus.', NULL, 1, 1, 1, 5, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(50, 8, 'App\\Models\\Product', 10, 5, 'Officia molestiae quis voluptatem.', 'Ut dolorem minima molestiae laudantium quidem. Similique voluptate non voluptas. Assumenda molestias magni vero.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 1, 1, 24, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(51, 14, 'App\\Models\\Plant', 3, 5, 'Omnis cum dolore mollitia.', 'Et nihil omnis vel aut explicabo in non. Nihil sunt officia facere debitis quis. Quod sint necessitatibus earum aperiam vel laudantium impedit.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 1, 1, 49, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(52, 7, 'App\\Models\\Plant', 7, 4, 'Adipisci odio magni sed.', 'Deserunt dolor est qui velit quos debitis laudantium. Et dolorum dolorem qui autem iure dolorem. Sit autem ea velit eligendi eum quia. Et totam qui dolorem ratione cupiditate quo et.', NULL, 1, 1, 1, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(53, 5, 'App\\Models\\Product', 10, 4, 'Modi provident distinctio.', 'Itaque vel eum consequuntur distinctio libero. Voluptas odio dolor ut ut sed. Blanditiis accusamus quibusdam quisquam molestiae. Nemo similique reiciendis rerum rerum.', NULL, 1, 1, 1, 22, 9, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(54, 11, 'App\\Models\\Plant', 5, 4, 'Quod natus laboriosam.', 'Qui aut quis voluptas suscipit numquam culpa quos sed. Et aliquam perspiciatis iusto consectetur. Et quis quae nisi placeat explicabo perferendis. Expedita dolorem iusto sapiente ab earum accusantium corporis. Voluptatum reprehenderit repellat tenetur in ipsam ut nesciunt praesentium.', NULL, 1, 1, 1, 17, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(55, 13, 'App\\Models\\Plant', 2, 4, 'Commodi aut consequatur.', 'Tenetur ipsum voluptatem corrupti quia rerum delectus nemo. Totam quia fugiat eveniet eos est. Cupiditate nesciunt fugiat molestiae. Non eum voluptas fugit similique.', NULL, 1, 1, 1, 34, 2, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(56, 5, 'App\\Models\\Product', 4, 4, 'Aut at.', 'Corrupti consectetur rerum sunt quae. Deserunt fugiat esse assumenda amet eum at. Sint porro accusamus dolorum sapiente.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 1, 1, 29, 1, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(57, 12, 'App\\Models\\Plant', 8, 4, 'Consequatur eos officiis perspiciatis.', 'Quidem quasi dolores quaerat nam eius. Quia neque vero est consequatur tenetur unde ut. Aut quasi perspiciatis blanditiis ut magnam. Sunt dolor voluptatem tempora amet nulla.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 1, 1, 39, 4, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(58, 13, 'App\\Models\\Product', 12, 5, 'Qui magnam ea.', 'Maiores beatae ut vitae velit laboriosam. Perferendis sed ut voluptas beatae commodi quaerat quo. Quos excepturi ut et occaecati.', NULL, 1, 1, 1, 12, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(59, 12, 'App\\Models\\Product', 7, 4, 'Accusamus velit.', 'Quis nemo et doloribus perspiciatis provident non voluptatem commodi. Et ducimus dolores minima rem sunt veritatis. Doloribus molestiae aspernatur dolorem numquam ut voluptas reiciendis.', '[\"https:\\/\\/images.unsplash.com\\/photo-1416879595882-3373a0480b5b?w=500\"]', 1, 0, 0, 18, 6, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(60, 6, 'App\\Models\\Product', 8, 4, 'Omnis maiores delectus unde.', 'Fugit inventore deserunt autem vitae et cupiditate. Non doloremque necessitatibus ipsam sit esse. Commodi adipisci facilis aut enim nulla.', NULL, 1, 0, 0, 0, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(61, 9, 'App\\Models\\Plant', 8, 2, 'Dolores molestiae eius.', 'In quas quae voluptas aut soluta. Beatae et et sapiente saepe et dolor et. Alias illum impedit veniam dolor earum omnis adipisci quis. Asperiores sed autem consectetur et qui nihil sapiente officiis.', NULL, 0, 0, 0, 6, 0, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(62, 9, 'App\\Models\\Product', 1, 2, 'Est itaque rem et.', 'Sint accusamus quos quo in. Quas nam eos impedit earum. Quod nostrum deserunt cum ut.', NULL, 1, 0, 0, 35, 8, '2025-12-12 05:22:46', '2025-12-12 05:22:46'),
(63, 14, 'App\\Models\\Product', 16, 1, 'Maiores quam non quas.', 'Sunt non ut est natus repellendus dolorum commodi dolores. Et aut recusandae vero beatae odit. Vero suscipit quae asperiores quaerat molestias excepturi.', NULL, 0, 0, 0, 11, 7, '2025-12-12 05:22:46', '2025-12-12 05:22:46');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(2, 'admin', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(3, 'manager', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(4, 'customer', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45'),
(5, 'vendor', 'web', '2025-12-12 05:22:45', '2025-12-12 05:22:45');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 1),
(2, 2),
(2, 5),
(3, 1),
(3, 2),
(3, 3),
(3, 5),
(4, 1),
(4, 2),
(4, 5),
(5, 1),
(5, 2),
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(8, 3),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(10, 3),
(10, 4),
(11, 1),
(11, 2),
(12, 1),
(12, 2),
(12, 3),
(13, 1),
(13, 2),
(14, 1),
(14, 2),
(14, 3),
(14, 4),
(14, 5),
(15, 1),
(15, 2),
(15, 3),
(15, 5),
(16, 1),
(17, 1),
(17, 2),
(17, 3),
(18, 1),
(18, 2),
(19, 1),
(20, 1),
(20, 2),
(21, 1),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(23, 3),
(23, 4),
(24, 1),
(24, 2),
(24, 3),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(27, 3),
(27, 5),
(28, 1),
(28, 2),
(29, 1),
(30, 1),
(31, 1),
(32, 5),
(33, 5);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `group`, `type`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Nursery App', 'general', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(2, 'site_description', 'Your one-stop shop for plants and gardening supplies', 'general', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(3, 'site_email', 'contact@nursery-app.com', 'general', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(4, 'site_phone', '+1 (555) 123-4567', 'general', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(5, 'currency', 'USD', 'general', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(6, 'timezone', 'UTC', 'general', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(7, 'items_per_page', '20', 'general', 'number', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(8, 'enable_reviews', '1', 'features', 'boolean', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(9, 'enable_wishlist', '1', 'features', 'boolean', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(10, 'enable_loyalty_points', '1', 'features', 'boolean', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(11, 'points_per_dollar', '10', 'loyalty', 'number', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(12, 'signup_bonus_points', '50', 'loyalty', 'number', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(13, 'smtp_host', 'smtp.mailtrap.io', 'email', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(14, 'smtp_port', '2525', 'email', 'number', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(15, 'smtp_username', '', 'email', 'string', '2025-12-14 21:00:32', '2025-12-14 21:00:32'),
(16, 'smtp_password', '', 'email', 'password', '2025-12-14 21:00:32', '2025-12-14 21:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `google_id` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `avatar`, `role`, `google_id`, `remember_token`, `created_at`, `updated_at`, `points`) VALUES
(1, 'Super Admin', 'admin@nursery-app.com', '$2y$12$UhB6LB1pSktrWXynWxRV3etj0.8TKA.C2Tsb3LN7QZw9YdjSrArYW', '+1234567890', NULL, 'super_admin', NULL, NULL, '2025-12-12 05:22:45', '2025-12-14 19:20:29', 0),
(2, 'Admin User', 'admin.user@nursery-app.com', '$2y$12$oPdeFOzkST0X0ih5abFfpuBazxW8rhpy8F2nuDLW.cTb2WJTdw9O6', NULL, NULL, 'admin', NULL, NULL, '2025-12-12 05:22:45', '2025-12-12 05:22:45', 0),
(3, 'Manager User', 'manager@nursery-app.com', '$2y$12$mnDEvGZywg/YBk9ilugaUOPh.KBeHfwOeIf3zORexhwLge3ELNAFm', NULL, NULL, 'manager', NULL, NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(4, 'Customer User', 'customer@example.com', '$2y$12$iDdebGb/YrtsRWO6czHiTOsFzL9z8lmnqnbny5gmYauG8Nsq5YL7C', '+1234567891', NULL, 'customer', NULL, NULL, '2025-12-12 05:22:46', '2025-12-14 23:53:03', 0);


-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `store_name` varchar(255) NOT NULL,
  `store_slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `banner_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','suspended') NOT NULL DEFAULT 'pending',
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 10.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_transactions`
--

CREATE TABLE `vendor_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('sale','payout','refund','adjustment') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('fixed','percent') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_purchase` decimal(10,2) NOT NULL DEFAULT 0.00,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`),
  ADD KEY `idx_activity_log_subject` (`subject_type`,`subject_id`,`created_at`),
  ADD KEY `idx_activity_log_causer` (`causer_type`,`causer_id`,`created_at`),
  ADD KEY `idx_activity_log_created_at` (`created_at`),
  ADD KEY `idx_activity_log_name_event` (`log_name`,`event`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_index` (`user_id`),
  ADD KEY `addresses_user_id_type_index` (`user_id`,`type`),
  ADD KEY `addresses_user_id_is_default_index` (`user_id`,`is_default`),
  ADD KEY `idx_addresses_type` (`type`),
  ADD KEY `idx_addresses_country` (`country`),
  ADD KEY `idx_addresses_user_default` (`user_id`,`type`,`is_default`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `audit_logs_action_created_at_index` (`action`,`created_at`),
  ADD KEY `idx_audit_logs_model` (`model_type`,`model_id`,`created_at`),
  ADD KEY `idx_audit_logs_user_action` (`user_id`,`action`,`created_at`),
  ADD KEY `idx_audit_logs_ip_address` (`ip_address`),
  ADD KEY `idx_audit_logs_created_at` (`created_at`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_item_id_item_type_unique` (`user_id`,`item_id`,`item_type`),
  ADD KEY `cart_items_item_type_item_id_index` (`item_type`,`item_id`),
  ADD KEY `cart_items_user_id_index` (`user_id`),
  ADD KEY `cart_items_item_id_item_type_index` (`item_id`,`item_type`),
  ADD KEY `cart_items_created_at_index` (`created_at`),
  ADD KEY `idx_cart_items_type` (`item_type`,`item_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_slug_index` (`slug`),
  ADD KEY `categories_parent_id_index` (`parent_id`),
  ADD KEY `categories_is_active_index` (`is_active`),
  ADD KEY `idx_categories_active_sort` (`is_active`,`sort_order`),
  ADD KEY `idx_categories_parent_active` (`parent_id`,`is_active`);
ALTER TABLE `categories` ADD FULLTEXT KEY `ft_categories_search` (`name`,`description`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comments_user_id` (`user_id`),
  ADD KEY `idx_comments_post_created` (`post_id`,`created_at`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_features_is_active` (`is_active`),
  ADD KEY `idx_features_order` (`order`),
  ADD KEY `idx_features_active_order` (`is_active`,`order`);

--
-- Indexes for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gift_cards_code_unique` (`code`),
  ADD UNIQUE KEY `unique_gift_card_code` (`code`),
  ADD KEY `gift_cards_user_id_foreign` (`user_id`),
  ADD KEY `idx_gift_cards_is_active` (`is_active`),
  ADD KEY `idx_gift_cards_expires_at` (`expires_at`),
  ADD KEY `idx_gift_cards_active_expires` (`is_active`,`expires_at`);

--
-- Indexes for table `gift_card_usages`
--
ALTER TABLE `gift_card_usages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_gift_card_order` (`gift_card_id`,`order_id`),
  ADD KEY `gift_card_usages_order_id_foreign` (`order_id`);

--
-- Indexes for table `health_logs`
--
ALTER TABLE `health_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_health_logs_status` (`status`),
  ADD KEY `idx_health_logs_created_at` (`created_at`),
  ADD KEY `idx_health_logs_reminder_status` (`plant_care_reminder_id`,`status`,`created_at`);

--
-- Indexes for table `helpful_votes`
--
ALTER TABLE `helpful_votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `helpful_votes_user_id_voteable_type_voteable_id_unique` (`user_id`,`voteable_type`,`voteable_id`),
  ADD KEY `helpful_votes_voteable_type_voteable_id_index` (`voteable_type`,`voteable_id`),
  ADD KEY `helpful_votes_user_id_index` (`user_id`),
  ADD KEY `idx_helpful_votes_voteable` (`voteable_type`,`voteable_id`,`is_helpful`),
  ADD KEY `idx_helpful_votes_user_voteable` (`user_id`,`voteable_type`,`voteable_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `likes_user_id_post_id_unique` (`user_id`,`post_id`),
  ADD KEY `fk_likes_post_id` (`post_id`),
  ADD KEY `idx_likes_user_post` (`user_id`,`post_id`);

--
-- Indexes for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalty_points_user_id_created_at_index` (`user_id`,`created_at`),
  ADD KEY `loyalty_points_type_created_at_index` (`type`,`created_at`),
  ADD KEY `loyalty_points_expires_at_index` (`expires_at`),
  ADD KEY `loyalty_points_user_id_index` (`user_id`),
  ADD KEY `loyalty_points_created_at_index` (`created_at`),
  ADD KEY `loyalty_points_order_id_foreign` (`order_id`),
  ADD KEY `loyalty_points_review_id_foreign` (`review_id`),
  ADD KEY `idx_loyalty_points_user_expires` (`user_id`,`expires_at`),
  ADD KEY `idx_loyalty_points_type` (`type`);

--
-- Indexes for table `migrations1`
--
ALTER TABLE `migrations1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_index` (`user_id`),
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_status_index` (`status`),
  ADD KEY `orders_payment_status_index` (`payment_status`),
  ADD KEY `orders_created_at_index` (`created_at`),
  ADD KEY `idx_orders_user_status` (`user_id`,`status`),
  ADD KEY `idx_orders_user_payment_status` (`user_id`,`payment_status`),
  ADD KEY `idx_orders_status_created` (`status`,`created_at`),
  ADD KEY `idx_orders_order_number` (`order_number`),
  ADD KEY `idx_orders_payment_transaction` (`payment_transaction_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_item_type_item_id_index` (`item_type`,`item_id`),
  ADD KEY `order_items_order_id_index` (`order_id`),
  ADD KEY `order_items_item_id_item_type_index` (`item_id`,`item_type`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plants`
--
ALTER TABLE `plants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plants_slug_unique` (`slug`),
  ADD UNIQUE KEY `plants_sku_unique` (`sku`),
  ADD KEY `plants_slug_index` (`slug`),
  ADD KEY `plants_category_id_index` (`category_id`),
  ADD KEY `plants_is_active_index` (`is_active`),
  ADD KEY `plants_is_featured_index` (`is_featured`),
  ADD KEY `plants_in_stock_index` (`in_stock`),
  ADD KEY `idx_plants_category_active` (`category_id`,`is_active`),
  ADD KEY `idx_plants_active_featured` (`is_active`,`is_featured`);
ALTER TABLE `plants` ADD FULLTEXT KEY `ft_plants_search` (`name`,`scientific_name`,`description`);
ALTER TABLE `plants` ADD FULLTEXT KEY `ft_plants_name` (`name`);

--
-- Indexes for table `plant_care_guides`
--
ALTER TABLE `plant_care_guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plant_care_guides_created_by_foreign` (`created_by`),
  ADD KEY `plant_care_guides_plant_id_index` (`plant_id`),
  ADD KEY `plant_care_guides_is_active_index` (`is_active`),
  ADD KEY `plant_care_guides_care_level_index` (`care_level`);
ALTER TABLE `plant_care_guides` ADD FULLTEXT KEY `ft_care_guides_search` (`title`,`description`,`light_requirements`,`water_needs`,`soil_type`);

--
-- Indexes for table `plant_care_reminders`
--
ALTER TABLE `plant_care_reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plant_care_reminders_plant_care_guide_id_foreign` (`plant_care_guide_id`),
  ADD KEY `plant_care_reminders_user_id_scheduled_date_index` (`user_id`,`scheduled_date`),
  ADD KEY `plant_care_reminders_is_active_scheduled_date_index` (`is_active`,`scheduled_date`),
  ADD KEY `idx_care_reminders_plant_id` (`plant_id`),
  ADD KEY `idx_care_reminders_scheduled` (`scheduled_date`),
  ADD KEY `idx_care_reminders_active` (`is_active`,`scheduled_date`);

--
-- Indexes for table `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `point_transactions_order_id_foreign` (`order_id`),
  ADD KEY `idx_point_trans_user_created` (`user_id`,`created_at`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `idx_posts_is_published` (`is_published`),
  ADD KEY `idx_posts_published_at` (`published_at`),
  ADD KEY `idx_posts_published_combo` (`is_published`,`published_at`),
  ADD KEY `idx_posts_user_id` (`user_id`);
ALTER TABLE `posts` ADD FULLTEXT KEY `ft_posts_search` (`title`,`content`);
ALTER TABLE `posts` ADD FULLTEXT KEY `ft_posts_title` (`title`);

--
-- Indexes for table `price_alerts`
--
ALTER TABLE `price_alerts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_product_alert` (`user_id`,`product_id`),
  ADD KEY `price_alerts_product_id_foreign` (`product_id`),
  ADD KEY `price_alerts_user_id_product_id_index` (`user_id`,`product_id`),
  ADD KEY `price_alerts_is_active_index` (`is_active`),
  ADD KEY `idx_price_alerts_active_triggered` (`is_active`,`is_triggered`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_slug_index` (`slug`),
  ADD KEY `products_category_id_index` (`category_id`),
  ADD KEY `products_is_active_index` (`is_active`),
  ADD KEY `products_is_featured_index` (`is_featured`),
  ADD KEY `products_in_stock_index` (`in_stock`),
  ADD KEY `products_category_id_is_active_index` (`category_id`,`is_active`),
  ADD KEY `idx_products_category_active` (`category_id`,`is_active`),
  ADD KEY `idx_products_active_featured` (`is_active`,`is_featured`),
  ADD KEY `idx_products_vendor` (`vendor_id`);
ALTER TABLE `products` ADD FULLTEXT KEY `ft_products_search` (`name`,`description`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`,`reviewable_id`),
  ADD KEY `reviews_is_approved_created_at_index` (`is_approved`,`created_at`),
  ADD KEY `reviews_rating_index` (`rating`),
  ADD KEY `reviews_user_id_index` (`user_id`),
  ADD KEY `reviews_created_at_index` (`created_at`),
  ADD KEY `idx_reviews_is_verified` (`is_verified_purchase`),
  ADD KEY `idx_reviews_is_featured` (`is_featured`),
  ADD KEY `idx_reviews_reviewable_approved` (`reviewable_type`,`reviewable_id`,`is_approved`),
  ADD KEY `idx_reviews_approved_rating` (`is_approved`,`rating`,`created_at`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`),
  ADD KEY `idx_sessions_last_activity` (`last_activity`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`),
  ADD KEY `idx_system_settings_group` (`group`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_testimonials_is_active` (`is_active`),
  ADD KEY `idx_testimonials_order` (`order`),
  ADD KEY `idx_testimonials_rating` (`rating`),
  ADD KEY `idx_testimonials_active_order` (`is_active`,`order`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_email_index` (`email`),
  ADD KEY `users_role_index` (`role`),
  ADD KEY `users_created_at_index` (`created_at`),
  ADD KEY `idx_users_role` (`role`),
  ADD KEY `idx_users_email_verified` (`email_verified_at`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_store_slug_unique` (`store_slug`),
  ADD UNIQUE KEY `unique_vendor_user` (`user_id`),
  ADD KEY `idx_vendors_status` (`status`);
ALTER TABLE `vendors` ADD FULLTEXT KEY `ft_vendors_search` (`store_name`,`description`);

--
-- Indexes for table `vendor_transactions`
--
ALTER TABLE `vendor_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vendor_trans_vendor_type` (`vendor_id`,`type`),
  ADD KEY `idx_vendor_trans_status` (`status`),
  ADD KEY `idx_vendor_trans_created_at` (`created_at`),
  ADD KEY `idx_vendor_trans_order_id` (`order_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`),
  ADD UNIQUE KEY `unique_voucher_code` (`code`),
  ADD KEY `idx_vouchers_is_active` (`is_active`),
  ADD KEY `idx_vouchers_expires_at` (`expires_at`),
  ADD KEY `idx_vouchers_active_expires` (`is_active`,`expires_at`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlist_items_user_id_item_id_item_type_unique` (`user_id`,`item_id`,`item_type`),
  ADD KEY `wishlist_items_item_type_item_id_index` (`item_type`,`item_id`),
  ADD KEY `wishlist_items_user_id_index` (`user_id`),
  ADD KEY `wishlist_items_item_id_item_type_index` (`item_id`,`item_type`),
  ADD KEY `idx_wishlist_items_type` (`item_type`,`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gift_cards`
--
ALTER TABLE `gift_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gift_card_usages`
--
ALTER TABLE `gift_card_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `health_logs`
--
ALTER TABLE `health_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `helpful_votes`
--
ALTER TABLE `helpful_votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations1`
--
ALTER TABLE `migrations1`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `plants`
--
ALTER TABLE `plants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plant_care_guides`
--
ALTER TABLE `plant_care_guides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plant_care_reminders`
--
ALTER TABLE `plant_care_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `point_transactions`
--
ALTER TABLE `point_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `price_alerts`
--
ALTER TABLE `price_alerts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_transactions`
--
ALTER TABLE `vendor_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comments_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_comments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `gift_cards`
--
ALTER TABLE `gift_cards`
  ADD CONSTRAINT `gift_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `gift_card_usages`
--
ALTER TABLE `gift_card_usages`
  ADD CONSTRAINT `gift_card_usages_gift_card_id_foreign` FOREIGN KEY (`gift_card_id`) REFERENCES `gift_cards` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gift_card_usages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `health_logs`
--
ALTER TABLE `health_logs`
  ADD CONSTRAINT `health_logs_plant_care_reminder_id_foreign` FOREIGN KEY (`plant_care_reminder_id`) REFERENCES `plant_care_reminders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `helpful_votes`
--
ALTER TABLE `helpful_votes`
  ADD CONSTRAINT `helpful_votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_post_id` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_likes_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likes_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `loyalty_points_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loyalty_points_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plants`
--
ALTER TABLE `plants`
  ADD CONSTRAINT `plants_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plant_care_guides`
--
ALTER TABLE `plant_care_guides`
  ADD CONSTRAINT `plant_care_guides_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plant_care_guides_plant_id_foreign` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plant_care_reminders`
--
ALTER TABLE `plant_care_reminders`
  ADD CONSTRAINT `plant_care_reminders_plant_care_guide_id_foreign` FOREIGN KEY (`plant_care_guide_id`) REFERENCES `plant_care_guides` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plant_care_reminders_plant_id_foreign` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `plant_care_reminders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `point_transactions`
--
ALTER TABLE `point_transactions`
  ADD CONSTRAINT `point_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `point_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `price_alerts`
--
ALTER TABLE `price_alerts`
  ADD CONSTRAINT `price_alerts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `price_alerts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_transactions`
--
ALTER TABLE `vendor_transactions`
  ADD CONSTRAINT `vendor_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_transactions_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
