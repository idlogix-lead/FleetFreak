-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2024 at 12:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
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
(1, 'trytrreews', 'jshshgsf', '09887788980', NULL, '2024-03-04 23:58:40', '2024-03-11 11:39:27', 5);

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
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
(12, '2024_02_27_101601_create_users_table', 7),
(13, '2014_10_12_100000_create_password_resets_table', 8),
(14, '2019_08_19_000000_create_failed_jobs_table', 8),
(15, '2019_12_14_000001_create_personal_access_tokens_table', 8),
(17, '2024_03_13_053749_add_additional_fields_to_users_table', 9),
(18, '2024_03_13_095145_create_user_social_profiles_table', 10),
(20, '2024_03_18_082833_add_theme_to_users_table', 11),
(21, '2024_03_20_075946_add_sidebar_to_users_table', 12),
(22, '2024_03_20_075958_add_header_to_users_table', 12),
(23, '2024_03_20_100948_create_role_permission_types_table', 13),
(24, '2024_04_01_062704_create_role_permission_type_functions_table', 14),
(25, '2024_02_27_101735_create_role_permissions_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `role_permission_type_functions`
--

CREATE TABLE `role_permission_type_functions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_permission_type_id` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(255) NOT NULL,
  `return_type` enum('view','json') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission_type_functions`
--

INSERT INTO `role_permission_type_functions` (`id`, `role_permission_type_id`, `method`, `return_type`, `created_at`, `updated_at`) VALUES
(12, 29, 'index', 'view', '2024-04-01 02:15:12', '2024-04-01 02:15:12'),
(97, 23, 'index', 'view', '2024-04-02 02:18:10', '2024-04-02 02:18:10'),
(98, 23, 'show', 'view', '2024-04-02 02:18:10', '2024-04-02 02:18:10'),
(110, 27, 'show', 'view', '2024-04-05 01:12:52', '2024-04-05 01:12:52'),
(111, 27, 'index', 'view', '2024-04-05 01:12:52', '2024-04-05 01:12:52'),
(112, 28, 'create', 'view', '2024-04-05 01:12:52', '2024-04-05 01:12:52'),
(113, 28, 'store', 'view', '2024-04-05 01:12:52', '2024-04-05 01:12:52'),
(114, 30, 'update', 'view', '2024-04-05 01:12:53', '2024-04-05 01:12:53'),
(115, 31, 'destroy', 'view', '2024-04-05 01:12:53', '2024-04-05 01:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', '/customers', NULL, 1, NULL, NULL, '2024-04-01 04:09:53'),
(2, 'customer_admin', '/complaints', 2, 2, NULL, '2024-03-04 07:28:53', '2024-03-04 07:32:12'),
(3, 'service_provider_admin', '/engineers', 2, 2, NULL, '2024-03-04 07:29:27', '2024-03-04 07:32:33'),
(4, 'new test role', '/customers', 1, 1, NULL, '2024-03-29 00:40:12', '2024-04-01 03:30:23'),
(5, 'test', 'home', 1, 1, '2024-04-02 02:18:42', '2024-04-01 03:54:55', '2024-04-02 02:18:42'),
(6, 'test2', 'home', 1, NULL, '2024-04-02 02:18:34', '2024-04-01 04:01:56', '2024-04-02 02:18:34');

-- --------------------------------------------------------

--
-- Table structure for table `role_modules`
--

CREATE TABLE `role_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role_permission_type_id` int(11) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_modules`
--

INSERT INTO `role_modules` (`id`, `name`, `role_permission_type_id`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Customers', NULL, 2, 1, NULL, '2024-03-04 07:23:22', '2024-03-21 04:08:06'),
(2, 'Service Providers', NULL, 2, NULL, NULL, '2024-03-04 07:26:45', '2024-03-04 07:26:45'),
(3, 'Complaints', NULL, 2, NULL, NULL, '2024-03-04 07:27:58', '2024-03-04 07:27:58'),
(4, 'Engineers', NULL, 2, NULL, NULL, '2024-03-04 07:28:09', '2024-03-04 07:28:09'),
(10, 'test_module', NULL, 1, NULL, '2024-04-02 02:16:07', '2024-03-26 04:26:57', '2024-04-02 02:16:07'),
(11, 'test_module 2', NULL, 1, NULL, '2024-04-02 02:16:03', '2024-03-26 04:33:42', '2024-04-02 02:16:03'),
(12, 'admin module', NULL, 1, 1, NULL, '2024-03-26 05:45:14', '2024-03-27 01:37:23'),
(18, 'test1', NULL, 1, 1, '2024-03-28 06:07:19', '2024-03-27 11:50:38', '2024-03-28 06:07:19'),
(20, 'test2_module', NULL, 1, 1, '2024-04-02 02:15:54', '2024-03-28 03:01:16', '2024-04-02 02:15:54'),
(26, 'advanced module', NULL, 1, 1, '2024-04-02 02:16:32', '2024-03-28 06:13:11', '2024-04-02 02:16:32'),
(27, 'final module', NULL, 1, 1, NULL, '2024-03-29 00:23:42', '2024-03-29 01:00:58'),
(28, 'testmodule4', NULL, 1, NULL, '2024-04-02 02:16:13', '2024-03-29 06:11:08', '2024-04-02 02:16:13'),
(29, 'dfwr', NULL, 1, NULL, '2024-04-02 02:16:17', '2024-04-01 01:52:15', '2024-04-02 02:16:17'),
(30, 'test hts', NULL, 1, 1, '2024-04-02 02:16:21', '2024-04-01 02:11:30', '2024-04-02 02:16:21');

-- --------------------------------------------------------

--
-- Table structure for table `role_permission_types`
--

CREATE TABLE `role_permission_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_module_id` bigint(20) UNSIGNED NOT NULL,
  `action` varchar(255) NOT NULL,
  `is_read` tinyint(4) NOT NULL,
  `denial_msg` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permission_types`
--

INSERT INTO `role_permission_types` (`id`, `role_module_id`, `action`, `is_read`, `denial_msg`, `created_at`, `updated_at`) VALUES
(5, 10, 'destroy', 0, 'grant access', '2024-03-26 04:26:57', '2024-03-26 04:26:57'),
(6, 11, 'destroy', 0, 'grant access', '2024-03-26 04:33:42', '2024-03-26 04:33:42'),
(7, 12, 'admin action1 updated 1 again', 0, 'not allowed1 again', '2024-03-26 05:45:14', '2024-03-29 02:52:41'),
(8, 12, 'admin action 2', 0, 'not allowed2', '2024-03-27 06:50:30', '2024-03-29 02:52:41'),
(9, 18, 'test action', 0, 'test message', '2024-03-27 11:50:38', '2024-03-27 11:50:38'),
(10, 20, 'test2module', 0, 'test messagezz', '2024-03-28 03:01:16', '2024-03-28 03:01:16'),
(20, 26, 'advanced action update', 0, 'grant permission update', '2024-03-28 06:13:11', '2024-03-29 00:18:10'),
(21, 26, 'add new action', 0, 'allowed', '2024-03-29 00:18:10', '2024-03-29 00:18:10'),
(23, 27, 'read', 1, 'not authorised', '2024-03-29 00:23:42', '2024-04-02 02:18:10'),
(24, 12, 'destroy', 0, 'granted', '2024-03-29 02:52:41', '2024-03-29 02:52:41'),
(25, 28, 'destroy1', 0, 'test message', '2024-03-29 06:11:08', '2024-03-29 06:11:08'),
(27, 1, 'read', 1, 'not allowed', '2024-04-01 01:36:44', '2024-04-05 01:12:52'),
(28, 1, 'create', 0, 'not allowed', '2024-04-01 01:38:12', '2024-04-05 01:12:52'),
(29, 30, 'read', 1, 'bds', '2024-04-01 02:11:30', '2024-04-01 02:15:12'),
(30, 1, 'update', 0, 'dgsfa', '2024-04-01 02:43:19', '2024-04-05 01:12:52'),
(31, 1, 'delete', 0, 'dsfa', '2024-04-01 02:43:19', '2024-04-05 01:12:53'),
(32, 1, 'global', 0, '456', '2024-04-01 03:59:48', '2024-04-05 01:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_module_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `role_permission_type_id` bigint(20) UNSIGNED NOT NULL,
  `permission` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_module_id`, `role_id`, `role_permission_type_id`, `permission`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 1, 4, 27, 1, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:09'),
(3, 1, 4, 28, 1, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(4, 1, 4, 30, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(5, 1, 4, 31, 1, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(6, 10, 4, 5, 1, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(7, 11, 4, 6, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(8, 12, 4, 7, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(9, 12, 4, 8, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(10, 12, 4, 24, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(11, 20, 4, 10, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(12, 26, 4, 20, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(13, 26, 4, 21, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(14, 27, 4, 23, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(15, 28, 4, 25, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(16, 30, 4, 29, 0, NULL, '2024-04-01 03:32:35', '2024-04-01 03:50:10'),
(17, 1, 5, 27, 1, NULL, '2024-04-01 03:54:55', '2024-04-01 04:01:11'),
(18, 1, 5, 28, 1, NULL, '2024-04-01 03:54:55', '2024-04-01 04:01:11'),
(19, 1, 5, 30, 0, NULL, '2024-04-01 03:54:55', '2024-04-01 04:01:11'),
(20, 1, 5, 31, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:11'),
(21, 10, 5, 5, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:11'),
(22, 11, 5, 6, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:11'),
(23, 12, 5, 7, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(24, 12, 5, 8, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(25, 12, 5, 24, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(26, 20, 5, 10, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(27, 26, 5, 20, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(28, 26, 5, 21, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(29, 27, 5, 23, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(30, 28, 5, 25, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(31, 30, 5, 29, 0, NULL, '2024-04-01 03:54:56', '2024-04-01 04:01:12'),
(32, 1, 5, 32, 1, NULL, '2024-04-01 04:01:11', '2024-04-01 04:01:11'),
(33, 1, 6, 27, 1, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(34, 1, 6, 28, 0, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(35, 1, 6, 30, 0, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(36, 1, 6, 31, 0, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(37, 1, 6, 32, 1, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(38, 10, 6, 5, 0, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(39, 11, 6, 6, 0, NULL, '2024-04-01 04:01:56', '2024-04-01 04:01:56'),
(40, 12, 6, 7, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(41, 12, 6, 8, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(42, 12, 6, 24, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(43, 20, 6, 10, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(44, 26, 6, 20, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(45, 26, 6, 21, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(46, 27, 6, 23, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(47, 28, 6, 25, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(48, 30, 6, 29, 0, NULL, '2024-04-01 04:01:57', '2024-04-01 04:01:57'),
(49, 1, 1, 27, 1, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:38'),
(50, 1, 1, 28, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(51, 1, 1, 30, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(52, 1, 1, 31, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(53, 1, 1, 32, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(54, 10, 1, 5, 1, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(55, 11, 1, 6, 1, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(56, 12, 1, 7, 1, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(57, 12, 1, 8, 1, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(58, 12, 1, 24, 1, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(59, 20, 1, 10, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(60, 26, 1, 20, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(61, 26, 1, 21, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(62, 27, 1, 23, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(63, 28, 1, 25, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39'),
(64, 30, 1, 29, 0, NULL, '2024-04-01 04:09:54', '2024-04-01 05:03:39');

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
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `CNIC` int(10) UNSIGNED DEFAULT NULL,
  `phone_no` int(10) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `theme` enum('light-theme','dark-theme','semi-dark') NOT NULL DEFAULT 'light-theme',
  `sidebar_color` varchar(255) DEFAULT NULL,
  `header_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `type`, `customer_id`, `service_provider_id`, `name`, `email`, `role_id`, `email_verified_at`, `password`, `remember_token`, `created_by`, `updated_by`, `created_at`, `updated_at`, `CNIC`, `phone_no`, `description`, `image`, `theme`, `sidebar_color`, `header_color`) VALUES
(1, 'admin', NULL, NULL, 'admin', 'admin@idl.pk', 1, NULL, '$2y$10$7fLA.7e4KcLDczRFre8PJehtqkjxWwHFgzIpH.SYLG3zs8smOmk0y', 'WFzJxtLJy84h42DtJYzLlSWm1rDiLvWspQy8egBLpMM9Bb7cPmekCqiJN3fa', 1, NULL, NULL, '2024-04-02 05:46:57', NULL, NULL, NULL, 'profile_images/default/default.jpeg', 'dark-theme', 'sidebarcolor8', 'headercolor8'),
(2, 'service_provider', NULL, 1, 'ali', 'ali@sp', 3, NULL, '$2y$10$efS97qkTKmYEusLXEkkptuhdYABKTDhvKqHfr7jbylsX1J18oVfAS', NULL, 1, 2, '2024-03-04 07:18:14', '2024-03-04 07:54:49', NULL, NULL, NULL, NULL, 'light-theme', NULL, NULL),
(3, 'service_provider', NULL, 2, 'asad', 'asad@sp', 3, NULL, '$2y$10$o4uh8Sn/mu6h4YRJk/3Mtu1DaKau7auNgFRaPP2GInxyLStW1Xpw6', NULL, 2, NULL, '2024-03-04 07:34:49', '2024-03-04 07:34:49', NULL, NULL, NULL, NULL, 'light-theme', NULL, NULL),
(4, 'service_provider', NULL, NULL, 'customer', 'customer@gmail.com', 3, NULL, '$2y$10$WPA8ITc5PzyC3IvsFY2JiurDJc113Bbya7OiWj98aE9cYYGRglmMu', NULL, 3, NULL, '2024-03-04 23:30:45', '2024-03-04 23:30:45', NULL, NULL, NULL, NULL, 'light-theme', NULL, NULL),
(5, 'customer', 1, NULL, 'customer', 'custo12@gmail.com', 2, NULL, '$2y$10$3LIIHUi3VMDrnBCYoROkOuZgIKhw1gdQaqtpQl6Zcym3X0Zg7RChy', NULL, 3, 1, '2024-03-04 23:58:40', '2024-03-11 11:39:27', NULL, NULL, NULL, NULL, 'light-theme', NULL, NULL),
(7, 'customer', NULL, NULL, 'john', 'test1@gmail.com', NULL, NULL, '$2y$10$xZF.xwv32han9daUlJFgnO9XJsxiZTGsNnxbYTAmraHGmTn6G.Zim', '6WlSoUoaXyGZBBaQz8nvZdgqFUnoDXGj1iJkTs8PousenNDxToYWDgJgnriD', NULL, NULL, '2024-03-12 05:27:28', '2024-03-21 06:32:15', 13331, 6666666, 'this is description1 update', 'profile_images/uploads/1710909762_webdesign.jpg', 'dark-theme', 'sidebarcolor1', 'headercolor1'),
(9, 'customer', NULL, NULL, 'anderson', 'test@gmail.com', NULL, NULL, '$2y$10$3uTI3oRxHV/mOlCsleOu4eN7xF2E046XQHXuyXJULfhbl4ifk7Rma', NULL, NULL, NULL, '2024-03-13 02:43:11', '2024-03-13 04:36:18', NULL, NULL, NULL, 'profile_images/uploads/1710322578_database.jpg', 'light-theme', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_social_profiles`
--

CREATE TABLE `user_social_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `website` text DEFAULT NULL,
  `github` text DEFAULT NULL,
  `twitter` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `facebook` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_social_profiles`
--

INSERT INTO `user_social_profiles` (`id`, `website`, `github`, `twitter`, `instagram`, `facebook`, `user_id`, `created_at`, `updated_at`) VALUES
(4, NULL, 'testgithub.com', NULL, NULL, 'testfacebook.com', 7, '2024-03-13 05:42:20', '2024-03-13 05:42:20');

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `role_permission_type_functions`
--
ALTER TABLE `role_permission_type_functions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permission_type_functions_role_permission_type_id_foreign` (`role_permission_type_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

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
-- Indexes for table `role_permission_types`
--
ALTER TABLE `role_permission_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permission_types_role_module_id_foreign` (`role_module_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_permissions_role_module_id_foreign` (`role_module_id`),
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_role_permission_type_id_foreign` (`role_permission_type_id`);

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
-- Indexes for table `user_social_profiles`
--
ALTER TABLE `user_social_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_social_profiles_user_id_unique` (`user_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `role_permission_type_functions`
--
ALTER TABLE `role_permission_type_functions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `role_modules`
--
ALTER TABLE `role_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `role_permission_types`
--
ALTER TABLE `role_permission_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_social_profiles`
--
ALTER TABLE `user_social_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `role_permission_type_functions`
--
ALTER TABLE `role_permission_type_functions`
  ADD CONSTRAINT `role_permission_type_functions_role_permission_type_id_foreign` FOREIGN KEY (`role_permission_type_id`) REFERENCES `role_permission_types` (`id`);

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
-- Constraints for table `role_permission_types`
--
ALTER TABLE `role_permission_types`
  ADD CONSTRAINT `role_permission_types_role_module_id_foreign` FOREIGN KEY (`role_module_id`) REFERENCES `role_modules` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_permissions_role_module_id_foreign` FOREIGN KEY (`role_module_id`) REFERENCES `role_modules` (`id`),
  ADD CONSTRAINT `role_permissions_role_permission_type_id_foreign` FOREIGN KEY (`role_permission_type_id`) REFERENCES `role_permission_types` (`id`);

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
