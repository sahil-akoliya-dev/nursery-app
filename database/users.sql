-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2025 at 12:12 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('customer','vendor','admin','manager','super_admin') NOT NULL DEFAULT 'customer',
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`two_factor_recovery_codes`)),
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `two_factor_phone` varchar(255) DEFAULT NULL,
  `two_factor_sms_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `phone` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `points` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `google_id`, `avatar`, `role`, `two_factor_enabled`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `two_factor_phone`, `two_factor_sms_enabled`, `phone`, `date_of_birth`, `remember_token`, `created_at`, `updated_at`, `points`) VALUES
(1, 'Updated Name', 'admin@nursery-app.com', '2025-12-12 05:22:45', '$2y$12$UhB6LB1pSktrWXynWxRV3etj0.8TKA.C2Tsb3LN7QZw9YdjSrArYW', NULL, NULL, 'manager', 0, NULL, NULL, NULL, NULL, 0, '+1234567890', NULL, NULL, '2025-12-12 05:22:45', '2025-12-14 19:20:29', 0),
(2, 'Admin User', 'admin.user@nursery-app.com', '2025-12-12 05:22:45', '$2y$12$oPdeFOzkST0X0ih5abFfpuBazxW8rhpy8F2nuDLW.cTb2WJTdw9O6', NULL, NULL, 'admin', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-12 05:22:45', '2025-12-12 05:22:45', 0),
(3, 'Manager User', 'manager@nursery-app.com', '2025-12-12 05:22:46', '$2y$12$mnDEvGZywg/YBk9ilugaUOPh.KBeHfwOeIf3zORexhwLge3ELNAFm', NULL, NULL, 'manager', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(4, 'Updated Name', 'customer@example.com', '2025-12-12 05:22:46', '$2y$12$iDdebGb/YrtsRWO6czHiTOsFzL9z8lmnqnbny5gmYauG8Nsq5YL7C', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, '+1234567891', NULL, NULL, '2025-12-12 05:22:46', '2025-12-14 23:53:03', 0),
(5, 'Helmer Denesik', 'zlind@example.net', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'tQ7FIqVNp8', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(6, 'Clifford McCullough', 'owilliamson@example.com', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'VRlipWJC7m', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(7, 'Elvera Spinka', 'alfredo.rohan@example.org', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, '1994-03-23', 'NOfUizgpVV', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(8, 'Cicero Torp', 'breilly@example.com', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, '1986-04-11', 'P4UgGMFr70', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(9, 'Mr. Javier Muller', 'dickinson.maryam@example.org', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, 'iSY54mfxgV', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(10, 'Mrs. Lesly Raynor II', 'monahan.verner@example.com', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, '1-657-769-7191', NULL, 'BlXrIdH467', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(11, 'Florence Cormier II', 'vandervort.raphaelle@example.org', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, '1988-05-30', 'bkX6BiZUOn', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(12, 'Brook Fritsch', 'sfritsch@example.org', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, '1-941-558-7718', NULL, 'xvnLnh9gAm', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(13, 'Heath VonRueden', 'rempel.felicita@example.com', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, '1-660-678-9652', '1985-04-07', 'Zc1ftmNEXX', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(14, 'Kaylah Borer', 'cassidy.effertz@example.net', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, '423.853.9093', '1987-06-15', 'LgTCXNYwpY', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(15, 'Dr. Reuben Turner', 'pflatley@example.com', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'admin', 0, NULL, NULL, NULL, NULL, 0, NULL, '1992-04-01', '2aHJr32UuU', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(16, 'Keenan Frami', 'bergstrom.janessa@example.net', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'admin', 0, NULL, NULL, NULL, NULL, 0, '(469) 393-5651', NULL, 'mW9YBu81yU', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(17, 'Camryn Sipes IV', 'furman.kuphal@example.org', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'manager', 0, NULL, NULL, NULL, NULL, 0, NULL, '1998-05-25', 'Uo7qw7ijlv', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(18, 'Else Heidenreich', 'hosinski@example.net', '2025-12-12 05:22:46', '$2y$12$xJb3RqEWEG0nbL6zIooSOOuDVJI9NIsXyc/C0BZTN5nG1s8M2U3LC', NULL, NULL, 'manager', 0, NULL, NULL, NULL, NULL, 0, '+1-779-438-7183', NULL, 'azQdwVqvGz', '2025-12-12 05:22:46', '2025-12-12 05:22:46', 0),
(19, 'Sahil Akoliya', 'sahil@matrixsoftwares.com', NULL, '$2y$12$zjBhWXmDzE.O163cOiA1bOLqoe5oB5hC81xtPF3W7AmX65oUG4nJq', NULL, NULL, 'vendor', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-12 07:00:48', '2025-12-12 07:00:48', 0),
(20, 'Sahil Akoliya', 'sahilakoliya1604@gmail.com', NULL, '$2y$12$Jym4IRbbaOqkxRI6CBd4i.nlt/7ulPgyfBceEHZBzU2EHX.Ctzl12', '114793373613715733410', 'https://lh3.googleusercontent.com/a/ACg8ocKtROJ68oiVczuHbxduJFVC7PQHXwO0PxR6YfrSeHBlrx5_Sg=s96-c', 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, '7YeJSa1rFqyIDvBv3FmtQftEBxJ32XeQOFrZa7Ua7VgvVenlWmc8SxAMfuyS', '2025-12-14 14:06:52', '2025-12-14 14:06:52', 0),
(21, 'Sahil', 'sahil@matrixsoftwaress.com', NULL, '$2y$12$SBiAkoQQ533NVMyeiI7ME.PEBRwZ8M0EFCJBZyBloNYK./l561Dny', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-14 15:17:13', '2025-12-14 15:17:13', 0),
(22, 'John Doe', 'john.doe@example.com', NULL, '$2y$12$/EwB53rgHGW925Z04tmhaezhv55eNPzUtiQ0jIzeCoSOzeVlX./P.', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-14 18:50:11', '2025-12-14 18:50:11', 0),
(23, 'New User', 'newuser@example.com', NULL, '$2y$12$TDEzXl4F9zQgZXGDo1U2W.67hSV.rN5lSuEn6RGhQ9NJ4f/aO7ECq', NULL, NULL, 'customer', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-14 19:20:28', '2025-12-14 19:20:28', 0),
(24, 'Sahil Akoliya', 'sahil@gmail.com', NULL, '$2y$12$L2GYX3EqYi6btu2wUySGruL.TYh3GSzfkOFkmpA8nZHNvykCG2CRG', NULL, NULL, 'admin', 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2025-12-14 19:53:24', '2025-12-14 19:53:24', 0);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
