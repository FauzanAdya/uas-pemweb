<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../models/KeuanganModel.php';
require_once __DIR__ . '/../helpers/auth_helper.php';

class DashboardController {

    private $pesananModel;
    private $keuanganModel;

    public function __construct() {
        $this->pesananModel  = new PesananModel();
        $this->keuanganModel = new KeuanganModel();
    }

    // Tampilkan dashboard utama admin
    public function index() {
        cekLogin();

        // Pesanan hari ini
        $pesananHariIni = $this->pesananModel->getPesananHariIni();

        // Semua pesanan urut dari pertama masuk + tanggal pesan & tanggal ambil
        $semuaPesanan = $this->pesananModel->getAllUrut();

        // Pesanan belum lunas
        $belumLunas = $this->pesananModel->getPesananBelumLunas();

        // Pesanan harus segera dikirim (3 hari ke depan)
        $segeraDikirim = $this->pesananModel->getPesananSegeraDikirim();

        // Rekap keuangan ringkasan bulan ini
        $rekapBulanIni = $this->keuanganModel->getRekapBulanIni();

        // Total pesanan per status
        $totalPerStatus = $this->pesananModel->getTotalPerStatus();

        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // Tampilkan riwayat semua pesanan selesai
    public function riwayat() {
        cekLogin();
        $riwayat = $this->pesananModel->getRiwayatSelesai();
        require_once __DIR__ . '/../views/admin/riwayat.php';
    }
}
