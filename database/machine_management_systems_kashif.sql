-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2024 at 10:49 AM
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
-- Database: `machine_management_systems`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `machine_id` bigint(20) UNSIGNED NOT NULL,
  `problem_statment` text NOT NULL,
  `date` date NOT NULL,
  `status` enum('normal','high','urgent') NOT NULL DEFAULT 'normal',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `customer_id`, `machine_id`, `problem_statment`, `date`, `status`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'mdjjdhebev', '2024-03-05', 'normal', 1, NULL, NULL, '2024-03-05 02:23:40', '2024-03-05 02:23:40');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `address`, `city`, `contact`, `deleted_at`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'trytrreews', 'jshshgsf', '09887788980', NULL, '2024-03-04 23:58:40', '2024-03-05 01:23:54', 5);

-- --------------------------------------------------------

--
-- Table structure for table `engineers`
--

CREATE TABLE `engineers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `engineers_type` enum('electrician','automobile_mechanics','filler_mechanics') NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `avaibility` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engineer_complaints`
--

CREATE TABLE `engineer_complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `engineer_id` bigint(20) UNSIGNED NOT NULL,
  `complaint_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','in_progress','resolved') NOT NULL DEFAULT 'pending',
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `engineer_feedback`
--

CREATE TABLE `engineer_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `engineer_id` bigint(20) UNSIGNED NOT NULL,
  `before_image` varchar(255) DEFAULT NULL,
  `after_image` varchar(255) DEFAULT NULL,
  `audio_file` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `working_days` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `machines`
--

CREATE TABLE `machines` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `model` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `machines`
--

INSERT INTO `machines` (`id`, `customer_id`, `serial_number`, `name`, `model`, `description`, `image`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'hdgdtsf', 'kdjhdhegxnxbb', '938387y', 'hdgdgvsgsffs', 'C:\\xampp\\tmp\\php7430.tmp', 1, NULL, NULL, '2024-03-05 02:17:53', '2024-03-05 02:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_02_19_070736_create_customers_table', 1),
(2, '2024_02_19_070736_create_service_providers_table', 1),
(3, '2024_02_27_101600_create_users_table', 2),
(4, '2024_02_27_101610_create_role_modules_table', 3),
(5, '2024_02_27_101619_create_roles_table', 3),
(6, '2024_02_27_101734_create_role_permissions_table', 3),
(7, '2024_02_19_074139_create_engineers_table', 4),
(8, '2024_02_19_073245_create_machines_table', 5),
(9, '2024_02_19_073246_create_complaints_table', 5),
(10, '2024_02_19_081259_create_engineer_complaints_table', 6),
(11, '2024_02_19_081260_create_engineer_feedback_table', 6),
(12, '2024_02_27_101601_create_users_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `home` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `home`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'admin', '/customers', NULL, 2, NULL, NULL, '2024-03-04 07:26:26'),
(2, 'customer_admin', '/complaints', 2, 2, NULL, '2024-03-04 07:28:53', '2024-03-04 07:32:12'),
(3, 'service_provider_admin', '/engineers', 2, 2, NULL, '2024-03-04 07:29:27', '2024-03-04 07:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `role_modules`
--

CREATE TABLE `role_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_modules`
--

INSERT INTO `role_modules` (`id`, `name`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Customers', 2, NULL, NULL, '2024-03-04 07:23:22', '2024-03-04 07:23:22'),
(2, 'Service Providers', 2, NULL, NULL, '2024-03-04 07:26:45', '2024-03-04 07:26:45'),
(3, 'Complaints', 2, NULL, NULL, '2024-03-04 07:27:58', '2024-03-04 07:27:58'),
(4, 'Engineers', 2, NULL, NULL, '2024-03-04 07:28:09', '2024-03-04 07:28:09');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `create` tinyint(1) NOT NULL DEFAULT 0,
  `read` tinyint(1) NOT NULL DEFAULT 0,
  `update` tinyint(1) NOT NULL DEFAULT 0,
  `delete` tinyint(1) NOT NULL DEFAULT 0,
  `recover` tinyint(1) NOT NULL DEFAULT 0,
  `global` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_module_id`, `role_id`, `create`, `read`, `update`, `delete`, `recover`, `global`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 0, 0, 0, 0, 0, 0, NULL, '2024-03-04 07:32:12', '2024-03-04 07:32:12'),
(2, 2, 2, 0, 0, 0, 0, 0, 0, NULL, '2024-03-04 07:32:12', '2024-03-04 07:32:12'),
(3, 3, 2, 1, 1, 1, 1, 1, 1, NULL, '2024-03-04 07:32:12', '2024-03-04 07:32:12'),
(4, 4, 2, 0, 0, 0, 0, 0, 0, NULL, '2024-03-04 07:32:12', '2024-03-04 07:32:12'),
(5, 1, 3, 0, 0, 0, 0, 0, 0, NULL, '2024-03-04 07:32:33', '2024-03-04 07:32:33'),
(6, 2, 3, 1, 1, 1, 1, 1, 1, NULL, '2024-03-04 07:32:33', '2024-03-04 07:32:33'),
(7, 3, 3, 0, 0, 0, 0, 0, 0, NULL, '2024-03-04 07:32:33', '2024-03-04 07:32:33'),
(8, 4, 3, 0, 0, 0, 0, 0, 0, NULL, '2024-03-04 07:32:33', '2024-03-04 07:32:33'),
(9, 1, 1, 1, 1, 1, 1, 1, 1, NULL, '2024-03-04 07:32:50', '2024-03-04 07:32:50'),
(10, 2, 1, 1, 1, 1, 1, 1, 1, NULL, '2024-03-04 07:32:50', '2024-03-04 07:32:50'),
(11, 3, 1, 1, 1, 1, 1, 1, 1, NULL, '2024-03-04 07:32:50', '2024-03-04 07:32:50'),
(12, 4, 1, 1, 1, 1, 1, 1, 1, NULL, '2024-03-04 07:32:50', '2024-03-04 07:32:50');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `user_id`, `address`, `city`, `contact`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'cvs', 'nd hkd', 'v m mc', NULL, '2024-03-04 07:18:14', '2024-03-04 07:54:49'),
(2, 3, 'vs  fv', 'dves', 'fwrc', NULL, '2024-03-04 07:34:48', '2024-03-04 07:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('customer','service_provider','admin') DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_provider_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `customer_id`, `service_provider_id`, `name`, `email`, `role_id`, `email_verified_at`, `password`, `remember_token`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL, 'admin', 'admin@idl.pk', 1, NULL, '$2y$10$7fLA.7e4KcLDczRFre8PJehtqkjxWwHFgzIpH.SYLG3zs8smOmk0y', NULL, 1, NULL, NULL, NULL),
(2, 'service_provider', NULL, 1, 'ali', 'ali@sp', 3, NULL, '$2y$10$efS97qkTKmYEusLXEkkptuhdYABKTDhvKqHfr7jbylsX1J18oVfAS', NULL, 1, 2, '2024-03-04 07:18:14', '2024-03-04 07:54:49'),
(3, 'service_provider', NULL, 2, 'asad', 'asad@sp', 3, NULL, '$2y$10$o4uh8Sn/mu6h4YRJk/3Mtu1DaKau7auNgFRaPP2GInxyLStW1Xpw6', NULL, 2, NULL, '2024-03-04 07:34:49', '2024-03-04 07:34:49'),
(4, 'service_provider', NULL, NULL, 'customer', 'customer@gmail.com', 3, NULL, '$2y$10$WPA8ITc5PzyC3IvsFY2JiurDJc113Bbya7OiWj98aE9cYYGRglmMu', NULL, 3, NULL, '2024-03-04 23:30:45', '2024-03-04 23:30:45'),
(5, 'customer', 1, NULL, 'customer', 'custo12@gmail.com', 2, NULL, '$2y$10$3LIIHUi3VMDrnBCYoROkOuZgIKhw1gdQaqtpQl6Zcym3X0Zg7RChy', NULL, 3, 1, '2024-03-04 23:58:40', '2024-03-05 01:23:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_customer_id_foreign` (`customer_id`),
  ADD KEY `complaints_machine_id_foreign` (`machine_id`),
  ADD KEY `complaints_created_by_foreign` (`created_by`),
  ADD KEY `complaints_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `engineers`
--
ALTER TABLE `engineers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `engineers_created_by_foreign` (`created_by`),
  ADD KEY `engineers_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `engineer_complaints`
--
ALTER TABLE `engineer_complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `engineer_complaints_engineer_id_foreign` (`engineer_id`),
  ADD KEY `engineer_complaints_complaint_id_foreign` (`complaint_id`),
  ADD KEY `engineer_complaints_created_by_foreign` (`created_by`),
  ADD KEY `engineer_complaints_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `engineer_feedback`
--
ALTER TABLE `engineer_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `engineer_feedback_engineer_id_foreign` (`engineer_id`),
  ADD KEY `engineer_feedback_created_by_foreign` (`created_by`),
  ADD KEY `engineer_feedback_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `machines`
--
ALTER TABLE `machines`
  ADD PRIMARY KEY (`id`),
  ADD KEY `machines_customer_id_foreign` (`customer_id`),
  ADD KEY `machines_created_by_foreign` (`created_by`),
  ADD KEY `machines_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_created_by_foreign` (`created_by`),
  ADD KEY `roles_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_modules_created_by_foreign` (`created_by`),
  ADD KEY `role_modules_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_module_id_foreign` (`role_module_id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_providers_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_customer_id_foreign` (`customer_id`),
  ADD KEY `users_service_provider_id_foreign` (`service_provider_id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_created_by_foreign` (`created_by`),
  ADD KEY `users_updated_by_foreign` (`updated_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `engineers`
--
ALTER TABLE `engineers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engineer_complaints`
--
ALTER TABLE `engineer_complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `engineer_feedback`
--
ALTER TABLE `engineer_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `machines`
--
ALTER TABLE `machines`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_modules`
--
ALTER TABLE `role_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `complaints_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `complaints_machine_id_foreign` FOREIGN KEY (`machine_id`) REFERENCES `machines` (`id`),
  ADD CONSTRAINT `complaints_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `engineers`
--
ALTER TABLE `engineers`
  ADD CONSTRAINT `engineers_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `engineers_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `engineer_complaints`
--
ALTER TABLE `engineer_complaints`
  ADD CONSTRAINT `engineer_complaints_complaint_id_foreign` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`),
  ADD CONSTRAINT `engineer_complaints_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `engineer_complaints_engineer_id_foreign` FOREIGN KEY (`engineer_id`) REFERENCES `engineers` (`id`),
  ADD CONSTRAINT `engineer_complaints_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `engineer_feedback`
--
ALTER TABLE `engineer_feedback`
  ADD CONSTRAINT `engineer_feedback_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `engineer_feedback_engineer_id_foreign` FOREIGN KEY (`engineer_id`) REFERENCES `engineers` (`id`),
  ADD CONSTRAINT `engineer_feedback_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `machines`
--
ALTER TABLE `machines`
  ADD CONSTRAINT `machines_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `machines_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `machines_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `roles_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD CONSTRAINT `role_modules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `role_modules_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_permissions_role_module_id_foreign` FOREIGN KEY (`role_module_id`) REFERENCES `role_modules` (`id`);

--
-- Constraints for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD CONSTRAINT `service_providers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_service_provider_id_foreign` FOREIGN KEY (`service_provider_id`) REFERENCES `service_providers` (`id`),
  ADD CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
