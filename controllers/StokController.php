<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/StokModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class StokController {

    private $stokModel;

    public function __construct() {
        $this->stokModel = new StokModel();
    }

    // Tampilkan daftar semua stok bahan
    public function index() {
        cekLogin();
        $stok         = $this->stokModel->getAll();
        $stokMenuipis = $this->stokModel->getStokMenuipis();
        require_once __DIR__ . '/../views/admin/stok_list.php';
    }

    // Form tambah bahan baru
    public function tambah() {
        cekLogin();
        require_once __DIR__ . '/../views/admin/stok_form.php';
    }

    // Proses simpan bahan baru
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

        if (empty($nama_bahan) || $jumlah < 0) {
            $_SESSION['error'] = 'Nama bahan dan jumlah wajib diisi.';
            header('Location: index.php?page=stok_tambah');
            exit;
        }

        $data = [
            'nama_bahan'   => $nama_bahan,
            'jumlah'       => $jumlah,
            'satuan'       => $satuan,
            'stok_minimum' => $stok_minimum,
        ];

        $hasil = $this->stokModel->simpan($data);

        if ($hasil) {
            $_SESSION['sukses'] = 'Bahan ' . $nama_bahan . ' berhasil ditambahkan.';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan bahan.';
        }

        header('Location: index.php?page=stok');
        exit;
    }

    // Form edit stok bahan
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

    // Proses update stok bahan
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

        if (!$id || empty($nama_bahan)) {
            $_SESSION['error'] = 'Data tidak lengkap.';
            header('Location: index.php?page=stok');
            exit;
        }

        $data = [
            'nama_bahan'   => $nama_bahan,
            'jumlah'       => $jumlah,
            'satuan'       => $satuan,
            'stok_minimum' => $stok_minimum,
        ];

        $hasil = $this->stokModel->update($id, $data);

        if ($hasil) {
            $_SESSION['sukses'] = 'Stok bahan berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui stok.';
        }

        header('Location: index.php?page=stok');
        exit;
    }

    // Hapus bahan dari stok
    public function hapus($id) {
        cekLogin();
        $hasil = $this->stokModel->hapus($id);

        if ($hasil) {
            $_SESSION['sukses'] = 'Bahan berhasil dihapus dari stok.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus bahan.';
        }

        header('Location: index.php?page=stok');
        exit;
    }
}
