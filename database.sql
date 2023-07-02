-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               5.7.24 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table db_dispensasi_siswa.izin
CREATE TABLE IF NOT EXISTS `izin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` int(10) unsigned NOT NULL,
  `guru_id` int(10) unsigned NOT NULL,
  `kelas_jurusan` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `keterangan` text NOT NULL,
  `status` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'pending',
  `waktu_status_diubah` datetime DEFAULT NULL COMMENT 'kapan status di verifikasi (disetujui/ditolak)',
  `keterangan_status_diubah` text COMMENT 'keterangan tambahan oleh verifikator',
  `waka_id` int(10) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK__siswa` (`siswa_id`),
  KEY `FK__guru` (`guru_id`),
  KEY `Index 4` (`waka_id`),
  CONSTRAINT `FK_guru` FOREIGN KEY (`guru_id`) REFERENCES `pengguna` (`id`),
  CONSTRAINT `FK_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `pengguna` (`id`),
  CONSTRAINT `FK_waka` FOREIGN KEY (`waka_id`) REFERENCES `pengguna` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table db_dispensasi_siswa.izin: ~0 rows (approximately)
/*!40000 ALTER TABLE `izin` DISABLE KEYS */;
/*!40000 ALTER TABLE `izin` ENABLE KEYS */;

-- Dumping structure for table db_dispensasi_siswa.pengguna
CREATE TABLE IF NOT EXISTS `pengguna` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nomor` varchar(100) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL DEFAULT 'L',
  `foto_profil` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `kontak` varchar(150) DEFAULT NULL,
  `rule` enum('waka','guru','siswa') NOT NULL DEFAULT 'waka',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 2` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table db_dispensasi_siswa.pengguna: ~3 rows (approximately)
/*!40000 ALTER TABLE `pengguna` DISABLE KEYS */;
INSERT INTO `pengguna` (`id`, `username`, `password`, `nomor`, `nama_lengkap`, `jenis_kelamin`, `foto_profil`, `email`, `kontak`, `rule`) VALUES
	(1, 'waka', '$2y$10$zEPZ0peNE4XJgYH1ojPBCOZcErboeJ8ieyEg6Qver4WeQAo1UCog6', '000123123', 'wakil kesiswaan', 'L', NULL, '', '', 'waka'),
	(8, 'Guru2', '$2y$10$pY5X1EeYtqtlUM9CBI9nK.9v1FbP./ZshFLwRlzMF2..IHBaRxzLi', '647273828823', 'Test', 'L', NULL, 'test@gmail.com', '08314553947', 'guru'),
	(9, 'Dora1', '$2y$10$wYEh8jHOeT/gAEbdP81T2u2r2M7lBmetFbHqTMlYOoEE5ZyyYCZyK', '2081', 'Dora', 'L', NULL, '', '', 'siswa');
/*!40000 ALTER TABLE `pengguna` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
