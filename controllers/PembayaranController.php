<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PembayaranModel.php';
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class PembayaranController {

    private $pembayaranModel;
    private $pesananModel;

    public function __construct() {
        $this->pembayaranModel = new PembayaranModel();
        $this->pesananModel    = new PesananModel();
    }

    // Daftar semua pembayaran yang perlu diverifikasi
    public function index() {
        cekLogin();
        $pembayaran = $this->pembayaranModel->getAllBelumVerifikasi();
        require_once __DIR__ . '/../views/admin/pembayaran_list.php';
    }

    // Tampilkan halaman verifikasi bukti pembayaran
    public function verifikasi($id) {
        cekLogin();
        $pembayaran = $this->pembayaranModel->getById($id);
        if (!$pembayaran) {
            $_SESSION['error'] = 'Data pembayaran tidak ditemukan.';
            header('Location: index.php?page=pembayaran');
            exit;
        }
        require_once __DIR__ . '/../views/admin/pembayaran_verifikasi.php';
    }

    // Proses konfirmasi pembayaran (valid)
    public function konfirmasi() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pembayaran');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: index.php?page=pembayaran');
            exit;
        }

        $hasil = $this->pembayaranModel->konfirmasi($id);

        if ($hasil) {
            // Otomatis ubah status pesanan terkait menjadi diproses
            $pembayaran = $this->pembayaranModel->getById($id);
            $this->pesananModel->ubahStatus($pembayaran['pesanan_id'], 'diproses');
            $_SESSION['sukses'] = 'Pembayaran berhasil dikonfirmasi. Status pesanan diubah ke Diproses.';
        } else {
            $_SESSION['error'] = 'Gagal mengkonfirmasi pembayaran.';
        }

        header('Location: index.php?page=pembayaran');
        exit;
    }

    // Proses tolak pembayaran (tidak valid)
    public function tolak() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pembayaran');
            exit;
        }

        $id     = (int)($_POST['id'] ?? 0);
        $alasan = trim($_POST['alasan'] ?? '');

        if (!$id) {
            $_SESSION['error'] = 'ID tidak valid.';
            header('Location: index.php?page=pembayaran');
            exit;
        }

        $hasil = $this->pembayaranModel->tolak($id, $alasan);

        if ($hasil) {
            $pembayaran = $this->pembayaranModel->getById($id);
            $this->pesananModel->ubahStatus($pembayaran['pesanan_id'], 'pending');
            $_SESSION['sukses'] = 'Pembayaran ditolak. Status pesanan dikembalikan ke Pending.';
        } else {
            $_SESSION['error'] = 'Gagal menolak pembayaran.';
        }

        header('Location: index.php?page=pembayaran');
        exit;
    }

    // Upload bukti pembayaran oleh pelanggan
    public function uploadBukti() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=katalog');
            exit;
        }

        $pesananId = (int)($_POST['pesanan_id'] ?? 0);
        $tipeBayar = $_POST['tipe_bayar'] ?? 'dp';

        if (!$pesananId) {
            $_SESSION['error'] = 'Data pesanan tidak valid.';
            header('Location: index.php?page=katalog');
            exit;
        }

        // Handle upload file bukti
        if (!isset($_FILES['bukti_bayar']) || $_FILES['bukti_bayar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Gagal mengupload file bukti pembayaran.';
            header('Location: index.php?page=pembayaran_pelanggan&id=' . $pesananId);
            exit;
        }

        $file      = $_FILES['bukti_bayar'];
        $ekstensi  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $boleh     = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($ekstensi, $boleh)) {
            $_SESSION['error'] = 'Format file tidak didukung. Gunakan JPG, PNG, atau PDF.';
            header('Location: index.php?page=pembayaran_pelanggan&id=' . $pesananId);
            exit;
        }

        $namaFile = 'bukti_' . $pesananId . '_' . time() . '.' . $ekstensi;
        $tujuan   = __DIR__ . '/../assets/img/bukti/' . $namaFile;

        if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
            $_SESSION['error'] = 'Gagal menyimpan file.';
            header('Location: index.php?page=pembayaran_pelanggan&id=' . $pesananId);
            exit;
        }

        $hasil = $this->pembayaranModel->simpanBukti($pesananId, $namaFile, $tipeBayar);

        if ($hasil) {
            // Kirim notifikasi ke admin (simpan di tabel notifikasi)
            $this->pembayaranModel->kirimNotifikasiAdmin($pesananId);
            header('Location: index.php?page=sukses');
        } else {
            $_SESSION['error'] = 'Gagal menyimpan data pembayaran.';
            header('Location: index.php?page=pembayaran_pelanggan&id=' . $pesananId);
        }
        exit;
    }
}
