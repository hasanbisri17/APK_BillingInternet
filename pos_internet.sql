-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 28, 2025 at 08:50 AM
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
-- Database: `pos_internet`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1743148195),
('laravel_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1743148195;', 1743148195);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_transactions`
--

CREATE TABLE `cash_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cash_transactions`
--

INSERT INTO `cash_transactions` (`id`, `date`, `type`, `amount`, `description`, `created_at`, `updated_at`, `category_id`) VALUES
(1, '2025-03-10', 'income', 300000.00, 'Pembayaran Internet - Mohamad Hasan Bisri (INV-202503-0002)', '2025-03-09 19:13:58', '2025-03-09 19:13:58', 1),
(2, '2025-03-10', 'income', 300000.00, 'Pembayaran Internet - Pak De Jami (INV-202503-0001)', '2025-03-10 00:24:57', '2025-03-10 00:24:57', 1),
(3, '2025-03-10', 'income', 120000.00, 'Pembayaran Internet - Pak De Sutris (INV-202503-0006)', '2025-03-10 01:10:55', '2025-03-10 01:10:55', 1),
(4, '2025-03-10', 'expense', 500000.00, 'Bayar Indibiz Maret 2025', '2025-03-10 01:15:10', '2025-03-10 01:15:10', 6),
(5, '2025-03-10', 'expense', 400000.00, 'Bayar Jasa Pasang', '2025-03-10 01:16:57', '2025-03-10 01:16:57', 4),
(6, '2025-03-07', 'income', 300000.00, 'Pembayaran Internet - Pak De Jami (INV-202503-0004)', '2025-03-10 01:19:12', '2025-03-10 01:19:12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `internet_package_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `address`, `created_at`, `updated_at`, `internet_package_id`) VALUES
(1, 'Pak De Jami', 'pakdejami@gmail.com', '8123456789', 'Magersari gang 2', '2025-03-07 21:30:24', '2025-03-07 21:31:08', 1),
(2, 'Mohamad Hasan Bisri', 'mhasanbisri84@gmail.com', '81259789714', 'Jl Kramat Raya 164, Dki Jakarta', '2025-03-07 21:30:50', '2025-03-07 21:30:50', 1),
(3, 'Pak De Sutris', 'sutriscaem@gmail.com', '081233882355', 'Jl. Kosambi Indah Nomor 233 Jekan Raya', '2025-03-10 00:17:49', '2025-03-10 00:33:49', 2);

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
-- Table structure for table `internet_packages`
--

CREATE TABLE `internet_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `speed` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internet_packages`
--

INSERT INTO `internet_packages` (`id`, `name`, `price`, `speed`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Paket Rumahan 10Mb', 300000.00, '10 Mbps', NULL, 1, '2025-03-07 21:29:54', '2025-03-07 21:29:54'),
(2, 'Paket IRL', 120000.00, '5 Mbps', NULL, 1, '2025-03-10 00:19:02', '2025-03-10 00:19:02');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_07_011729_add_is_admin_to_users_table', 1),
(5, '2025_03_07_033512_create_customers_table', 1),
(6, '2025_03_07_034512_create_internet_packages_table', 1),
(7, '2025_03_07_035512_add_internet_package_id_to_customers_table', 1),
(8, '2025_03_07_036512_create_payments_table', 1),
(9, '2025_03_07_037512_create_payment_methods_table', 1),
(10, '2025_03_07_038512_create_cash_transactions_table', 1),
(11, '2025_03_07_039512_create_transaction_categories_table', 1),
(12, '2025_03_07_040512_create_whatsapp_messages_table', 1),
(13, '2025_03_07_041512_create_whatsapp_settings_table', 1),
(14, '2025_03_07_042512_create_whatsapp_templates_table', 1),
(15, '2025_03_08_000000_add_scheduled_at_to_whats_app_messages_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `internet_package_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `status` enum('pending','paid','overdue') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `customer_id`, `internet_package_id`, `invoice_number`, `amount`, `due_date`, `payment_date`, `status`, `notes`, `created_at`, `updated_at`, `payment_method_id`) VALUES
(1, 1, 1, 'INV-202503-0001', 300000.00, '2025-03-25', '2025-03-10', 'paid', NULL, '2025-03-09 19:13:00', '2025-03-10 00:24:57', 1),
(2, 2, 1, 'INV-202503-0002', 300000.00, '2025-03-25', '2025-03-10', 'paid', NULL, '2025-03-09 19:13:03', '2025-03-09 19:13:58', 1),
(3, 3, 2, 'INV-202503-0003', 120000.00, '2025-03-25', NULL, 'pending', NULL, '2025-03-10 01:07:04', '2025-03-10 01:07:04', NULL),
(5, 1, 1, 'INV-202503-0004', 300000.00, '2025-02-25', '2025-03-07', 'paid', 'Bayar bisri', '2025-03-10 01:08:17', '2025-03-10 01:19:12', 1),
(6, 2, 1, 'INV-202503-0005', 300000.00, '2025-02-25', NULL, 'pending', NULL, '2025-03-10 01:08:18', '2025-03-10 01:08:18', NULL),
(7, 3, 2, 'INV-202503-0006', 120000.00, '2025-02-25', '2025-03-10', 'paid', 'Bayar Tunai via Bisri', '2025-03-10 01:08:18', '2025-03-10 01:10:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `type` enum('cash','bank_transfer','e_wallet') NOT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `code`, `type`, `provider`, `account_number`, `account_name`, `instructions`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tunai', 'CASH', 'cash', NULL, NULL, NULL, 'Pembayaran langsung di kantor', 1, '2025-03-07 20:57:04', '2025-03-07 20:57:04'),
(2, 'Transfer Bank BCA', 'BCA', 'bank_transfer', 'BCA', '1234567890', 'PT Internet Provider', 'Transfer ke rekening BCA kami', 1, '2025-03-07 20:57:04', '2025-03-07 20:57:04'),
(3, 'DANA', 'DANA', 'e_wallet', 'DANA', '081234567890', 'PT Internet Provider', 'Pembayaran melalui DANA', 1, '2025-03-07 20:57:04', '2025-03-07 20:57:04');

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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('gv9bq9DJWKvAHSMOqQh1qZHFjCBzCjrtiQ6DsZG6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36 Edg/134.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRGwzRGc2ZURZZDRQZTdqV1Q2T3JYSGxaOEN5dnpPU2lvQ2xnMkZ5cSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkR1dib0thOTFobGoyUTVmcmxLbWFXZXYzM3NZejdqOWouS1NWSjAwUVc2UURIYzFBWGRORm0iO30=', 1743148153);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_categories`
--

CREATE TABLE `transaction_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_categories`
--

INSERT INTO `transaction_categories` (`id`, `name`, `type`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Pembayaran Internet', 'income', 'Pendapatan dari layanan internet', '2025-03-07 20:57:04', '2025-03-07 20:57:04'),
(2, 'Pemasangan Baru', 'income', 'Pendapatan dari pemasangan internet baru', '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(3, 'Lain-lain', 'income', 'Pendapatan lainnya', '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(4, 'Gaji Karyawan', 'expense', 'Pengeluaran untuk gaji karyawan', '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(5, 'Peralatan', 'expense', 'Pembelian peralatan dan perlengkapan', '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(6, 'Operasional', 'expense', 'Biaya operasional harian', '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(7, 'Maintenance', 'expense', 'Biaya pemeliharaan perangkat', '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(8, 'Lain-lain', 'expense', 'Pengeluaran lainnya', '2025-03-07 20:57:05', '2025-03-07 20:57:05');

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
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$12$GWboKa91hlj2Q5frlKmaWev33sYz7j9j.KSVJ00QW6QDHc1AXdNFm', NULL, '2025-03-07 20:57:04', '2025-03-07 20:57:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_scheduled_messages`
--

CREATE TABLE `whatsapp_scheduled_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message_type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `scheduled_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `response` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_settings`
--

CREATE TABLE `whatsapp_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_token` varchar(255) NOT NULL,
  `api_url` varchar(255) NOT NULL DEFAULT 'https://api.fonnte.com',
  `default_country_code` varchar(255) NOT NULL DEFAULT '62',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_settings`
--

INSERT INTO `whatsapp_settings` (`id`, `api_token`, `api_url`, `default_country_code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '26mGcrLMtKX1!P9WPrXW', 'https://api.fonnte.com', '62', 1, '2025-03-07 20:57:05', '2025-03-07 20:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_templates`
--

CREATE TABLE `whatsapp_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `description` text DEFAULT NULL,
  `variables` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`variables`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whatsapp_templates`
--

INSERT INTO `whatsapp_templates` (`id`, `name`, `code`, `content`, `description`, `variables`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Tagihan Baru', 'billing.new', 'Yth. {customer_name},\n\nTagihan internet Anda untuk periode {period} telah dibuat:\nNo. Invoice: {invoice_number}\nJumlah: Rp {amount}\nJatuh Tempo: {due_date}\n\nMohon melakukan pembayaran sebelum jatuh tempo.\nTerima kasih.', 'Template untuk tagihan baru', '[\"customer_name\",\"period\",\"invoice_number\",\"amount\",\"due_date\"]', 1, '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(2, 'Pengingat Tagihan', 'billing.reminder', 'Yth. {customer_name},\n\nMengingatkan tagihan internet Anda yang akan jatuh tempo:\nNo. Invoice: {invoice_number}\nJumlah: Rp {amount}\nJatuh Tempo: {due_date}\n\nMohon segera melakukan pembayaran.\nTerima kasih.', 'Template untuk pengingat tagihan', '[\"customer_name\",\"invoice_number\",\"amount\",\"due_date\"]', 1, '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(3, 'Tagihan Terlambat', 'billing.overdue', 'Yth. {customer_name},\n\nTagihan internet Anda telah melewati jatuh tempo:\nNo. Invoice: {invoice_number}\nJumlah: Rp {amount}\nJatuh Tempo: {due_date}\n\nMohon segera melakukan pembayaran untuk menghindari pemutusan layanan.\nTerima kasih.', 'Template untuk tagihan terlambat', '[\"customer_name\",\"invoice_number\",\"amount\",\"due_date\"]', 1, '2025-03-07 20:57:05', '2025-03-07 20:57:05'),
(4, 'Konfirmasi Pembayaran', 'billing.paid', 'Yth. {customer_name},\n\nTerima kasih, pembayaran tagihan internet Anda telah kami terima:\nNo. Invoice: {invoice_number}\nJumlah: Rp {amount}\nTanggal Pembayaran: {payment_date}\n\nTerima kasih atas kerjasamanya.', 'Template untuk konfirmasi pembayaran', '[\"customer_name\",\"invoice_number\",\"amount\",\"payment_date\"]', 1, '2025-03-07 20:57:05', '2025-03-07 20:57:05');

-- --------------------------------------------------------

--
-- Table structure for table `whats_app_messages`
--

CREATE TABLE `whats_app_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `message_type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `response` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whats_app_messages`
--

INSERT INTO `whats_app_messages` (`id`, `customer_id`, `payment_id`, `message_type`, `message`, `status`, `response`, `sent_at`, `scheduled_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'broadcast', 'yyyyy', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86226241\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":995,\"remaining\":994,\"used\":1}},\"requestid\":15786508,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-07 21:35:21', NULL, '2025-03-07 21:35:21', '2025-03-07 21:35:21'),
(2, 2, NULL, 'broadcast', 'sjfbsabj', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86226731\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":994,\"remaining\":993,\"used\":1}},\"requestid\":15787962,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-07 21:39:04', NULL, '2025-03-07 21:39:04', '2025-03-07 21:39:04'),
(3, 2, NULL, 'broadcast', 'hsbds', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86475515\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":993,\"remaining\":992,\"used\":1}},\"requestid\":16610718,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-09 19:10:19', NULL, '2025-03-09 19:10:19', '2025-03-09 19:10:19'),
(4, 1, 1, 'billing.new', 'Yth. Pak De Jami,\n\nTagihan internet Anda untuk periode March 2025 telah dibuat:\nNo. Invoice: INV-202503-0001\nJumlah: Rp 300.000\nJatuh Tempo: 25 March 2025\n\nMohon melakukan pembayaran sebelum jatuh tempo.\nTerima kasih.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86476769\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":992,\"remaining\":991,\"used\":1}},\"requestid\":16613157,\"status\":true,\"target\":[\"628123456789\"]}}', '2025-03-09 19:13:03', NULL, '2025-03-09 19:13:03', '2025-03-09 19:13:03'),
(5, 2, 2, 'billing.new', 'Yth. Mohamad Hasan Bisri,\n\nTagihan internet Anda untuk periode March 2025 telah dibuat:\nNo. Invoice: INV-202503-0002\nJumlah: Rp 300.000\nJatuh Tempo: 25 March 2025\n\nMohon melakukan pembayaran sebelum jatuh tempo.\nTerima kasih.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86476771\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":991,\"remaining\":990,\"used\":1}},\"requestid\":16613159,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-09 19:13:03', NULL, '2025-03-09 19:13:03', '2025-03-09 19:13:03'),
(6, 2, 2, 'billing.paid', 'Yth. Mohamad Hasan Bisri,\n\nTerima kasih, pembayaran tagihan internet Anda telah kami terima:\nNo. Invoice: INV-202503-0002\nJumlah: Rp 300.000\nTanggal Pembayaran: 10 March 2025\n\nTerima kasih atas kerjasamanya.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86477137\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":990,\"remaining\":989,\"used\":1}},\"requestid\":16613928,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-09 19:13:58', NULL, '2025-03-09 19:13:58', '2025-03-09 19:13:58'),
(10, 2, NULL, 'broadcast', 'ladsn\n', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86516957\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":988,\"remaining\":987,\"used\":1}},\"requestid\":16668096,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-09 20:50:54', NULL, '2025-03-09 20:50:54', '2025-03-09 20:50:54'),
(11, 1, NULL, 'broadcast', 'Tes Semua Pelanggan', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86530831\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":987,\"remaining\":986,\"used\":1}},\"requestid\":16689181,\"status\":true,\"target\":[\"628123456789\"]}}', '2025-03-09 21:33:15', NULL, '2025-03-09 21:33:15', '2025-03-09 21:33:15'),
(12, 2, NULL, 'broadcast', 'Tes Semua Pelanggan', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86530835\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":986,\"remaining\":985,\"used\":1}},\"requestid\":16689189,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-09 21:33:16', NULL, '2025-03-09 21:33:16', '2025-03-09 21:33:16'),
(13, 1, 1, 'billing.paid', 'Yth. Pak De Jami,\n\nTerima kasih, pembayaran tagihan internet Anda telah kami terima:\nNo. Invoice: INV-202503-0001\nJumlah: Rp 300.000\nTanggal Pembayaran: 10 March 2025\n\nTerima kasih atas kerjasamanya.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86570310\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":985,\"remaining\":984,\"used\":1}},\"requestid\":16773543,\"status\":true,\"target\":[\"628123456789\"]}}', '2025-03-10 00:24:58', NULL, '2025-03-10 00:24:58', '2025-03-10 00:24:58'),
(14, 1, 5, 'billing.new', 'Yth. Pak De Jami,\n\nTagihan internet Anda untuk periode February 2025 telah dibuat:\nNo. Invoice: INV-202503-0004\nJumlah: Rp 300.000\nJatuh Tempo: 25 February 2025\n\nMohon melakukan pembayaran sebelum jatuh tempo.\nTerima kasih.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86580515\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":984,\"remaining\":983,\"used\":1}},\"requestid\":16798535,\"status\":true,\"target\":[\"628123456789\"]}}', '2025-03-10 01:08:18', NULL, '2025-03-10 01:08:18', '2025-03-10 01:08:18'),
(15, 2, 6, 'billing.new', 'Yth. Mohamad Hasan Bisri,\n\nTagihan internet Anda untuk periode February 2025 telah dibuat:\nNo. Invoice: INV-202503-0005\nJumlah: Rp 300.000\nJatuh Tempo: 25 February 2025\n\nMohon melakukan pembayaran sebelum jatuh tempo.\nTerima kasih.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86580518\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":983,\"remaining\":982,\"used\":1}},\"requestid\":16798539,\"status\":true,\"target\":[\"6281259789714\"]}}', '2025-03-10 01:08:18', NULL, '2025-03-10 01:08:18', '2025-03-10 01:08:18'),
(16, 3, 7, 'billing.new', 'Yth. Pak De Sutris,\n\nTagihan internet Anda untuk periode February 2025 telah dibuat:\nNo. Invoice: INV-202503-0006\nJumlah: Rp 120.000\nJatuh Tempo: 25 February 2025\n\nMohon melakukan pembayaran sebelum jatuh tempo.\nTerima kasih.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86580519\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":982,\"remaining\":981,\"used\":1}},\"requestid\":16798540,\"status\":true,\"target\":[\"6281233882355\"]}}', '2025-03-10 01:08:18', NULL, '2025-03-10 01:08:18', '2025-03-10 01:08:18'),
(17, 3, 7, 'billing.paid', 'Yth. Pak De Sutris,\n\nTerima kasih, pembayaran tagihan internet Anda telah kami terima:\nNo. Invoice: INV-202503-0006\nJumlah: Rp 120.000\nTanggal Pembayaran: 10 March 2025\n\nTerima kasih atas kerjasamanya.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86580953\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":981,\"remaining\":980,\"used\":1}},\"requestid\":16800275,\"status\":true,\"target\":[\"6281233882355\"]}}', '2025-03-10 01:10:55', NULL, '2025-03-10 01:10:55', '2025-03-10 01:10:55'),
(18, 1, 5, 'billing.paid', 'Yth. Pak De Jami,\n\nTerima kasih, pembayaran tagihan internet Anda telah kami terima:\nNo. Invoice: INV-202503-0004\nJumlah: Rp 300.000\nTanggal Pembayaran: 07 March 2025\n\nTerima kasih atas kerjasamanya.', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86582420\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":980,\"remaining\":979,\"used\":1}},\"requestid\":16805580,\"status\":true,\"target\":[\"628123456789\"]}}', '2025-03-10 01:19:13', NULL, '2025-03-10 01:19:13', '2025-03-10 01:19:13'),
(19, 3, NULL, 'broadcast', 'Pak De Ngapunten tolong sampean bayar bulan niki ngge', 'sent', '{\"success\":true,\"response\":{\"detail\":\"success! message in queue\",\"id\":[\"86582805\"],\"process\":\"pending\",\"quota\":{\"6285198401345\":{\"details\":\"deduced from total quota\",\"quota\":979,\"remaining\":978,\"used\":1}},\"requestid\":16806635,\"status\":true,\"target\":[\"6281233882355\"]}}', '2025-03-10 01:21:14', NULL, '2025-03-10 01:21:14', '2025-03-10 01:21:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cash_transactions_category_id_foreign` (`category_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_email_unique` (`email`),
  ADD KEY `customers_internet_package_id_foreign` (`internet_package_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `internet_packages`
--
ALTER TABLE `internet_packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_invoice_number_unique` (`invoice_number`),
  ADD KEY `payments_customer_id_foreign` (`customer_id`),
  ADD KEY `payments_internet_package_id_foreign` (`internet_package_id`),
  ADD KEY `payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_code_unique` (`code`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `whatsapp_scheduled_messages`
--
ALTER TABLE `whatsapp_scheduled_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whatsapp_scheduled_messages_customer_id_foreign` (`customer_id`),
  ADD KEY `whatsapp_scheduled_messages_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `whatsapp_settings`
--
ALTER TABLE `whatsapp_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `whatsapp_templates_code_unique` (`code`);

--
-- Indexes for table `whats_app_messages`
--
ALTER TABLE `whats_app_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whats_app_messages_customer_id_foreign` (`customer_id`),
  ADD KEY `whats_app_messages_payment_id_foreign` (`payment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `internet_packages`
--
ALTER TABLE `internet_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transaction_categories`
--
ALTER TABLE `transaction_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `whatsapp_scheduled_messages`
--
ALTER TABLE `whatsapp_scheduled_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_settings`
--
ALTER TABLE `whatsapp_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `whatsapp_templates`
--
ALTER TABLE `whatsapp_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `whats_app_messages`
--
ALTER TABLE `whats_app_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cash_transactions`
--
ALTER TABLE `cash_transactions`
  ADD CONSTRAINT `cash_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `transaction_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_internet_package_id_foreign` FOREIGN KEY (`internet_package_id`) REFERENCES `internet_packages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_internet_package_id_foreign` FOREIGN KEY (`internet_package_id`) REFERENCES `internet_packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `whatsapp_scheduled_messages`
--
ALTER TABLE `whatsapp_scheduled_messages`
  ADD CONSTRAINT `whatsapp_scheduled_messages_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `whatsapp_scheduled_messages_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `whats_app_messages`
--
ALTER TABLE `whats_app_messages`
  ADD CONSTRAINT `whats_app_messages_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `whats_app_messages_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
