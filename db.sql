
CREATE DATABASE IF NOT EXISTS db_monitoring_ibadah CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE db_monitoring_ibadah;


CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('siswa','guru','admin') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `log_ibadah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `sholat_subuh` tinyint(1) DEFAULT 0,
  `sholat_dzuhur` tinyint(1) DEFAULT 0,
  `sholat_ashar` tinyint(1) DEFAULT 0,
  `sholat_maghrib` tinyint(1) DEFAULT 0,
  `sholat_isya` tinyint(1) DEFAULT 0,
  `tilawah_halaman` int(11) DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `catatan_guru` text DEFAULT NULL,
  `status_validasi` enum('menunggu','disetujui') NOT NULL DEFAULT 'menunggu',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Menambahkan Foreign Key Constraint
-- Ini akan menghubungkan `log_ibadah`.`id_user` ke `users`.`id`
-- ON DELETE CASCADE berarti jika seorang user dihapus, semua log ibadahnya juga akan terhapus.
--
ALTER TABLE `log_ibadah`
  ADD CONSTRAINT `fk_log_to_users` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



-- Menambahkan beberapa data pengguna contoh
-- pastikan untuk mengganti '#' dengan data yang sesuai dan '$Generate password hash' dengan hash password yang benar
INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, '#', '$Generate password hash', '#', 'siswa');

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(2, '#', 'generate password hash', '#', 'guru');

-- Menambahkan beberapa data log ibadah untuk siswa dengan id=1
INSERT INTO `log_ibadah` (`id_user`, `tanggal`, `sholat_subuh`, `sholat_dzuhur`, `sholat_ashar`, `sholat_maghrib`, `sholat_isya`, `tilawah_halaman`, `status_validasi`) VALUES
(1, '2025-09-20', 1, 1, 1, 1, 1, 3, 'disetujui'),
(1, '2025-09-21', 1, 1, 0, 1, 1, 2, 'menunggu');