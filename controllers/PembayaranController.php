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

    public function index() {
        cekLogin();
        $pembayaran        = $this->pembayaranModel->getAllBelumVerifikasi();
        $pesananBelumLunas = $this->pesananModel->getPesananBelumLunas();
        require_once __DIR__ . '/../views/admin/pembayaran_list.php';
    }

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

    public function konfirmasi() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pembayaran');
            exit;
        }
        $id         = (int)($_POST['id'] ?? 0);
        $pembayaran = $this->pembayaranModel->getById($id);
        $hasil      = $this->pembayaranModel->konfirmasi($id);

        if ($hasil) {
            $isLunas = $pembayaran['tipe_bayar'] === 'lunas';
            $this->pesananModel->ubahStatus($pembayaran['pesanan_id'], 'diproses');
            $this->pembayaranModel->updateStatusBayarPesanan($pembayaran['pesanan_id'], $isLunas ? 'lunas' : 'dp');
            $_SESSION['sukses'] = $isLunas
                ? '✅ Pembayaran LUNAS dikonfirmasi.'
                : '💳 Pembayaran DP dikonfirmasi. Sisa via COD saat pengambilan.';
        } else {
            $_SESSION['error'] = 'Gagal mengkonfirmasi pembayaran.';
        }
        header('Location: index.php?page=pembayaran');
        exit;
    }

    public function tolak() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pembayaran');
            exit;
        }
        $id     = (int)($_POST['id'] ?? 0);
        $alasan = trim($_POST['alasan'] ?? '');
        $hasil  = $this->pembayaranModel->tolak($id, $alasan);

        if ($hasil) {
            $pembayaran = $this->pembayaranModel->getById($id);
            $this->pesananModel->ubahStatus($pembayaran['pesanan_id'], 'pending');
            $_SESSION['sukses'] = 'Pembayaran ditolak. Pesanan dikembalikan ke Pending.';
        } else {
            $_SESSION['error'] = 'Gagal menolak pembayaran.';
        }
        header('Location: index.php?page=pembayaran');
        exit;
    }

    public function formCod() {
        cekLogin();
        $pesananBelumLunas = $this->pesananModel->getPesananBelumLunas();
        $pesananId         = (int)($_GET['pesanan_id'] ?? 0);
        $pesanan           = $pesananId ? $this->pesananModel->getById($pesananId) : null;
        require_once __DIR__ . '/../views/admin/pembayaran_cod.php';
    }

    public function simpanCod() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=pembayaran');
            exit;
        }
        $pesananId  = (int)($_POST['pesanan_id'] ?? 0);
        $jumlahCod  = (int)($_POST['jumlah_cod'] ?? 0);
        $tanggalCod = trim($_POST['tanggal_cod'] ?? date('Y-m-d'));
        $catatan    = trim($_POST['catatan'] ?? '');

        if (!$pesananId || $jumlahCod <= 0) {
            $_SESSION['error'] = 'Data tidak valid.';
            header('Location: index.php?page=pembayaran&action=form_cod');
            exit;
        }

        $hasil = $this->pembayaranModel->simpanCod($pesananId, $jumlahCod, $tanggalCod, $catatan);
        if ($hasil) {
            $this->pembayaranModel->updateStatusBayarPesanan($pesananId, 'lunas');
            $this->pesananModel->ubahStatus($pesananId, 'selesai');
            $_SESSION['sukses'] = '✅ Pelunasan COD berhasil dicatat. Pesanan ditandai Lunas & Selesai.';
        } else {
            $_SESSION['error'] = 'Gagal mencatat pelunasan COD.';
        }
        header('Location: index.php?page=pembayaran');
        exit;
    }

    public function uploadBukti() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=katalog');
            exit;
        }

        $pesananId = (int)($_POST['pesanan_id'] ?? 0);
        $tipeBayar = $_POST['tipe_bayar'] ?? 'dp';

        if (!in_array($tipeBayar, ['dp', 'lunas']) || !$pesananId) {
            $_SESSION['error'] = 'Data tidak valid.';
            header('Location: index.php?page=katalog');
            exit;
        }

        if (!isset($_FILES['bukti_bayar']) || $_FILES['bukti_bayar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Gagal mengupload file.';
            header('Location: index.php?page=pesan&action=upload&id=' . $pesananId . '&mode=' . $tipeBayar);
            exit;
        }

        $file     = $_FILES['bukti_bayar'];
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ekstensi, ['jpg', 'jpeg', 'png', 'pdf'])) {
            $_SESSION['error'] = 'Format file tidak didukung.';
            header('Location: index.php?page=pesan&action=upload&id=' . $pesananId . '&mode=' . $tipeBayar);
            exit;
        }

        $dirBukti = __DIR__ . '/../assets/img/bukti/';
        if (!is_dir($dirBukti)) mkdir($dirBukti, 0755, true);

        $namaFile = 'bukti_' . $pesananId . '_' . time() . '.' . $ekstensi;

        if (!move_uploaded_file($file['tmp_name'], $dirBukti . $namaFile)) {
            $_SESSION['error'] = 'Gagal menyimpan file.';
            header('Location: index.php?page=pesan&action=upload&id=' . $pesananId . '&mode=' . $tipeBayar);
            exit;
        }

        $hasil = $this->pembayaranModel->simpanBukti($pesananId, $namaFile, $tipeBayar);
        if ($hasil) {
            $this->pembayaranModel->kirimNotifikasiAdmin($pesananId);
            header('Location: index.php?page=sukses');
        } else {
            $_SESSION['error'] = 'Gagal menyimpan data pembayaran.';
            header('Location: index.php?page=pesan&action=upload&id=' . $pesananId . '&mode=' . $tipeBayar);
        }
        exit;
    }
}
