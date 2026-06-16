<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PesananModel.php';
require_once __DIR__ . '/../models/KatalogModel.php';

class PemesananController
{
    private $pesananModel;
    private $katalogModel;

    public function __construct()
    {
        $this->pesananModel = new PesananModel();
        $this->katalogModel = new KatalogModel();
    }

    public function formPesan($produkId)
    {
        $produk = $this->katalogModel->getById($produkId);

        if (!$produk) {
            header('Location: index.php?page=katalog');
            exit;
        }

        require_once __DIR__ . '/../views/pelanggan/pesan_form.php';
    }

    public function formCustom()
    {
        $katalog = $this->katalogModel->getAll();
        require_once __DIR__ . '/../views/pelanggan/custom_form.php';
    }

    public function kalkulasiHarga()
    {
        header('Content-Type: application/json');

        $produkId    = (int)($_POST['produk_id'] ?? 0);
        $jumlah      = (int)($_POST['jumlah'] ?? 1);
        $isCustom    = (bool)($_POST['is_custom'] ?? false);
        $tambahanArr = $_POST['tambahan'] ?? [];

        $produk = $this->katalogModel->getById($produkId);

        if (!$produk) {
            echo json_encode([
                'sukses' => false,
                'pesan'  => 'Produk tidak ditemukan.'
            ]);
            exit;
        }

        $hargaDasar  = $produk['harga_dasar'] * $jumlah;
        $biayaCustom = 0;

        if ($isCustom) {
            $biayaCustom += 15000;

            foreach ($tambahanArr as $t) {
                switch ($t) {
                    case 'pita_premium':
                        $biayaCustom += 5000;
                        break;

                    case 'bunga_segar':
                        $biayaCustom += 20000;
                        break;

                    case 'coklat':
                        $biayaCustom += 10000;
                        break;

                    case 'boneka':
                        $biayaCustom += 25000;
                        break;
                }
            }
        }

        $total = $hargaDasar + $biayaCustom;

        echo json_encode([
            'sukses'      => true,
            'total'       => $total,
            'dp_minimal'  => $total * 0.5,
        ]);
        exit;
    }

    public function simpanPesanan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=katalog');
            exit;
        }

        $nama_pemesan  = trim($_POST['nama_pemesan'] ?? '');
        $no_wa         = trim($_POST['no_wa'] ?? '');
        $produk_id     = (int)($_POST['produk_id'] ?? 0);
        $jumlah        = (int)($_POST['jumlah'] ?? 1);
        $tanggal_ambil = trim($_POST['tanggal_ambil'] ?? '');
        $ucapan        = trim($_POST['ucapan'] ?? '');
        $is_custom     = (int)($_POST['is_custom'] ?? 0);

        /*
         * WARNA KERTAS
         */
        $warna_kertas = $_POST['warna_kertas'] ?? '';

        if (is_array($warna_kertas)) {
            $warna_kertas = implode(', ', $warna_kertas);
        } else {
            $warna_kertas = trim($warna_kertas);
        }

        /*
         * JENIS ISI
         */
        $jenis_isi = $_POST['jenis_isi'] ?? '';

        if (is_array($jenis_isi)) {
            $jenis_isi = implode(', ', $jenis_isi);
        } else {
            $jenis_isi = trim($jenis_isi);
        }

        /*
         * TAMBAHAN
         */
        $tambahan = $_POST['tambahan'] ?? [];

        if (is_array($tambahan)) {
            $tambahan = implode(', ', $tambahan);
        } else {
            $tambahan = trim($tambahan);
        }

        $total_harga      = (int)($_POST['total_harga'] ?? 0);
        $tipe_pengambilan = trim($_POST['tipe_pengambilan'] ?? 'ambil');

        // Validasi
        if (
            empty($nama_pemesan) ||
            empty($no_wa) ||
            empty($produk_id) ||
            empty($tanggal_ambil)
        ) {
            $_SESSION['error'] = 'Data pemesanan tidak lengkap.';

            if ($is_custom) {
                header('Location: index.php?page=custom');
            } else {
                header('Location: index.php?page=pesan&id=' . $produk_id);
            }

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
            'tipe_pengambilan' => $tipe_pengambilan
        ];

        $pesananId = $this->pesananModel->simpan($data);

        if ($pesananId) {

            $_SESSION['pesanan_id'] = $pesananId;
            $_SESSION['sukses'] = 'Pesanan berhasil dibuat.';

            header('Location: index.php?page=pembayaran_info&id=' . $pesananId);
            exit;
        }

        $_SESSION['error'] = 'Gagal menyimpan pesanan.';

        if ($is_custom) {
            header('Location: index.php?page=custom');
        } else {
            header('Location: index.php?page=pesan&id=' . $produk_id);
        }

        exit;
    }
}