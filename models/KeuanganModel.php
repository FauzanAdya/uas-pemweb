<?php
require_once __DIR__ . '/../config/database.php';

class KeuanganModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getTotalPemasukan($bulan, $tahun) {
        $stmt = $this->db->prepare(
            "SELECT COALESCE(SUM(p.total_harga),0) AS total 
             FROM pesanan p
             WHERE p.status = 'selesai' 
             AND MONTH(p.tanggal_pesan) = ? AND YEAR(p.tanggal_pesan) = ?"
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
            "SELECT p.id, p.nama_pemesan, p.total_harga, p.tanggal_pesan, p.status
             FROM pesanan p
             WHERE p.status = 'selesai'
             AND MONTH(p.tanggal_pesan) = ? AND YEAR(p.tanggal_pesan) = ?
             ORDER BY p.tanggal_pesan DESC"
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
        return [
            'pemasukan'   => $this->getTotalPemasukan($bulan, $tahun),
            'pengeluaran' => $this->getTotalPengeluaran($bulan, $tahun),
            'keuntungan'  => $this->getTotalPemasukan($bulan, $tahun) - $this->getTotalPengeluaran($bulan, $tahun),
        ];
    }

    public function getRekapPerBulan($tahun) {
        $stmt = $this->db->prepare(
            "SELECT MONTH(tanggal_pesan) AS bulan, COALESCE(SUM(total_harga),0) AS total
             FROM pesanan WHERE status = 'selesai' AND YEAR(tanggal_pesan) = ?
             GROUP BY MONTH(tanggal_pesan) ORDER BY bulan ASC"
        );
        $stmt->bind_param("i", $tahun);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function simpanTransaksi($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO keuangan (keterangan, jumlah, tipe, tanggal) VALUES (?,?,?,?)"
        );
        $stmt->bind_param("siss", $data['keterangan'], $data['jumlah'], $data['tipe'], $data['tanggal']);
        return $stmt->execute();
    }

    public function hapusTransaksi($id) {
        $stmt = $this->db->prepare("DELETE FROM keuangan WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
