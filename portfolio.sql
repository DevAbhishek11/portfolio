-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2026 at 01:39 PM
-- Server version: 8.0.44
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `read_time` int UNSIGNED DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `user_id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `category`, `tags`, `status`, `is_featured`, `view_count`, `read_time`, `published_at`, `meta_title`, `meta_description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Building Scalable APIs with Laravel', 'building-scalable-apis-laravel', 'Learn how to design and build production-ready REST APIs using Laravel.', '<h2>Introduction</h2><p>Laravel makes building APIs enjoyable and powerful. In this article we explore best practices.</p>', 'storage/blogs/2aad2df1-852b-4d9c-8932-cf4532d86196.jpg', 'Laravel', '[\"php\", \"laravel\", \"api\"]', 'published', 1, 19, 1, '2026-05-12 06:44:00', NULL, NULL, '2026-05-12 06:44:13', '2026-05-18 06:06:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE `blog_comments` (
  `id` bigint UNSIGNED NOT NULL,
  `blog_id` bigint UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_comments`
--

INSERT INTO `blog_comments` (`id`, `blog_id`, `parent_id`, `name`, `email`, `comment`, `is_approved`, `ip_address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, NULL, 'Abhishek', 'abhishekmern.gigsoft@gmail.com', 'Nice blog', 1, '127.0.0.1', '2026-05-18 05:55:04', '2026-05-18 05:55:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-comment:127.0.0.1', 'i:2;', 1779107093),
('laravel-cache-comment:127.0.0.1:timer', 'i:1779107093;', 1779107093);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_queries`
--

CREATE TABLE `contact_queries` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('unread','read','replied','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_queries`
--

INSERT INTO `contact_queries` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `ip_address`, `user_agent`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Abhishek', 'abhishekmern.gigsoft@gmail.com', '7056298363', 'sdsadd', 'dsadasdasdasd d dwq da d wd', 'read', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', '2026-05-15 04:45:25', '2026-05-15 04:47:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contact_replies`
--

CREATE TABLE `contact_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `contact_query_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"22edba4e-afd5-41b3-b5e5-7ebb36e3ffca\",\"displayName\":\"App\\\\Mail\\\\TwoFactorOtpMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":18:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\TwoFactorOtpMail\\\":4:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:3:\\\"otp\\\";s:6:\\\"193395\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"dev.abhishek.ap11@gmail.com\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1778840383,\"delay\":null}', 0, NULL, 1778840383, 1778840383),
(2, 'default', '{\"uuid\":\"29dc67b1-8870-41fd-8445-dc09ce327bb8\",\"displayName\":\"App\\\\Mail\\\\TwoFactorOtpMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":18:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\TwoFactorOtpMail\\\":4:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:3:\\\"otp\\\";s:6:\\\"991758\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"dev.abhishek.ap11@gmail.com\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1778840554,\"delay\":null}', 0, NULL, 1778840554, 1778840554),
(3, 'default', '{\"uuid\":\"fdca5a25-4f26-4cbc-98e0-61cc0aec5acd\",\"displayName\":\"App\\\\Mail\\\\TwoFactorOtpMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":18:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\TwoFactorOtpMail\\\":4:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:3:\\\"otp\\\";s:6:\\\"416027\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"dev.abhishek.ap11@gmail.com\\\";}}s:6:\\\"mailer\\\";s:3:\\\"log\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1778840719,\"delay\":null}', 0, NULL, 1778840719, 1778840719),
(4, 'default', '{\"uuid\":\"82b69ade-6a64-48df-b784-d505e2b746ec\",\"displayName\":\"App\\\\Mail\\\\TwoFactorOtpMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":18:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\TwoFactorOtpMail\\\":4:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:3:\\\"otp\\\";s:6:\\\"156467\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"dev.abhishek.ap11@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1778841093,\"delay\":null}', 0, NULL, 1778841093, 1778841093),
(5, 'default', '{\"uuid\":\"78e17b8c-7e3d-48e1-8872-35f0734169de\",\"displayName\":\"App\\\\Mail\\\\TwoFactorOtpMail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Mail\\\\SendQueuedMailable\",\"command\":\"O:34:\\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\\":18:{s:8:\\\"mailable\\\";O:25:\\\"App\\\\Mail\\\\TwoFactorOtpMail\\\":4:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:3:\\\"otp\\\";s:6:\\\"796429\\\";s:2:\\\"to\\\";a:1:{i:0;a:2:{s:4:\\\"name\\\";N;s:7:\\\"address\\\";s:27:\\\"dev.abhishek.ap11@gmail.com\\\";}}s:6:\\\"mailer\\\";s:4:\\\"smtp\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:13:\\\"maxExceptions\\\";N;s:17:\\\"shouldBeEncrypted\\\";b:0;s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:12:\\\"deduplicator\\\";N;s:13:\\\"debounceOwner\\\";s:0:\\\"\\\";s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\",\"batchId\":null},\"createdAt\":1778841432,\"delay\":null}', 0, NULL, 1778841432, 1778841432);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `successful` tinyint(1) NOT NULL DEFAULT '0',
  `failure_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `email`, `ip_address`, `user_agent`, `successful`, `failure_reason`, `created_at`, `updated_at`) VALUES
(1, 'dev.abhishek.ap11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 1, NULL, '2026-05-12 02:16:26', '2026-05-12 02:16:26'),
(2, 'dev.abhishek.ap11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, NULL, '2026-05-13 22:28:37', '2026-05-13 22:28:37'),
(3, 'ap2290731@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 0, 'User not found', '2026-05-15 03:47:55', '2026-05-15 03:47:55'),
(4, 'dev.abhishek.ap11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, NULL, '2026-05-15 03:48:00', '2026-05-15 03:48:00'),
(5, 'dev.abhishek.ap11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, NULL, '2026-05-15 05:14:21', '2026-05-15 05:14:21'),
(6, 'dev.abhishek.ap11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 0, 'Wrong password', '2026-05-18 05:55:29', '2026-05-18 05:55:29'),
(7, 'dev.abhishek.ap11@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 1, NULL, '2026-05-18 05:55:46', '2026-05-18 05:55:46');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint UNSIGNED NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_12_060958_create_projects_table', 1),
(5, '2026_05_12_061018_create_project_images_table', 1),
(6, '2026_05_12_061035_create_project_tech_stacks_table', 1),
(7, '2026_05_12_061050_create_blogs_table', 1),
(8, '2026_05_12_061107_create_blog_comments_table', 1),
(9, '2026_05_12_061131_create_contact_queries_table', 1),
(10, '2026_05_12_061211_create_contact_replies_table', 1),
(11, '2026_05_12_061940_create_page_views_table', 1),
(12, '2026_05_12_062004_create_login_attempts_table', 1),
(13, '2026_05_12_062033_create_two_factor_tokens_table', 1),
(14, '2026_05_12_064105_create_password_reset_tokens_table', 1),
(15, '2026_05_13_041216_add_performance_indexes_to_all_tables', 2),
(16, '2026_05_13_075451_create_skills_table', 2),
(17, '2026_05_13_084748_create_media_table', 3),
(18, '2026_05_14_040058_add_is_published_to_projects_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `page_views`
--

CREATE TABLE `page_views` (
  `id` bigint UNSIGNED NOT NULL,
  `page` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `referrer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` enum('desktop','mobile','tablet') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `viewable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `viewable_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `page_views`
--

INSERT INTO `page_views` (`id`, `page`, `url`, `ip_address`, `user_agent`, `referrer`, `country`, `device_type`, `browser`, `session_id`, `viewable_type`, `viewable_id`, `created_at`, `updated_at`) VALUES
(1, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'BPPk3OoUUbCvfrWeEMTWbsCqBA173HRdIH5dvNSm', NULL, NULL, '2026-05-12 01:58:33', '2026-05-12 01:58:33'),
(2, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/admin/profile', NULL, 'desktop', 'Chrome', 'BPPk3OoUUbCvfrWeEMTWbsCqBA173HRdIH5dvNSm', NULL, NULL, '2026-05-12 02:00:09', '2026-05-12 02:00:09'),
(3, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:44:41', '2026-05-12 06:44:41'),
(4, 'about', 'http://127.0.0.1:8000/about', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:44:55', '2026-05-12 06:44:55'),
(5, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/about', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:14', '2026-05-12 06:45:14'),
(6, 'projects.index', 'http://127.0.0.1:8000/projects?category=frontend', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:17', '2026-05-12 06:45:17'),
(7, 'projects.index', 'http://127.0.0.1:8000/projects?category=backend', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects?category=frontend', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:18', '2026-05-12 06:45:18'),
(8, 'projects.index', 'http://127.0.0.1:8000/projects?category=fullstack', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects?category=backend', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:20', '2026-05-12 06:45:20'),
(9, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects?category=fullstack', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:21', '2026-05-12 06:45:21'),
(10, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:22', '2026-05-12 06:45:22'),
(11, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:29', '2026-05-12 06:45:29'),
(12, 'blogs.show', 'http://127.0.0.1:8000/blogs/building-scalable-apis-laravel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:38', '2026-05-12 06:45:38'),
(13, 'contact', 'http://127.0.0.1:8000/contact', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:45:54', '2026-05-12 06:45:54'),
(14, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'J2AQUQYcunm0XwMCgObyEjjVifUoZZ6a57T9T4yD', NULL, NULL, '2026-05-12 06:46:02', '2026-05-12 06:46:02'),
(15, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:47:50', '2026-05-12 22:47:50'),
(16, 'contact', 'http://127.0.0.1:8000/contact', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:48:51', '2026-05-12 22:48:51'),
(17, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:48:58', '2026-05-12 22:48:58'),
(18, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:49:06', '2026-05-12 22:49:06'),
(19, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:00', '2026-05-12 22:50:00'),
(20, 'projects.index', 'http://127.0.0.1:8000/projects?category=frontend', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:04', '2026-05-12 22:50:04'),
(21, 'projects.index', 'http://127.0.0.1:8000/projects?category=backend', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects?category=frontend', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:06', '2026-05-12 22:50:06'),
(22, 'projects.index', 'http://127.0.0.1:8000/projects?category=fullstack', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects?category=backend', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:07', '2026-05-12 22:50:07'),
(23, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects?category=fullstack', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:10', '2026-05-12 22:50:10'),
(24, 'about', 'http://127.0.0.1:8000/about', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:17', '2026-05-12 22:50:17'),
(25, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/about', NULL, 'desktop', 'Chrome', 'vpkJWlALFm61CvHIlpqwmNAhW8kpjZUuJ8exPTKZ', NULL, NULL, '2026-05-12 22:50:26', '2026-05-12 22:50:26'),
(26, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:09:36', '2026-05-13 01:09:36'),
(27, 'projects.show', 'http://127.0.0.1:8000/projects/e-commerce-platform', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:11:00', '2026-05-13 01:11:00'),
(28, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects/e-commerce-platform', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:11:30', '2026-05-13 01:11:30'),
(29, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:16:51', '2026-05-13 01:16:51'),
(30, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:17:45', '2026-05-13 01:17:45'),
(31, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:20:08', '2026-05-13 01:20:08'),
(32, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:20:10', '2026-05-13 01:20:10'),
(33, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 01:34:40', '2026-05-13 01:34:40'),
(34, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 02:02:07', '2026-05-13 02:02:07'),
(35, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:34:45', '2026-05-13 03:34:45'),
(36, 'contact', 'http://127.0.0.1:8000/contact', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:10', '2026-05-13 03:35:10'),
(37, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:50', '2026-05-13 03:35:50'),
(38, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:50', '2026-05-13 03:35:50'),
(39, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:50', '2026-05-13 03:35:50'),
(40, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:52', '2026-05-13 03:35:52'),
(41, 'contact', 'http://127.0.0.1:8000/contact', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:52', '2026-05-13 03:35:52'),
(42, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'wCrUBgB3gmBquIHqEx6ITVS8U9fL416e0sGPlS9Q', NULL, NULL, '2026-05-13 03:35:53', '2026-05-13 03:35:53'),
(43, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'u16ws4UoXPjvd4vkScljqHmYmDJQoEV6RqAyb1r7', NULL, NULL, '2026-05-13 06:03:35', '2026-05-13 06:03:35'),
(44, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:04:04', '2026-05-13 06:04:04'),
(45, 'projects.show', 'http://127.0.0.1:8000/projects/create', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:04:10', '2026-05-13 06:04:10'),
(46, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:05:42', '2026-05-13 06:05:42'),
(47, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:13:44', '2026-05-13 06:13:44'),
(48, 'blogs.show', 'http://127.0.0.1:8000/blogs/building-scalable-apis-laravel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:13:55', '2026-05-13 06:13:55'),
(49, 'about', 'http://127.0.0.1:8000/about', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:15:00', '2026-05-13 06:15:00'),
(50, 'projects.show', 'http://127.0.0.1:8000/projects/e-commerce-platform', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:15:53', '2026-05-13 06:15:53'),
(51, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects/e-commerce-platform', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:16:02', '2026-05-13 06:16:02'),
(52, 'contact', 'http://127.0.0.1:8000/contact', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'FfMknkugDA28rFhfqcYbygjHgwTO2Qad8lCiUS0N', NULL, NULL, '2026-05-13 06:16:11', '2026-05-13 06:16:11'),
(53, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'YqJ8o1OP2AtgJFXpFGYd8U4Trpvf7z2zs6hjXHwp', NULL, NULL, '2026-05-13 22:28:08', '2026-05-13 22:28:08'),
(54, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'RgjelEwN7BVZ2J7dTxDtjc62LlYqz1wywE75sQyK', NULL, NULL, '2026-05-14 03:57:55', '2026-05-14 03:57:55'),
(55, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 02:10:11', '2026-05-15 02:10:11'),
(56, 'about', 'http://127.0.0.1:8000/about', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 02:10:55', '2026-05-15 02:10:55'),
(57, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/about', NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 02:11:19', '2026-05-15 02:11:19'),
(58, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 02:11:24', '2026-05-15 02:11:24'),
(59, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 02:11:32', '2026-05-15 02:11:32'),
(60, 'contact', 'http://127.0.0.1:8000/contact', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 02:11:38', '2026-05-15 02:11:38'),
(61, 'blogs.show', 'http://127.0.0.1:8000/blogs/building-scalable-apis-laravel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'hAmQgl5WvwcjlGbXbhAbW34Dvj3Zc51saRv0SyPX', NULL, NULL, '2026-05-15 03:42:02', '2026-05-15 03:42:02'),
(62, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', '7vo5JqGZ4ZIpgWh9yTYXnwOgUrTe6ywhbGvLOQgz', NULL, NULL, '2026-05-15 04:50:34', '2026-05-15 04:50:34'),
(63, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/contact', NULL, 'desktop', 'Chrome', 'GWmhTmtjCTU1sGFYFR1odRQKibDtlYGv7TfVZvuN', NULL, NULL, '2026-05-15 05:30:44', '2026-05-15 05:30:44'),
(64, 'home', 'http://127.0.0.1:8000', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', NULL, NULL, 'desktop', 'Chrome', 'LwlPzMEjnOQR1KHGdKgfAYL7Y4AEhyTcnsoJgewK', NULL, NULL, '2026-05-18 05:52:47', '2026-05-18 05:52:47'),
(65, 'about', 'http://127.0.0.1:8000/about', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/', NULL, 'desktop', 'Chrome', 'LwlPzMEjnOQR1KHGdKgfAYL7Y4AEhyTcnsoJgewK', NULL, NULL, '2026-05-18 05:53:04', '2026-05-18 05:53:04'),
(66, 'projects.index', 'http://127.0.0.1:8000/projects', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/about', NULL, 'desktop', 'Chrome', 'LwlPzMEjnOQR1KHGdKgfAYL7Y4AEhyTcnsoJgewK', NULL, NULL, '2026-05-18 05:53:25', '2026-05-18 05:53:25'),
(67, 'services', 'http://127.0.0.1:8000/services', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/projects', NULL, 'desktop', 'Chrome', 'LwlPzMEjnOQR1KHGdKgfAYL7Y4AEhyTcnsoJgewK', NULL, NULL, '2026-05-18 05:53:28', '2026-05-18 05:53:28'),
(68, 'blogs.index', 'http://127.0.0.1:8000/blogs', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/services', NULL, 'desktop', 'Chrome', 'LwlPzMEjnOQR1KHGdKgfAYL7Y4AEhyTcnsoJgewK', NULL, NULL, '2026-05-18 05:53:33', '2026-05-18 05:53:33'),
(69, 'blogs.show', 'http://127.0.0.1:8000/blogs/building-scalable-apis-laravel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'http://127.0.0.1:8000/blogs', NULL, 'desktop', 'Chrome', 'LwlPzMEjnOQR1KHGdKgfAYL7Y4AEhyTcnsoJgewK', NULL, NULL, '2026-05-18 05:53:42', '2026-05-18 05:53:42');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('dev.abhishek.ap11@gmail.com', '$2y$12$ng.5lJN2g5lzOb5RpDLaoefCfHVeH29KTUHUnVa7Pfh1uC2xUQBrC', '2026-05-15 04:50:49');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `live_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` enum('frontend','backend','fullstack') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `view_count` bigint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `user_id`, `title`, `slug`, `short_description`, `description`, `thumbnail`, `github_url`, `live_url`, `category`, `status`, `is_featured`, `start_date`, `end_date`, `sort_order`, `view_count`, `created_at`, `updated_at`, `deleted_at`, `is_published`) VALUES
(1, 1, 'E-Commerce Platform', 'e-commerce-platform', 'A full-featured online store with real-time inventory management.', '<h2>Overview</h2><p>Built with Laravel and React, this platform handles thousands of daily transactions.</p>', 'storage/projects/253cd9f3-640e-4af2-8284-fbf3819a6214.jpg', 'https://github.com', 'https://example.com', 'fullstack', 'published', 1, NULL, NULL, 0, 2, '2026-05-12 06:44:10', '2026-05-15 03:55:37', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_images`
--

CREATE TABLE `project_images` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_tech_stacks`
--

CREATE TABLE `project_tech_stacks` (
  `id` bigint UNSIGNED NOT NULL,
  `project_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('language','framework','database','tool','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_tech_stacks`
--

INSERT INTO `project_tech_stacks` (`id`, `project_id`, `name`, `category`, `icon`, `created_at`, `updated_at`) VALUES
(7, 1, 'Laravel', 'framework', NULL, '2026-05-15 03:55:37', '2026-05-15 03:55:37'),
(8, 1, 'React', 'framework', NULL, '2026-05-15 03:55:37', '2026-05-15 03:55:37'),
(9, 1, 'MySQL', 'database', NULL, '2026-05-15 03:55:37', '2026-05-15 03:55:37');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` tinyint UNSIGNED NOT NULL DEFAULT '80',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#8b5cf6',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `user_id`, `name`, `category`, `level`, `color`, `icon`, `sort_order`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 1, 'React / Next.js', 'Frontend', 92, '#06b6d4', NULL, 1, 1, NULL, NULL),
(2, 1, 'HTML / CSS', 'Frontend', 96, '#f43f5e', NULL, 2, 1, NULL, NULL),
(3, 1, 'TypeScript', 'Frontend', 85, '#3178c6', NULL, 3, 1, NULL, NULL),
(4, 1, 'Tailwind CSS', 'Frontend', 94, '#38bdf8', NULL, 4, 1, NULL, NULL),
(5, 1, 'Laravel / PHP', 'Backend', 95, '#8b5cf6', NULL, 1, 1, NULL, NULL),
(6, 1, 'Node.js', 'Backend', 82, '#4ade80', NULL, 2, 1, NULL, NULL),
(7, 1, 'REST APIs', 'Backend', 90, '#a78bfa', NULL, 3, 1, NULL, NULL),
(8, 1, 'GraphQL', 'Backend', 72, '#e879f9', NULL, 4, 1, NULL, NULL),
(9, 1, 'MySQL', 'Database', 90, '#f97316', NULL, 1, 0, NULL, NULL),
(10, 1, 'PostgreSQL', 'Database', 82, '#60a5fa', NULL, 2, 0, NULL, NULL),
(11, 1, 'MongoDB', 'Database', 75, '#4ade80', NULL, 3, 0, NULL, NULL),
(12, 1, 'Redis', 'Database', 70, '#f43f5e', NULL, 4, 0, NULL, NULL),
(13, 1, 'React Native', 'Mobile', 78, '#06b6d4', NULL, 1, 0, NULL, NULL),
(14, 1, 'Electron', 'Mobile', 65, '#a78bfa', NULL, 2, 0, NULL, NULL),
(15, 1, 'Git / GitHub', 'Tools', 92, '#f97316', NULL, 1, 0, NULL, NULL),
(16, 1, 'Docker', 'Tools', 75, '#60a5fa', NULL, 2, 0, NULL, NULL),
(17, 1, 'Linux / Bash', 'Tools', 80, '#facc15', NULL, 3, 0, NULL, NULL),
(18, 1, 'Figma', 'Tools', 70, '#f43f5e', NULL, 4, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `two_factor_tokens`
--

CREATE TABLE `two_factor_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('login','password_reset') COLLATE utf8mb4_unicode_ci NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `two_factor_tokens`
--

INSERT INTO `two_factor_tokens` (`id`, `user_id`, `token`, `type`, `used`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 1, '$2y$12$eFKebN4zfV4ggk/VnqWExud3p5FK5/OkHqnwcP1OBg5oY4nEIHNna', 'password_reset', 0, '2026-05-15 05:50:49', '2026-05-15 04:50:49', '2026-05-15 04:50:49'),
(9, 1, '$2y$12$D5vMfpPRgaWhRuITvJIzmOR5PDSWePPP/biGh5U.pRjdOtltjAMNa', 'login', 1, '2026-05-18 06:17:59', '2026-05-18 06:07:59', '2026-05-18 06:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `github_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `linkedin_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resume_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_method` enum('email_otp','auth_app') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email_otp',
  `two_factor_verified_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `avatar`, `bio`, `title`, `phone`, `location`, `website`, `github_url`, `linkedin_url`, `twitter_url`, `resume_url`, `two_factor_enabled`, `two_factor_secret`, `two_factor_method`, `two_factor_verified_at`, `is_admin`, `last_login_at`, `last_login_ip`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Abhishek Prajapati', 'dev.abhishek.ap11@gmail.com', '$2y$12$TZGR9IzfcTLbyftbSqhmJeZqpVl14XdQpzBqnDL.s1eMbcnrq93za', 'storage/profile/e0b76a8e-4d90-4400-9dd9-ba6c9dab1a17.png', 'Passionate full stack developer.', 'Full Stack Developer', '+91 705 629 8363', 'Chandigarh, India', 'https://devabhi.site', NULL, NULL, NULL, NULL, 0, NULL, 'email_otp', '2026-05-15 05:14:30', 1, '2026-05-18 05:55:46', '127.0.0.1', NULL, '2026-05-12 01:57:55', '2026-05-18 06:08:25', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`),
  ADD KEY `blogs_user_id_foreign` (`user_id`),
  ADD KEY `blogs_status_is_featured_index` (`status`,`is_featured`),
  ADD KEY `blogs_published_at_index` (`published_at`),
  ADD KEY `blogs_slug_index` (`slug`),
  ADD KEY `blogs_view_count_index` (`view_count`),
  ADD KEY `blogs_category_index` (`category`);

--
-- Indexes for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_comments_blog_id_foreign` (`blog_id`),
  ADD KEY `blog_comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contact_queries`
--
ALTER TABLE `contact_queries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_queries_status_index` (`status`),
  ADD KEY `contact_queries_created_at_index` (`created_at`);

--
-- Indexes for table `contact_replies`
--
ALTER TABLE `contact_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contact_replies_contact_query_id_foreign` (`contact_query_id`),
  ADD KEY `contact_replies_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_attempts_email_ip_address_index` (`email`,`ip_address`),
  ADD KEY `login_attempts_created_at_index` (`created_at`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
  ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `media_order_column_index` (`order_column`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_views`
--
ALTER TABLE `page_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_views_viewable_type_viewable_id_index` (`viewable_type`,`viewable_id`),
  ADD KEY `page_views_page_index` (`page`),
  ADD KEY `page_views_created_at_index` (`created_at`),
  ADD KEY `page_views_device_type_index` (`device_type`),
  ADD KEY `page_views_session_id_page_index` (`session_id`,`page`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_slug_unique` (`slug`),
  ADD KEY `projects_user_id_foreign` (`user_id`),
  ADD KEY `projects_status_is_featured_index` (`status`,`is_featured`),
  ADD KEY `projects_category_index` (`category`),
  ADD KEY `projects_slug_index` (`slug`),
  ADD KEY `projects_sort_order_index` (`sort_order`),
  ADD KEY `projects_view_count_index` (`view_count`);

--
-- Indexes for table `project_images`
--
ALTER TABLE `project_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_images_project_id_foreign` (`project_id`);

--
-- Indexes for table `project_tech_stacks`
--
ALTER TABLE `project_tech_stacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_tech_stacks_project_id_foreign` (`project_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skills_user_id_category_index` (`user_id`,`category`),
  ADD KEY `skills_sort_order_index` (`sort_order`);

--
-- Indexes for table `two_factor_tokens`
--
ALTER TABLE `two_factor_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `two_factor_tokens_user_id_type_used_index` (`user_id`,`type`,`used`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `blog_comments`
--
ALTER TABLE `blog_comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_queries`
--
ALTER TABLE `contact_queries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_replies`
--
ALTER TABLE `contact_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `page_views`
--
ALTER TABLE `page_views`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `project_images`
--
ALTER TABLE `project_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `project_tech_stacks`
--
ALTER TABLE `project_tech_stacks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `two_factor_tokens`
--
ALTER TABLE `two_factor_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `blog_comments`
--
ALTER TABLE `blog_comments`
  ADD CONSTRAINT `blog_comments_blog_id_foreign` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `blog_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `blog_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `contact_replies`
--
ALTER TABLE `contact_replies`
  ADD CONSTRAINT `contact_replies_contact_query_id_foreign` FOREIGN KEY (`contact_query_id`) REFERENCES `contact_queries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contact_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_images`
--
ALTER TABLE `project_images`
  ADD CONSTRAINT `project_images_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_tech_stacks`
--
ALTER TABLE `project_tech_stacks`
  ADD CONSTRAINT `project_tech_stacks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `two_factor_tokens`
--
ALTER TABLE `two_factor_tokens`
  ADD CONSTRAINT `two_factor_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
