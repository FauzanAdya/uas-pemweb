<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/KeuanganModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';
require_once __DIR__ . '/../helpers/format_helper.php';

class KeuanganController {

    private $keuanganModel;

    public function __construct() {
        $this->keuanganModel = new KeuanganModel();
    }

    // Tampilkan halaman rekap keuangan
    public function index() {
        cekLogin();

        $bulan = $_GET['bulan'] ?? date('m');
        $tahun = $_GET['tahun'] ?? date('Y');

        // Total pemasukan dari pesanan lunas bulan ini
        $totalPemasukan = $this->keuanganModel->getTotalPemasukan($bulan, $tahun);

        // Total pengeluaran (pembelian bahan) bulan ini
        $totalPengeluaran = $this->keuanganModel->getTotalPengeluaran($bulan, $tahun);

        // Keuntungan bersih
        $keuntungan = $totalPemasukan - $totalPengeluaran;

        // Daftar transaksi masuk bulan ini
        $transaksiMasuk = $this->keuanganModel->getTransaksiMasuk($bulan, $tahun);

        // Daftar transaksi keluar bulan ini
        $transaksiKeluar = $this->keuanganModel->getTransaksiKeluar($bulan, $tahun);

        // Rekap per bulan untuk grafik
        $rekapPerBulan = $this->keuanganModel->getRekapPerBulan($tahun);

        require_once __DIR__ . '/../views/admin/keuangan.php';
    }

    // Tambah catatan pengeluaran manual (beli bahan)
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

        $data = [
            'keterangan' => $keterangan,
            'jumlah'     => $jumlah,
            'tanggal'    => $tanggal,
            'tipe'       => 'keluar',
        ];

        $hasil = $this->keuanganModel->simpanTransaksi($data);

        if ($hasil) {
            $_SESSION['sukses'] = 'Pengeluaran berhasil dicatat.';
        } else {
            $_SESSION['error'] = 'Gagal mencatat pengeluaran.';
        }

        header('Location: index.php?page=keuangan');
        exit;
    }

    // Hapus catatan pengeluaran
    public function hapusPengeluaran($id) {
        cekLogin();
        $hasil = $this->keuanganModel->hapusTransaksi($id);

        if ($hasil) {
            $_SESSION['sukses'] = 'Catatan pengeluaran berhasil dihapus.';
        } else {
            $_SESSION['error'] = 'Gagal menghapus catatan.';
        }

        header('Location: index.php?page=keuangan');
        exit;
    }
}
