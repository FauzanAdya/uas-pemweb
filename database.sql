-- ============================================
-- DATABASE: buket_db
-- ============================================
CREATE DATABASE IF NOT EXISTS buket_db;
USE buket_db;

-- 1. Tabel admin
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabel katalog
CREATE TABLE katalog (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga_dasar INT NOT NULL,
    kategori VARCHAR(50),
    foto VARCHAR(255),
    ditambahkan_oleh INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ditambahkan_oleh) REFERENCES admin(id) ON DELETE SET NULL
);

-- 3. Tabel pesanan
CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produk_id INT,
    nama_pemesan VARCHAR(100) NOT NULL,
    no_wa VARCHAR(20) NOT NULL,
    jumlah INT DEFAULT 1,
    tanggal_pesan DATETIME NOT NULL,
    tanggal_ambil DATE NOT NULL,
    ucapan TEXT,
    is_custom TINYINT(1) DEFAULT 0,
    warna_kertas VARCHAR(50),
    jenis_isi VARCHAR(50),
    tambahan TEXT,
    total_harga INT NOT NULL,
    tipe_pengambilan VARCHAR(10) DEFAULT 'ambil',
    status ENUM('pending','diproses','selesai','dibatalkan') DEFAULT 'pending',
    status_bayar ENUM('belum_lunas','dp','lunas') DEFAULT 'belum_lunas',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produk_id) REFERENCES katalog(id) ON DELETE SET NULL
);

-- 4. Tabel pembayaran
CREATE TABLE pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT NOT NULL,
    diverifikasi_oleh INT,
    file_bukti VARCHAR(255),
    tipe_bayar ENUM('dp','lunas','cod') NOT NULL,
    tanggal_upload DATETIME NOT NULL,
    status_verifikasi ENUM('menunggu','diterima','ditolak') DEFAULT 'menunggu',
    alasan_tolak TEXT,
    jumlah_cod INT DEFAULT NULL,
    catatan_cod TEXT,
    tanggal_verifikasi DATETIME,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (diverifikasi_oleh) REFERENCES admin(id) ON DELETE SET NULL
);

-- 5. Tabel stok_bahan (dengan kolom kode_bahan)
CREATE TABLE stok_bahan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_bahan VARCHAR(100) NOT NULL,
    kode_bahan VARCHAR(50) NULL,
    jumlah INT DEFAULT 0,
    satuan VARCHAR(20),
    stok_minimum INT DEFAULT 5,
    diupdate_oleh INT,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (diupdate_oleh) REFERENCES admin(id) ON DELETE SET NULL
);

-- 6. Tabel keuangan
CREATE TABLE keuangan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT DEFAULT NULL,
    dicatat_oleh INT,
    keterangan VARCHAR(255) NOT NULL,
    jumlah INT NOT NULL,
    tipe ENUM('masuk','keluar') NOT NULL,
    tanggal DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE SET NULL,
    FOREIGN KEY (dicatat_oleh) REFERENCES admin(id) ON DELETE SET NULL
);

-- 7. Tabel notifikasi
CREATE TABLE notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT NOT NULL,
    pesan TEXT NOT NULL,
    dibaca TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE
);

-- ============================================
-- DATA AWAL
-- ============================================

-- Admin default (password: admin123)
INSERT INTO admin (nama, username, password) VALUES
('Admin Toko Buket', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Contoh produk katalog
INSERT INTO katalog (nama, deskripsi, harga_dasar, kategori, foto, ditambahkan_oleh) VALUES
('Buket Bunga Mawar',   'Rangkaian bunga mawar segar dengan wrapping cantik', 75000,  'bunga',  NULL, 1),
('Buket Snack Coklat',  'Rangkaian snack dan coklat favorit',                 65000,  'snack',  NULL, 1),
('Buket Uang 100K',     'Buket dengan rangkaian uang asli',                   150000, 'uang',   NULL, 1),
('Buket Wisuda Custom', 'Buket spesial untuk wisuda, bisa custom',            90000,  'custom', NULL, 1);

-- Stok bahan dengan kode_bahan
INSERT INTO stok_bahan (nama_bahan, kode_bahan, jumlah, satuan, stok_minimum, diupdate_oleh) VALUES
('Kertas Wrap Pink',       'pink',             20, 'lembar', 5,  1),
('Kertas Wrap Putih',      'putih',            15, 'lembar', 5,  1),
('Kertas Wrap Biru',       'biru',             10, 'lembar', 5,  1),
('Kertas Wrap Ungu',       'ungu',             10, 'lembar', 5,  1),
('Kertas Wrap Kuning',     'kuning',           10, 'lembar', 5,  1),
('Kertas Wrap Hijau',      'hijau',            10, 'lembar', 5,  1),
('Bunga Mawar Artifisial', 'bunga_artifisial', 50, 'buah',   15, 1),
('Bunga Segar',            'bunga_segar',      20, 'buah',   10, 1),
('Snack Mix',              'snack',            30, 'pack',   10, 1),
('Uang Dekorasi',          'uang',             10, 'lembar', 3,  1),
('Pita Satin Premium',     'pita_premium',     10, 'rol',    3,  1),
('Coklat Hiasan',          'coklat',           25, 'buah',   5,  1),
('Boneka Kecil',           'boneka',           10, 'buah',   3,  1);
