<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/KeuanganModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class KeuanganController {

    private $keuanganModel;

    public function __construct() {
        $this->keuanganModel = new KeuanganModel();
    }

    public function index() {
        cekLogin();
        $bulan            = $_GET['bulan'] ?? date('m');
        $tahun            = $_GET['tahun'] ?? date('Y');
        $totalPemasukan   = $this->keuanganModel->getTotalPemasukan($bulan, $tahun);
        $totalPengeluaran = $this->keuanganModel->getTotalPengeluaran($bulan, $tahun);
        $keuntungan       = $totalPemasukan - $totalPengeluaran;
        $transaksiMasuk   = $this->keuanganModel->getTransaksiMasuk($bulan, $tahun);
        $transaksiKeluar  = $this->keuanganModel->getTransaksiKeluar($bulan, $tahun);
        $rekapPerBulan    = $this->keuanganModel->getRekapPerBulan($tahun);
        require_once __DIR__ . '/../views/admin/keuangan.php';
    }

    public function tambahPengeluaran() {
        cekLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=keuangan');
            exit;
        }

        $keterangan = trim($_POST['keterangan'] ?? '');
        $jumlah     = (int)($_POST['jumlah'] ?? 0);
        $tanggal    = trim($_POST['tanggal'] ?? date('Y-m-d'));

        if (empty($keterangan) || $jumlah <= 0) {
            $_SESSION['error'] = 'Keterangan dan jumlah wajib diisi.';
            header('Location: index.php?page=keuangan');
            exit;
        }

        $hasil = $this->keuanganModel->simpanTransaksi([
            'keterangan' => $keterangan,
            'jumlah'     => $jumlah,
            'tipe'       => 'keluar',
            'tanggal'    => $tanggal,
        ]);

        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Pengeluaran berhasil dicatat.'
            : 'Gagal mencatat pengeluaran.';
        header('Location: index.php?page=keuangan');
        exit;
    }

    public function hapusPengeluaran($id) {
        cekLogin();
        $hasil = $this->keuanganModel->hapusTransaksi($id);
        $_SESSION[$hasil ? 'sukses' : 'error'] = $hasil
            ? 'Catatan berhasil dihapus.'
            : 'Gagal menghapus catatan.';
        header('Location: index.php?page=keuangan');
        exit;
    }
}
