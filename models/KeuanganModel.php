<?php
require_once __DIR__ . '/../config/database.php';

class KeuanganModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getTotalPemasukan($bulan, $tahun) {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(jumlah),0) AS total FROM keuangan 
             WHERE tipe = 'masuk' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?"
        );
        $stmt->bind_param("ii", $bulan, $tahun);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTotalPengeluaran($bulan, $tahun) {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(jumlah),0) AS total FROM keuangan 
             WHERE tipe = 'keluar' AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?"
        );
        $stmt->bind_param("ii", $bulan, $tahun);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getTransaksiMasuk($bulan, $tahun) {
        $stmt = $this->db->prepare(
            "SELECT k.*, p.nama_pemesan 
             FROM keuangan k
             LEFT JOIN pesanan p ON k.pesanan_id = p.id
             WHERE k.tipe = 'masuk'
             AND MONTH(k.tanggal) = ? AND YEAR(k.tanggal) = ?
             ORDER BY k.tanggal DESC"
        );
        $stmt->bind_param("ii", $bulan, $tahun);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTransaksiKeluar($bulan, $tahun) {
        $stmt = $this->db->prepare(
            "SELECT * FROM keuangan WHERE tipe = 'keluar'
             AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?
             ORDER BY tanggal DESC"
        );
        $stmt->bind_param("ii", $bulan, $tahun);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getRekapBulanIni() {
        $bulan = date('m');
        $tahun = date('Y');
        $pemasukan   = $this->getTotalPemasukan($bulan, $tahun);
        $pengeluaran = $this->getTotalPengeluaran($bulan, $tahun);
        return [
            'pemasukan'   => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'keuntungan'  => $pemasukan - $pengeluaran,
        ];
    }

    public function getRekapPerBulan($tahun) {
        $stmt = $this->db->prepare(
            "SELECT MONTH(tanggal) AS bulan, 
                    SUM(CASE WHEN tipe='masuk' THEN jumlah ELSE 0 END) AS masuk,
                    SUM(CASE WHEN tipe='keluar' THEN jumlah ELSE 0 END) AS keluar
             FROM keuangan WHERE YEAR(tanggal) = ?
             GROUP BY MONTH(tanggal) ORDER BY bulan ASC"
        );
        $stmt->bind_param("i", $tahun);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // pesanan_id boleh NULL (pengeluaran manual), dicatat_oleh wajib diisi
    public function simpanTransaksi($data) {
        $pesananId = $data['pesanan_id'] ?? null;
        $stmt = $this->db->prepare(
            "INSERT INTO keuangan (pesanan_id, dicatat_oleh, keterangan, jumlah, tipe, tanggal, created_at) 
             VALUES (?,?,?,?,?,?,NOW())"
        );
        $stmt->bind_param(
            "iisiss",
            $pesananId, $data['dicatat_oleh'], $data['keterangan'],
            $data['jumlah'], $data['tipe'], $data['tanggal']
        );
        return $stmt->execute();
    }

    public function hapusTransaksi($id) {
        $stmt = $this->db->prepare("DELETE FROM keuangan WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
