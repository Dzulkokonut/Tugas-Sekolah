-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 18, 2026 at 11:49 AM
-- Server version: 12.2.2-MariaDB
-- PHP Version: 8.5.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_casp`
--

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id_galeri` int(11) NOT NULL,
  `id_pengguna` int(11) DEFAULT NULL,
  `judul_kenangan` varchar(150) NOT NULL,
  `keterangan` text NOT NULL,
  `nama_file_foto` varchar(255) NOT NULL,
  `tag_jurusan` enum('RPL','TKR','TBSM','MEKA','ELIND') NOT NULL,
  `status` enum('aman','ditakedown') DEFAULT 'aman',
  `waktu_upload` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id_galeri`, `id_pengguna`, `judul_kenangan`, `keterangan`, `nama_file_foto`, `tag_jurusan`, `status`, `waktu_upload`) VALUES
(1, 1, 'Tugas Pertama Anak RPL', 'Momen anak anak Sedikit Menderita Saat Diberi tugas.', 'rpl1.jpg', 'RPL', 'aman', '2026-05-18 10:37:19'),
(2, 2, 'Bongkar Mesin', 'Cik tes.', 'tkr1.jpg', 'TKR', 'aman', '2026-05-18 10:37:19'),
(3, 1, 'Foto tidak senonoh', 'contoh konten tidak senonoh.', 'toxic.jpg', 'RPL', 'ditakedown', '2026-05-18 10:37:19'),
(4, 1, 'contohj', 'contoh', '6a0afc3bd8eb4.gif', 'RPL', 'aman', '2026-05-18 11:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `jurusan` enum('RPL','TKR','TBSM','MEKA','ELIND') NOT NULL,
  `role` enum('siswa','admin') DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id_pengguna`, `username`, `nama_lengkap`, `password`, `jurusan`, `role`) VALUES
(1, 'dzulkokonat', 'Ibnu Dzaki', 'rahasia123', 'RPL', 'admin'),
(2, 'budi_tkr', 'Budi Otomotif', 'tkr123', 'TKR', 'siswa'),
(3, 'siti_elind', 'Siti Solder', 'elind123', 'ELIND', 'siswa');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id_galeri`),
  ADD KEY `id_pengguna` (`id_pengguna`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id_pengguna`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `galeri`
--
ALTER TABLE `galeri`
  ADD CONSTRAINT `1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
