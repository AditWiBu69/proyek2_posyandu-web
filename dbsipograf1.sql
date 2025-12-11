-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 11 Des 2025 pada 08.03
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbsipograf1`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `masuk`
--

CREATE TABLE `masuk` (
  `id_user` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(8) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `masuk`
--

INSERT INTO `masuk` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin', 'Abc123', 'admin'),
(2, 'Ani Nursanti', '123', 'user'),
(4, 'Catur Wulandari', '123', 'user'),
(6, 'Sekar Ayuningtyas', '123', 'user'),
(7, 'Sulistyowati Eka', '123', 'user'),
(8, 'Kayla Restu', '123', 'user'),
(9, 'wan', '123', 'user'),
(10, 'momoka nishina', '123', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_anak`
--

CREATE TABLE `t_anak` (
  `id_anak` int(11) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan','','') NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_anak`
--

INSERT INTO `t_anak` (`id_anak`, `nama_anak`, `nama_ibu`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`) VALUES
(1, 'Aditya Putra Utama', 'Ani Nursanti', 'Sukoharjo', '2021-10-15', 'Laki-laki', 'Joho RT 1 RW 3'),
(11, 'Davin Muflih', 'Catur Wulandari', 'Sukoharjo', '2023-06-03', 'Laki-laki', 'Joho RT 1 RW 3 '),
(12, 'Guntur Permana', 'Sekar Ayuningtyas', 'Sukoharjo', '2023-01-09', 'Laki-laki', 'Joho RT 2 RW 2'),
(13, 'Nasya Nur Fitri', 'Sulistyowati Eka', 'Sukoharjo', '2023-04-10', 'Perempuan', 'Joho RT 1 RW 4'),
(15, 'Melinda Safira', 'Kayla Restu', 'Sukoharjo', '2023-07-10', 'Perempuan', 'Joho RT 2 RW 3'),
(16, 'ridwan', 'wan', 'solo', '2023-11-22', 'Laki-laki', 'jauh'),
(17, 'IQBAL', 'momoka nishina', 'isekai', '2004-03-28', 'Laki-laki', 'soul socity');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_penimbangan`
--

CREATE TABLE `t_penimbangan` (
  `id_penimbangan` int(11) NOT NULL,
  `id_anak` int(11) NOT NULL,
  `tgl_penimbangan` date NOT NULL,
  `umur` int(2) NOT NULL,
  `berat_badan` float NOT NULL,
  `tinggi_badan` float NOT NULL,
  `keterangan` text NOT NULL,
  `petugas` varchar(100) NOT NULL,
  `posyandu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_penimbangan`
--

INSERT INTO `t_penimbangan` (`id_penimbangan`, `id_anak`, `tgl_penimbangan`, `umur`, `berat_badan`, `tinggi_badan`, `keterangan`, `petugas`, `posyandu`) VALUES
(37, 1, '2021-10-17', 0, 3.5, 50, 'Asi Eksklusif, Imunisasi Hepatitis B', 'Rini', 'Posyandu Melati'),
(38, 1, '2021-11-17', 1, 5, 55, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(39, 1, '2021-12-17', 2, 6.7, 58, 'Asi Eksklusif, Imunisasi DPT', 'Rini', 'Posyandu Melati'),
(40, 1, '2022-01-17', 3, 7.2, 62, 'Asi Eksklusif, Imunisasi DPT', 'Yanti', 'Posyandu Melati'),
(41, 1, '2022-02-17', 4, 7.7, 64.2, 'Asi Eksklusif, Imunisasi DPT', 'Yanti', 'Posyandu Melati'),
(42, 1, '2022-03-17', 5, 8, 65.9, 'Asi Eksklusif', 'Yanti', 'Posyandu Melati'),
(43, 1, '2022-04-17', 6, 8.2, 67.6, 'Asi Eksklusif, Imunisasi Hepatitis B', 'Yanti', 'Posyandu Melati'),
(44, 1, '2022-05-18', 7, 8.1, 69.2, 'Asi Eksklusif', 'Yanti', 'Posyandu Melati'),
(45, 1, '2022-06-17', 8, 8.7, 70.6, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(46, 1, '2022-07-17', 9, 9.3, 85.1, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(47, 1, '2022-08-18', 10, 9.2, 73.3, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(48, 1, '2022-09-16', 11, 9, 75, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(49, 1, '2022-10-17', 12, 9.5, 76, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(50, 1, '2022-11-17', 13, 9.6, 76.9, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(51, 1, '2023-12-17', 14, 9.5, 78, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(52, 1, '2023-01-17', 15, 9.7, 79.1, 'Asi Eksklusif', 'Titin', 'Posyandu Melati'),
(53, 1, '2023-02-17', 16, 10, 80.2, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(54, 1, '2023-03-17', 17, 10.2, 81.2, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(55, 1, '2023-04-17', 18, 10.5, 82.3, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(56, 1, '2023-05-17', 19, 11, 83.2, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(57, 1, '2023-06-17', 20, 11.4, 84.2, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(58, 1, '2023-07-17', 21, 12, 85.3, 'Asi Eksklusif', 'Rini', 'Posyandu Melati'),
(59, 1, '2023-08-18', 22, 12.3, 86, 'Asi Eksklusif', 'Yanti', 'Posyandu Melati'),
(60, 1, '2023-09-18', 23, 12.5, 86.9, 'Asi Eksklusif', 'Yanti', 'Posyandu Melati'),
(61, 1, '2023-10-17', 24, 12.7, 87.8, 'Asi Eksklusif', 'Yanti', 'Posyandu Melati'),
(62, 11, '2023-11-17', 0, 5, 50, 'Asi Eksklusif', 'Tanti', 'Posyandu Melati'),
(63, 11, '2023-11-24', 1, 4, 56, '-', '-', '-'),
(64, 16, '2025-12-10', 1, 5, 55, 'Asi Eksklusif', 'Rini', 'Posyandu Melati');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `masuk`
--
ALTER TABLE `masuk`
  ADD PRIMARY KEY (`id_user`);

--
-- Indeks untuk tabel `t_anak`
--
ALTER TABLE `t_anak`
  ADD PRIMARY KEY (`id_anak`);

--
-- Indeks untuk tabel `t_penimbangan`
--
ALTER TABLE `t_penimbangan`
  ADD PRIMARY KEY (`id_penimbangan`),
  ADD KEY `data_penimbangan_ibfk_1` (`id_anak`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `masuk`
--
ALTER TABLE `masuk`
  MODIFY `id_user` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `t_anak`
--
ALTER TABLE `t_anak`
  MODIFY `id_anak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `t_penimbangan`
--
ALTER TABLE `t_penimbangan`
  MODIFY `id_penimbangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
