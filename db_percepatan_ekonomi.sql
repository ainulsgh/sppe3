-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 15 Sep 2025 pada 05.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_percepatan_ekonomi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-perikanan@example.co|127.0.0.1', 'i:1;', 1757644910),
('laravel-cache-perikanan@example.co|127.0.0.1:timer', 'i:1757644910;', 1757644910),
('laravel-cache-perikanan@monitoring.com|127.0.0.1', 'i:1;', 1757660806),
('laravel-cache-perikanan@monitoring.com|127.0.0.1:timer', 'i:1757660806;', 1757660806),
('laravel-cache-pertanian@example.com|127.0.0.1', 'i:2;', 1757853579),
('laravel-cache-pertanian@example.com|127.0.0.1:timer', 'i:1757853579;', 1757853579),
('laravel-cache-peternakan@gmail.com|127.0.0.1', 'i:1;', 1757558986),
('laravel-cache-peternakan@gmail.com|127.0.0.1:timer', 'i:1757558986;', 1757558986);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dpmptsp_records`
--

CREATE TABLE `dpmptsp_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` tinyint(4) NOT NULL,
  `pbg` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dpmptsp_records`
--

INSERT INTO `dpmptsp_records` (`id`, `user_id`, `tahun`, `bulan`, `pbg`, `created_at`, `updated_at`) VALUES
(1, 11, 2020, 1, 5, '2025-09-14 17:36:07', '2025-09-14 17:36:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_10_085827_add_role_to_users_table', 1),
(5, '2025_09_10_085858_create_perikanan_records_table', 1),
(6, '2025_09_10_085914_create_pertanian_records_table', 1),
(7, '2025_09_10_113404_rename_columns_in_perikanan_records_table', 2),
(8, '2025_09_10_114232_add_bulan_to_perikanan_records_table', 3),
(9, '2025_09_10_114731_add_indikator_fields_to_perikanan_records_table', 4),
(10, '2025_09_10_120838_add_perikanan_new_fields', 5),
(14, '2025_09_10_122457_rename_perikanan_columns_to_snake_case', 6),
(15, '2025_09_10_124603_ensure_all_perikanan_columns_exist', 7),
(16, '2025_09_11_024550_create_peternakan_records_table', 8),
(17, '2025_09_11_030830_alter_perikanan_records_make_decimal', 9),
(18, '2025_09_11_031937_create_perikanan_records_table', 10),
(19, '2025_09_11_033124_create_peternakan_records_table', 11),
(20, '2025_09_12_080504_add_rak_columns_to_peternakan_records_table', 12),
(21, '2025_09_14_121024_create_perhubungan_records_table', 13),
(22, '2025_09_14_121056_create_dpmptsp_records_table', 13),
(23, '2025_09_14_121250_create_dpmptsp_records_table', 14);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `perhubungan_records`
--

CREATE TABLE `perhubungan_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tahun` int(11) NOT NULL,
  `bulan` tinyint(4) NOT NULL,
  `retribusi_truk` double NOT NULL DEFAULT 0,
  `retribusi_pick_up` double NOT NULL DEFAULT 0,
  `retribusi_parkir_motor` double NOT NULL DEFAULT 0,
  `retribusi_parkir_angkot` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `perikanan_records`
--

CREATE TABLE `perikanan_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tahun` smallint(5) UNSIGNED NOT NULL,
  `bulan` tinyint(3) UNSIGNED NOT NULL,
  `penangkapan_di_laut` decimal(15,2) NOT NULL DEFAULT 0.00,
  `penangkapan_di_perairan_umum` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_laut_rumput_laut` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_tambak_rumput_laut` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_tambak_udang` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_tambak_bandeng` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_tambak_lainnya` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_kolam` decimal(15,2) NOT NULL DEFAULT 0.00,
  `budidaya_sawah` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `perikanan_records`
--

INSERT INTO `perikanan_records` (`id`, `user_id`, `tahun`, `bulan`, `penangkapan_di_laut`, `penangkapan_di_perairan_umum`, `budidaya_laut_rumput_laut`, `budidaya_tambak_rumput_laut`, `budidaya_tambak_udang`, `budidaya_tambak_bandeng`, `budidaya_tambak_lainnya`, `budidaya_kolam`, `budidaya_sawah`, `created_at`, `updated_at`) VALUES
(1, 3, 2020, 1, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 1.00, '2025-09-10 19:21:47', '2025-09-11 23:28:22'),
(2, 3, 2020, 4, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, '2025-09-11 23:15:28', '2025-09-11 23:30:45'),
(3, 3, 2021, 6, 1.00, 2.00, 3.00, 7.00, 5.00, 5.00, 6.00, 2.50, 1.00, '2025-09-12 03:45:14', '2025-09-12 04:05:02'),
(4, 3, 2028, 6, 1.00, 1.00, 2.00, 2.00, 3.00, 4.00, 4.00, 2.00, 2.00, '2025-09-12 03:50:54', '2025-09-12 03:50:54'),
(5, 3, 2025, 5, 7.00, 3.00, 4.00, 5.00, 6.00, 7.00, 6.00, 7.00, 7.00, '2025-09-12 03:51:34', '2025-09-14 03:00:15'),
(6, 3, 2021, 5, 1.00, 2.00, 3.00, 3.00, 3.00, 3.00, 33.00, 3.00, 3.00, '2025-09-12 03:56:22', '2025-09-12 03:56:22'),
(7, 3, 2023, 5, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-09-12 04:10:20', '2025-09-12 04:31:11'),
(8, 3, 2024, 5, 1.00, 2.00, 1.00, 7.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-09-12 04:10:35', '2025-09-14 03:39:40'),
(9, 3, 2025, 10, 9.00, 9.00, 99.00, 9.00, 9.00, 9.00, 9.00, 99.00, 9.00, '2025-09-12 04:15:05', '2025-09-12 04:15:05'),
(10, 3, 2025, 7, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, 9.00, '2025-09-12 04:15:20', '2025-09-12 04:15:20'),
(11, 3, 2023, 7, 1.00, 1.00, 2.00, 3.00, 4.00, 5.00, 5.00, 5.00, 5.00, '2025-09-12 04:21:38', '2025-09-12 04:21:38'),
(12, 3, 2029, 6, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, '2025-09-12 04:34:38', '2025-09-12 04:34:38'),
(13, 3, 2024, 7, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-09-12 04:38:18', '2025-09-12 04:46:42'),
(14, 3, 2029, 5, 1.00, 3.00, 4.00, 5.00, 6.00, 7.00, 7.00, 7.00, 7.00, '2025-09-12 04:44:26', '2025-09-12 04:44:26'),
(15, 3, 2027, 9, 1.00, 2.00, 3.00, 4.00, 5.00, 5.00, 6.00, 7.00, 8.00, '2025-09-12 04:44:39', '2025-09-12 04:44:39'),
(16, 3, 2030, 4, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, 1.00, '2025-09-12 04:56:17', '2025-09-12 04:56:17'),
(17, 3, 2030, 1, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, '2025-09-12 05:03:15', '2025-09-12 05:03:15'),
(18, 3, 2029, 1, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, '2025-09-12 05:03:51', '2025-09-12 05:03:51'),
(19, 3, 2024, 2, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, '2025-09-14 02:33:37', '2025-09-14 02:33:37'),
(20, 3, 2027, 5, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, '2025-09-14 02:55:21', '2025-09-14 02:55:21'),
(21, 3, 2029, 8, 1.00, 3.00, 4.00, 5.00, 6.00, 7.00, 6.00, 7.00, 7.00, '2025-09-14 02:56:26', '2025-09-14 02:58:39'),
(22, 3, 2028, 2, 1.00, 3.00, 4.00, 5.00, 6.00, 7.00, 6.00, 7.00, 7.00, '2025-09-14 03:00:01', '2025-09-14 03:00:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pertanian_records`
--

CREATE TABLE `pertanian_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tahun` year(4) NOT NULL,
  `luas_lahan` int(10) UNSIGNED NOT NULL,
  `produksi_padi` bigint(20) UNSIGNED NOT NULL,
  `produksi_jagung` bigint(20) UNSIGNED NOT NULL,
  `produktivitas_padi` decimal(8,2) NOT NULL,
  `jumlah_petani` int(10) UNSIGNED NOT NULL,
  `irigasi_aktif` int(10) UNSIGNED NOT NULL,
  `harga_gabah` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pertanian_records`
--

INSERT INTO `pertanian_records` (`id`, `user_id`, `tahun`, `luas_lahan`, `produksi_padi`, `produksi_jagung`, `produktivitas_padi`, `jumlah_petani`, `irigasi_aktif`, `harga_gabah`, `created_at`, `updated_at`) VALUES
(1, 4, '2025', 25000, 180000, 95000, 5.40, 8200, 120, 6500, '2025-09-10 01:20:49', '2025-09-10 01:20:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peternakan_records`
--

CREATE TABLE `peternakan_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tahun` smallint(5) UNSIGNED NOT NULL,
  `bulan` tinyint(3) UNSIGNED NOT NULL,
  `daging_sapi` decimal(15,2) NOT NULL DEFAULT 0.00,
  `daging_kambing` decimal(15,2) NOT NULL DEFAULT 0.00,
  `daging_kuda` decimal(15,2) NOT NULL DEFAULT 0.00,
  `daging_ayam_buras` decimal(15,2) NOT NULL DEFAULT 0.00,
  `daging_ayam_ras_pedaging` decimal(15,2) NOT NULL DEFAULT 0.00,
  `daging_itik` decimal(15,2) NOT NULL DEFAULT 0.00,
  `telur_ayam_petelur` decimal(15,2) NOT NULL DEFAULT 0.00,
  `telur_ayam_buras` decimal(15,2) NOT NULL DEFAULT 0.00,
  `telur_itik` decimal(15,2) NOT NULL DEFAULT 0.00,
  `telur_ayam_ras_petelur_rak` decimal(15,2) NOT NULL DEFAULT 0.00,
  `telur_ayam_buras_rak` decimal(15,2) NOT NULL DEFAULT 0.00,
  `telur_itik_rak` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `peternakan_records`
--

INSERT INTO `peternakan_records` (`id`, `user_id`, `tahun`, `bulan`, `daging_sapi`, `daging_kambing`, `daging_kuda`, `daging_ayam_buras`, `daging_ayam_ras_pedaging`, `daging_itik`, `telur_ayam_petelur`, `telur_ayam_buras`, `telur_itik`, `telur_ayam_ras_petelur_rak`, `telur_ayam_buras_rak`, `telur_itik_rak`, `created_at`, `updated_at`) VALUES
(1, 4, 2020, 1, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 10.00, 10.00, 10.00, '2025-09-10 19:32:20', '2025-09-12 03:18:21'),
(3, 4, 2022, 1, 99.50, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, 99.00, '2025-09-10 19:38:17', '2025-09-14 04:00:12'),
(4, 4, 2023, 5, 1.00, 2.00, 3.00, 4.00, 5.00, 6.00, 7.00, 8.00, 9.00, 10.00, 11.00, 12.00, '2025-09-12 02:55:40', '2025-09-12 02:55:40'),
(5, 4, 2026, 7, 1.00, 2.00, 3.00, 3.00, 3.00, 5.00, 5.00, 5.00, 5.00, 5.00, 5.00, 5.00, '2025-09-14 03:30:55', '2025-09-14 03:30:55'),
(6, 4, 2023, 1, 1.00, 2.00, 3.00, 3.00, 3.00, 5.00, 5.00, 5.00, 5.00, 5.00, 5.00, 5.00, '2025-09-14 03:37:54', '2025-09-14 03:37:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('XEWtW1SmBWQBKnKJZ784JncguKcwVT4cXBfzUvLB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSFdENHNWamVoR3MzTzY1a1N0ZXBWZnNrOWZFMFNZNG5wOUJvQ2VLaSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fX0=', 1757905924);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'dinas perikanan',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Admin', 'admin@example.com', 'admin', '2025-09-10 01:20:48', '$2y$12$ZSse66QrSZynQpSh2sCpLuYDCVYFFuWGEYfQHknb2krFVoa1s5QFy', '7ONlRS7bgvjhXIndVZeY4j6Jk2Mw5HRQKBYBAeleMy9kaaOTmVbxfZfOtkqN', '2025-09-10 01:20:48', '2025-09-10 01:20:48'),
(3, 'Petugas Perikanan', 'perikanan@example.com', 'dinas perikanan', '2025-09-10 01:20:48', '$2y$12$1sHrhHkAuoipGvVZbCFz/u6XDw7Pap2PanOCo8IWZfvuw.X7DLUaC', '9Y6UmdUM5niU3xtOB0Rr7cAqk8eoldRvv6Fwpy5aIAyKyeEdz5uBYTprmCIX', '2025-09-10 01:20:48', '2025-09-10 01:20:48'),
(4, 'Peternakan', 'peternakan@example.com', 'dinas peternakan', '2025-09-10 01:20:49', '$2y$12$fqlqQb7asSm5GzG1xJ5jBewsIHvg1UdLFSUr8rbzPUyl.lmLaNKoq', 'uy84oTLX5kwbUX6KpXOBghXmYnNYMWI5iLXrBUZzPVrRufGFEp6LkTuX4gEk', '2025-09-10 01:20:49', '2025-09-10 01:20:49'),
(10, 'perhubungan', 'perhubungan@example.com', 'dinas perhubungan', NULL, '$2y$12$BDfri79NXygX.qAOwCCB6uZVLvnJdcwBkKD13nYrwPX7T2sIYGOx.', NULL, '2025-09-14 17:34:10', '2025-09-14 17:34:10'),
(11, 'dpmptsp', 'dpmptsp@example.com', 'dpmptsp', NULL, '$2y$12$iy2/0QXDiHHOkDkEioeFOuEc.E8zoPGz.WPcTmIqyi407tTe6FzQu', '9rJXzmJPUHrOvKWA28eoJFbMsaLqCKdB552IAEM16AFviD1OUb1NJg6bs3fD', '2025-09-14 17:34:10', '2025-09-14 17:34:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `dpmptsp_records`
--
ALTER TABLE `dpmptsp_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dpmptsp_records_tahun_bulan_unique` (`tahun`,`bulan`),
  ADD KEY `dpmptsp_records_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `perhubungan_records`
--
ALTER TABLE `perhubungan_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perhubungan_records_tahun_bulan_unique` (`tahun`,`bulan`),
  ADD KEY `perhubungan_records_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `perikanan_records`
--
ALTER TABLE `perikanan_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `perikanan_records_tahun_bulan_unique` (`tahun`,`bulan`),
  ADD KEY `perikanan_records_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `pertanian_records`
--
ALTER TABLE `pertanian_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pertanian_records_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `peternakan_records`
--
ALTER TABLE `peternakan_records`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `peternakan_records_tahun_bulan_unique` (`tahun`,`bulan`),
  ADD KEY `peternakan_records_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dpmptsp_records`
--
ALTER TABLE `dpmptsp_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `perhubungan_records`
--
ALTER TABLE `perhubungan_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `perikanan_records`
--
ALTER TABLE `perikanan_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `pertanian_records`
--
ALTER TABLE `pertanian_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `peternakan_records`
--
ALTER TABLE `peternakan_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `dpmptsp_records`
--
ALTER TABLE `dpmptsp_records`
  ADD CONSTRAINT `dpmptsp_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `perhubungan_records`
--
ALTER TABLE `perhubungan_records`
  ADD CONSTRAINT `perhubungan_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `perikanan_records`
--
ALTER TABLE `perikanan_records`
  ADD CONSTRAINT `perikanan_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pertanian_records`
--
ALTER TABLE `pertanian_records`
  ADD CONSTRAINT `pertanian_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peternakan_records`
--
ALTER TABLE `peternakan_records`
  ADD CONSTRAINT `peternakan_records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
