-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2026 at 10:33 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasirukk`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `harga_menu` bigint NOT NULL,
  `qty` int NOT NULL,
  `subtotal` bigint NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id`, `transaksi_id`, `menu_id`, `nama_menu`, `harga_menu`, `qty`, `subtotal`, `created_at`) VALUES
(1, 3, 1, 'Pizza', 99000, 5, 495000, NULL),
(2, 2, 7, 'Ayam Chicken', 100000, 1, 100000, NULL),
(3, 1, 15, 'Kue Cikung', 1000, 10, 10000, NULL),
(4, 1, 2, 'Mie Kucing', 15000, 2, 30000, NULL),
(9, 7, 20, 'Coffee Latte', 15000, 2, 30000, '2026-10-06 23:40:43'),
(10, 7, 17, 'Udang Krispi', 30000, 1, 30000, '2026-10-06 23:40:43'),
(11, 8, 15, 'Kue Cikung', 1000, 1, 1000, '2026-10-06 23:43:12'),
(12, 8, 7, 'Ayam Chicken', 100000, 6, 600000, '2026-10-06 23:43:12'),
(13, 8, 2, 'Mie Kucinggg', 15000, 1, 15000, '2026-10-06 23:43:13'),
(14, 8, 1, 'Pizza', 99000, 1, 99000, '2026-10-06 23:43:13'),
(15, 9, 17, 'Udang Krispi', 30000, 1, 30000, '2026-10-07 00:01:20'),
(22, 14, 21, 'Aice Chocolate Sundae', 8000, 1, 8000, '2026-10-11 19:46:20'),
(23, 14, 16, 'Ganci', 20000, 2, 40000, '2026-10-11 19:46:20'),
(24, 14, 22, 'Aice Chocolate Crispy', 6000, 2, 12000, '2026-10-11 19:46:20'),
(32, 19, 7, 'Ayam Chicken', 20000, 1, 20000, '2026-10-15 05:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`, `slug`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', 'makanan', NULL, '2026-09-09 22:36:36', '2026-10-14 21:55:57'),
(4, 'Minuman', 'minuman', NULL, '2026-09-09 22:36:59', '2026-10-14 14:59:06'),
(6, 'Dessert', 'dessert', NULL, '2026-09-23 23:30:57', '2026-10-14 14:59:04'),
(7, 'Merchandise', 'merchandise', NULL, '2026-09-24 00:24:12', '2026-10-14 14:59:02'),
(11, 'Es Krim', 'es-krim', NULL, '2026-10-04 17:58:53', '2026-10-15 03:19:03'),
(15, 'Aksesoris Gantungan Kunci', 'aksesoris-gantungan-kunci', '2026-10-14 15:04:07', '2026-10-04 22:39:06', '2026-10-14 15:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `harga` bigint NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'uploads/foto_menu/default.png',
  `deskripsi` text,
  `ketersediaan` enum('tersedia','habis') NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `kategori_id`, `nama`, `harga`, `foto`, `deskripsi`, `ketersediaan`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Pizza', 99000, 'uploads/foto_menu/img_6ac49f2657e117.43497750.png', 'Pizzaa', 'tersedia', NULL, '2026-08-31 14:30:39', '2026-10-06 14:11:34'),
(2, 1, 'Mie Kucinggg', 15000, 'uploads/foto_menu/img_6ab0c915600a56.76814655.jpg', 'Mie ayam adalah makanan yang mengandung mie kucing yang sangat lezat dan bergizi', 'tersedia', '2026-10-14 22:02:54', '2026-09-08 07:47:42', '2026-10-06 16:54:51'),
(7, 1, 'Ayam Chicken', 20000, 'uploads/foto_menu/img_6acfdf13d24507.58865512.png', 'KFC', 'tersedia', NULL, '2026-09-13 22:43:43', '2026-10-15 02:59:28'),
(15, 6, 'Kue Cikung', 1000, 'uploads/foto_menu/img_6ab3ff1e706bb5.26489392.jpg', '', 'tersedia', '2026-10-14 22:02:59', '2026-09-23 23:32:30', '2026-10-14 14:58:34'),
(16, 7, 'Ganci', 20000, 'uploads/foto_menu/default.png', '', 'habis', '2026-10-14 22:02:47', '2026-09-24 00:29:00', '2026-10-14 14:58:51'),
(17, 1, 'Udang Krispi', 30000, 'uploads/foto_menu/img_6ab9fc19201914.44816263.jpg', 'Ujang skripsi', 'tersedia', NULL, '2026-09-28 12:33:13', '2026-10-06 14:09:33'),
(20, 4, 'Coffee Latte', 16000, 'uploads/foto_menu/img_6ac0e7ae389722.65081698.png', 'Perpaduan antara kopi dan susu yang menyegarkan. Harga nya cocok untuk pelajar, ramah kantong pelajar. Rasanya sangat nikmat sekali.', 'tersedia', NULL, '2026-10-03 18:31:58', '2026-10-14 14:58:42'),
(21, 11, 'Aice Chocolate Sundae', 8000, 'uploads/foto_menu/img_6ac7851113e586.45136996.jpg', 'Aice Chocolate Sundae adalah produk es krim dari Aice berupa es krim cup berukuran 100 ml yang memadukan kelembutan es krim rasa cokelat dengan siraman saus cokelat yang manis.', 'tersedia', NULL, '2026-10-08 18:57:05', '2026-10-15 03:06:59'),
(22, 11, 'Aice Chocolate Crispy', 6000, 'uploads/foto_menu/img_6ac785431cd442.71549219.png', '', 'tersedia', NULL, '2026-10-08 18:57:55', '2026-10-15 04:44:42'),
(23, 1, 'Nasi Padang', 10000, 'uploads/foto_menu/img_6ac7858c1df057.95069014.webp', '', 'tersedia', NULL, '2026-10-08 18:59:08', '2026-10-14 22:59:02'),
(24, 6, 'Brownies Coklat', 5000, 'uploads/foto_menu/img_6ac785e01e5865.01758631.png', 'Brownies Coklat, perpaduan antara bolu coklat yang lembut dan topping chococips yang sangat fun untuk dimakan. Nikmat sekali.', 'tersedia', NULL, '2026-10-08 19:00:32', '2026-10-15 03:09:01'),
(25, 4, 'Es Teh', 2000, 'uploads/foto_menu/img_6acc9237ad0964.33379994.png', 'Minuman ini adalah minuman yang sangat digemari oleh masyarakat Indonesia. Apalagi diminum saat cuaca yang panas. Behhh segernyaa....', 'tersedia', NULL, '2026-10-12 14:54:31', '2026-10-15 03:52:57');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id` int NOT NULL,
  `nama_restoran` varchar(100) NOT NULL DEFAULT 'Nama Restoran',
  `logo` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `alamat` text,
  `no_telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pesan_struk` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO `pengaturan` (`id`, `nama_restoran`, `logo`, `deskripsi`, `alamat`, `no_telepon`, `email`, `pesan_struk`, `updated_at`, `created_at`) VALUES
(1, 'RM. Maju Jaya', 'uploads/logo/img_6acfdbbe793139.25797034.png', 'Sistem Kasir', 'Jl. Penuh Kenangan No.45<br>\r\nBojongsemen, Pemalang<br>\r\nJawa Tengah, Korea Selatan', '081234567890', 'sarankritik@majujaya.com', 'Terima Kasih Telah Berkunjung<br>\r\nSelamat Menikmati', '2026-10-15 03:59:43', '2026-10-14 17:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `kode_transaksi` varchar(18) NOT NULL,
  `nama_pelanggan` varchar(50) DEFAULT 'Umum',
  `subtotal` bigint NOT NULL,
  `pajak` bigint NOT NULL,
  `total` bigint NOT NULL,
  `tipe_order` enum('dine_in','take_away') NOT NULL,
  `no_meja` varchar(4) DEFAULT NULL,
  `keterangan` text,
  `tanggal_transaksi` datetime NOT NULL,
  `tunai` bigint NOT NULL,
  `kembalian` bigint NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `user_id`, `kode_transaksi`, `nama_pelanggan`, `subtotal`, `pajak`, `total`, `tipe_order`, `no_meja`, `keterangan`, `tanggal_transaksi`, `tunai`, `kembalian`, `created_at`) VALUES
(1, 2, 'TX-20260927-001', 'Umum', 40000, 4000, 44000, 'dine_in', '7', NULL, '2026-09-03 14:36:23', 20000, 9000, '2026-08-31 14:36:23'),
(2, 17, 'TX-20260927-002', 'Elung', 100000, 10000, 110000, 'take_away', '10', 'tidak ada', '2026-09-07 13:43:05', 120000, 10000, '2026-09-07 13:43:05'),
(3, 2, 'TX-20260927-003', 'Orang kaya', 495000, 49500, 544500, 'take_away', NULL, 'yang asin ya bos', '2026-09-07 13:47:44', 550000, 5500, '2026-09-07 13:47:44'),
(7, 17, 'TX-20261006-001', 'Jokowi', 60000, 6000, 66000, 'take_away', '', 'Makanan', '2026-10-06 23:38:00', 70000, 4000, '2026-10-06 23:40:43'),
(8, 17, 'TX-20261006-002', 'Sal Priadi', 715000, 71500, 786500, 'dine_in', '67', '', '2026-10-06 23:42:00', 800000, 13500, '2026-10-06 23:43:12'),
(9, 18, 'TX-20261007-001', 'Yanto', 30000, 3000, 33000, 'dine_in', '9', '', '2026-10-07 07:42:13', 33000, 0, '2026-10-07 00:01:20'),
(14, 17, 'TX-20261011-001', 'Umum', 60000, 6000, 66000, 'take_away', '', '', '2026-10-11 19:45:00', 100000, 34000, '2026-10-11 19:46:20'),
(19, 17, 'TX-20261015-001', 'Umum', 20000, 2000, 22000, 'dine_in', '', '', '2026-10-15 05:06:00', 22000, 0, '2026-10-15 05:06:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Admin RM Maju Jaya', 'admin@rmmajujaya.com', '$2y$10$XieDhNyDn0OfQH9U94Ld7O97sRUfZ/jb4qBUwzDQ0.vJjj0RaDuUS', 'admin', NULL, '2026-08-30 15:36:26', '2026-09-23 21:37:02'),
(2, 'Kasir', 'kasir@rmmajujaya.com', '$2y$10$PBxjEiAFNV9PPSPqMyu0ReKfJg/7TsQR57tcMxRz97sDRVWgTwNUy', 'kasir', NULL, '2026-08-30 15:37:43', '2026-10-11 21:14:09'),
(12, 'Elang', 'elanghabibi@rmmajujaya.com', '$2y$10$TYtGps9MSkTbkMHG9kbTfuA9/o6jlrESxdXjc/unaAPKgy7RbcnhC', 'admin', NULL, '2026-09-09 13:34:34', '2026-09-24 00:53:04'),
(17, 'Habibi Alamsyah', 'habibi@rmmajujaya.com', '$2y$10$zpJq7LhWx/iZrZlvat9d8Oklljzh.0fH6agIli4WpmmVOKLtCDlFm', 'kasir', NULL, '2026-09-28 14:18:59', '2026-10-15 02:52:40'),
(18, 'Jhon', 'jhon@rmmajujaya.com', '$2y$10$d9cscxnKttQ9HRIbsZRRIOLAnyc6PuQ3Axz7bLjuTnlMxQXDag4c.', 'kasir', NULL, '2026-10-06 23:59:08', '2026-10-12 11:29:44'),
(19, 'test', 'test@gmail.com', '$2y$10$X4Tjkpu/aXB4v/7wEVzgoO2bXbcygGtG.CdtZxhV.W3T0LkLkApn.', 'admin', NULL, '2026-10-11 21:09:24', '2026-10-12 11:29:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_transaksi` (`kode_transaksi`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `menu_id` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_id` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `kategori_id` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
