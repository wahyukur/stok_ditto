-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Bulan Mei 2020 pada 14.46
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
(5, 'Air', 'GalonAqua', '2020-04-26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keluar_bahan_detail`
--

CREATE TABLE `keluar_bahan_detail` (
  `id_bahan_keluar` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `id_keluar` varchar(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `qty_total` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keluar_bahan_detail`
--

INSERT INTO `keluar_bahan_detail` (`id_bahan_keluar`, `id`, `id_keluar`, `id_menu`, `id_bahan`, `qty_total`, `id_unit`) VALUES
(27, 14, 'OUT0320002', 2, 1, 100, 11),
(28, 14, 'OUT0320002', 2, 3, 1, 7),
(31, 16, 'OUT0320003', 2, 1, 100, 11),
(32, 16, 'OUT0320003', 2, 3, 1, 7),
(33, 17, 'OUT0420001', 2, 1, 100, 11),
(34, 17, 'OUT0420001', 2, 3, 1, 7),
(35, 18, 'OUT0420002', 2, 1, 100, 11),
(36, 18, 'OUT0420002', 2, 3, 1, 7),
(37, 19, 'OUT0420003', 2, 1, 100, 11),
(38, 19, 'OUT0420003', 2, 3, 1, 7),
(39, 20, 'OUT0520001', 2, 1, 100, 11),
(40, 20, 'OUT0520001', 2, 3, 1, 7),
(41, 21, 'OUT0520002', 2, 1, 100, 11),
(42, 21, 'OUT0520002', 2, 3, 1, 7),
(43, 22, 'XOUT0520005', 0, 1, 1, 9),
(44, 23, 'XOUT0520006', 0, 1, 1000, 11),
(45, 24, 'XOUT0520007', 0, 1, 1, 9),
(46, 25, 'XOUT0520008', 0, 1, 1000, 11);

--
-- Trigger `keluar_bahan_detail`
--
DELIMITER $$
CREATE TRIGGER `tg_deleteUpStok` AFTER DELETE ON `keluar_bahan_detail` FOR EACH ROW BEGIN
	DECLARE jumlah INT;
    SET jumlah = (SELECT jumlah_bahan FROM stok_bahan WHERE id_bahan = old.id_bahan AND id_unit = old.id_unit);
    
    UPDATE stok_bahan 
    SET jumlah_bahan = jumlah + old.qty_total 
    WHERE id_bahan = old.id_bahan AND id_unit = old.id_unit;
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_stok_insert` AFTER INSERT ON `keluar_bahan_detail` FOR EACH ROW BEGIN
     DECLARE jumlah INT;
     DECLARE tanggal datetime;
     DECLARE endQty INT;
     
     SET jumlah = (SELECT jumlah_bahan FROM stok_bahan WHERE id_bahan = new.id_bahan AND id_unit = new.id_unit);
     SET tanggal = (select tanggal_keluar from laporan_keluar where id_keluar = new.id_keluar);
     SET endQty = jumlah - new.qty_total;
     
     INSERT INTO `stok_movement`(`id_move`, `tanggal_trans`, `nomor_trans`, `detail_trans`, `id_bahan`, `begin_qty`, `masuk`, `keluar`, `id_unit`, `end_qty`) 
     VALUES ('',tanggal,new.id_keluar,new.id,new.id_bahan,jumlah,0,new.qty_total,new.id_unit,endQty);
     
     UPDATE stok_bahan 
     SET jumlah_bahan = endQty, changed_date = current_date() 
   	 WHERE id_bahan = new.id_bahan AND id_unit = new.id_unit;
     
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `keluar_detail`
--

CREATE TABLE `keluar_detail` (
  `id` int(11) NOT NULL,
  `id_keluar` varchar(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `keluar_detail`
--

INSERT INTO `keluar_detail` (`id`, `id_keluar`, `id_menu`, `qty`) VALUES
(14, 'OUT0320002', 2, 1),
(16, 'OUT0320003', 2, 1),
(17, 'OUT0420001', 2, 1),
(18, 'OUT0420002', 2, 1),
(19, 'OUT0420003', 2, 1),
(20, 'OUT0520001', 2, 1),
(21, 'OUT0520002', 2, 1),
(22, 'XOUT0520005', 0, 0),
(23, 'XOUT0520006', 0, 0),
(24, 'XOUT0520007', 0, 0),
(25, 'XOUT0520008', 0, 0);

--
-- Trigger `keluar_detail`
--
DELIMITER $$
CREATE TRIGGER `tg_delete_BahanKeluar` AFTER DELETE ON `keluar_detail` FOR EACH ROW BEGIN
    
    DELETE FROM keluar_bahan_detail 
	WHERE id = old.id and id_keluar = old.id_keluar; 
    
    DELETE FROM stok_movement 
	WHERE detail_trans = old.id and nomor_trans = old.id_keluar;
    
END
$$
DELIMITER ;

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
('OUT0320002', '2020-03-06'),
('OUT0320003', '2020-03-20'),
('OUT0420001', '2020-04-16'),
('OUT0420002', '2020-04-21'),
('OUT0420003', '2020-04-22'),
('OUT0520001', '2020-05-01'),
('OUT0520002', '2020-05-05'),
('XOUT0520003', '2020-05-02'),
('XOUT0520004', '2020-05-02'),
('XOUT0520005', '2020-05-02'),
('XOUT0520006', '2020-05-02'),
('XOUT0520007', '2020-05-02'),
('XOUT0520008', '2020-05-02');

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
('EXIN0520001', '2020-05-02'),
('EXIN0520002', '2020-05-02'),
('EXIN0520003', '2020-05-02'),
('EXIN0520004', '2020-05-02'),
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
(20, 'IN0320004', 1, 100, 9),
(25, 'IN0320004', 1, 500, 11),
(26, 'IN0320004', 1, 100, 9),
(27, 'IN0320004', 2, 10000, 6),
(28, 'EXIN0520001', 1, 1000, 11),
(29, 'EXIN0520002', 1, 1000, 9),
(30, 'EXIN0520003', 1, 1000, 11),
(31, 'EXIN0520004', 1, 1, 9);

--
-- Trigger `masuk_detail`
--
DELIMITER $$
CREATE TRIGGER `tg_cancel_stok` AFTER DELETE ON `masuk_detail` FOR EACH ROW BEGIN 
    
    UPDATE stok_bahan 
    SET jumlah_bahan = jumlah_bahan - old.qty 
    WHERE id_bahan = old.id_bahan and id_unit = old.id_unit; 
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tg_insert_stok` AFTER INSERT ON `masuk_detail` FOR EACH ROW BEGIN 
    
    DECLARE jumlah integer;
    DECLARE cek integer;
    DECLARE tanggal datetime;
    DECLARE endQty INT;
    
    SET jumlah = (SELECT jumlah_bahan FROM stok_bahan 
                  WHERE id_bahan = new.id_bahan and id_unit = new.id_unit);
    SET cek = (SELECT count(*) FROM stok_bahan 
                  WHERE id_bahan = new.id_bahan and id_unit = new.id_unit);
    SET tanggal = (select tanggal_masuk from laporan_masuk where id_masuk = new.id_masuk);
    SET endQty = jumlah + new.qty;
    
    INSERT INTO `stok_movement`(`id_move`, `tanggal_trans`, `nomor_trans`, `detail_trans`, `id_bahan`, `begin_qty`, `masuk`, `keluar`, `id_unit`, `end_qty`) 
     VALUES ('',tanggal,new.id_masuk,new.id,new.id_bahan,jumlah,new.qty,0,new.id_unit,endQty);
     
    IF cek = 0 THEN 
        INSERT INTO `stok_bahan`
        (`id_bahan`, `jumlah_bahan`, `id_unit`, `changed_date`) 
        SELECT id_bahan, qty as 'jumlah_bahan', id_unit, CURDATE() as 'changed_date' 
        FROM masuk_detail 
        WHERE id_bahan = new.id_bahan 
        and qty = new.qty 
        and id_unit = new.id_unit; 
    ELSE 
    	UPDATE stok_bahan 
		SET jumlah_bahan = jumlah + new.qty  
		WHERE id_bahan = new.id_bahan 
        AND id_unit = new.id_unit; 
    END IF;   
    
END
$$
DELIMITER ;

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
-- Struktur dari tabel `stok_bahan`
--

CREATE TABLE `stok_bahan` (
  `id_stok` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `jumlah_bahan` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `changed_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `stok_bahan`
--

INSERT INTO `stok_bahan` (`id_stok`, `id_bahan`, `jumlah_bahan`, `id_unit`, `changed_date`) VALUES
(1, 2, 10343, 6, '2020-04-12'),
(2, 3, 3, 7, '2020-04-18'),
(3, 1, 0, 11, '2020-05-02'),
(4, 1, 1199, 9, '2020-05-02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_movement`
--

CREATE TABLE `stok_movement` (
  `id_move` int(11) NOT NULL,
  `tanggal_trans` date NOT NULL,
  `nomor_trans` varchar(11) NOT NULL,
  `detail_trans` int(11) NOT NULL,
  `id_bahan` int(11) NOT NULL,
  `begin_qty` int(11) NOT NULL,
  `masuk` int(11) NOT NULL,
  `keluar` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `end_qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `stok_movement`
--

INSERT INTO `stok_movement` (`id_move`, `tanggal_trans`, `nomor_trans`, `detail_trans`, `id_bahan`, `begin_qty`, `masuk`, `keluar`, `id_unit`, `end_qty`) VALUES
(7, '2020-03-06', 'OUT0320002', 14, 1, 200, 0, 100, 11, 100),
(8, '2020-03-06', 'OUT0320002', 14, 3, 10, 0, 1, 7, 9),
(11, '2020-03-20', 'OUT0320003', 16, 1, 100, 0, 100, 11, 0),
(12, '2020-03-20', 'OUT0320003', 16, 3, 9, 0, 1, 7, 8),
(13, '2020-03-04', 'IN0320004', 25, 1, 0, 500, 0, 11, 500),
(14, '2020-04-16', 'OUT0420001', 17, 1, 500, 0, 100, 11, 400),
(15, '2020-04-16', 'OUT0420001', 17, 3, 8, 0, 1, 7, 7),
(16, '2020-04-21', 'OUT0420002', 18, 1, 400, 0, 100, 11, 300),
(17, '2020-04-21', 'OUT0420002', 18, 3, 7, 0, 1, 7, 6),
(18, '2020-04-22', 'OUT0420003', 19, 1, 300, 0, 100, 11, 200),
(19, '2020-04-22', 'OUT0420003', 19, 3, 6, 0, 1, 7, 5),
(20, '2020-05-01', 'OUT0520001', 20, 1, 200, 0, 100, 11, 100),
(21, '2020-05-01', 'OUT0520001', 20, 3, 5, 0, 1, 7, 4),
(22, '2020-05-05', 'OUT0520002', 21, 1, 100, 0, 100, 11, 0),
(23, '2020-05-05', 'OUT0520002', 21, 3, 4, 0, 1, 7, 3),
(24, '2020-03-04', 'IN0320004', 26, 1, 100, 100, 0, 9, 200),
(25, '2020-03-04', 'IN0320004', 27, 2, 343, 10000, 0, 6, 10343),
(26, '2020-05-02', 'XOUT0520005', 22, 1, 200, 0, 1, 9, 199),
(27, '2020-05-02', 'EXIN0520001', 28, 1, 0, 1000, 0, 11, 1000),
(28, '2020-05-02', 'XOUT0520006', 23, 1, 1000, 0, 1000, 11, 0),
(29, '2020-05-02', 'EXIN0520002', 29, 1, 199, 1000, 0, 9, 1199),
(30, '2020-05-02', 'XOUT0520007', 24, 1, 1199, 0, 1, 9, 1198),
(31, '2020-05-02', 'EXIN0520003', 30, 1, 0, 1000, 0, 11, 1000),
(32, '2020-05-02', 'XOUT0520008', 25, 1, 1000, 0, 1000, 11, 0),
(33, '2020-05-02', 'EXIN0520004', 31, 1, 1198, 1, 0, 9, 1199);

--
-- Trigger `stok_movement`
--
DELIMITER $$
CREATE TRIGGER `tg_movement_update` AFTER DELETE ON `stok_movement` FOR EACH ROW BEGIN
     
     DECLARE cek_bahan INT;
     SET cek_bahan = (SELECT count(*) FROM stok_movement WHERE id_bahan = old.id_bahan AND id_unit = old.id_unit AND id_move > old.id_move);
     
     IF cek_bahan != 0 THEN 
     
     	UPDATE stok_movement a join keluar_bahan_detail b on a.detail_trans = a.id 
     	SET begin_qty = begin_qty + old.begin_qty 
   	 	WHERE id_bahan = old.id_bahan AND id_unit = old.id_unit AND id_move > old.id_move;
        
      END IF;
     
END
$$
DELIMITER ;

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
(11, 'Gram', 'KG', 1000),
(12, 'gram', 'GLKG', 150000),
(13, 'Galon', 'GalonAqua', 1),
(14, 'Liter', 'GalonAqua', 19),
(15, 'Mililiter', 'GalonAqua', 19000);

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
('GalonAqua', 'Galon Aqua', '2020-04-26'),
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
-- Indeks untuk tabel `keluar_bahan_detail`
--
ALTER TABLE `keluar_bahan_detail`
  ADD PRIMARY KEY (`id_bahan_keluar`);

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
-- Indeks untuk tabel `stok_bahan`
--
ALTER TABLE `stok_bahan`
  ADD PRIMARY KEY (`id_stok`);

--
-- Indeks untuk tabel `stok_movement`
--
ALTER TABLE `stok_movement`
  ADD PRIMARY KEY (`id_move`);

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
  MODIFY `id_bahan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `keluar_bahan_detail`
--
ALTER TABLE `keluar_bahan_detail`
  MODIFY `id_bahan_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `keluar_detail`
--
ALTER TABLE `keluar_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT untuk tabel `komposisi_menu`
--
ALTER TABLE `komposisi_menu`
  MODIFY `id_composition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `masuk_detail`
--
ALTER TABLE `masuk_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `stok_bahan`
--
ALTER TABLE `stok_bahan`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `stok_movement`
--
ALTER TABLE `stok_movement`
  MODIFY `id_move` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `unit`
--
ALTER TABLE `unit`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
