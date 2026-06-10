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

    public function index() {
        cekLogin();
        $pesananHariIni  = $this->pesananModel->getPesananHariIni();
        $semuaPesanan    = $this->pesananModel->getAllUrut();
        $belumLunas      = $this->pesananModel->getPesananBelumLunas();
        $segeraDikirim   = $this->pesananModel->getPesananSegeraDikirim();
        $rekapBulanIni   = $this->keuanganModel->getRekapBulanIni();
        $totalPerStatus  = $this->pesananModel->getTotalPerStatus();
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function riwayat() {
        cekLogin();
        $riwayat = $this->pesananModel->getRiwayatSelesai();
        require_once __DIR__ . '/../views/admin/riwayat.php';
    }
}
