-- =====================================================
-- DATABASE: Toko Randu Mekar
-- Created for: PHP Native + MySQL Project
-- =====================================================

CREATE DATABASE IF NOT EXISTS randu_mekar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE randu_mekar;

-- =====================================================
-- TABLE: admin
-- =====================================================
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO admin (username, password, nama_lengkap) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');
-- Default password: password

-- =====================================================
-- TABLE: produk
-- =====================================================
CREATE TABLE IF NOT EXISTS produk (
    id_produk INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk VARCHAR(200) NOT NULL,
    harga DECIMAL(15,0) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    gambar_3d VARCHAR(255),
    kategori ENUM('kasur','kasur_lantai','karpet','bantal','guling','sofa','rak_piring','lainnya') DEFAULT 'lainnya',
    stok INT DEFAULT 0,
    unggulan TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO produk (nama_produk, harga, deskripsi, gambar, gambar_3d, kategori, stok, unggulan) VALUES
('Kasur Springbed Premium', 2500000, 'Kasur springbed berkualitas tinggi dengan sistem pegas individual yang memberikan kenyamanan tidur optimal. Cocok untuk kamar tidur utama dengan desain modern dan elegan. Tersedia dalam berbagai ukuran.', NULL, NULL, 'kasur', 10, 1),
('Karpet Minimalis Modern', 450000, 'Karpet bulu halus dengan motif minimalis modern yang cocok untuk ruang tamu maupun kamar tidur. Bahan lembut dan mudah dibersihkan, tersedia dalam berbagai ukuran dan warna netral.', NULL, NULL, 'karpet', 25, 1),
('Sofa Sectional Modern', 3800000, 'Sofa sectional dengan desain modern yang nyaman untuk ruang keluarga. Bahan fabric premium tahan lama, rangka kayu solid, tersedia dalam pilihan warna cream, abu dan coklat.', NULL, NULL, 'sofa', 5, 1),
('Bantal Premium Microfiber', 85000, 'Bantal tidur dengan isian microfiber berkualitas tinggi. Memberikan dukungan kepala yang tepat untuk tidur nyaman sepanjang malam. Sarung bantal mudah dicuci.', NULL, NULL, 'bantal', 50, 0),
('Guling Comfort Plus', 95000, 'Guling dengan isian serat pillow fiber yang lembut dan tidak mudah kempes. Ukuran standar, cocok untuk orang dewasa maupun anak-anak.', NULL, NULL, 'guling', 50, 0),
('Kasur Lantai Tebal 10cm', 320000, 'Kasur lantai dengan ketebalan 10cm menggunakan bahan busa berkualitas. Ringan dan mudah dilipat untuk penyimpanan. Cocok sebagai kasur tamu atau alas tidur tambahan.', NULL, NULL, 'kasur_lantai', 20, 0),
('Rak Piring Stainless 4 Susun', 280000, 'Rak piring stainless steel 4 susun anti karat dan kokoh. Desain terbuka memudahkan pengeringan piring. Kapasitas besar untuk kebutuhan dapur sehari-hari.', NULL, NULL, 'rak_piring', 15, 0),
('Kasur Busa Foam Premium', 1200000, 'Kasur busa dengan densitas tinggi untuk kenyamanan tidur optimal. Tidak mudah kempes meski digunakan bertahun-tahun. Tersedia ukuran single, double dan queen.', NULL, NULL, 'kasur', 12, 0);

-- =====================================================
-- TABLE: kontak
-- =====================================================
CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    pesan TEXT NOT NULL,
    status ENUM('baru','dibaca') DEFAULT 'baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO kontak (nama, email, pesan) VALUES
('Budi Santoso', 'budi@email.com', 'Halo, apakah kasur springbed tersedia dalam ukuran 160x200?'),
('Siti Rahayu', 'siti@email.com', 'Saya tertarik dengan sofa sectional, apakah bisa diantar ke area Solo?');
