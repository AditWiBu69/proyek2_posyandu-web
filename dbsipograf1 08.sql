-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Waktu pembuatan: 08 Jan 2026 pada 09.19
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
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `masuk`
--

INSERT INTO `masuk` (`id_user`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', 'admin123', 'Administrator Posyandu', 'admin'),
(2, 'ratnasari', '12345', 'Ratna Sari', 'user'),
(3, 'dewi_ayu', '12345', 'Dewi Ayu Lestari', 'user'),
(4, 'susi_susanti', '12345', 'Susi Susanti', 'user');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_anak`
--

CREATE TABLE `t_anak` (
  `id_anak` int(11) NOT NULL,
  `nama_anak` varchar(100) NOT NULL,
  `id_orangtua` int(11) DEFAULT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_anak`
--

INSERT INTO `t_anak` (`id_anak`, `nama_anak`, `id_orangtua`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`) VALUES
(1, 'Rizky Pratama', 1, 'Bandung', '2023-01-15', 'Laki-laki', 'Jl. Merpati No. 10, RT 01/RW 03'),
(2, 'Alya Kinanti', 1, 'Bandung', '2025-02-10', 'Perempuan', 'Jl. Merpati No. 10, RT 01/RW 03'),
(3, 'Bagas Kara', 2, 'Jakarta', '2022-06-20', 'Laki-laki', 'Jl. Kenari Blok B, RT 05/RW 02'),
(4, 'Citra Kirana', 3, 'Surabaya', '2024-03-05', 'Perempuan', 'Gg. Melati Putih No. 5'),
(5, 'Doni Tata', 4, 'Semarang', '2023-11-11', 'Laki-laki', 'Jl. Anggrek Raya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_galeri`
--

CREATE TABLE `t_galeri` (
  `id_galeri` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_galeri`
--

INSERT INTO `t_galeri` (`id_galeri`, `judul`, `nama_file`, `tanggal_upload`, `keterangan`) VALUES
(5, 'tes', 'galeri_1767802077.jpg', '2026-01-07 16:07:57', NULL),
(6, 'tes2', 'galeri_1767802285.jpg', '2026-01-07 16:11:25', 'tes2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_jadwal`
--

CREATE TABLE `t_jadwal` (
  `id_jadwal` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` varchar(50) NOT NULL,
  `tempat` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_jadwal`
--

INSERT INTO `t_jadwal` (`id_jadwal`, `nama_kegiatan`, `tanggal`, `waktu`, `tempat`, `deskripsi`, `created_at`) VALUES
(1, 'Pemberian Vitamin A', '2026-02-10', '08:00 - 11:00', 'Posyandu Melati 1', 'Pembagian vitamin A gratis untuk balita usia 6-59 bulan.', '2026-01-07 15:24:10'),
(2, 'Imunisasi Campak & Polio', '2026-02-24', '09:00 - 12:00', 'Balai Desa Suka Maju', 'Wajib membawa buku KIA.', '2026-01-07 15:24:10'),
(3, 'Penyuluhan Gizi Buruk', '2026-03-05', '08:30 - 11:30', 'Aula Puskesmas', 'Edukasi MPASI yang sehat untuk mencegah stunting.', '2026-01-07 15:24:10'),
(4, 'Penimbangan Rutin Januari', '2026-01-15', '08:00 - 11:00', 'Posyandu Melati 1', 'Kegiatan rutin bulanan selesai dilaksanakan.', '2026-01-07 15:24:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_orangtua`
--

CREATE TABLE `t_orangtua` (
  `id_orangtua` int(11) NOT NULL,
  `nama_ibu` varchar(100) NOT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `alamat_ortu` text NOT NULL,
  `no_hp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_orangtua`
--

INSERT INTO `t_orangtua` (`id_orangtua`, `nama_ibu`, `nama_ayah`, `alamat_ortu`, `no_hp`) VALUES
(1, 'Ratna Sari', 'Budi Santoso', 'Jl. Merpati No. 10, RT 01/RW 03', '081234567890'),
(2, 'Dewi Ayu Lestari', 'Andi Pratama', 'Jl. Kenari Blok B, RT 05/RW 02', '085678901234'),
(3, 'Susi Susanti', 'Hendra Wijaya', 'Gg. Melati Putih No. 5', '089988776655'),
(4, 'Mawar Melati', 'Joko Anwar', 'Jl. Anggrek Raya (Warga Pendatang)', '081122334455');

-- --------------------------------------------------------

--
-- Struktur dari tabel `t_pendaftaran`
--

CREATE TABLE `t_pendaftaran` (
  `id_daftar` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_jadwal` int(11) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `t_pendaftaran`
--

INSERT INTO `t_pendaftaran` (`id_daftar`, `id_user`, `id_jadwal`, `tgl_daftar`) VALUES
(1, 2, 1, '2026-01-20 01:00:00'),
(2, 2, 2, '2026-01-20 01:05:00'),
(3, 3, 1, '2026-01-21 02:00:00'),
(4, 4, 3, '2026-01-22 03:00:00');

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
(1, 1, '2023-02-15', 1, 4.2, 54, 'Sehat, ASI Eksklusif', 'Bidan Ani', 'Posyandu Melati 1'),
(2, 1, '2023-03-15', 2, 5, 58, 'Normal', 'Bidan Ani', 'Posyandu Melati 1'),
(3, 1, '2023-06-15', 5, 7.1, 64, 'Berat Badan Naik', 'Kader Siti', 'Posyandu Melati 1'),
(4, 1, '2024-01-15', 12, 9.5, 75, 'Tumbuh Kembang Baik', 'Bidan Ani', 'Posyandu Melati 1'),
(5, 3, '2022-07-20', 1, 4.5, 55, 'Normal', 'Bidan Rina', 'Posyandu Mawar'),
(6, 3, '2023-06-20', 12, 9.8, 76, 'Sehat', 'Bidan Rina', 'Posyandu Mawar'),
(7, 3, '2024-06-20', 24, 12.2, 87, 'Gizi Baik', 'Kader Yuli', 'Posyandu Mawar'),
(8, 4, '2024-04-05', 1, 3.8, 52, 'ASI Eksklusif', 'Bidan Ani', 'Posyandu Melati 1'),
(9, 4, '2024-05-05', 2, 4.5, 56, 'Naik', 'Bidan Ani', 'Posyandu Melati 1'),
(10, 2, '2026-01-07', 5, 10, 55, 'vaksin', 'titin', 'ciwidey');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_laporan_kegiatan`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_laporan_kegiatan` (
`id_daftar` int(11)
,`nama_kegiatan` varchar(255)
,`tgl_kegiatan` date
,`waktu` varchar(50)
,`tempat` varchar(100)
,`nama_pendaftar` varchar(100)
,`tgl_daftar` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_laporan_tumbuh_kembang`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_laporan_tumbuh_kembang` (
`id_penimbangan` int(11)
,`nama_anak` varchar(100)
,`jenis_kelamin` enum('Laki-laki','Perempuan')
,`tanggal_lahir` date
,`nama_ibu` varchar(100)
,`alamat_ortu` text
,`tgl_penimbangan` date
,`umur_bulan` int(2)
,`berat_badan` float
,`tinggi_badan` float
,`keterangan` text
,`petugas` varchar(100)
,`posyandu` varchar(100)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_laporan_kegiatan`
--
DROP TABLE IF EXISTS `v_laporan_kegiatan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan_kegiatan`  AS SELECT `d`.`id_daftar` AS `id_daftar`, `j`.`nama_kegiatan` AS `nama_kegiatan`, `j`.`tanggal` AS `tgl_kegiatan`, `j`.`waktu` AS `waktu`, `j`.`tempat` AS `tempat`, `u`.`nama_lengkap` AS `nama_pendaftar`, `d`.`tgl_daftar` AS `tgl_daftar` FROM ((`t_pendaftaran` `d` join `t_jadwal` `j` on(`d`.`id_jadwal` = `j`.`id_jadwal`)) join `masuk` `u` on(`d`.`id_user` = `u`.`id_user`)) ORDER BY `j`.`tanggal` DESC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_laporan_tumbuh_kembang`
--
DROP TABLE IF EXISTS `v_laporan_tumbuh_kembang`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan_tumbuh_kembang`  AS SELECT `p`.`id_penimbangan` AS `id_penimbangan`, `a`.`nama_anak` AS `nama_anak`, `a`.`jenis_kelamin` AS `jenis_kelamin`, `a`.`tanggal_lahir` AS `tanggal_lahir`, `o`.`nama_ibu` AS `nama_ibu`, `o`.`alamat_ortu` AS `alamat_ortu`, `p`.`tgl_penimbangan` AS `tgl_penimbangan`, `p`.`umur` AS `umur_bulan`, `p`.`berat_badan` AS `berat_badan`, `p`.`tinggi_badan` AS `tinggi_badan`, `p`.`keterangan` AS `keterangan`, `p`.`petugas` AS `petugas`, `p`.`posyandu` AS `posyandu` FROM ((`t_penimbangan` `p` join `t_anak` `a` on(`p`.`id_anak` = `a`.`id_anak`)) left join `t_orangtua` `o` on(`a`.`id_orangtua` = `o`.`id_orangtua`)) ORDER BY `p`.`tgl_penimbangan` DESC ;

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
  ADD PRIMARY KEY (`id_anak`),
  ADD KEY `fk_anak_orangtua` (`id_orangtua`);

--
-- Indeks untuk tabel `t_galeri`
--
ALTER TABLE `t_galeri`
  ADD PRIMARY KEY (`id_galeri`);

--
-- Indeks untuk tabel `t_jadwal`
--
ALTER TABLE `t_jadwal`
  ADD PRIMARY KEY (`id_jadwal`);

--
-- Indeks untuk tabel `t_orangtua`
--
ALTER TABLE `t_orangtua`
  ADD PRIMARY KEY (`id_orangtua`);

--
-- Indeks untuk tabel `t_pendaftaran`
--
ALTER TABLE `t_pendaftaran`
  ADD PRIMARY KEY (`id_daftar`),
  ADD KEY `fk_daftar_user` (`id_user`),
  ADD KEY `fk_daftar_jadwal` (`id_jadwal`);

--
-- Indeks untuk tabel `t_penimbangan`
--
ALTER TABLE `t_penimbangan`
  ADD PRIMARY KEY (`id_penimbangan`),
  ADD KEY `fk_penimbangan_anak` (`id_anak`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `masuk`
--
ALTER TABLE `masuk`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_anak`
--
ALTER TABLE `t_anak`
  MODIFY `id_anak` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `t_galeri`
--
ALTER TABLE `t_galeri`
  MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `t_jadwal`
--
ALTER TABLE `t_jadwal`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_orangtua`
--
ALTER TABLE `t_orangtua`
  MODIFY `id_orangtua` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_pendaftaran`
--
ALTER TABLE `t_pendaftaran`
  MODIFY `id_daftar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `t_penimbangan`
--
ALTER TABLE `t_penimbangan`
  MODIFY `id_penimbangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `t_anak`
--
ALTER TABLE `t_anak`
  ADD CONSTRAINT `fk_anak_orangtua` FOREIGN KEY (`id_orangtua`) REFERENCES `t_orangtua` (`id_orangtua`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_pendaftaran`
--
ALTER TABLE `t_pendaftaran`
  ADD CONSTRAINT `fk_daftar_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `t_jadwal` (`id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_daftar_user` FOREIGN KEY (`id_user`) REFERENCES `masuk` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `t_penimbangan`
--
ALTER TABLE `t_penimbangan`
  ADD CONSTRAINT `fk_penimbangan_anak` FOREIGN KEY (`id_anak`) REFERENCES `t_anak` (`id_anak`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
