<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/StokModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class StokController {

    private $stokModel;

    public function __construct() {
        $this->stokModel = new StokModel();
    }

    public function index() {
        cekLogin();
        $stok         = $this->stokModel->getAll();
        $stokMenuipis = $this->stokModel->getStokMenuipis();
        require_once __DIR__ . '/../views/admin/stok_list.php';
    }

    public function tambah() {
        cekLogin();
        require_once __DIR__ . '/../views/admin/stok_form.php';
    }

    public function simpan() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=stok');
            exit;
        }

        $nama_bahan   = trim($_POST['nama_bahan'] ?? '');
        $jumlah       = (int)($_POST['jumlah'] ?? 0);
        $satuan       = trim($_POST['satuan'] ?? '');
        $stok_minimum = (int)($_POST['stok_minimum'] ?? 5);

        if (empty($nama_bahan)) {
            $_SESSION['error'] = 'Nama bahan wajib diisi.';
            header('Location: index.php?page=stok_tambah');
            exit;
        }

        $diupdate_oleh = $_SESSION['admin_id']; // FK ke tabel admin
        $hasil = $this->stokModel->simpan(compact('nama_bahan', 'jumlah', 'satuan', 'stok_minimum', 'diupdate_oleh'));
        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Bahan berhasil ditambahkan.'
            : 'Gagal menambahkan bahan.';
        header('Location: index.php?page=stok');
        exit;
    }

    public function edit($id) {
        cekLogin();
        $bahan = $this->stokModel->getById($id);
        if (!$bahan) {
            $_SESSION['error'] = 'Bahan tidak ditemukan.';
            header('Location: index.php?page=stok');
            exit;
        }
        require_once __DIR__ . '/../views/admin/stok_form.php';
    }

    public function update() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=stok');
            exit;
        }

        $id           = (int)($_POST['id'] ?? 0);
        $nama_bahan   = trim($_POST['nama_bahan'] ?? '');
        $jumlah       = (int)($_POST['jumlah'] ?? 0);
        $satuan       = trim($_POST['satuan'] ?? '');
        $stok_minimum = (int)($_POST['stok_minimum'] ?? 5);

        $diupdate_oleh = $_SESSION['admin_id']; // FK ke tabel admin
        $hasil = $this->stokModel->update($id, compact('nama_bahan', 'jumlah', 'satuan', 'stok_minimum', 'diupdate_oleh'));
        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Stok berhasil diperbarui.'
            : 'Gagal memperbarui stok.';
        header('Location: index.php?page=stok');
        exit;
    }

    public function hapus($id) {
        cekLogin();
        $hasil = $this->stokModel->hapus($id);
        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Bahan berhasil dihapus.'
            : 'Gagal menghapus bahan.';
        header('Location: index.php?page=stok');
        exit;
    }
}
