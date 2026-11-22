CREATE DATABASE IF NOT EXISTS `penjualan`;
USE `penjualan`;

CREATE TABLE IF NOT EXISTS `user` (
  `id_user` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `alamat` TEXT NULL,
  `hp` VARCHAR(20) NULL,
  `level` TINYINT(1) NOT NULL COMMENT '1 = Owner, 2 = Kasir',
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'owner', '123456', 'Achmad Zainuri Efendi (Owner)', 'Burneh, Bangkalan', '081234567890', 1),
(2, 'kasir', '123456', 'Kasir Utama', 'Bangkalan, Madura', '085678901234', 2);

CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id` VARCHAR(20) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `jenis_kelamin` ENUM('L', 'P') NOT NULL,
  `telp` VARCHAR(20) NOT NULL,
  `alamat` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `supplier` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `telp` VARCHAR(20) NOT NULL,
  `alamat` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `barang` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `kode_barang` VARCHAR(20) NOT NULL,
  `nama_barang` VARCHAR(100) NOT NULL,
  `harga` INT(11) NOT NULL,
  `stok` INT(11) NOT NULL,
  `supplier_id` INT(11) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `waktu_transaksi` DATE NOT NULL,
  `keterangan` TEXT NULL,
  `total` INT(11) NOT NULL,
  `pelanggan_id` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `transaksi_detail` (
  `transaksi_id` INT(11) NOT NULL,
  `barang_id` INT(11) NOT NULL,
  `harga` INT(11) NOT NULL,
  `qty` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;