<?php
require_once __DIR__ . '/../config/database.php';

class PesananModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAllUrut() {
        $sql = "SELECT p.*, k.nama AS nama_produk 
                FROM pesanan p 
                LEFT JOIN katalog k ON p.produk_id = k.id 
                ORDER BY p.tanggal_pesan ASC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare(
            "SELECT p.*, k.nama AS nama_produk, k.foto 
             FROM pesanan p 
             LEFT JOIN katalog k ON p.produk_id = k.id 
             WHERE p.id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getPesananHariIni() {
        $hari = date('Y-m-d');
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) AS total FROM pesanan WHERE DATE(tanggal_pesan) = ?"
        );
        $stmt->bind_param("s", $hari);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    public function getPesananBelumLunas() {
        $sql = "SELECT p.*, k.nama AS nama_produk 
                FROM pesanan p 
                LEFT JOIN katalog k ON p.produk_id = k.id
                WHERE p.status_bayar != 'lunas' AND p.status != 'dibatalkan'
                ORDER BY p.tanggal_ambil ASC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getPesananSegeraDikirim() {
        $batas = date('Y-m-d', strtotime('+3 days'));
        $hari  = date('Y-m-d');
        $stmt  = $this->db->prepare(
            "SELECT p.*, k.nama AS nama_produk 
             FROM pesanan p 
             LEFT JOIN katalog k ON p.produk_id = k.id
             WHERE p.tanggal_ambil BETWEEN ? AND ? 
             AND p.status NOT IN ('selesai','dibatalkan')
             ORDER BY p.tanggal_ambil ASC"
        );
        $stmt->bind_param("ss", $hari, $batas);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getTotalPerStatus() {
        $sql = "SELECT status, COUNT(*) AS total FROM pesanan GROUP BY status";
        $result = $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
        $data = ['pending'=>0,'diproses'=>0,'selesai'=>0,'dibatalkan'=>0];
        foreach ($result as $r) $data[$r['status']] = $r['total'];
        return $data;
    }

    // Hitung pesanan baru/masih pending untuk badge notifikasi sidebar
    public function getCountPesananPending() {
        $sql = "SELECT COUNT(*) AS total FROM pesanan WHERE status = 'pending'";
        return $this->db->query($sql)->fetch_assoc()['total'];
    }

    public function getRiwayatSelesai() {
        $sql = "SELECT p.*, k.nama AS nama_produk 
                FROM pesanan p 
                LEFT JOIN katalog k ON p.produk_id = k.id 
                WHERE p.status = 'selesai' 
                ORDER BY p.tanggal_pesan DESC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function simpan($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO pesanan 
            (produk_id, nama_pemesan, no_wa, jumlah, tanggal_pesan, tanggal_ambil, 
             ucapan, is_custom, warna_kertas, jenis_isi, tambahan, total_harga, 
             tipe_pengambilan, status, status_bayar, created_at) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,'pending','belum_lunas',NOW())"
        );
        $stmt->bind_param(
            "ississsisssis",
            $data['produk_id'], $data['nama_pemesan'], $data['no_wa'],
            $data['jumlah'], $data['tanggal_pesan'], $data['tanggal_ambil'],
            $data['ucapan'], $data['is_custom'], $data['warna_kertas'],
            $data['jenis_isi'], $data['tambahan'], $data['total_harga'],
            $data['tipe_pengambilan']
        );
        $stmt->execute();
        return $this->db->insert_id;
    }

    public function ubahStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    public function ubahStatusBayar($id, $statusBayar) {
        $stmt = $this->db->prepare("UPDATE pesanan SET status_bayar = ? WHERE id = ?");
        $stmt->bind_param("si", $statusBayar, $id);
        return $stmt->execute();
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM pesanan WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
