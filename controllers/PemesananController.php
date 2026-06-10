<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../models/KatalogModel.php';
require_once __DIR__ . '/../helpers/format_helper.php';

class PemesananController {

    private $pesananModel;
    private $katalogModel;

    public function __construct() {
        $this->pesananModel = new PesananModel();
        $this->katalogModel = new KatalogModel();
    }

    // Tampilkan form pemesanan berdasarkan produk yang dipilih
    public function formPesan($produkId) {
        $produk = $this->katalogModel->getById($produkId);
        if (!$produk) {
            header('Location: index.php?page=katalog');
            exit;
        }
        require_once __DIR__ . '/../views/pelanggan/pesan_form.php';
    }

    // Tampilkan form custom buket
    public function formCustom() {
        $katalog = $this->katalogModel->getAll();
        require_once __DIR__ . '/../views/pelanggan/custom_form.php';
    }

    // Kalkulasi harga otomatis (dipanggil via AJAX)
    public function kalkulasiHarga() {
        header('Content-Type: application/json');

        $produkId    = (int)($_POST['produk_id'] ?? 0);
        $jumlah      = (int)($_POST['jumlah'] ?? 1);
        $isCustom    = (bool)($_POST['is_custom'] ?? false);
        $tambahanArr = $_POST['tambahan'] ?? [];

        if (!$produkId) {
            echo json_encode(['sukses' => false, 'pesan' => 'Produk tidak valid.']);
            exit;
        }

        $produk = $this->katalogModel->getById($produkId);
        if (!$produk) {
            echo json_encode(['sukses' => false, 'pesan' => 'Produk tidak ditemukan.']);
            exit;
        }

        $hargaDasar = $produk['harga_dasar'] * $jumlah;
        $biayaCustom = 0;

        // Tambahan biaya jika custom
        if ($isCustom) {
            $biayaCustom = 15000; // biaya kustomisasi dasar
            foreach ($tambahanArr as $tambahan) {
                switch ($tambahan) {
                    case 'pita_premium': $biayaCustom += 5000; break;
                    case 'bunga_segar':  $biayaCustom += 20000; break;
                    case 'coklat':       $biayaCustom += 10000; break;
                    case 'boneka':       $biayaCustom += 25000; break;
                }
            }
        }

        $totalHarga = $hargaDasar + $biayaCustom;
        $dpMinimal  = $totalHarga * 0.5;

        echo json_encode([
            'sukses'       => true,
            'harga_dasar'  => formatRupiah($hargaDasar),
            'biaya_custom' => formatRupiah($biayaCustom),
            'total'        => formatRupiah($totalHarga),
            'dp_minimal'   => formatRupiah($dpMinimal),
            'total_angka'  => $totalHarga,
            'dp_angka'     => $dpMinimal,
        ]);
        exit;
    }

    // Proses simpan pesanan dari pelanggan
    public function simpanPesanan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=katalog');
            exit;
        }

        $nama_pemesan   = trim($_POST['nama_pemesan'] ?? '');
        $no_wa          = trim($_POST['no_wa'] ?? '');
        $produk_id      = (int)($_POST['produk_id'] ?? 0);
        $jumlah         = (int)($_POST['jumlah'] ?? 1);
        $tanggal_ambil  = trim($_POST['tanggal_ambil'] ?? '');
        $ucapan         = trim($_POST['ucapan'] ?? '');
        $is_custom      = (int)($_POST['is_custom'] ?? 0);
        $warna_kertas   = trim($_POST['warna_kertas'] ?? '');
        $jenis_isi      = trim($_POST['jenis_isi'] ?? '');
        $tambahan       = trim($_POST['tambahan'] ?? '');
        $total_harga    = (int)($_POST['total_harga'] ?? 0);
        $tipe_pengambilan = trim($_POST['tipe_pengambilan'] ?? 'ambil');

        // Validasi wajib
        if (empty($nama_pemesan) || empty($no_wa) || !$produk_id || empty($tanggal_ambil)) {
            $_SESSION['error'] = 'Data pemesanan tidak lengkap.';
            header('Location: index.php?page=pesan&id=' . $produk_id);
            exit;
        }

        // Validasi format no WA
        if (!preg_match('/^[0-9]{10,15}$/', $no_wa)) {
            $_SESSION['error'] = 'Nomor WhatsApp tidak valid.';
            header('Location: index.php?page=pesan&id=' . $produk_id);
            exit;
        }

        $data = [
            'nama_pemesan'     => $nama_pemesan,
            'no_wa'            => $no_wa,
            'produk_id'        => $produk_id,
            'jumlah'           => $jumlah,
            'tanggal_pesan'    => date('Y-m-d H:i:s'),
            'tanggal_ambil'    => $tanggal_ambil,
            'ucapan'           => $ucapan,
            'is_custom'        => $is_custom,
            'warna_kertas'     => $warna_kertas,
            'jenis_isi'        => $jenis_isi,
            'tambahan'         => $tambahan,
            'total_harga'      => $total_harga,
            'tipe_pengambilan' => $tipe_pengambilan,
            'status'           => 'pending',
        ];

        $pesananId = $this->pesananModel->simpan($data);

        if ($pesananId) {
            // Simpan id pesanan di session untuk proses pembayaran
            $_SESSION['pesanan_id'] = $pesananId;
            header('Location: index.php?page=pembayaran_pelanggan&id=' . $pesananId);
        } else {
            $_SESSION['error'] = 'Gagal menyimpan pesanan. Silakan coba lagi.';
            header('Location: index.php?page=pesan&id=' . $produk_id);
        }
        exit;
    }
}
