<?php
require_once __DIR__ . '/../config/database.php';

class PembayaranModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAllBelumVerifikasi() {
        $sql = "SELECT pb.*, p.nama_pemesan, p.no_wa, p.total_harga, p.tanggal_ambil
                FROM pembayaran pb
                LEFT JOIN pesanan p ON pb.pesanan_id = p.id
                WHERE pb.status_verifikasi = 'menunggu'
                ORDER BY pb.tanggal_upload ASC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare(
            "SELECT pb.*, p.nama_pemesan, p.no_wa, p.total_harga, 
                    p.tanggal_ambil, p.ucapan, p.is_custom
             FROM pembayaran pb
             LEFT JOIN pesanan p ON pb.pesanan_id = p.id
             WHERE pb.id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function simpanBukti($pesananId, $namaFile, $tipeBayar) {
        $stmt = $this->db->prepare(
            "INSERT INTO pembayaran (pesanan_id, file_bukti, tipe_bayar, tanggal_upload, status_verifikasi)
             VALUES (?, ?, ?, NOW(), 'menunggu')"
        );
        $stmt->bind_param("iss", $pesananId, $namaFile, $tipeBayar);
        return $stmt->execute();
    }

    public function konfirmasi($id) {
        $stmt = $this->db->prepare(
            "UPDATE pembayaran SET status_verifikasi = 'diterima', tanggal_verifikasi = NOW() WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            // Update status bayar pesanan
            $pb = $this->getById($id);
            $s  = $this->db->prepare("UPDATE pesanan SET status_bayar = ? WHERE id = ?");
            $tipe = $pb['tipe_bayar'] === 'lunas' ? 'lunas' : 'dp';
            $s->bind_param("si", $tipe, $pb['pesanan_id']);
            $s->execute();
            return true;
        }
        return false;
    }

    public function tolak($id, $alasan) {
        $stmt = $this->db->prepare(
            "UPDATE pembayaran SET status_verifikasi = 'ditolak', alasan_tolak = ?, tanggal_verifikasi = NOW() WHERE id = ?"
        );
        $stmt->bind_param("si", $alasan, $id);
        return $stmt->execute();
    }

    public function kirimNotifikasiAdmin($pesananId) {
        $pesan = "Ada pesanan baru #$pesananId yang menunggu verifikasi pembayaran.";
        $stmt  = $this->db->prepare(
            "INSERT INTO notifikasi (pesan, pesanan_id, dibaca, created_at) VALUES (?, ?, 0, NOW())"
        );
        $stmt->bind_param("si", $pesan, $pesananId);
        return $stmt->execute();
    }
}
