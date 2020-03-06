-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Mar 2020 pada 17.18
-- Versi server: 10.4.8-MariaDB
-- Versi PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stok_ditto`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan`
--

CREATE TABLE `bahan` (
  `id_bahan` int(11) NOT NULL,
  `nama_bahan` varchar(100) NOT NULL,
  `unit_groupid` varchar(20) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `bahan`
--

INSERT INTO `bahan` (`id_bahan`, `nama_bahan`, `unit_groupid`, `created_at`) VALUES
(1, 'Gula', 'KG', '2020-01-19'),
(2, 'Kopi Gayo', 'Kopi25L', '2020-01-19'),
(3, 'Galon Aqua', 'Galon', '2020-01-19'),
(4, 'gula kiloan', 'GLKG', '2020-02-28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keluar_detail`
--

CREATE TABLE `keluar_detail` (
  `id` int(11) NOT NULL,
  `id_keluar` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keluar_detail`
--

INSERT INTO `keluar_detail` (`id`, `id_keluar`, `id_menu`, `qty`) VALUES
(3, 0, 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `komposisi_menu`
--

CREATE TABLE `komposisi_menu` (
  `id_composition` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `unitid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `komposisi_menu`
--

INSERT INTO `komposisi_menu` (`id_composition`, `id_menu`, `id_bahan`, `jumlah`, `unitid`) VALUES
(3, 45, 2, 2, '2'),
(10, 1, 2, 2223, '6'),
(11, 1, 1, 1, '9'),
(12, 1, 3, 2, '7'),
(13, 2, 1, 100, '11'),
(14, 2, 3, 1, '7');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_keluar`
--

CREATE TABLE `laporan_keluar` (
  `id_keluar` varchar(11) NOT NULL,
  `tanggal_keluar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_keluar`
--

INSERT INTO `laporan_keluar` (`id_keluar`, `tanggal_keluar`) VALUES
('OUT0320001', '2020-03-06'),
('OUT0320002', '2020-03-06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_masuk`
--

CREATE TABLE `laporan_masuk` (
  `id_masuk` varchar(11) NOT NULL,
  `tanggal_masuk` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `laporan_masuk`
--

INSERT INTO `laporan_masuk` (`id_masuk`, `tanggal_masuk`) VALUES
('IN0320001', '2020-03-17'),
('IN0320002', '2020-03-03'),
('IN0320003', '2020-03-11'),
('IN0320004', '2020-03-04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `masuk_detail`
--

CREATE TABLE `masuk_detail` (
  `id` int(11) NOT NULL,
  `id_masuk` varchar(15) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `masuk_detail`
--

INSERT INTO `masuk_detail` (`id`, `id_masuk`, `id_bahan`, `qty`, `id_unit`) VALUES
(1, 'IN0320004', 1, 2, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `category`) VALUES
(1, 'Kopi Luwak', 'coffee'),
(2, 'es teh', 'squash');

-- --------------------------------------------------------

--
-- Struktur dari tabel `periode`
--

CREATE TABLE `periode` (
  `id_periode` int(11) NOT NULL,
  `description` varchar(30) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_bahan`
--

CREATE TABLE `stok_bahan` (
  `id_stok` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `jumlah_bahan` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `changed_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit`
--

CREATE TABLE `unit` (
  `id_unit` int(11) NOT NULL,
  `unitid` varchar(20) NOT NULL,
  `unit_groupid` varchar(10) NOT NULL,
  `convertion` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `unit`
--

INSERT INTO `unit` (`id_unit`, `unitid`, `unit_groupid`, `convertion`) VALUES
(6, 'Gram', 'Kopi25L', 25000),
(7, 'Liter', 'Galon', 19),
(8, 'Liter', 'Kopi25L', 256),
(9, 'Kilogram', 'KG', 1),
(11, 'Gram', 'KG', 200),
(12, 'gram', 'GLKG', 150000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit_group`
--

CREATE TABLE `unit_group` (
  `unit_groupid` varchar(20) NOT NULL,
  `description` varchar(50) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `unit_group`
--

INSERT INTO `unit_group` (`unit_groupid`, `description`, `created_at`) VALUES
('GLKG', 'gula kiloan 15 kg', '2020-02-28'),
('KG', 'Kilogram', '2020-01-19'),
('Kopi25L', 'Bungkus Kopi 25 Liter', '2020-01-19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `nama`, `username`, `password`) VALUES
(1, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan`
--
ALTER TABLE `bahan`
  ADD PRIMARY KEY (`id_bahan`);

--
-- Indeks untuk tabel `keluar_detail`
--
ALTER TABLE `keluar_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `komposisi_menu`
--
ALTER TABLE `komposisi_menu`
  ADD PRIMARY KEY (`id_composition`);

--
-- Indeks untuk tabel `laporan_keluar`
--
ALTER TABLE `laporan_keluar`
  ADD PRIMARY KEY (`id_keluar`);

--
-- Indeks untuk tabel `laporan_masuk`
--
ALTER TABLE `laporan_masuk`
  ADD PRIMARY KEY (`id_masuk`);

--
-- Indeks untuk tabel `masuk_detail`
--
ALTER TABLE `masuk_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indeks untuk tabel `stok_bahan`
--
ALTER TABLE `stok_bahan`
  ADD PRIMARY KEY (`id_stok`);

--
-- Indeks untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id_unit`);

--
-- Indeks untuk tabel `unit_group`
--
ALTER TABLE `unit_group`
  ADD PRIMARY KEY (`unit_groupid`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan`
--
ALTER TABLE `bahan`
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `keluar_detail`
--
ALTER TABLE `keluar_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `komposisi_menu`
--
ALTER TABLE `komposisi_menu`
  MODIFY `id_composition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `masuk_detail`
--
ALTER TABLE `masuk_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `periode`
--
ALTER TABLE `periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `stok_bahan`
--
ALTER TABLE `stok_bahan`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `unit`
--
ALTER TABLE `unit`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
