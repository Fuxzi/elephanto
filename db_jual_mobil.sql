-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2026 at 09:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_jual_mobil`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pembelian`
--

CREATE TABLE `detail_pembelian` (
  `id_detail` int(11) NOT NULL,
  `id_pembelian` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_beli` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pembelian`
--

INSERT INTO `detail_pembelian` (`id_detail`, `id_pembelian`, `jumlah`, `harga_beli`, `subtotal`) VALUES
(1, 1, 4, 123123, 492492),
(2, 2, 1, 111, 111);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pemesanan` int(11) DEFAULT NULL,
  `id_mobil` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pemesanan`
--

INSERT INTO `detail_pemesanan` (`id_detail`, `id_pemesanan`, `id_mobil`, `jumlah`, `harga`, `subtotal`) VALUES
(1, 1, 1, 1, 200000000, 200000000),
(2, 1, 3, 1, 250000000, 250000000),
(3, 2, 2, 1, 300000000, 300000000);

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_pembeli`
--

CREATE TABLE `dokumen_pembeli` (
  `id_dokumen` int(11) NOT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `ktp` varchar(50) DEFAULT NULL,
  `kk` varchar(50) DEFAULT NULL,
  `sim` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokumen_pembeli`
--

INSERT INTO `dokumen_pembeli` (`id_dokumen`, `id_pembeli`, `ktp`, `kk`, `sim`) VALUES
(1, 1, '1234567890123456', '1234567890123456', '9876543210'),
(2, 2, '2234567890123456', '2234567890123456', '8876543210'),
(3, 3, '3234567890123456', '3234567890123456', '7876543210');

-- --------------------------------------------------------

--
-- Table structure for table `master_mobil`
--

CREATE TABLE `master_mobil` (
  `id_master` int(11) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `tipe` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_mobil`
--

INSERT INTO `master_mobil` (`id_master`, `merk`, `tipe`) VALUES
(1, 'Toyota', 'Avanza'),
(2, 'Toyota', 'Innova'),
(3, 'Toyota', 'Fortuner'),
(4, 'Toyota', 'Rush'),
(5, 'Honda', 'Brio'),
(6, 'Honda', 'Jazz'),
(7, 'Honda', 'Civic'),
(8, 'Honda', 'Mobilio'),
(9, 'Daihatsu', 'Xenia'),
(10, 'Daihatsu', 'Terios'),
(11, 'Daihatsu', 'Sigra'),
(12, 'Suzuki', 'Ertiga'),
(13, 'Suzuki', 'XL7'),
(14, 'Mitsubishi', 'Xpander'),
(15, 'Mitsubishi', 'Pajero Sport'),
(16, 'Hyundai', 'Creta'),
(17, 'Hyundai', 'Stargazer'),
(18, 'Wuling', 'Almaz'),
(19, 'Wuling', 'Confero'),
(20, 'Toyota', 'Avanza'),
(21, 'Toyota', 'Innova'),
(22, 'Toyota', 'Fortuner'),
(23, 'Toyota', 'Rush'),
(24, 'Toyota', 'Agya'),
(25, 'Toyota', 'Calya'),
(26, 'Toyota', 'Yaris'),
(27, 'Toyota', 'Vios'),
(28, 'Toyota', 'Raize'),
(29, 'Toyota', 'Camry'),
(30, 'Honda', 'Brio'),
(31, 'Honda', 'Jazz'),
(32, 'Honda', 'Civic'),
(33, 'Honda', 'Mobilio'),
(34, 'Honda', 'BR-V'),
(35, 'Honda', 'HR-V'),
(36, 'Honda', 'CR-V'),
(37, 'Honda', 'City'),
(38, 'Daihatsu', 'Xenia'),
(39, 'Daihatsu', 'Terios'),
(40, 'Daihatsu', 'Sigra'),
(41, 'Daihatsu', 'Ayla'),
(42, 'Daihatsu', 'Rocky'),
(43, 'Daihatsu', 'Sirion'),
(44, 'Suzuki', 'Ertiga'),
(45, 'Suzuki', 'XL7'),
(46, 'Suzuki', 'Ignis'),
(47, 'Suzuki', 'Baleno'),
(48, 'Suzuki', 'Swift'),
(49, 'Suzuki', 'Carry'),
(50, 'Mitsubishi', 'Xpander'),
(51, 'Mitsubishi', 'Xpander Cross'),
(52, 'Mitsubishi', 'Pajero Sport'),
(53, 'Mitsubishi', 'Outlander Sport'),
(54, 'Mitsubishi', 'Triton'),
(55, 'Nissan', 'Livina'),
(56, 'Nissan', 'March'),
(57, 'Nissan', 'Serena'),
(58, 'Nissan', 'X-Trail'),
(59, 'Nissan', 'Juke'),
(60, 'Mazda', 'Mazda 2'),
(61, 'Mazda', 'Mazda 3'),
(62, 'Mazda', 'CX-3'),
(63, 'Mazda', 'CX-5'),
(64, 'Hyundai', 'Creta'),
(65, 'Hyundai', 'Stargazer'),
(66, 'Hyundai', 'Santa Fe'),
(67, 'Hyundai', 'Palisade'),
(68, 'Hyundai', 'Ioniq 5'),
(69, 'Wuling', 'Almaz'),
(70, 'Wuling', 'Confero'),
(71, 'Wuling', 'Cortez'),
(72, 'Wuling', 'Air EV'),
(73, 'Kia', 'Picanto'),
(74, 'Kia', 'Seltos'),
(75, 'Kia', 'Sonet'),
(76, 'Kia', 'Carnival'),
(77, 'BMW', '320i'),
(78, 'BMW', 'X1'),
(79, 'BMW', 'X3'),
(80, 'BMW', 'X5'),
(81, 'Mercedes-Benz', 'C-Class'),
(82, 'Mercedes-Benz', 'E-Class'),
(83, 'Mercedes-Benz', 'GLA'),
(84, 'Mercedes-Benz', 'GLC');

-- --------------------------------------------------------

--
-- Table structure for table `mobil`
--

CREATE TABLE `mobil` (
  `id_mobil` int(11) NOT NULL,
  `id_master` int(11) DEFAULT NULL,
  `merk` varchar(50) DEFAULT NULL,
  `tipe` varchar(50) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `warna` varchar(20) DEFAULT NULL,
  `harga` float DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `status` enum('Tersedia','Dipesan','Terjual') DEFAULT 'Tersedia',
  `kondisi` varchar(100) DEFAULT NULL,
  `kilometer` int(11) DEFAULT NULL,
  `transmisi` enum('Manual','Matic') DEFAULT NULL,
  `bahan_bakar` varchar(50) DEFAULT NULL,
  `pajak_berlaku` date DEFAULT NULL,
  `plat_nomor` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mobil`
--

INSERT INTO `mobil` (`id_mobil`, `id_master`, `merk`, `tipe`, `tahun`, `warna`, `harga`, `stock`, `status`, `kondisi`, `kilometer`, `transmisi`, `bahan_bakar`, `pajak_berlaku`, `plat_nomor`, `deskripsi`) VALUES
(1, NULL, 'Toyota', 'Avanza', 2021, 'Hitam', 200000000, 2, 'Tersedia', 'Bekas sangat baik', 35000, 'Manual', 'Bensin', '2026-12-10', 'B 1234 ABC', 'Body mulus, mesin normal, surat lengkap'),
(2, NULL, 'Honda', 'Civic', 2020, 'Putih', 300000000, 2, 'Tersedia', 'Bekas baik', 42000, 'Matic', 'Bensin', '2026-09-15', 'D 5678 XYZ', 'Interior rapi, servis rutin, pajak hidup'),
(3, NULL, 'Suzuki', 'Ertiga', 2022, 'Merah', 250000000, 1, 'Tersedia', 'Bekas sangat baik', 28000, 'Manual', 'Bensin', '2027-01-20', 'L 9012 QWE', 'Mobil keluarga, ban masih tebal, surat lengkap'),
(4, NULL, '123123', 'suv', 2009, 'Merah', 200000, 4, 'Tersedia', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, NULL, 'test', 'test', 21313, 'test', 213123, 1, 'Tersedia', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, NULL, 'test', 'test', 1231, 'test', 123123, 4, 'Tersedia', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 80, 'BMW', 'X5', 1983, 'Merah', 11111, 1, 'Tersedia', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pembatalan`
--

CREATE TABLE `pembatalan` (
  `id_pembatalan` int(11) NOT NULL,
  `id_transaksi` int(11) DEFAULT NULL,
  `tanggal_batal` date DEFAULT NULL,
  `alasan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembatalan`
--

INSERT INTO `pembatalan` (`id_pembatalan`, `id_transaksi`, `tanggal_batal`, `alasan`) VALUES
(1, 2, '2026-06-08', 'Gagal');

-- --------------------------------------------------------

--
-- Table structure for table `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `nama`, `alamat`, `no_hp`) VALUES
(1, 'Budi Santoso', 'Jl. Melati No.10, Jakarta', '081234567890'),
(2, 'Siti Aminah', 'Jl. Mawar No.5, Bandung', '081298765432'),
(3, 'Agus Salim', 'Jl. Kenanga No.8, Surabaya', '081212345678'),
(4, 'eses', 'asdawd', '1232123');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int(11) NOT NULL,
  `id_mobil` int(11) DEFAULT NULL,
  `nama_penjual` varchar(100) DEFAULT NULL,
  `no_hp_penjual` varchar(20) DEFAULT NULL,
  `tanggal_pembelian` date DEFAULT NULL,
  `total` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `id_mobil`, `nama_penjual`, `no_hp_penjual`, `tanggal_pembelian`, `total`) VALUES
(1, 6, 'test', '12312312', '2026-06-08', 492492),
(2, 7, 'test', '112313', '2026-06-08', 111);

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `id_pembeli` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Pending','Lunas','Batal') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `id_pembeli`, `tanggal`, `status`) VALUES
(1, 1, '2026-06-08', 'Pending'),
(2, 2, '2026-06-08', 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_pemesanan` int(11) DEFAULT NULL,
  `total` float DEFAULT NULL,
  `metode_pembayaran` enum('Tunai','Transfer') DEFAULT 'Tunai',
  `status` enum('Belum Dibayar','Lunas','Batal') DEFAULT 'Belum Dibayar'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_pemesanan`, `total`, `metode_pembayaran`, `status`) VALUES
(1, 1, 450000000, 'Tunai', 'Belum Dibayar'),
(2, 2, 300000000, 'Transfer', 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`) VALUES
(1, 'admin1', 'password123', 'Admin'),
(2, 'staff1', 'password123', 'Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pembelian` (`id_pembelian`);

--
-- Indexes for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pemesanan` (`id_pemesanan`),
  ADD KEY `id_mobil` (`id_mobil`);

--
-- Indexes for table `dokumen_pembeli`
--
ALTER TABLE `dokumen_pembeli`
  ADD PRIMARY KEY (`id_dokumen`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indexes for table `master_mobil`
--
ALTER TABLE `master_mobil`
  ADD PRIMARY KEY (`id_master`);

--
-- Indexes for table `mobil`
--
ALTER TABLE `mobil`
  ADD PRIMARY KEY (`id_mobil`),
  ADD KEY `fk_mobil_master` (`id_master`);

--
-- Indexes for table `pembatalan`
--
ALTER TABLE `pembatalan`
  ADD PRIMARY KEY (`id_pembatalan`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `id_mobil` (`id_mobil`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_pembeli` (`id_pembeli`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pemesanan` (`id_pemesanan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dokumen_pembeli`
--
ALTER TABLE `dokumen_pembeli`
  MODIFY `id_dokumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_mobil`
--
ALTER TABLE `master_mobil`
  MODIFY `id_master` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `mobil`
--
ALTER TABLE `mobil`
  MODIFY `id_mobil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pembatalan`
--
ALTER TABLE `pembatalan`
  MODIFY `id_pembatalan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pembelian`
--
ALTER TABLE `detail_pembelian`
  ADD CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `pembelian` (`id_pembelian`);

--
-- Constraints for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `detail_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`),
  ADD CONSTRAINT `detail_pemesanan_ibfk_2` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`);

--
-- Constraints for table `dokumen_pembeli`
--
ALTER TABLE `dokumen_pembeli`
  ADD CONSTRAINT `dokumen_pembeli_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`);

--
-- Constraints for table `mobil`
--
ALTER TABLE `mobil`
  ADD CONSTRAINT `fk_mobil_master` FOREIGN KEY (`id_master`) REFERENCES `master_mobil` (`id_master`);

--
-- Constraints for table `pembatalan`
--
ALTER TABLE `pembatalan`
  ADD CONSTRAINT `pembatalan_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id_mobil`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
