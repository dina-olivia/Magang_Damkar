-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2026 at 08:30 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `app_damkar`
--

-- --------------------------------------------------------

--
-- Table structure for table `armada`
--

CREATE TABLE `armada` (
  `id` int NOT NULL,
  `kode_armada` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `merk` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` year NOT NULL,
  `pos_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bidang`
--

CREATE TABLE `bidang` (
  `id_bidang` int NOT NULL,
  `nama_bidang` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  `urutan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bidang`
--

INSERT INTO `bidang` (`id_bidang`, `nama_bidang`, `deskripsi`, `urutan`) VALUES
(1, 'Bidang Operasional', 'Mengelola operasional pemadaman kebakaran di lapangan', 1),
(2, 'Bidang Rescue', 'Penyelamatan dan evakuasi korban bencana', 2),
(3, 'Sarpras & Logistik', 'Infrastruktur dan logistik pendukung operasional damkar', 3);

-- --------------------------------------------------------

--
-- Table structure for table `hydrant`
--

CREATE TABLE `hydrant` (
  `id_hydrant` int NOT NULL,
  `nama_hydrant` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hydrant`
--

INSERT INTO `hydrant` (`id_hydrant`, `nama_hydrant`, `lokasi`, `kondisi`, `keterangan`) VALUES
(1, 'HDR-01 Kantor Walikota', 'Kantor Walikota Padang', 'Baik', 'Air lancar');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_piket`
--

CREATE TABLE `jadwal_piket` (
  `id` int NOT NULL,
  `tanggal` date NOT NULL,
  `shift` varchar(50) NOT NULL,
  `jam_kerja` varchar(50) NOT NULL,
  `nama_personil` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_piket`
--

INSERT INTO `jadwal_piket` (`id`, `tanggal`, `shift`, `jam_kerja`, `nama_personil`) VALUES
(6, '2026-06-01', 'Siang', '14:00 - 22:00', 'Melani Putri'),
(7, '2026-05-31', 'Malam', '22:00 - 06:00', 'Indri Natalia Siregar'),
(8, '2026-06-01', 'Siang', '14:00 - 22:00', 'Annisa Bilqis');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `bidang` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `unit` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `keadaan` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `bidang`, `unit`, `status`, `keadaan`) VALUES
(1, 'Bidang Operasional', 'Operasional', 'Damkar', 'Aktif', 'Siaga'),
(2, 'Bidang Rescue', 'Rescue', 'Damkar', 'Aktif', 'Digunakan'),
(3, 'Sarpras & Logistik', 'Sarpras & Logistik', 'Logistik', 'Aktif', 'Siaga');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_kejadian`
--

CREATE TABLE `laporan_kejadian` (
  `id` int NOT NULL,
  `nomor_laporan` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `pelapor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi` text COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kejadian` enum('kebakaran','banjir','rescue','lainnya') COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `status` enum('masuk','proses','selesai') COLLATE utf8mb4_general_ci DEFAULT 'masuk',
  `waktu_proses` timestamp NULL DEFAULT NULL,
  `waktu_selesai` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_kejadian`
--

INSERT INTO `laporan_kejadian` (`id`, `nomor_laporan`, `tanggal`, `pelapor`, `no_hp`, `lokasi`, `jenis_kejadian`, `deskripsi`, `status`, `waktu_proses`, `waktu_selesai`, `created_at`, `updated_at`) VALUES
(4, 'LP-20260603-001', '2026-06-03', 'Annisa', '081234567890', 'Jln. Aru Jaya, No. 99', 'rescue', 'kucingg', 'proses', NULL, NULL, '2026-06-03 08:35:05', '2026-06-05 03:58:24'),
(5, 'LP-20260605-001', '2026-06-05', 'Melani', '082385591335', 'Jln.Lubuk Begalung Nan XX No. 5', 'kebakaran', '', 'proses', NULL, NULL, '2026-06-05 03:47:06', '2026-06-05 04:04:40'),
(6, 'LP-20260605-002', '2026-06-05', 'caca', '089976897765', 'Jln. Aru Jaya, No. 99', 'banjir', 'banjirrrr', 'proses', NULL, NULL, '2026-06-05 08:47:31', '2026-06-05 08:51:42'),
(7, 'LP-20260610-001', '2026-06-10', 'Yaya', '082346578807', 'Jln. Gajah Mada, No. 88', 'rescue', 'Biawak Masuk', 'proses', NULL, NULL, '2026-06-10 06:11:54', '2026-06-10 07:44:06');

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `aktivitas` text COLLATE utf8mb4_general_ci NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penempatan_pos`
--

CREATE TABLE `penempatan_pos` (
  `id` int NOT NULL,
  `nama_personil` varchar(50) NOT NULL,
  `pos_penempatan` varchar(50) NOT NULL,
  `tanggal_penempatan` date NOT NULL,
  `masa_penugasan` varchar(100) NOT NULL,
  `status` enum('Aktif','Non-Aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penempatan_pos`
--

INSERT INTO `penempatan_pos` (`id`, `nama_personil`, `pos_penempatan`, `tanggal_penempatan`, `masa_penugasan`, `status`) VALUES
(1, 'Melani Putri', 'Pos Kuranji', '2026-05-01', '1 Tahun', 'Aktif'),
(2, 'Yana Manora', 'Pos Pusat', '2026-03-05', '8 Bulan', 'Aktif'),
(6, 'Indri Natalia Siregar', 'Pos Bungus', '2026-05-21', '9 Bulan', 'Aktif'),
(7, 'Dina Olivia', 'Pos Bungus', '2026-01-10', '2 Tahun', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `rekap_kejadian`
--

CREATE TABLE `rekap_kejadian` (
  `id` int NOT NULL,
  `bulan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `tahun` year NOT NULL,
  `jumlah_kebakaran` int NOT NULL,
  `jumlah_rescue` int NOT NULL,
  `kerugian` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekap_kejadian`
--

INSERT INTO `rekap_kejadian` (`id`, `bulan`, `tahun`, `jumlah_kebakaran`, `jumlah_rescue`, `kerugian`) VALUES
(3, 'Mei', 2026, 1, 2, '10000000.00'),
(4, 'Maret', 2029, 10, 34, '9999999999999.99'),
(5, 'Februari', 2019, 2, 4, '1000.00');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_tugas`
--

CREATE TABLE `riwayat_tugas` (
  `id` int NOT NULL,
  `nama_personil` varchar(50) NOT NULL,
  `tanggal_tugas` date NOT NULL,
  `durasi_jam` int NOT NULL,
  `kejadian_ditangani` int NOT NULL,
  `rating` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sarpras`
--

CREATE TABLE `sarpras` (
  `id_sarpras` int NOT NULL,
  `nama_alat` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `kondisi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lokasi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sarpras`
--

INSERT INTO `sarpras` (`id_sarpras`, `nama_alat`, `jenis`, `kondisi`, `lokasi`) VALUES
(3, 'Selang Pemadam', 'Peralatan Pemadam', 'Baik', 'Gudang Damkar Padang'),
(14, 'APAR', 'Alat Pemadam', 'Baik', 'Posko Damkar');

-- --------------------------------------------------------

--
-- Table structure for table `spt`
--

CREATE TABLE `spt` (
  `id` int NOT NULL,
  `nomor_spt` varchar(30) NOT NULL,
  `laporan_kejadian_id` int NOT NULL,
  `nama_regu` varchar(50) NOT NULL,
  `waktu_keberangkatan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('berangkat','tiba','selesai','bata') NOT NULL DEFAULT 'berangkat'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `spt`
--

INSERT INTO `spt` (`id`, `nomor_spt`, `laporan_kejadian_id`, `nama_regu`, `waktu_keberangkatan`, `status`) VALUES
(1, 'SPT-2026-001', 4, 'REGU ALPHA', '2026-06-05 03:58:24', 'berangkat'),
(2, 'SPT-2026-002', 5, 'REGU ALPHA', '2026-06-05 04:04:40', 'berangkat'),
(3, 'SPT-2026-003', 6, 'REGU BRAVO', '2026-06-05 08:51:42', 'berangkat'),
(4, 'SPT-2026-004', 7, 'REGU ALPHA', '2026-06-10 06:12:16', 'berangkat');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_daftar`
--

CREATE TABLE `tbl_daftar` (
  `id` int NOT NULL,
  `nip` varchar(20) NOT NULL,
  `nama_personil` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tbl_daftar`
--

INSERT INTO `tbl_daftar` (`id`, `nip`, `nama_personil`, `jabatan`, `telepon`, `email`, `tanggal_lahir`, `status`) VALUES
(1, 'PK-0460-164', 'Melani Putri', 'Petugas', '082385591335', 'putrimelani464@gmail.com', '2005-04-16', 'Aktif'),
(2, 'PK-0460-111', 'Yana Manora', 'Petugas', '081123765377', 'yanamanora111@gmail.com', '1999-01-11', 'Aktif'),
(7, 'PK-0460-211', 'Annisa Bilqis', 'Petugas', '085233445568', 'annisabilqis211@gmail.com', '2012-11-21', 'Aktif'),
(8, 'PK-0456-156', 'Indri Natalia Siregar', 'Pengemudi', '082345678910', 'indrinatalia@gmail.com', '2005-06-15', 'Aktif'),
(10, 'PK-0449-149', 'Dina Olivia', 'Pengemudi', '085186734533', 'dinaolivia@gmail.com', '2005-09-10', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `nama` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `opd_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `armada`
--
ALTER TABLE `armada`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bidang`
--
ALTER TABLE `bidang`
  ADD PRIMARY KEY (`id_bidang`);

--
-- Indexes for table `hydrant`
--
ALTER TABLE `hydrant`
  ADD PRIMARY KEY (`id_hydrant`);

--
-- Indexes for table `jadwal_piket`
--
ALTER TABLE `jadwal_piket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `laporan_kejadian`
--
ALTER TABLE `laporan_kejadian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penempatan_pos`
--
ALTER TABLE `penempatan_pos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rekap_kejadian`
--
ALTER TABLE `rekap_kejadian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riwayat_tugas`
--
ALTER TABLE `riwayat_tugas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sarpras`
--
ALTER TABLE `sarpras`
  ADD PRIMARY KEY (`id_sarpras`);

--
-- Indexes for table `spt`
--
ALTER TABLE `spt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `spt_laporan_kejadian` (`laporan_kejadian_id`);

--
-- Indexes for table `tbl_daftar`
--
ALTER TABLE `tbl_daftar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `armada`
--
ALTER TABLE `armada`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bidang`
--
ALTER TABLE `bidang`
  MODIFY `id_bidang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hydrant`
--
ALTER TABLE `hydrant`
  MODIFY `id_hydrant` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_piket`
--
ALTER TABLE `jadwal_piket`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `laporan_kejadian`
--
ALTER TABLE `laporan_kejadian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penempatan_pos`
--
ALTER TABLE `penempatan_pos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rekap_kejadian`
--
ALTER TABLE `rekap_kejadian`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `riwayat_tugas`
--
ALTER TABLE `riwayat_tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sarpras`
--
ALTER TABLE `sarpras`
  MODIFY `id_sarpras` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `spt`
--
ALTER TABLE `spt`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_daftar`
--
ALTER TABLE `tbl_daftar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `spt`
--
ALTER TABLE `spt`
  ADD CONSTRAINT `spt_laporan_kejadian` FOREIGN KEY (`laporan_kejadian_id`) REFERENCES `laporan_kejadian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
