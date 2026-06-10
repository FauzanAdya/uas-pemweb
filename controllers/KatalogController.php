<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/KatalogModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class KatalogController {

    private $katalogModel;

    public function __construct() {
        $this->katalogModel = new KatalogModel();
    }

    public function index() {
        $katalog = $this->katalogModel->getAll();
        require_once __DIR__ . '/../views/pelanggan/katalog.php';
    }

    public function adminIndex() {
        cekLogin();
        $katalog = $this->katalogModel->getAll();
        require_once __DIR__ . '/../views/admin/katalog_list.php';
    }

    public function tambah() {
        cekLogin();
        require_once __DIR__ . '/../views/admin/katalog_form.php';
    }

    public function simpan() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=katalog_admin');
            exit;
        }

        $nama        = trim($_POST['nama'] ?? '');
        $deskripsi   = trim($_POST['deskripsi'] ?? '');
        $harga_dasar = (int)($_POST['harga_dasar'] ?? 0);
        $kategori    = trim($_POST['kategori'] ?? '');

        if (empty($nama) || $harga_dasar <= 0) {
            $_SESSION['error'] = 'Nama produk dan harga wajib diisi.';
            header('Location: index.php?page=katalog_tambah');
            exit;
        }

        $namaFoto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['foto'];
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ekstensi, ['jpg', 'jpeg', 'png', 'webp'])) {
                $namaFoto = 'produk_' . time() . '.' . $ekstensi;
                move_uploaded_file($file['tmp_name'], __DIR__ . '/../assets/img/' . $namaFoto);
            }
        }

        $hasil = $this->katalogModel->simpan([
            'nama'        => $nama,
            'deskripsi'   => $deskripsi,
            'harga_dasar' => $harga_dasar,
            'kategori'    => $kategori,
            'foto'        => $namaFoto,
        ]);

        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Produk berhasil ditambahkan.'
            : 'Gagal menambahkan produk.';
        header('Location: index.php?page=katalog_admin');
        exit;
    }

    public function edit($id) {
        cekLogin();
        $produk = $this->katalogModel->getById($id);
        if (!$produk) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: index.php?page=katalog_admin');
            exit;
        }
        require_once __DIR__ . '/../views/admin/katalog_form.php';
    }

    public function update() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=katalog_admin');
            exit;
        }

        $id          = (int)($_POST['id'] ?? 0);
        $nama        = trim($_POST['nama'] ?? '');
        $deskripsi   = trim($_POST['deskripsi'] ?? '');
        $harga_dasar = (int)($_POST['harga_dasar'] ?? 0);
        $kategori    = trim($_POST['kategori'] ?? '');
        $data        = compact('nama', 'deskripsi', 'harga_dasar', 'kategori');

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['foto'];
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ekstensi, ['jpg', 'jpeg', 'png', 'webp'])) {
                $namaFoto = 'produk_' . time() . '.' . $ekstensi;
                move_uploaded_file($file['tmp_name'], __DIR__ . '/../assets/img/' . $namaFoto);
                $data['foto'] = $namaFoto;
            }
        }

        $hasil = $this->katalogModel->update($id, $data);
        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Produk berhasil diperbarui.'
            : 'Gagal memperbarui produk.';
        header('Location: index.php?page=katalog_admin');
        exit;
    }

    public function hapus($id) {
        cekLogin();
        $hasil = $this->katalogModel->hapus($id);
        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Produk berhasil dihapus.'
            : 'Gagal menghapus produk.';
        header('Location: index.php?page=katalog_admin');
        exit;
    }
}
