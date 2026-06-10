<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class PesananController {

    private $pesananModel;

    public function __construct() {
        $this->pesananModel = new PesananModel();
    }

    public function index() {
        cekLogin();
        $pesanan = $this->pesananModel->getAllUrut();
        require_once __DIR__ . '/../views/admin/pesanan_list.php';
    }

    public function detail($id) {
        cekLogin();
        $pesanan = $this->pesananModel->getById($id);
        if (!$pesanan) {
            $_SESSION['error'] = 'Pesanan tidak ditemukan.';
            header('Location: index.php?page=pesanan');
            exit;
        }
        require_once __DIR__ . '/../views/admin/pesanan_detail.php';
    }

    public function ubahStatus() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pesanan');
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $valid  = ['diproses', 'pending', 'selesai', 'dibatalkan'];

        if (!$id || !in_array($status, $valid)) {
            $_SESSION['error'] = 'Data tidak valid.';
            header('Location: index.php?page=pesanan');
            exit;
        }

        $hasil = $this->pesananModel->ubahStatus($id, $status);
        if ($hasil) {
            $_SESSION['sukses'] = 'Status pesanan berhasil diubah menjadi ' . ucfirst($status) . '.';
        } else {
            $_SESSION['error'] = 'Gagal mengubah status pesanan.';
        }

        header('Location: index.php?page=pesanan&action=detail&id=' . $id);
        exit;
    }

    public function hapus($id) {
        cekLogin();
        $hasil = $this->pesananModel->hapus($id);
        if ($hasil) {
            $_SESSION['sukses'] = 'Pesanan berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus pesanan.';
        }
        header('Location: index.php?page=pesanan');
        exit;
    }
}
