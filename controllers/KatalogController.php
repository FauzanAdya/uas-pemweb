<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/KatalogModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class KatalogController {

    private $katalogModel;

    public function __construct() {
        $this->katalogModel = new KatalogModel();
    }

    // Tampilkan katalog untuk pelanggan (tanpa login)
    public function index() {
        $katalog = $this->katalogModel->getAll();
        require_once __DIR__ . '/../views/pelanggan/katalog.php';
    }

    // Tampilkan daftar katalog untuk admin
    public function adminIndex() {
        cekLogin();
        $katalog = $this->katalogModel->getAll();
        require_once __DIR__ . '/../views/admin/katalog_list.php';
    }

    // Form tambah produk baru
    public function tambah() {
        cekLogin();
        require_once __DIR__ . '/../views/admin/katalog_form.php';
    }

    // Proses simpan produk baru
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

        // Handle upload foto produk
        $namaFoto = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['foto'];
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $boleh    = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ekstensi, $boleh)) {
                $namaFoto = 'produk_' . time() . '.' . $ekstensi;
                move_uploaded_file($file['tmp_name'], __DIR__ . '/../assets/img/' . $namaFoto);
            }
        }

        $data = [
            'nama'        => $nama,
            'deskripsi'   => $deskripsi,
            'harga_dasar' => $harga_dasar,
            'kategori'    => $kategori,
            'foto'        => $namaFoto,
        ];

        $hasil = $this->katalogModel->simpan($data);

        if ($hasil) {
            $_SESSION['sukses'] = 'Produk berhasil ditambahkan ke katalog.';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan produk.';
        }

        header('Location: index.php?page=katalog_admin');
        exit;
    }

    // Form edit produk
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

    // Proses update produk
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

        if (!$id || empty($nama) || $harga_dasar <= 0) {
            $_SESSION['error'] = 'Data tidak lengkap.';
            header('Location: index.php?page=katalog_admin');
            exit;
        }

        $data = [
            'nama'        => $nama,
            'deskripsi'   => $deskripsi,
            'harga_dasar' => $harga_dasar,
            'kategori'    => $kategori,
        ];

        // Handle upload foto baru jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $file     = $_FILES['foto'];
            $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $boleh    = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ekstensi, $boleh)) {
                $namaFoto = 'produk_' . time() . '.' . $ekstensi;
                move_uploaded_file($file['tmp_name'], __DIR__ . '/../assets/img/' . $namaFoto);
                $data['foto'] = $namaFoto;
            }
        }

        $hasil = $this->katalogModel->update($id, $data);

        if ($hasil) {
            $_SESSION['sukses'] = 'Produk berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Gagal memperbarui produk.';
        }

        header('Location: index.php?page=katalog_admin');
        exit;
    }

    // Hapus produk dari katalog
    public function hapus($id) {
        cekLogin();
        $hasil = $this->katalogModel->hapus($id);

        if ($hasil) {
            $_SESSION['sukses'] = 'Produk berhasil dihapus dari katalog.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus produk.';
        }

        header('Location: index.php?page=katalog_admin');
        exit;
    }
}
