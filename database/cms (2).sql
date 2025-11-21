-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2024 at 02:45 PM
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
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `actors`
--

INSERT INTO `actors` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'this is super admin', '2024-04-18 02:41:55', '2024-04-18 02:41:55'),
(2, 'admin', 'this is admin', '2024-04-18 02:42:24', '2024-04-18 02:42:24'),
(3, 'business', 'this is Business', '2024-04-18 02:43:32', '2024-04-18 02:43:32'),
(4, 'agent', 'this is Agent\r\n', '2024-04-18 02:43:51', '2024-04-18 02:43:51'),
(5, 'driver', 'this is driver', '2024-04-19 00:55:58', '2024-04-19 00:55:58'),
(6, 'business_customer', 'this is business customer', '2024-04-19 00:56:40', '2024-04-19 00:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `car_companies`
--

CREATE TABLE `car_companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_companies`
--

INSERT INTO `car_companies` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Toyota', 'this is toyota', NULL, NULL),
(2, 'Honda', 'This is Honda Description', '2024-04-30 04:37:26', '2024-04-30 04:37:26'),
(3, 'BMW', 'this is BMW', '2024-04-30 04:42:41', '2024-04-30 04:42:41');

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
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `country` varchar(100) DEFAULT NULL,
  `currency` varchar(100) DEFAULT NULL,
  `code` varchar(4) DEFAULT NULL,
  `symbol` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`country`, `currency`, `code`, `symbol`) VALUES
('Afghanistan', 'Afghani', 'AFN', '؋'),
('Åland Islands', 'Euro', 'EUR', '€'),
('Albania', 'Lek', 'ALL', 'Lek'),
('Algeria', 'Algerian Dinar', 'DZD', NULL),
('American Samoa', 'US Dollar', 'USD', '$'),
('Andorra', 'Euro', 'EUR', '€'),
('Angola', 'Kwanza', 'AOA', NULL),
('Anguilla', 'East Caribbean Dollar', 'XCD', NULL),
('Antigua And Barbuda', 'East Caribbean Dollar', 'XCD', NULL),
('Argentina', 'Argentine Peso', 'ARS', '$'),
('Armenia', 'Armenian Dram', 'AMD', NULL),
('Aruba', 'Aruban Florin', 'AWG', NULL),
('Australia', 'Australian Dollar', 'AUD', '$'),
('Austria', 'Euro', 'EUR', '€'),
('Azerbaijan', 'Azerbaijan Manat', 'AZN', NULL),
('Bahamas', 'Bahamian Dollar', 'BSD', '$'),
('Bahrain', 'Bahraini Dinar', 'BHD', NULL),
('Bangladesh', 'Taka', 'BDT', '৳'),
('Barbados', 'Barbados Dollar', 'BBD', '$'),
('Belarus', 'Belarusian Ruble', 'BYN', NULL),
('Belgium', 'Euro', 'EUR', '€'),
('Belize', 'Belize Dollar', 'BZD', 'BZ$'),
('Benin', 'CFA Franc BCEAO', 'XOF', NULL),
('Bermuda', 'Bermudian Dollar', 'BMD', NULL),
('Bhutan', 'Indian Rupee', 'INR', '₹'),
('Bhutan', 'Ngultrum', 'BTN', NULL),
('Bolivia', 'Boliviano', 'BOB', NULL),
('Bolivia', 'Mvdol', 'BOV', NULL),
('Bonaire, Sint Eustatius And Saba', 'US Dollar', 'USD', '$'),
('Bosnia And Herzegovina', 'Convertible Mark', 'BAM', NULL),
('Botswana', 'Pula', 'BWP', NULL),
('Bouvet Island', 'Norwegian Krone', 'NOK', NULL),
('Brazil', 'Brazilian Real', 'BRL', 'R$'),
('British Indian Ocean Territory', 'US Dollar', 'USD', '$'),
('Brunei Darussalam', 'Brunei Dollar', 'BND', NULL),
('Bulgaria', 'Bulgarian Lev', 'BGN', 'лв'),
('Burkina Faso', 'CFA Franc BCEAO', 'XOF', NULL),
('Burundi', 'Burundi Franc', 'BIF', NULL),
('Cabo Verde', 'Cabo Verde Escudo', 'CVE', NULL),
('Cambodia', 'Riel', 'KHR', '៛'),
('Cameroon', 'CFA Franc BEAC', 'XAF', NULL),
('Canada', 'Canadian Dollar', 'CAD', '$'),
('Cayman Islands', 'Cayman Islands Dollar', 'KYD', NULL),
('Central African Republic', 'CFA Franc BEAC', 'XAF', NULL),
('Chad', 'CFA Franc BEAC', 'XAF', NULL),
('Chile', 'Chilean Peso', 'CLP', '$'),
('Chile', 'Unidad de Fomento', 'CLF', NULL),
('China', 'Yuan Renminbi', 'CNY', '¥'),
('Christmas Island', 'Australian Dollar', 'AUD', NULL),
('Cocos (keeling) Islands', 'Australian Dollar', 'AUD', NULL),
('Colombia', 'Colombian Peso', 'COP', '$'),
('Colombia', 'Unidad de Valor Real', 'COU', NULL),
('Comoros', 'Comorian Franc ', 'KMF', NULL),
('Congo (the Democratic Republic Of The)', 'Congolese Franc', 'CDF', NULL),
('Congo', 'CFA Franc BEAC', 'XAF', NULL),
('Cook Islands', 'New Zealand Dollar', 'NZD', '$'),
('Costa Rica', 'Costa Rican Colon', 'CRC', NULL),
('Côte D\'ivoire', 'CFA Franc BCEAO', 'XOF', NULL),
('Croatia', 'Kuna', 'HRK', 'kn'),
('Cuba', 'Cuban Peso', 'CUP', NULL),
('Cuba', 'Peso Convertible', 'CUC', NULL),
('Curaçao', 'Netherlands Antillean Guilder', 'ANG', NULL),
('Cyprus', 'Euro', 'EUR', '€'),
('Czechia', 'Czech Koruna', 'CZK', 'Kč'),
('Denmark', 'Danish Krone', 'DKK', 'kr'),
('Djibouti', 'Djibouti Franc', 'DJF', NULL),
('Dominica', 'East Caribbean Dollar', 'XCD', NULL),
('Dominican Republic', 'Dominican Peso', 'DOP', NULL),
('Ecuador', 'US Dollar', 'USD', '$'),
('Egypt', 'Egyptian Pound', 'EGP', NULL),
('El Salvador', 'El Salvador Colon', 'SVC', NULL),
('El Salvador', 'US Dollar', 'USD', '$'),
('Equatorial Guinea', 'CFA Franc BEAC', 'XAF', NULL),
('Eritrea', 'Nakfa', 'ERN', NULL),
('Estonia', 'Euro', 'EUR', '€'),
('Eswatini', 'Lilangeni', 'SZL', NULL),
('Ethiopia', 'Ethiopian Birr', 'ETB', NULL),
('European Union', 'Euro', 'EUR', '€'),
('Falkland Islands [Malvinas]', 'Falkland Islands Pound', 'FKP', NULL),
('Faroe Islands', 'Danish Krone', 'DKK', NULL),
('Fiji', 'Fiji Dollar', 'FJD', NULL),
('Finland', 'Euro', 'EUR', '€'),
('France', 'Euro', 'EUR', '€'),
('French Guiana', 'Euro', 'EUR', '€'),
('French Polynesia', 'CFP Franc', 'XPF', NULL),
('French Southern Territories', 'Euro', 'EUR', '€'),
('Gabon', 'CFA Franc BEAC', 'XAF', NULL),
('Gambia', 'Dalasi', 'GMD', NULL),
('Georgia', 'Lari', 'GEL', '₾'),
('Germany', 'Euro', 'EUR', '€'),
('Ghana', 'Ghana Cedi', 'GHS', NULL),
('Gibraltar', 'Gibraltar Pound', 'GIP', NULL),
('Greece', 'Euro', 'EUR', '€'),
('Greenland', 'Danish Krone', 'DKK', NULL),
('Grenada', 'East Caribbean Dollar', 'XCD', NULL),
('Guadeloupe', 'Euro', 'EUR', '€'),
('Guam', 'US Dollar', 'USD', '$'),
('Guatemala', 'Quetzal', 'GTQ', NULL),
('Guernsey', 'Pound Sterling', 'GBP', '£'),
('Guinea', 'Guinean Franc', 'GNF', NULL),
('Guinea-bissau', 'CFA Franc BCEAO', 'XOF', NULL),
('Guyana', 'Guyana Dollar', 'GYD', NULL),
('Haiti', 'Gourde', 'HTG', NULL),
('Haiti', 'US Dollar', 'USD', '$'),
('Heard Island And Mcdonald Islands', 'Australian Dollar', 'AUD', NULL),
('Holy See (Vatican)', 'Euro', 'EUR', '€'),
('Honduras', 'Lempira', 'HNL', NULL),
('Hong Kong', 'Hong Kong Dollar', 'HKD', '$'),
('Hungary', 'Forint', 'HUF', 'ft'),
('Iceland', 'Iceland Krona', 'ISK', NULL),
('India', 'Indian Rupee', 'INR', '₹'),
('Indonesia', 'Rupiah', 'IDR', 'Rp'),
('International Monetary Fund (IMF)', 'SDR (Special Drawing Right)', 'XDR', NULL),
('Iran', 'Iranian Rial', 'IRR', NULL),
('Iraq', 'Iraqi Dinar', 'IQD', NULL),
('Ireland', 'Euro', 'EUR', '€'),
('Isle Of Man', 'Pound Sterling', 'GBP', '£'),
('Israel', 'New Israeli Sheqel', 'ILS', '₪'),
('Italy', 'Euro', 'EUR', '€'),
('Jamaica', 'Jamaican Dollar', 'JMD', NULL),
('Japan', 'Yen', 'JPY', '¥'),
('Jersey', 'Pound Sterling', 'GBP', '£'),
('Jordan', 'Jordanian Dinar', 'JOD', NULL),
('Kazakhstan', 'Tenge', 'KZT', NULL),
('Kenya', 'Kenyan Shilling', 'KES', 'Ksh'),
('Kiribati', 'Australian Dollar', 'AUD', NULL),
('Korea (the Democratic People’s Republic Of)', 'North Korean Won', 'KPW', NULL),
('Korea (the Republic Of)', 'Won', 'KRW', '₩'),
('Kuwait', 'Kuwaiti Dinar', 'KWD', NULL),
('Kyrgyzstan', 'Som', 'KGS', NULL),
('Lao People’s Democratic Republic', 'Lao Kip', 'LAK', NULL),
('Latvia', 'Euro', 'EUR', '€'),
('Lebanon', 'Lebanese Pound', 'LBP', NULL),
('Lesotho', 'Loti', 'LSL', NULL),
('Lesotho', 'Rand', 'ZAR', NULL),
('Liberia', 'Liberian Dollar', 'LRD', NULL),
('Libya', 'Libyan Dinar', 'LYD', NULL),
('Liechtenstein', 'Swiss Franc', 'CHF', NULL),
('Lithuania', 'Euro', 'EUR', '€'),
('Luxembourg', 'Euro', 'EUR', '€'),
('Macao', 'Pataca', 'MOP', NULL),
('North Macedonia', 'Denar', 'MKD', NULL),
('Madagascar', 'Malagasy Ariary', 'MGA', NULL),
('Malawi', 'Malawi Kwacha', 'MWK', NULL),
('Malaysia', 'Malaysian Ringgit', 'MYR', 'RM'),
('Maldives', 'Rufiyaa', 'MVR', NULL),
('Mali', 'CFA Franc BCEAO', 'XOF', NULL),
('Malta', 'Euro', 'EUR', '€'),
('Marshall Islands', 'US Dollar', 'USD', '$'),
('Martinique', 'Euro', 'EUR', '€'),
('Mauritania', 'Ouguiya', 'MRU', NULL),
('Mauritius', 'Mauritius Rupee', 'MUR', NULL),
('Mayotte', 'Euro', 'EUR', '€'),
('Member Countries Of The African Development Bank Group', 'ADB Unit of Account', 'XUA', NULL),
('Mexico', 'Mexican Peso', 'MXN', '$'),
('Mexico', 'Mexican Unidad de Inversion (UDI)', 'MXV', NULL),
('Micronesia', 'US Dollar', 'USD', '$'),
('Moldova', 'Moldovan Leu', 'MDL', NULL),
('Monaco', 'Euro', 'EUR', '€'),
('Mongolia', 'Tugrik', 'MNT', NULL),
('Montenegro', 'Euro', 'EUR', '€'),
('Montserrat', 'East Caribbean Dollar', 'XCD', NULL),
('Morocco', 'Moroccan Dirham', 'MAD', ' .د.م '),
('Mozambique', 'Mozambique Metical', 'MZN', NULL),
('Myanmar', 'Kyat', 'MMK', NULL),
('Namibia', 'Namibia Dollar', 'NAD', NULL),
('Namibia', 'Rand', 'ZAR', NULL),
('Nauru', 'Australian Dollar', 'AUD', NULL),
('Nepal', 'Nepalese Rupee', 'NPR', NULL),
('Netherlands', 'Euro', 'EUR', '€'),
('New Caledonia', 'CFP Franc', 'XPF', NULL),
('New Zealand', 'New Zealand Dollar', 'NZD', '$'),
('Nicaragua', 'Cordoba Oro', 'NIO', NULL),
('Niger', 'CFA Franc BCEAO', 'XOF', NULL),
('Nigeria', 'Naira', 'NGN', '₦'),
('Niue', 'New Zealand Dollar', 'NZD', '$'),
('Norfolk Island', 'Australian Dollar', 'AUD', NULL),
('Northern Mariana Islands', 'US Dollar', 'USD', '$'),
('Norway', 'Norwegian Krone', 'NOK', 'kr'),
('Oman', 'Rial Omani', 'OMR', NULL),
('Pakistan', 'Pakistan Rupee', 'PKR', 'Rs'),
('Palau', 'US Dollar', 'USD', '$'),
('Panama', 'Balboa', 'PAB', NULL),
('Panama', 'US Dollar', 'USD', '$'),
('Papua New Guinea', 'Kina', 'PGK', NULL),
('Paraguay', 'Guarani', 'PYG', NULL),
('Peru', 'Sol', 'PEN', 'S'),
('Philippines', 'Philippine Peso', 'PHP', '₱'),
('Pitcairn', 'New Zealand Dollar', 'NZD', '$'),
('Poland', 'Zloty', 'PLN', 'zł'),
('Portugal', 'Euro', 'EUR', '€'),
('Puerto Rico', 'US Dollar', 'USD', '$'),
('Qatar', 'Qatari Rial', 'QAR', NULL),
('Réunion', 'Euro', 'EUR', '€'),
('Romania', 'Romanian Leu', 'RON', 'lei'),
('Russian Federation', 'Russian Ruble', 'RUB', '₽'),
('Rwanda', 'Rwanda Franc', 'RWF', NULL),
('Saint Barthélemy', 'Euro', 'EUR', '€'),
('Saint Helena, Ascension And Tristan Da Cunha', 'Saint Helena Pound', 'SHP', NULL),
('Saint Kitts And Nevis', 'East Caribbean Dollar', 'XCD', NULL),
('Saint Lucia', 'East Caribbean Dollar', 'XCD', NULL),
('Saint Martin (French Part)', 'Euro', 'EUR', '€'),
('Saint Pierre And Miquelon', 'Euro', 'EUR', '€'),
('Saint Vincent And The Grenadines', 'East Caribbean Dollar', 'XCD', NULL),
('Samoa', 'Tala', 'WST', NULL),
('San Marino', 'Euro', 'EUR', '€'),
('Sao Tome And Principe', 'Dobra', 'STN', NULL),
('Saudi Arabia', 'Saudi Riyal', 'SAR', NULL),
('Senegal', 'CFA Franc BCEAO', 'XOF', NULL),
('Serbia', 'Serbian Dinar', 'RSD', NULL),
('Seychelles', 'Seychelles Rupee', 'SCR', NULL),
('Sierra Leone', 'Leone', 'SLL', NULL),
('Singapore', 'Singapore Dollar', 'SGD', '$'),
('Sint Maarten (Dutch Part)', 'Netherlands Antillean Guilder', 'ANG', NULL),
('Sistema Unitario De Compensacion Regional De Pagos \"sucre\"\"\"', 'Sucre', 'XSU', NULL),
('Slovakia', 'Euro', 'EUR', '€'),
('Slovenia', 'Euro', 'EUR', '€'),
('Solomon Islands', 'Solomon Islands Dollar', 'SBD', NULL),
('Somalia', 'Somali Shilling', 'SOS', NULL),
('South Africa', 'Rand', 'ZAR', 'R'),
('South Sudan', 'South Sudanese Pound', 'SSP', NULL),
('Spain', 'Euro', 'EUR', '€'),
('Sri Lanka', 'Sri Lanka Rupee', 'LKR', 'Rs'),
('Sudan (the)', 'Sudanese Pound', 'SDG', NULL),
('Suriname', 'Surinam Dollar', 'SRD', NULL),
('Svalbard And Jan Mayen', 'Norwegian Krone', 'NOK', NULL),
('Sweden', 'Swedish Krona', 'SEK', 'kr'),
('Switzerland', 'Swiss Franc', 'CHF', NULL),
('Switzerland', 'WIR Euro', 'CHE', NULL),
('Switzerland', 'WIR Franc', 'CHW', NULL),
('Syrian Arab Republic', 'Syrian Pound', 'SYP', NULL),
('Taiwan', 'New Taiwan Dollar', 'TWD', NULL),
('Tajikistan', 'Somoni', 'TJS', NULL),
('Tanzania, United Republic Of', 'Tanzanian Shilling', 'TZS', NULL),
('Thailand', 'Baht', 'THB', '฿'),
('Timor-leste', 'US Dollar', 'USD', '$'),
('Togo', 'CFA Franc BCEAO', 'XOF', NULL),
('Tokelau', 'New Zealand Dollar', 'NZD', '$'),
('Tonga', 'Pa’anga', 'TOP', NULL),
('Trinidad And Tobago', 'Trinidad and Tobago Dollar', 'TTD', NULL),
('Tunisia', 'Tunisian Dinar', 'TND', NULL),
('Turkey', 'Turkish Lira', 'TRY', '₺'),
('Turkmenistan', 'Turkmenistan New Manat', 'TMT', NULL),
('Turks And Caicos Islands', 'US Dollar', 'USD', '$'),
('Tuvalu', 'Australian Dollar', 'AUD', NULL),
('Uganda', 'Uganda Shilling', 'UGX', NULL),
('Ukraine', 'Hryvnia', 'UAH', '₴'),
('United Arab Emirates', 'UAE Dirham', 'AED', 'د.إ'),
('United Kingdom Of Great Britain And Northern Ireland', 'Pound Sterling', 'GBP', '£'),
('United States Minor Outlying Islands', 'US Dollar', 'USD', '$'),
('United States Of America', 'US Dollar', 'USD', '$'),
('United States Of America', 'US Dollar (Next day)', 'USN', NULL),
('Uruguay', 'Peso Uruguayo', 'UYU', NULL),
('Uruguay', 'Uruguay Peso en Unidades Indexadas (UI)', 'UYI', NULL),
('Uruguay', 'Unidad Previsional', 'UYW', NULL),
('Uzbekistan', 'Uzbekistan Sum', 'UZS', NULL),
('Vanuatu', 'Vatu', 'VUV', NULL),
('Venezuela', 'Bolívar Soberano', 'VES', NULL),
('Vietnam', 'Dong', 'VND', '₫'),
('Virgin Islands (British)', 'US Dollar', 'USD', '$'),
('Virgin Islands (U.S.)', 'US Dollar', 'USD', '$'),
('Wallis And Futuna', 'CFP Franc', 'XPF', NULL),
('Western Sahara', 'Moroccan Dirham', 'MAD', NULL),
('Yemen', 'Yemeni Rial', 'YER', NULL),
('Zambia', 'Zambian Kwacha', 'ZMW', NULL),
('Zimbabwe', 'Zimbabwe Dollar', 'ZWL', NULL);

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
(25, '2024_02_27_101735_create_role_permissions_table', 15),
(28, '2024_04_15_080059_create_actors_table', 16),
(29, '2024_04_17_095924_add_actor_id_to_users_table', 17),
(30, '2024_04_17_103058_add_actor_id_to_rolemodules_table', 18),
(32, '2024_04_19_060709_add_is_system_to_roles_table', 20),
(34, '2024_04_17_104019_create_role_module_actors_table', 21),
(35, '2024_04_22_110242_create_vehicle_classes_table', 22),
(36, '2024_04_22_110845_create_makes_table', 22),
(37, '2024_04_22_111753_create_vehicles_table', 23),
(38, '2024_04_23_061313_create_partners_table', 24),
(39, '2024_04_23_060926_add_detail_fields_to_users_table', 25),
(40, '2024_04_22_133434_add_condition_to_vehicles_table', 26),
(41, '2024_04_23_061314_create_partners_table', 27),
(42, '2024_04_24_082257_create_links_table', 28),
(43, '2024_04_24_094253_create_sidebar_items_table', 29),
(44, '2024_04_26_060544_add__a_c_to_vehicles_table', 30),
(46, '2024_04_26_094054_create_routes_table', 31),
(47, '2024_04_26_094055_create_routes_table', 32),
(48, '2024_04_26_094056_create_route_rates_table', 33),
(49, '2024_04_26_081827_create_packages_table', 34),
(50, '2024_04_26_111606_create_package_routes_table', 35),
(51, '2024_04_30_042953_create_car_companies_table', 36),
(52, '2024_04_22_111754_create_vehicles_table', 37),
(53, '2024_04_30_105252_add_vehicle_classes_id_to_packages_table', 38);

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
(50, 1, 'create', 'view', '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(51, 1, 'store', 'view', '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(52, 2, 'index', 'view', '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(53, 2, 'show', 'view', '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(54, 3, 'edit', 'view', '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(55, 3, 'update', 'view', '2024-04-30 04:47:29', '2024-04-30 04:47:29'),
(56, 4, 'delete', 'view', '2024-04-30 04:47:29', '2024-04-30 04:47:29'),
(57, 6, 'create', 'view', '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(58, 6, 'store', 'view', '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(59, 7, 'index', 'view', '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(60, 7, 'show', 'view', '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(61, 8, 'edit', 'view', '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(62, 8, 'update', 'view', '2024-04-30 04:47:48', '2024-04-30 04:47:48'),
(63, 9, 'delete', 'view', '2024-04-30 04:47:48', '2024-04-30 04:47:48'),
(64, 16, 'create', 'view', '2024-04-30 04:48:07', '2024-04-30 04:48:07'),
(65, 16, 'store', 'view', '2024-04-30 04:48:07', '2024-04-30 04:48:07'),
(66, 17, 'index', 'view', '2024-04-30 04:48:07', '2024-04-30 04:48:07'),
(67, 17, 'show', 'view', '2024-04-30 04:48:08', '2024-04-30 04:48:08'),
(68, 18, 'edit', 'view', '2024-04-30 04:48:08', '2024-04-30 04:48:08'),
(69, 18, 'update', 'view', '2024-04-30 04:48:08', '2024-04-30 04:48:08'),
(70, 19, 'delete', 'view', '2024-04-30 04:48:08', '2024-04-30 04:48:08'),
(71, 21, 'create', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(72, 21, 'store', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(73, 22, 'index', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(74, 22, 'show', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(75, 23, 'edit', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(76, 23, 'update', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(77, 24, 'delete', 'view', '2024-04-30 04:48:26', '2024-04-30 04:48:26'),
(78, 26, 'create', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(79, 26, 'store', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(80, 27, 'index', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(81, 27, 'show', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(82, 28, 'edit', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(83, 28, 'update', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(84, 29, 'delete', 'view', '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(85, 31, 'index', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(86, 31, 'show', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(87, 32, 'create', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(88, 32, 'store', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(89, 33, 'edit', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(90, 33, 'update', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(91, 34, 'delete', 'view', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(99, 36, 'index', 'view', '2024-04-30 07:29:05', '2024-04-30 07:29:05'),
(100, 36, 'show', 'view', '2024-04-30 07:29:05', '2024-04-30 07:29:05'),
(101, 37, 'create', 'view', '2024-04-30 07:29:05', '2024-04-30 07:29:05'),
(102, 37, 'store', 'view', '2024-04-30 07:29:05', '2024-04-30 07:29:05'),
(103, 38, 'edit', 'view', '2024-04-30 07:29:05', '2024-04-30 07:29:05'),
(104, 38, 'update', 'view', '2024-04-30 07:29:06', '2024-04-30 07:29:06'),
(105, 39, 'delete', 'view', '2024-04-30 07:29:06', '2024-04-30 07:29:06'),
(106, 11, 'create', 'view', '2024-04-30 07:29:21', '2024-04-30 07:29:21'),
(107, 11, 'store', 'view', '2024-04-30 07:29:21', '2024-04-30 07:29:21'),
(108, 12, 'index', 'view', '2024-04-30 07:29:21', '2024-04-30 07:29:21'),
(109, 12, 'show', 'view', '2024-04-30 07:29:22', '2024-04-30 07:29:22'),
(110, 13, 'edit', 'view', '2024-04-30 07:29:22', '2024-04-30 07:29:22'),
(111, 13, 'update', 'view', '2024-04-30 07:29:22', '2024-04-30 07:29:22'),
(112, 14, 'delete', 'view', '2024-04-30 07:29:22', '2024-04-30 07:29:22');

-- --------------------------------------------------------

--
-- Table structure for table `package_details`
--

CREATE TABLE `package_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `partner_type` enum('customer','employee','business') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `whatsapp_no` varchar(255) DEFAULT NULL,
  `cnic` varchar(255) NOT NULL,
  `address1` varchar(255) DEFAULT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `address3` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `partners`
--

INSERT INTO `partners` (`id`, `name`, `partner_type`, `email`, `phone_no`, `whatsapp_no`, `cnic`, `address1`, `address2`, `address3`, `city`, `country`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(4, 'haris1', 'business', 'haris1@gmail.com', '03672829191', '03672829191', '35201', NULL, NULL, NULL, 'multan', 'uk', 2, NULL, '2024-04-25 07:14:30', '2024-04-25 07:14:30');

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
-- Table structure for table `rate_lists`
--

CREATE TABLE `rate_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vehicle_classes_id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rate_lists`
--

INSERT INTO `rate_lists` (`id`, `name`, `description`, `price`, `created_at`, `updated_at`, `vehicle_classes_id`, `route_id`) VALUES
(5, 'dfs', 'dfs', 12345.00, '2024-04-30 07:42:03', '2024-04-30 07:42:03', 2, 3),
(6, 'fm', 'dgfsda', 4783.00, '2024-04-30 07:42:24', '2024-04-30 07:42:24', 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `home` varchar(255) NOT NULL,
  `actor_id` bigint(20) UNSIGNED NOT NULL,
  `is_system` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `home`, `actor_id`, `is_system`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Supper Admin', '/', 1, 1, 1, 1, NULL, '2024-04-23 06:34:47', '2024-04-23 06:35:47'),
(2, 'admin', '/', 2, 0, 2, 2, NULL, '2024-04-23 06:39:21', '2024-04-27 11:01:32');

-- --------------------------------------------------------

--
-- Table structure for table `role_modules`
--

CREATE TABLE `role_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role_permission_type_id` int(11) DEFAULT NULL,
  `actor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_modules`
--

INSERT INTO `role_modules` (`id`, `name`, `role_permission_type_id`, `actor_id`, `created_by`, `updated_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Users', NULL, 1, 1, 1, NULL, '2024-04-23 06:28:27', '2024-04-30 04:43:57'),
(2, 'Roles', NULL, 1, 1, 1, NULL, '2024-04-23 06:28:51', '2024-04-30 04:47:47'),
(3, 'RoleModules', NULL, 1, 1, 1, NULL, '2024-04-23 06:29:07', '2024-04-30 07:29:21'),
(4, 'Actors', NULL, 1, 1, 1, NULL, '2024-04-23 06:29:26', '2024-04-30 04:48:07'),
(5, 'Vehicle', NULL, 1, 1, 1, NULL, '2024-04-23 06:29:42', '2024-04-30 04:48:25'),
(6, 'Partners', NULL, 1, 1, 1, NULL, '2024-04-23 06:31:39', '2024-04-30 04:48:41'),
(7, 'Routes', NULL, 1, 1, NULL, NULL, '2024-04-30 04:51:08', '2024-04-30 04:51:08'),
(8, 'RateList', NULL, 1, 1, 1, NULL, '2024-04-30 04:51:51', '2024-04-30 07:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `role_module_actors`
--

CREATE TABLE `role_module_actors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_module_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_module_actors`
--

INSERT INTO `role_module_actors` (`id`, `actor_id`, `role_module_id`, `created_at`, `updated_at`) VALUES
(20, 1, 1, '2024-04-30 04:47:27', '2024-04-30 04:47:27'),
(21, 2, 1, '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(22, 3, 1, '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(23, 4, 1, '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(24, 5, 1, '2024-04-30 04:47:28', '2024-04-30 04:47:28'),
(25, 1, 2, '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(26, 2, 2, '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(27, 3, 2, '2024-04-30 04:47:47', '2024-04-30 04:47:47'),
(28, 1, 4, '2024-04-30 04:48:07', '2024-04-30 04:48:07'),
(29, 2, 5, '2024-04-30 04:48:25', '2024-04-30 04:48:25'),
(30, 2, 6, '2024-04-30 04:48:41', '2024-04-30 04:48:41'),
(31, 3, 6, '2024-04-30 04:48:42', '2024-04-30 04:48:42'),
(32, 2, 7, '2024-04-30 04:51:08', '2024-04-30 04:51:08'),
(34, 2, 8, '2024-04-30 07:29:05', '2024-04-30 07:29:05'),
(35, 1, 3, '2024-04-30 07:29:21', '2024-04-30 07:29:21');

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
(1, 1, 'create', 0, 'You are not allowed to create', '2024-04-23 06:28:27', '2024-04-30 04:47:28'),
(2, 1, 'read', 1, 'You are not allowed to read', '2024-04-23 06:28:27', '2024-04-30 04:47:28'),
(3, 1, 'update', 0, 'You are not allowed to update', '2024-04-23 06:28:27', '2024-04-30 04:47:28'),
(4, 1, 'delete', 0, 'You are not allowed to delete', '2024-04-23 06:28:28', '2024-04-30 04:47:29'),
(5, 1, 'global', 0, 'You are not allowed globally', '2024-04-23 06:28:28', '2024-04-30 04:47:29'),
(6, 2, 'create', 0, 'You are not allowed to create', '2024-04-23 06:28:51', '2024-04-30 04:47:47'),
(7, 2, 'read', 1, 'You are not allowed to read', '2024-04-23 06:28:51', '2024-04-30 04:47:47'),
(8, 2, 'update', 0, 'You are not allowed to update', '2024-04-23 06:28:51', '2024-04-30 04:47:47'),
(9, 2, 'delete', 0, 'You are not allowed to delete', '2024-04-23 06:28:52', '2024-04-30 04:47:48'),
(10, 2, 'global', 0, 'You are not allowed globally', '2024-04-23 06:28:52', '2024-04-30 04:47:48'),
(11, 3, 'create', 0, 'You are not allowed to create', '2024-04-23 06:29:07', '2024-04-30 07:29:21'),
(12, 3, 'read', 1, 'You are not allowed to read', '2024-04-23 06:29:07', '2024-04-30 07:29:21'),
(13, 3, 'update', 0, 'You are not allowed to update', '2024-04-23 06:29:07', '2024-04-30 07:29:22'),
(14, 3, 'delete', 0, 'You are not allowed to delete', '2024-04-23 06:29:07', '2024-04-30 07:29:22'),
(15, 3, 'global', 0, 'You are not allowed globally', '2024-04-23 06:29:07', '2024-04-30 07:29:22'),
(16, 4, 'create', 0, 'You are not allowed to create', '2024-04-23 06:29:26', '2024-04-30 04:48:07'),
(17, 4, 'read', 1, 'You are not allowed to read', '2024-04-23 06:29:26', '2024-04-30 04:48:07'),
(18, 4, 'update', 0, 'You are not allowed to update', '2024-04-23 06:29:26', '2024-04-30 04:48:08'),
(19, 4, 'delete', 0, 'You are not allowed to delete', '2024-04-23 06:29:26', '2024-04-30 04:48:08'),
(20, 4, 'global', 0, 'You are not allowed globally', '2024-04-23 06:29:26', '2024-04-30 04:48:08'),
(21, 5, 'create', 0, 'You are not allowed to create', '2024-04-23 06:29:42', '2024-04-30 04:48:25'),
(22, 5, 'read', 1, 'You are not allowed to read', '2024-04-23 06:29:42', '2024-04-30 04:48:26'),
(23, 5, 'update', 0, 'You are not allowed to update', '2024-04-23 06:29:42', '2024-04-30 04:48:26'),
(24, 5, 'delete', 0, 'You are not allowed to delete', '2024-04-23 06:29:43', '2024-04-30 04:48:26'),
(25, 5, 'global', 0, 'You are not allowed globally', '2024-04-23 06:29:43', '2024-04-30 04:48:26'),
(26, 6, 'create', 0, 'You are not allowed to create', '2024-04-23 06:31:39', '2024-04-30 04:48:42'),
(27, 6, 'read', 1, 'You are not allowed to read', '2024-04-23 06:31:40', '2024-04-30 04:48:42'),
(28, 6, 'update', 0, 'You are not allowed to update', '2024-04-23 06:31:40', '2024-04-30 04:48:42'),
(29, 6, 'delete', 0, 'You are not allowed to delete', '2024-04-23 06:31:40', '2024-04-30 04:48:42'),
(30, 6, 'global', 0, 'You are not allowed globally', '2024-04-23 06:31:40', '2024-04-30 04:48:42'),
(31, 7, 'read', 1, 'You are not allowed to read', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(32, 7, 'create', 0, 'You are not allowed to create', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(33, 7, 'update', 0, 'You are not allowed to update', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(34, 7, 'delete', 0, 'You are not allowed to delete', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(35, 7, 'global', 0, 'You are not allowed globally', '2024-04-30 04:51:09', '2024-04-30 04:51:09'),
(36, 8, 'read', 1, 'You are not allowed to read', '2024-04-30 04:51:51', '2024-04-30 07:29:05'),
(37, 8, 'create', 0, 'You are not allowed to create', '2024-04-30 04:51:52', '2024-04-30 07:29:05'),
(38, 8, 'update', 0, 'You are not allowed to update', '2024-04-30 04:51:52', '2024-04-30 07:29:05'),
(39, 8, 'delete', 0, 'You are not allowed to delete', '2024-04-30 04:51:52', '2024-04-30 07:29:06'),
(40, 8, 'global', 0, 'You are not allowed globally', '2024-04-30 04:51:52', '2024-04-30 07:29:06');

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
(1, 1, 1, 1, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(2, 1, 1, 2, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(3, 1, 1, 3, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(4, 1, 1, 4, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(5, 1, 1, 5, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(6, 2, 1, 6, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(7, 2, 1, 7, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(8, 2, 1, 8, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(9, 2, 1, 9, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(10, 2, 1, 10, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(11, 3, 1, 11, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(12, 3, 1, 12, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:14'),
(13, 3, 1, 13, 1, NULL, '2024-04-23 06:34:47', '2024-04-30 04:33:15'),
(14, 3, 1, 14, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(15, 3, 1, 15, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(16, 4, 1, 16, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(17, 4, 1, 17, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(18, 4, 1, 18, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(19, 4, 1, 19, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(20, 4, 1, 20, 1, NULL, '2024-04-23 06:34:48', '2024-04-30 04:33:15'),
(21, 1, 2, 1, 1, NULL, '2024-04-23 06:39:21', '2024-04-30 04:53:05'),
(22, 1, 2, 2, 1, NULL, '2024-04-23 06:39:21', '2024-04-30 04:53:05'),
(23, 1, 2, 3, 1, NULL, '2024-04-23 06:39:21', '2024-04-30 04:53:05'),
(24, 1, 2, 4, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:05'),
(25, 1, 2, 5, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:05'),
(26, 2, 2, 6, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:05'),
(27, 2, 2, 7, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:05'),
(28, 2, 2, 8, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:05'),
(29, 2, 2, 9, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:05'),
(30, 2, 2, 10, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:06'),
(31, 5, 2, 21, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:06'),
(32, 5, 2, 22, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:06'),
(33, 5, 2, 23, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:07'),
(34, 5, 2, 24, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:08'),
(35, 5, 2, 25, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:08'),
(36, 6, 2, 26, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:08'),
(37, 6, 2, 27, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:09'),
(38, 6, 2, 28, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:09'),
(39, 6, 2, 29, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:09'),
(40, 6, 2, 30, 1, NULL, '2024-04-23 06:39:22', '2024-04-30 04:53:09'),
(41, 7, 2, 31, 1, NULL, '2024-04-30 04:53:09', '2024-04-30 04:53:09'),
(42, 7, 2, 32, 1, NULL, '2024-04-30 04:53:09', '2024-04-30 04:53:09'),
(43, 7, 2, 33, 1, NULL, '2024-04-30 04:53:09', '2024-04-30 04:53:09'),
(44, 7, 2, 34, 1, NULL, '2024-04-30 04:53:10', '2024-04-30 04:53:10'),
(45, 7, 2, 35, 1, NULL, '2024-04-30 04:53:10', '2024-04-30 04:53:10'),
(46, 8, 2, 36, 1, NULL, '2024-04-30 04:53:10', '2024-04-30 04:53:10'),
(47, 8, 2, 37, 1, NULL, '2024-04-30 04:53:10', '2024-04-30 04:53:10'),
(48, 8, 2, 38, 1, NULL, '2024-04-30 04:53:10', '2024-04-30 04:53:10'),
(49, 8, 2, 39, 1, NULL, '2024-04-30 04:53:11', '2024-04-30 04:53:11'),
(50, 8, 2, 40, 1, NULL, '2024-04-30 04:53:11', '2024-04-30 04:53:11');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `from` varchar(255) NOT NULL,
  `to` varchar(255) NOT NULL,
  `distance` decimal(20,2) NOT NULL,
  `distance_unit` enum('km','m','miles') NOT NULL DEFAULT 'km',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `name`, `from`, `to`, `distance`, `distance_unit`, `created_at`, `updated_at`) VALUES
(3, 'madina route', 'makkah', 'madina', 200.00, 'km', '2024-04-29 01:01:06', '2024-04-29 01:01:06'),
(4, 'makkah route', 'madina', 'makkah', 200.00, 'km', '2024-04-29 01:43:04', '2024-04-29 01:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `route_rates`
--

CREATE TABLE `route_rates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route_id` bigint(20) UNSIGNED NOT NULL,
  `rate_with_fuel` decimal(20,2) NOT NULL,
  `rate_without_fuel` decimal(20,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `route_rates`
--

INSERT INTO `route_rates` (`id`, `route_id`, `rate_with_fuel`, `rate_without_fuel`, `created_at`, `updated_at`) VALUES
(2, 3, 21000.00, 15000.00, '2024-04-29 01:01:06', '2024-04-29 01:01:06'),
(3, 4, 18000.00, 12000.00, '2024-04-29 01:43:04', '2024-04-29 01:49:33');

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
-- Table structure for table `sidebar_items`
--

CREATE TABLE `sidebar_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_permission_type_id` bigint(20) UNSIGNED NOT NULL,
  `link` text DEFAULT NULL,
  `link_name` varchar(255) DEFAULT NULL,
  `link_icon` text DEFAULT NULL,
  `link_position` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sidebar_items`
--

INSERT INTO `sidebar_items` (`id`, `role_permission_type_id`, `link`, `link_name`, `link_icon`, `link_position`, `created_at`, `updated_at`) VALUES
(1, 2, 'users', 'Users', '\n<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-users\"><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"></path><circle cx=\"9\" cy=\"7\" r=\"4\"></circle><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"></path><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"></path></svg>', 15, NULL, NULL),
(2, 7, 'roles', 'Roles', '<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-info link-icon\"><circle cx=\"12\" cy=\"12\" r=\"10\"></circle><line x1=\"12\" y1=\"16\" x2=\"12\" y2=\"12\"></line><line x1=\"12\" y1=\"8\" x2=\"12.01\" y2=\"8\"></line></svg>', 17, NULL, NULL),
(3, 12, 'role_modules', 'Role Modules', '<svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-info link-icon\"><circle cx=\"12\" cy=\"12\" r=\"10\"></circle><line x1=\"12\" y1=\"16\" x2=\"12\" y2=\"12\"></line><line x1=\"12\" y1=\"8\" x2=\"12.01\" y2=\"8\"></line></svg>', 20, NULL, NULL),
(4, 17, 'actors', 'Actors', '<svg xmlns= width=\"18\" height=\"18\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-users\"><path d=\"M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2\"></path><circle cx=\"9\" cy=\"7\" r=\"4\"></circle><path d=\"M23 21v-2a4 4 0 0 0-3-3.87\"></path><path d=\"M16 3.13a4 4 0 0 1 0 7.75\"></line><polyline points=\"10 9 9 9 8 9\"></polyline></path></svg>', 19, NULL, NULL),
(5, 22, 'vehicles', 'Vehicles', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-bus-front-fill\" viewBox=\"0 0 16 16\">\n  <path d=\"M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1\"/>\n</svg>', 2, NULL, NULL),
(6, 27, 'partners', 'Partners', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-people-fill\" viewBox=\"0 0 16 16\">\n  <path d=\"M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5\"/>\n</svg>', 1, NULL, NULL),
(7, 31, 'routes', 'Routes', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"20\" height=\"20\" fill=\"currentColor\" class=\"bi bi-signpost\" viewBox=\"0 0 16 16\">\n  <path d=\"M7 1.414V4H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h5v6h2v-6h3.532a1 1 0 0 0 .768-.36l1.933-2.32a.5.5 0 0 0 0-.64L13.3 4.36a1 1 0 0 0-.768-.36H9V1.414a1 1 0 0 0-2 0M12.532 5l1.666 2-1.666 2H2V5z\"/>\n</svg>', 30, '2024-04-30 09:55:02', NULL),
(8, 36, 'ratelists', 'Rate List', '\n<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-dollar-sign\"><line x1=\"12\" y1=\"1\" x2=\"12\" y2=\"23\"></line><path d=\"M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6\"></path></svg>', 35, '2024-04-30 09:55:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `actor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `partner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_type` enum('administrator','driver','agent') DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_no1` int(10) UNSIGNED DEFAULT NULL,
  `phone_no2` int(10) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `theme` enum('light-theme','dark-theme','semi-dark') NOT NULL DEFAULT 'light-theme',
  `sidebar_color` varchar(255) DEFAULT NULL,
  `header_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `actor_id`, `role_id`, `partner_id`, `user_type`, `email_verified_at`, `password`, `remember_token`, `created_by`, `updated_by`, `created_at`, `updated_at`, `phone_no1`, `phone_no2`, `description`, `image`, `theme`, `sidebar_color`, `header_color`) VALUES
(1, 'super_admin', 'super_admin@idl.pk', 1, 1, NULL, NULL, NULL, '$2y$10$2ytPzpTy/AQvb0dicokxZ.HUYcIAp4e4E6u.4e.9wxuqKxgq/etYq', 'EtoyfrIt60yIbXTwIh0DUB8YgqLyKOQTRC8WaIMLRMpe3yufHV9gvYkyiZxu', 1, NULL, NULL, '2024-04-26 01:58:53', 121216, NULL, NULL, 'profile_images/default/default.jpeg', 'light-theme', 'sidebarcolor1', 'headercolor4'),
(2, 'admin', 'admin@idl.pk', 2, 2, NULL, NULL, NULL, '$2y$10$Wi6NVYqj1Pyhxu7/hk8QKeKXsfchdYGmFIjgxWHjapXZLPozOfuNS', NULL, 1, 2, '2024-03-04 07:18:14', '2024-04-30 05:36:29', NULL, NULL, NULL, 'profile_images/default/default.jpeg', 'light-theme', NULL, NULL),
(3, 'asad', 'asad@sp', NULL, 2, NULL, NULL, NULL, '$2y$10$o4uh8Sn/mu6h4YRJk/3Mtu1DaKau7auNgFRaPP2GInxyLStW1Xpw6', NULL, 2, NULL, '2024-03-04 07:34:49', '2024-04-26 01:59:21', 11111, NULL, NULL, 'profile_images/default/default.jpeg', 'light-theme', NULL, NULL),
(4, 'customer', 'customer@gmail.com', NULL, 3, NULL, NULL, NULL, '$2y$10$WPA8ITc5PzyC3IvsFY2JiurDJc113Bbya7OiWj98aE9cYYGRglmMu', NULL, 3, NULL, '2024-03-04 23:30:45', '2024-04-18 04:34:45', NULL, NULL, NULL, 'profile_images/default/default.jpeg', 'light-theme', NULL, NULL),
(5, 'customer', 'custo12@gmail.com', NULL, 2, NULL, NULL, NULL, '$2y$10$3LIIHUi3VMDrnBCYoROkOuZgIKhw1gdQaqtpQl6Zcym3X0Zg7RChy', NULL, 3, 1, '2024-03-04 23:58:40', '2024-04-19 06:51:04', NULL, NULL, NULL, 'profile_images/uploads/1713527464_no-entry.png', 'light-theme', NULL, NULL),
(12, 'newuser2', 'newuser2@gmail.com', 1, NULL, NULL, NULL, NULL, '$2y$10$HvNX5GXOJ1w99TVlYejG/OtFmNJxCsbBWMWbetx.TubVcEdvpjl2u', NULL, 1, NULL, '2024-04-19 06:05:35', '2024-04-19 06:05:35', NULL, NULL, 'this is new user2', 'profile_images/uploads/1713524735_call center.jpg', 'light-theme', NULL, NULL),
(13, 'rizwan', 'rizwan@gmail.com', 1, NULL, NULL, NULL, NULL, '$2y$10$yMq8aM87tfxyFhTp0M5mVeu06qIfibusR4RYwL3mHBZiXh5cTejaK', NULL, 1, NULL, '2024-04-22 00:12:34', '2024-04-22 00:12:34', NULL, NULL, 'this is rizwan', 'profile_images/uploads/1713762753_database.jpg', 'light-theme', NULL, NULL),
(15, 'haris1', 'haris1@gmail.com', NULL, NULL, 4, NULL, NULL, '$2y$10$Ib2s11ueHEINvR6YsZ37Z.4Tokumv5cHlcAzi6l9tzeTrxZNqqa9S', NULL, NULL, NULL, '2024-04-25 07:14:30', '2024-04-25 07:14:57', NULL, NULL, NULL, 'profile_images/default/default.jpeg', 'light-theme', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_identification_number` varchar(255) NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `model` varchar(255) NOT NULL,
  `year` smallint(5) UNSIGNED NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `vehicle_no` varchar(255) NOT NULL,
  `registration_no` varchar(255) NOT NULL,
  `ownership` enum('owned','leased') NOT NULL,
  `is_ac` tinyint(1) NOT NULL,
  `is_status` enum('active','inactive','sold') NOT NULL DEFAULT 'active',
  `fuel_type` enum('petrol','diesel','electric','cng') NOT NULL,
  `engine_type` varchar(255) DEFAULT NULL,
  `transmission_type` enum('automatic','manual') NOT NULL,
  `vehicle_class_id` bigint(20) UNSIGNED DEFAULT NULL,
  `weight` int(11) DEFAULT NULL,
  `car_condition` enum('new','used','excellent','fair') NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_identification_number`, `company_id`, `model`, `year`, `color`, `vehicle_no`, `registration_no`, `ownership`, `is_ac`, `is_status`, `fuel_type`, `engine_type`, `transmission_type`, `vehicle_class_id`, `weight`, `car_condition`, `image`, `reason`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, '1234', 3, 'V8', 2018, 'black', 'leh-1212', 'leh-1212', 'owned', 1, 'active', 'petrol', '1800 cc', 'automatic', 1, NULL, 'new', 'resources_images/uploads/1714456037_car.png', NULL, 2, NULL, '2024-04-30 00:47:17', '2024-04-30 05:48:03');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_classes`
--

CREATE TABLE `vehicle_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_classes`
--

INSERT INTO `vehicle_classes` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Car', 'this is Car', '2024-04-22 06:55:17', '2024-04-22 06:55:17'),
(2, 'Bus', 'this is bus', '2024-04-22 06:55:45', '2024-04-22 06:55:45'),
(3, 'SUV', 'this is SUV', '2024-04-22 06:55:45', '2024-04-22 06:55:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `car_companies`
--
ALTER TABLE `car_companies`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `package_details`
--
ALTER TABLE `package_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_rates_package_id_foreign` (`package_id`),
  ADD KEY `package_rates_route_id_foreign` (`route_id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `partners_created_by_foreign` (`created_by`),
  ADD KEY `partners_updated_by_foreign` (`updated_by`);

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
-- Indexes for table `rate_lists`
--
ALTER TABLE `rate_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `packages_vehicle_classes_id_foreign` (`vehicle_classes_id`),
  ADD KEY `packages_route_id_foreign` (`route_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roles_created_by_foreign` (`created_by`),
  ADD KEY `roles_updated_by_foreign` (`updated_by`),
  ADD KEY `roles_actor_id_foreign` (`actor_id`);

--
-- Indexes for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_modules_created_by_foreign` (`created_by`),
  ADD KEY `role_modules_updated_by_foreign` (`updated_by`),
  ADD KEY `role_modules_actor_id_foreign` (`actor_id`);

--
-- Indexes for table `role_module_actors`
--
ALTER TABLE `role_module_actors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_module_actors_actor_id_foreign` (`actor_id`),
  ADD KEY `role_module_actors_actor_module_id_foreign` (`role_module_id`);

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
  ADD KEY `role_permissions_role_id_foreign` (`role_id`),
  ADD KEY `role_permissions_role_permission_type_id_foreign` (`role_permission_type_id`),
  ADD KEY `role_permissions_role_module_id_foreign` (`role_module_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `route_rates`
--
ALTER TABLE `route_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `route_rates_route_id_foreign` (`route_id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_providers_user_id_foreign` (`user_id`);

--
-- Indexes for table `sidebar_items`
--
ALTER TABLE `sidebar_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sidebar_items_role_permission_type_id_foreign` (`role_permission_type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_created_by_foreign` (`created_by`),
  ADD KEY `users_updated_by_foreign` (`updated_by`),
  ADD KEY `users_actor_id_foreign` (`actor_id`),
  ADD KEY `users_partner_id_foreign` (`partner_id`);

--
-- Indexes for table `user_social_profiles`
--
ALTER TABLE `user_social_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_social_profiles_user_id_unique` (`user_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicles_company_id_foreign` (`company_id`),
  ADD KEY `vehicles_vehicle_class_id_foreign` (`vehicle_class_id`),
  ADD KEY `vehicles_created_by_foreign` (`created_by`),
  ADD KEY `vehicles_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `vehicle_classes`
--
ALTER TABLE `vehicle_classes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `car_companies`
--
ALTER TABLE `car_companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `role_permission_type_functions`
--
ALTER TABLE `role_permission_type_functions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `package_details`
--
ALTER TABLE `package_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rate_lists`
--
ALTER TABLE `rate_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role_modules`
--
ALTER TABLE `role_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role_module_actors`
--
ALTER TABLE `role_module_actors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `role_permission_types`
--
ALTER TABLE `role_permission_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `route_rates`
--
ALTER TABLE `route_rates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sidebar_items`
--
ALTER TABLE `sidebar_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_social_profiles`
--
ALTER TABLE `user_social_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vehicle_classes`
--
ALTER TABLE `vehicle_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- Constraints for table `package_details`
--
ALTER TABLE `package_details`
  ADD CONSTRAINT `package_rates_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `rate_lists` (`id`),
  ADD CONSTRAINT `package_rates_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);

--
-- Constraints for table `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `partners_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `rate_lists`
--
ALTER TABLE `rate_lists`
  ADD CONSTRAINT `packages_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`),
  ADD CONSTRAINT `packages_vehicle_classes_id_foreign` FOREIGN KEY (`vehicle_classes_id`) REFERENCES `vehicle_classes` (`id`);

--
-- Constraints for table `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`),
  ADD CONSTRAINT `roles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `roles_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD CONSTRAINT `role_modules_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`),
  ADD CONSTRAINT `role_modules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `role_modules_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `role_module_actors`
--
ALTER TABLE `role_module_actors`
  ADD CONSTRAINT `role_module_actors_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`),
  ADD CONSTRAINT `role_module_actors_actor_module_id_foreign` FOREIGN KEY (`role_module_id`) REFERENCES `role_modules` (`id`);

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
  ADD CONSTRAINT `role_permissions_role_permission_type_id_foreign` FOREIGN KEY (`role_permission_type_id`) REFERENCES `role_permission_types` (`id`);

--
-- Constraints for table `route_rates`
--
ALTER TABLE `route_rates`
  ADD CONSTRAINT `route_rates_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);

--
-- Constraints for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD CONSTRAINT `service_providers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sidebar_items`
--
ALTER TABLE `sidebar_items`
  ADD CONSTRAINT `sidebar_items_role_permission_type_id_foreign` FOREIGN KEY (`role_permission_type_id`) REFERENCES `role_permission_types` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_actor_id_foreign` FOREIGN KEY (`actor_id`) REFERENCES `actors` (`id`),
  ADD CONSTRAINT `users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `users_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`),
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `car_companies` (`id`),
  ADD CONSTRAINT `vehicles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vehicles_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `vehicles_vehicle_class_id_foreign` FOREIGN KEY (`vehicle_class_id`) REFERENCES `vehicle_classes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
