-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 12 Bulan Mei 2026 pada 16.32
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
-- Database: `app_damkar`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `armada`
--

CREATE TABLE `armada` (
  `id` int(11) NOT NULL,
  `kode_armada` varchar(20) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `pos_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_kejadian`
--

CREATE TABLE `laporan_kejadian` (
  `id` int(11) NOT NULL,
  `nomor_laporan` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `pelapor` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `lokasi` text NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `jenis_kejadian` enum('kebakaran','banjir','rescue','lainnya') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('masuk','proses','selesai') DEFAULT 'masuk',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `laporan_kejadian`
--

INSERT INTO `laporan_kejadian` (`id`, `nomor_laporan`, `tanggal`, `pelapor`, `no_hp`, `lokasi`, `latitude`, `longitude`, `jenis_kejadian`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(1, 'LPK-001', '2026-05-10', 'Putri Amelia', '081234567890', 'Lubuk Begalung', -0.1234500, 100.1123450, 'kebakaran', 'gas meledak', 'selesai', '2026-05-10 09:15:04', '2026-05-10 09:23:35'),
(2, '', '2026-05-10', 'indri', '08123455667', 'lubuk begalung', NULL, NULL, '', '', '', '2026-05-10 17:21:50', '2026-05-10 17:21:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `aktivitas` text NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekap_kejadian`
--

CREATE TABLE `rekap_kejadian` (
  `id` int(11) NOT NULL,
  `bulan` varchar(20) NOT NULL,
  `tahun` year(4) NOT NULL,
  `jumlah_kebakaran` int(11) NOT NULL,
  `jumlah_rescue` int(11) NOT NULL,
  `kerugian` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekap_kejadian`
--

INSERT INTO `rekap_kejadian` (`id`, `bulan`, `tahun`, `jumlah_kebakaran`, `jumlah_rescue`, `kerugian`) VALUES
(3, 'Mei', '2026', 1, 2, 10000000.00),
(4, 'Maret', '2029', 10, 34, 9999999999999.99),
(5, 'Februari', '2019', 2, 4, 1000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(50) NOT NULL,
  `role` varchar(13) NOT NULL,
  `opd_id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `armada`
--
ALTER TABLE `armada`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_kejadian`
--
ALTER TABLE `laporan_kejadian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rekap_kejadian`
--
ALTER TABLE `rekap_kejadian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `armada`
--
ALTER TABLE `armada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_kejadian`
--
ALTER TABLE `laporan_kejadian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekap_kejadian`
--
ALTER TABLE `rekap_kejadian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
