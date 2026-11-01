-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2026 at 05:49 PM
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
-- Database: `dispertaru`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `username` char(100) NOT NULL,
  `password` char(100) NOT NULL,
  `no_hp` char(20) NOT NULL,
  `emailadmin` varchar(100) NOT NULL,
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `last_seen_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `username`, `password`, `no_hp`, `emailadmin`, `is_active`, `last_seen_at`) VALUES
(2, 'admin', '$2y$12$7UFUz8EZXEOXMwHMQOwTyu6kfPo6BKRlD6WAzyBxDe35.3Xc6dm62', '', '', 1, NULL),
(4, 'admin2', '$2y$12$m5X5WmMmWkk9CmSf0qVUku13G/EzZ8NjOO6wRJyCBYiCaG4BY452K', '', '', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_work_log`
--

CREATE TABLE `admin_work_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `application_id` int(10) UNSIGNED NOT NULL,
  `adminid` int(10) UNSIGNED NOT NULL,
  `action` varchar(40) NOT NULL,
  `from_status` tinyint(4) DEFAULT NULL,
  `to_status` tinyint(4) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `admin_work_log`
--

INSERT INTO `admin_work_log` (`id`, `application_id`, `adminid`, `action`, `from_status`, `to_status`, `created_at`) VALUES
(1, 1, 2, 'application_received', NULL, 2, '2026-10-30 16:38:37'),
(2, 2, 2, 'application_received', NULL, 0, '2026-10-30 19:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `form_p3`
--

CREATE TABLE `form_p3` (
  `id` int(10) UNSIGNED NOT NULL,
  `pemohon_id` int(10) UNSIGNED NOT NULL,
  `kdpermohonan` varchar(100) NOT NULL,
  `foto_ktp` varchar(255) NOT NULL,
  `file_permohonan` varchar(255) NOT NULL,
  `tgl_masuk` timestamp NOT NULL DEFAULT current_timestamp(),
  `tgl_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_p3`
--

INSERT INTO `form_p3` (`id`, `pemohon_id`, `kdpermohonan`, `foto_ktp`, `file_permohonan`, `tgl_masuk`, `tgl_update`, `status`) VALUES
(1, 1, 'P2945C204A69FAE23', '1793353117_1139360d83d67d3ab87e.png', '1793353117_791573a0a7342e4196ec.pdf', '2026-10-30 09:38:37', '2026-10-30 09:51:55', 2),
(2, 2, 'P840B3F928A545BD9', '1793364101_4129612ce3b67a60e7ea.png', '1793364101_77377e3f7427536f17d7.pdf', '2026-10-30 12:41:41', '2026-10-30 12:41:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `manager_accounts`
--

CREATE TABLE `manager_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `manager_accounts`
--

INSERT INTO `manager_accounts` (`id`, `username`, `password_hash`, `created_at`) VALUES
(5, 'manager', '$2y$12$H0myAfomJx1dit1qADYb0.0kDGHRb8n3/a.YtEG8buHmyDZtAdinC', '2026-10-30 18:56:38');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-10-30-170000', 'App\\Database\\Migrations\\CreateApplicantAccountsAndManagers', 'default', 'App', 1793354633, 1),
(2, '2026-10-30-190000', 'App\\Database\\Migrations\\AddAdminManagementFields', 'default', 'App', 1793362804, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pemohon`
--

CREATE TABLE `pemohon` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `nik` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tanggal_lahir` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `pekerjaan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemohon`
--

INSERT INTO `pemohon` (`id`, `user_id`, `nik`, `nama`, `email`, `tempat_lahir`, `tanggal_lahir`, `no_hp`, `alamat`, `pekerjaan`) VALUES
(1, NULL, '1806031407020001', 'Madani Shofa', 'madanishofa@gmail.com', 'Tolitoli', '2002-07-14', '123123123123', '6CP9+W24, Jalan Ring Road Utara Pugeran, Jenengan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', 'Mahasewa'),
(2, 4, '2424223534636346', 'Madani Shofa', 'madanishofa@gmail.com', 'Tolitoli', '2002-07-14', '123123123123', '6CP9+W24, Jalan Ring Road Utara Pugeran, Jenengan, Maguwoharjo, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281', 'Pengagguran');

-- --------------------------------------------------------

--
-- Table structure for table `pemohon_accounts`
--

CREATE TABLE `pemohon_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pemohon_accounts`
--

INSERT INTO `pemohon_accounts` (`id`, `email`, `password_hash`, `created_at`) VALUES
(4, 'madanishofa@gmail.com', '$2y$12$5ga8aqwy1KVPw.tx8QIByOutGBpCAY/gz3RxAaN3eVxuOpmxJedL6', '2026-10-30 19:39:39');

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi`
--

CREATE TABLE `verifikasi` (
  `id` int(10) UNSIGNED NOT NULL,
  `pemohon_id` int(10) UNSIGNED NOT NULL,
  `adminid` int(11) DEFAULT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `verifikasi`
--

INSERT INTO `verifikasi` (`id`, `pemohon_id`, `adminid`, `tanggal`, `status`) VALUES
(1, 1, 2, '2026-10-30 16:38:37', 0),
(2, 2, 2, '2026-10-30 19:41:41', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`);

--
-- Indexes for table `admin_work_log`
--
ALTER TABLE `admin_work_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id_created_at` (`application_id`,`created_at`),
  ADD KEY `adminid_created_at` (`adminid`,`created_at`);

--
-- Indexes for table `form_p3`
--
ALTER TABLE `form_p3`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_p3_pemohon_id_idx` (`pemohon_id`);

--
-- Indexes for table `manager_accounts`
--
ALTER TABLE `manager_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pemohon`
--
ALTER TABLE `pemohon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pemohon_user_id_idx` (`user_id`);

--
-- Indexes for table `pemohon_accounts`
--
ALTER TABLE `pemohon_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `verifikasi`
--
ALTER TABLE `verifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verifikasi_admin_idx` (`adminid`),
  ADD KEY `verifikasi_pemohon_idx` (`pemohon_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin_work_log`
--
ALTER TABLE `admin_work_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `form_p3`
--
ALTER TABLE `form_p3`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `manager_accounts`
--
ALTER TABLE `manager_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemohon`
--
ALTER TABLE `pemohon`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemohon_accounts`
--
ALTER TABLE `pemohon_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `verifikasi`
--
ALTER TABLE `verifikasi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_p3`
--
ALTER TABLE `form_p3`
  ADD CONSTRAINT `form_p3_pemohon_fk` FOREIGN KEY (`pemohon_id`) REFERENCES `pemohon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `verifikasi`
--
ALTER TABLE `verifikasi`
  ADD CONSTRAINT `verifikasi_admin_fk` FOREIGN KEY (`adminid`) REFERENCES `admin` (`adminid`),
  ADD CONSTRAINT `verifikasi_form_p3_fk` FOREIGN KEY (`pemohon_id`) REFERENCES `form_p3` (`pemohon_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
