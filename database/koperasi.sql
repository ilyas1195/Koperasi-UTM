-- ==================================================
-- DATABASE KOPMA UTM
-- Katalog Produk Koperasi Mahasiswa Universitas Trunodjoyo Madura
-- ==================================================

CREATE DATABASE IF NOT EXISTS db_kopma_utm;
USE db_kopma_utm;

-- ---------------------------------------------------
-- Tabel Admin
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id_admin INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password default: admin123 (bcrypt)
INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- ---------------------------------------------------
-- Tabel Kategori
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    parent_id INT(11) DEFAULT NULL,
    FOREIGN KEY (parent_id) REFERENCES kategori(id_kategori) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO kategori (nama_kategori, parent_id) VALUES
('Retail', NULL),
('Konsinyasi', NULL),
('Lainnya', NULL),
('Makanan', 1),
('Minuman', 1);

-- ---------------------------------------------------
-- Tabel Produk
-- ---------------------------------------------------
CREATE TABLE IF NOT EXISTS produk (
    id_produk INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(200) NOT NULL,
    harga INT(11) NOT NULL,
    stok INT(11) NOT NULL DEFAULT 0,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    kategori_id INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id_kategori) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data Produk
INSERT INTO produk (nama_produk, harga, stok, deskripsi, gambar, kategori_id) VALUES
('Indomie Goreng', 3500, 100, 'Mie instan goreng favorit semua kalangan. Cocok untuk kebutuhan sehari-hari mahasiswa.', 'indomie-goreng.jpg', 4),
('Kopiko 78s', 5000, 75, 'Permen kopi dengan rasa original yang menyegarkan.', 'kopiko.jpg', 4),
('Aqua 600ml', 3000, 120, 'Air mineral berkualitas dalam kemasan botol praktis.', 'aqua.jpg', 5),
('Pocari Sweat 500ml', 7000, 60, 'Minuman isotonik untuk menggantikan cairan tubuh yang hilang.', 'pocari.jpg', 5),
('Teh Botol Sosro 500ml', 5000, 80, 'Teh botol dengan rasa melati yang segar dan nikmat.', 'teh-botol.jpg', 5),
('Buku Tulis SIDU 38 Lembar', 5000, 200, 'Buku tulis dengan kertas berkualitas, cocok untuk catatan kuliah.', 'buku-tulis.jpg', 3),
('Pulpen Standard AE7', 3000, 150, 'Pulpen dengan tinta hitam yang halus dan tidak mudah macet.', 'pulpen.jpg', 3),
('Pensil 2B Faber Castell', 5000, 100, 'Pensil 2B berkualitas untuk ujian dan menggambar.', 'pensil.jpg', 3),
('Tempat Pensil', 15000, 50, 'Tempat pensil dengan bahan kain yang kuat dan tahan lama.', 'tempat-pensil.jpg', 3),
('Sticky Notes 3x3', 8000, 90, 'Catatan tempel warna-warni untuk memudahkan belajar.', 'sticky-notes.jpg', 3);
