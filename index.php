<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$page   = $_GET['page']   ?? 'katalog';
$action = $_GET['action'] ?? 'index';
$id     = (int)($_GET['id'] ?? 0);

switch ($page) {
    case 'login':
        require_once __DIR__ . '/controllers/AuthController.php';
        $c = new AuthController();
        ($action === 'proses') ? $c->prosesLogin() : $c->login();
        break;

    case 'logout':
        require_once __DIR__ . '/controllers/AuthController.php';
        (new AuthController())->logout();
        break;

    case 'dashboard':
        require_once __DIR__ . '/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    case 'riwayat':
        require_once __DIR__ . '/controllers/DashboardController.php';
        (new DashboardController())->riwayat();
        break;

    case 'pesanan':
        require_once __DIR__ . '/controllers/PesananController.php';
        $c = new PesananController();
        if ($action === 'detail')      $c->detail($id);
        elseif ($action === 'status')  $c->ubahStatus();
        elseif ($action === 'hapus')   $c->hapus($id);
        else                           $c->index();
        break;

    case 'pembayaran':
        require_once __DIR__ . '/controllers/PembayaranController.php';
        $c = new PembayaranController();
        if ($action === 'verifikasi')  $c->verifikasi($id);
        elseif ($action === 'konfirmasi') $c->konfirmasi();
        elseif ($action === 'tolak')   $c->tolak();
        else                           $c->index();
        break;

    case 'katalog_admin':
        require_once __DIR__ . '/controllers/KatalogController.php';
        $c = new KatalogController();
        if ($action === 'tambah')  $c->tambah();
        elseif ($action === 'simpan') $c->simpan();
        elseif ($action === 'edit')   $c->edit($id);
        elseif ($action === 'update') $c->update();
        elseif ($action === 'hapus')  $c->hapus($id);
        else                          $c->adminIndex();
        break;

    case 'stok':
        require_once __DIR__ . '/controllers/StokController.php';
        $c = new StokController();
        if ($action === 'tambah')     $c->tambah();
        elseif ($action === 'simpan') $c->simpan();
        elseif ($action === 'edit')   $c->edit($id);
        elseif ($action === 'update') $c->update();
        elseif ($action === 'hapus')  $c->hapus($id);
        else                          $c->index();
        break;

    case 'keuangan':
        require_once __DIR__ . '/controllers/KeuanganController.php';
        $c = new KeuanganController();
        if ($action === 'tambah_pengeluaran') $c->tambahPengeluaran();
        elseif ($action === 'hapus')          $c->hapusPengeluaran($id);
        else                                  $c->index();
        break;

    case 'katalog':
        require_once __DIR__ . '/controllers/KatalogController.php';
        (new KatalogController())->index();
        break;

    case 'pesan':
        require_once __DIR__ . '/controllers/PemesananController.php';
        $c = new PemesananController();
        if ($action === 'custom')      $c->formCustom();
        elseif ($action === 'harga')   $c->kalkulasiHarga();
        elseif ($action === 'simpan')  $c->simpanPesanan();
        else                           $c->formPesan($id);
        break;

    case 'pembayaran_pelanggan':
        require_once __DIR__ . '/controllers/PembayaranController.php';
        (new PembayaranController())->uploadBukti();
        break;

    case 'sukses':
        require_once __DIR__ . '/views/pelanggan/sukses.php';
        break;

    default:
        require_once __DIR__ . '/controllers/KatalogController.php';
        (new KatalogController())->index();
        break;
}
