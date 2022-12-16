-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jan 2021 pada 16.52
-- Versi server: 10.4.6-MariaDB
-- Versi PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rohadi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akses_user`
--

CREATE TABLE `akses_user` (
  `id_akses` int(11) NOT NULL,
  `nama_akses` varchar(200) NOT NULL,
  `Aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `akses_user`
--

INSERT INTO `akses_user` (`id_akses`, `nama_akses`, `Aktif`) VALUES
(1, 'Admin', 1),
(2, 'Kasir', 1),
(3, 'PEMILIK', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `Kodebarang` varchar(10) NOT NULL,
  `NamaBarang` varchar(100) NOT NULL,
  `Deskripsi` varchar(200) NOT NULL,
  `Diskon` decimal(18,2) DEFAULT NULL,
  `Diskon_Jual` decimal(18,2) DEFAULT NULL,
  `Keuntungan_Persen` decimal(18,2) DEFAULT NULL,
  `Diskon_Reject` decimal(18,2) DEFAULT NULL,
  `Foto` varchar(100) DEFAULT NULL,
  `Aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`Kodebarang`, `NamaBarang`, `Deskripsi`, `Diskon`, `Diskon_Jual`, `Keuntungan_Persen`, `Diskon_Reject`, `Foto`, `Aktif`) VALUES
('KBR2012001', 'SX 123 BGE KW1', 'kw1', '0.00', '2.00', '10.00', '5.00', 'KBR2012001.jpg', 1),
('KBR2012002', 'HTG 123 BROWN KW1', 'kw1 ukuran 60x60', '0.00', '3.00', '10.00', '4.00', 'KBR2012002.jpg', 1),
('KBR2012003', 'SZ 8888 KW1', 'kw1', '0.00', '2.00', '10.00', '5.00', 'KBR2012003.jpg', 1),
('KBR2012004', 'SL 1234 KW1', 'kw1', '0.00', '2.00', '10.00', '5.00', 'KBR2012004.jpg', 1),
('KBR2012005', 'R123 KW1', 'kw1 rowman', '0.00', '2.00', '10.00', '5.00', 'KBR2012005.jpg', 1),
('KBR2012006', 'SX 567 KW1', 'kw1', '0.00', '2.00', '10.00', '0.00', 'KBR2012006.jpg', 1),
('KBR2012007', 'TEST', 'test', '0.00', '2.00', '10.00', '0.00', 'KBR2012007.png', 1),
('KBR2101008', 'HTG SEVIA BEIGE KW 1', 'kw 1', '0.00', '2.00', '10.00', '2.00', 'KBR2101008.png', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `NomorBarangKeluar` varchar(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `Jenis` varchar(10) NOT NULL,
  `Jenis_Jual` varchar(20) DEFAULT NULL,
  `Tglinput` datetime NOT NULL,
  `UserInput` varchar(10) NOT NULL,
  `id_ongkir` int(11) NOT NULL,
  `jumlah_min_order` decimal(18,2) NOT NULL,
  `harga_ongkir` decimal(18,2) NOT NULL,
  `Total_Harga` decimal(18,2) NOT NULL,
  `Total_Ongkir` decimal(18,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `barang_keluar`
--

INSERT INTO `barang_keluar` (`NomorBarangKeluar`, `id_customer`, `Tanggal`, `Jenis`, `Jenis_Jual`, `Tglinput`, `UserInput`, `id_ongkir`, `jumlah_min_order`, `harga_ongkir`, `Total_Harga`, `Total_Ongkir`) VALUES
('BK201230002', 3, '2020-12-30', 'MASUK', NULL, '2020-12-30 16:48:35', 'PG20120001', 0, '0.00', '0.00', '0.00', '0.00'),
('BK201230003', 1, '2020-12-30', 'MASUK', NULL, '2020-12-30 16:48:50', 'PG20120001', 0, '0.00', '0.00', '0.00', '0.00'),
('BK201230004', 1, '2020-12-30', 'REJECT', NULL, '2020-12-30 16:56:34', 'PG20120001', 0, '0.00', '0.00', '0.00', '0.00'),
('BK201230005', 1, '2020-12-30', 'REJECT', NULL, '2020-12-30 17:01:27', 'PG20120001', 0, '0.00', '0.00', '0.00', '0.00'),
('BK201231006', 2, '2020-12-31', 'MASUK', NULL, '2020-12-31 12:47:42', 'PG20120001', 0, '0.00', '0.00', '0.00', '0.00'),
('BK210105007', 2, '2021-01-05', 'MASUK', NULL, '2021-01-05 17:34:40', 'PG20120001', 0, '0.00', '0.00', '0.00', '0.00'),
('BK210105008', 2, '2021-01-05', 'MASUK', 'pemakai', '2021-01-05 17:59:49', 'PG20120001', 1, '0.00', '0.00', '0.00', '0.00'),
('BK210105009', 2, '2021-01-05', 'MASUK', 'pemakai', '2021-01-05 18:00:45', 'PG20120001', 2, '30.00', '100000.00', '1650000.00', '100000.00'),
('BK210106010', 3, '2021-01-06', 'MASUK', 'pemakai', '2021-01-06 15:26:13', 'PG20120001', 2, '30.00', '100000.00', '1595000.00', '100000.00'),
('BK210106011', 1, '2021-01-06', 'MASUK', 'penjual', '2021-01-06 17:22:46', 'PG20120001', 2, '30.00', '100000.00', '1266650.00', '100000.00'),
('BK210106012', 2, '2021-01-06', 'MASUK', 'pemakai', '2021-01-06 17:35:56', 'PG20120001', 1, '0.00', '0.00', '2750000.00', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_keluar_detail`
--

CREATE TABLE `barang_keluar_detail` (
  `NomorBarangKeluar` varchar(11) NOT NULL,
  `KodeBarang` varchar(10) NOT NULL,
  `Qty` decimal(18,2) NOT NULL,
  `Harga_Terakhir` decimal(18,2) DEFAULT NULL,
  `Keuntungan_Persen` decimal(18,2) DEFAULT NULL,
  `Harga_Jual` decimal(18,2) DEFAULT NULL,
  `Diskon` decimal(18,2) DEFAULT NULL,
  `Harga_Diskon` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `barang_keluar_detail`
--

INSERT INTO `barang_keluar_detail` (`NomorBarangKeluar`, `KodeBarang`, `Qty`, `Harga_Terakhir`, `Keuntungan_Persen`, `Harga_Jual`, `Diskon`, `Harga_Diskon`) VALUES
('BK201230002', 'KBR2012002', '20.00', '120000.00', '10.00', '132000.00', '0.00', '132000.00'),
('BK201230003', 'KBR2012003', '5.00', '40000.00', '10.00', '44000.00', '0.00', '44000.00'),
('BK201230004', 'KBR2012002', '10.00', '120000.00', '10.00', '132000.00', '5.00', '125400.00'),
('BK201230005', 'KBR2012002', '3.00', '120000.00', '10.00', '132000.00', '5.00', '125400.00'),
('BK201231006', 'KBR2012002', '20.00', '150000.00', '10.00', '165000.00', '0.00', '165000.00'),
('BK201231006', 'KBR2012006', '50.00', '50000.00', '10.00', '55000.00', '0.00', '55000.00'),
('BK210105007', 'KBR2012001', '10.00', '45000.00', '10.00', '49500.00', '2.00', '48510.00'),
('BK210105007', 'KBR2012002', '10.00', '150000.00', '10.00', '165000.00', '2.00', '161700.00'),
('BK210105008', 'KBR2012001', '10.00', '45000.00', '10.00', '49500.00', '0.00', '49500.00'),
('BK210105008', 'KBR2012002', '20.00', '150000.00', '10.00', '165000.00', '0.00', '165000.00'),
('BK210105009', 'KBR2012002', '10.00', '150000.00', '10.00', '165000.00', '0.00', '165000.00'),
('BK210106010', 'KBR2012003', '10.00', '40000.00', '10.00', '44000.00', '0.00', '44000.00'),
('BK210106010', 'KBR2012005', '15.00', '70000.00', '10.00', '77000.00', '0.00', '77000.00'),
('BK210106011', 'KBR2012002', '6.00', '150000.00', '10.00', '165000.00', '2.00', '161700.00'),
('BK210106011', 'KBR2012004', '5.00', '55000.00', '10.00', '60500.00', '2.00', '59290.00'),
('BK210106012', 'KBR2012006', '50.00', '50000.00', '10.00', '55000.00', '0.00', '55000.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `NomorBarangMasuk` varchar(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `TglInput` datetime NOT NULL,
  `UserInput` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`NomorBarangMasuk`, `id_supplier`, `Tanggal`, `TglInput`, `UserInput`) VALUES
('BM201230001', 1, '2020-12-30', '2020-12-30 16:45:19', 'PG20120001'),
('BM201230002', 2, '2020-12-30', '2020-12-30 16:44:06', 'PG20120001'),
('BM201230003', 1, '2020-12-30', '2020-12-30 16:47:41', 'PG20120001'),
('BM201231004', 1, '2020-12-31', '2020-12-31 12:05:25', 'PG20120001');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_masuk_detail`
--

CREATE TABLE `barang_masuk_detail` (
  `NomorBarangMasuk` varchar(11) NOT NULL,
  `KodeBarang` varchar(10) NOT NULL,
  `Qty` decimal(10,0) NOT NULL,
  `Harga` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `barang_masuk_detail`
--

INSERT INTO `barang_masuk_detail` (`NomorBarangMasuk`, `KodeBarang`, `Qty`, `Harga`) VALUES
('BM201230001', 'KBR2012002', '50', '120000'),
('BM201230001', 'KBR2012004', '60', '55000'),
('BM201230002', 'KBR2012005', '50', '70000'),
('BM201230003', 'KBR2012001', '20', '45000'),
('BM201230003', 'KBR2012003', '15', '40000'),
('BM201231004', 'KBR2012002', '50', '150000'),
('BM201231004', 'KBR2012006', '200', '50000');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE `customer` (
  `id_customer` int(11) NOT NULL,
  `NamaCustomer` varchar(200) NOT NULL,
  `Alamat` varchar(500) DEFAULT NULL,
  `Notlp` varchar(13) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`id_customer`, `NamaCustomer`, `Alamat`, `Notlp`, `Email`, `Aktif`) VALUES
(1, 'MAHFUD', 'jati', '', '', 1),
(2, 'AGUS ', 'kuta jaya', '', '', 1),
(3, 'BPK. INDRA', 'pasar kemis', '', '', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `path_menu` varchar(300) DEFAULT NULL,
  `parent_1` int(11) DEFAULT NULL,
  `parent_2` int(11) DEFAULT NULL,
  `parent_3` int(11) DEFAULT NULL,
  `NoUrut` int(11) DEFAULT NULL,
  `Aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id_menu`, `nama_menu`, `path_menu`, `parent_1`, `parent_2`, `parent_3`, `NoUrut`, `Aktif`) VALUES
(6, 'SETTING', '', NULL, NULL, NULL, 10, 1),
(7, 'Akses User User', 'setting/user', 6, NULL, NULL, 10, 1),
(8, 'Menu', 'setting/menu', 6, NULL, NULL, 20, 1),
(9, 'Akses User Menu', 'setting/akses_user', 6, NULL, NULL, 30, 1),
(10, 'Master Data', NULL, NULL, NULL, NULL, 20, 1),
(11, 'Transaksi', NULL, NULL, NULL, NULL, 30, 1),
(12, 'Laporan', NULL, NULL, NULL, NULL, 40, 1),
(13, 'Barang', 'barang/c_barang', 10, NULL, NULL, 10, 1),
(14, 'Supplier', 'supplier/c_supplier', 10, NULL, NULL, 20, 1),
(15, 'Customer', 'customer/c_customer', 10, NULL, NULL, 30, 1),
(16, 'Barang Masuk', 'barang_masuk/C_barang_masuk', 11, NULL, NULL, 10, 1),
(17, 'Barang Keluar', 'barang_keluar/C_barang_keluar', 11, NULL, NULL, 20, 1),
(18, 'Barang Retur', 'barang_retur/C_barang_retur', 11, NULL, NULL, 30, 1),
(19, 'Laporan Barang', 'laporan/C_laporan_barang', 12, NULL, NULL, 10, 1),
(20, 'Laporan Transaksi', 'laporan/C_laporan_transaksi', 12, NULL, NULL, 20, 1),
(21, 'Ongkir', 'ongkir/c_ongkir', 10, NULL, NULL, 40, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `ongkir`
--

CREATE TABLE `ongkir` (
  `id_ongkir` int(11) NOT NULL,
  `nama_ongkir` varchar(100) NOT NULL,
  `jumlah_min_order` decimal(18,2) DEFAULT NULL,
  `harga_ongkir` decimal(10,0) DEFAULT NULL,
  `Aktif` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `ongkir`
--

INSERT INTO `ongkir` (`id_ongkir`, `nama_ongkir`, `jumlah_min_order`, `harga_ongkir`, `Aktif`) VALUES
(1, 'Tidak Ada', '0.00', '0', 1),
(2, 'Minimal Order (Dus)', '30.00', '100000', 1),
(3, 'Per Dus', '0.00', '3000', 1),
(4, 'MINIMAL ORDER (DUS) LAMPUNNG', '120.00', '200000', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `retur`
--

CREATE TABLE `retur` (
  `NomorRetur` varchar(11) NOT NULL,
  `NomorBarangKeluar` varchar(11) NOT NULL,
  `Tanggal` date NOT NULL,
  `UserInput` varchar(50) NOT NULL,
  `TglInput` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `retur`
--

INSERT INTO `retur` (`NomorRetur`, `NomorBarangKeluar`, `Tanggal`, `UserInput`, `TglInput`) VALUES
('NR201230001', 'BK201230001', '2020-12-30', 'PG20120001', '2020-12-30 16:50:19'),
('NR201230003', 'BK201230002', '2020-12-30', 'PG20120001', '2020-12-30 16:51:01'),
('NR201230004', 'BK201230001', '2020-12-30', 'PG20120001', '2020-12-30 16:58:13'),
('NR201231005', 'BK201231006', '2020-12-31', 'PG20120001', '2020-12-31 12:49:16'),
('NR201231006', 'BK201231006', '2020-12-31', 'PG20120001', '2020-12-31 12:50:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `retur_detail`
--

CREATE TABLE `retur_detail` (
  `NomorRetur` varchar(11) NOT NULL,
  `KodeBarang` varchar(10) NOT NULL,
  `Qty` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `retur_detail`
--

INSERT INTO `retur_detail` (`NomorRetur`, `KodeBarang`, `Qty`) VALUES
('NR201230001', 'KBR2012002', '5'),
('NR201230002', 'KBR2012002', '3'),
('NR201230003', 'KBR2012002', '5'),
('NR201230004', 'KBR2012002', '5'),
('NR201231005', 'KBR2012002', '4'),
('NR201231006', 'KBR2012002', '2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_barang`
--

CREATE TABLE `stok_barang` (
  `KodeBarang` varchar(10) NOT NULL,
  `Jenis` varchar(10) NOT NULL,
  `Qty_Stok` decimal(18,2) NOT NULL,
  `Qty_Gantung` decimal(18,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `stok_barang`
--

INSERT INTO `stok_barang` (`KodeBarang`, `Jenis`, `Qty_Stok`, `Qty_Gantung`) VALUES
('KBR2012001', 'MASUK', '0.00', NULL),
('KBR2012002', 'MASUK', '14.00', NULL),
('KBR2012002', 'REJECT', '8.00', NULL),
('KBR2012003', 'MASUK', '0.00', NULL),
('KBR2012004', 'MASUK', '45.00', NULL),
('KBR2012005', 'MASUK', '35.00', NULL),
('KBR2012006', 'MASUK', '100.00', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `Nama_supplier` varchar(200) NOT NULL,
  `Alamat` varchar(500) DEFAULT NULL,
  `Notlp` varchar(13) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Aktif` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `Nama_supplier`, `Alamat`, `Notlp`, `Email`, `Aktif`) VALUES
(1, 'IKAD', 'pasar kemis', '', '', 1),
(2, 'ROWMAN', 'balaraja', '', '', 1),
(3, 'ARWANA', 'serang', '', '', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `kode_pegawai` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`kode_pegawai`, `username`, `password`) VALUES
('PG20120001', 'ADMIN', 'e10adc3949ba59abbe56e057f20f883e'),
('PG20120002', 'KASIR', 'e10adc3949ba59abbe56e057f20f883e'),
('PG20120003', 'PEMILIK', 'e10adc3949ba59abbe56e057f20f883e'),
('PG20120004', 'KASIR 2', 'c33367701511b4f6020ec61ded352059');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_akses_menu`
--

CREATE TABLE `user_akses_menu` (
  `id_menu` int(11) NOT NULL,
  `id_akses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_akses_menu`
--

INSERT INTO `user_akses_menu` (`id_menu`, `id_akses`) VALUES
(6, 2),
(6, 3),
(7, 2),
(7, 3),
(10, 2),
(10, 3),
(11, 2),
(11, 3),
(12, 3),
(13, 2),
(13, 3),
(14, 2),
(14, 3),
(15, 2),
(15, 3),
(16, 2),
(16, 3),
(17, 2),
(17, 3),
(18, 2),
(18, 3),
(19, 3),
(20, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_akses_user`
--

CREATE TABLE `user_akses_user` (
  `id_akses` int(11) NOT NULL,
  `kode_pegawai` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_akses_user`
--

INSERT INTO `user_akses_user` (`id_akses`, `kode_pegawai`) VALUES
(1, 'p01'),
(2, 'p02'),
(1, 'PG20120001'),
(2, 'PG20120002'),
(3, 'PG20120003'),
(2, 'PG20120004');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akses_user`
--
ALTER TABLE `akses_user`
  ADD PRIMARY KEY (`id_akses`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`Kodebarang`);

--
-- Indeks untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`NomorBarangKeluar`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indeks untuk tabel `barang_keluar_detail`
--
ALTER TABLE `barang_keluar_detail`
  ADD PRIMARY KEY (`NomorBarangKeluar`,`KodeBarang`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`NomorBarangMasuk`);

--
-- Indeks untuk tabel `barang_masuk_detail`
--
ALTER TABLE `barang_masuk_detail`
  ADD PRIMARY KEY (`NomorBarangMasuk`,`KodeBarang`);

--
-- Indeks untuk tabel `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indeks untuk tabel `ongkir`
--
ALTER TABLE `ongkir`
  ADD PRIMARY KEY (`id_ongkir`);

--
-- Indeks untuk tabel `retur`
--
ALTER TABLE `retur`
  ADD PRIMARY KEY (`NomorRetur`);

--
-- Indeks untuk tabel `retur_detail`
--
ALTER TABLE `retur_detail`
  ADD PRIMARY KEY (`NomorRetur`,`KodeBarang`);

--
-- Indeks untuk tabel `stok_barang`
--
ALTER TABLE `stok_barang`
  ADD PRIMARY KEY (`KodeBarang`,`Jenis`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`kode_pegawai`);

--
-- Indeks untuk tabel `user_akses_menu`
--
ALTER TABLE `user_akses_menu`
  ADD PRIMARY KEY (`id_menu`,`id_akses`);

--
-- Indeks untuk tabel `user_akses_user`
--
ALTER TABLE `user_akses_user`
  ADD PRIMARY KEY (`kode_pegawai`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akses_user`
--
ALTER TABLE `akses_user`
  MODIFY `id_akses` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `customer`
--
ALTER TABLE `customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `ongkir`
--
ALTER TABLE `ongkir`
  MODIFY `id_ongkir` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
