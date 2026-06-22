<?php
require_once __DIR__ . '/../config/database.php';

class PembayaranModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAllBelumVerifikasi()
    {
        $sql = "SELECT pb.*, p.nama_pemesan, p.no_wa, p.total_harga, p.tanggal_ambil
                FROM pembayaran pb
                LEFT JOIN pesanan p ON pb.pesanan_id = p.id
                WHERE pb.status_verifikasi = 'menunggu'
                ORDER BY pb.tanggal_upload ASC";
        return $this->db->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT pb.*, p.nama_pemesan, p.no_wa, p.total_harga,
                p.tanggal_ambil, p.ucapan, p.is_custom, p.status_bayar,
                p.warna_kertas, p.jenis_isi, p.tambahan
         FROM pembayaran pb
         LEFT JOIN pesanan p ON pb.pesanan_id = p.id
         WHERE pb.id = ? LIMIT 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    // Simpan bukti upload dari pelanggan
    public function simpanBukti($pesananId, $namaFile, $tipeBayar)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO pembayaran 
             (pesanan_id, file_bukti, tipe_bayar, tanggal_upload, status_verifikasi)
             VALUES (?, ?, ?, NOW(), 'menunggu')"
        );
        $stmt->bind_param("iss", $pesananId, $namaFile, $tipeBayar);
        return $stmt->execute();
    }

    // Konfirmasi pembayaran valid — FK diverifikasi_oleh diisi
    public function konfirmasi($id, $adminId)
    {
        $stmt = $this->db->prepare(
            "UPDATE pembayaran 
             SET status_verifikasi = 'diterima', 
                 tanggal_verifikasi = NOW(),
                 diverifikasi_oleh = ?
             WHERE id = ?"
        );
        $stmt->bind_param("ii", $adminId, $id);
        return $stmt->execute();
    }

    // Tolak pembayaran — FK diverifikasi_oleh diisi
    public function tolak($id, $alasan, $adminId)
    {
        $stmt = $this->db->prepare(
            "UPDATE pembayaran 
             SET status_verifikasi = 'ditolak', 
                 alasan_tolak = ?, 
                 tanggal_verifikasi = NOW(),
                 diverifikasi_oleh = ?
             WHERE id = ?"
        );
        $stmt->bind_param("sii", $alasan, $adminId, $id);
        return $stmt->execute();
    }

    // Update status_bayar di tabel pesanan (dp / lunas)
    public function updateStatusBayarPesanan($pesananId, $statusBayar)
    {
        $stmt = $this->db->prepare("UPDATE pesanan SET status_bayar = ? WHERE id = ?");
        $stmt->bind_param("si", $statusBayar, $pesananId);
        return $stmt->execute();
    }

    // Simpan pelunasan COD oleh admin (juga FK diverifikasi_oleh)
    public function simpanCod($pesananId, $jumlah, $tanggal, $catatan, $adminId = null)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO pembayaran 
             (pesanan_id, file_bukti, tipe_bayar, tanggal_upload, status_verifikasi, 
              catatan_cod, jumlah_cod, diverifikasi_oleh, tanggal_verifikasi)
             VALUES (?, 'COD', 'cod', ?, 'diterima', ?, ?, ?, NOW())"
        );
        $stmt->bind_param("issii", $pesananId, $tanggal, $catatan, $jumlah, $adminId);
        return $stmt->execute();
    }

    // Kirim notifikasi ke admin
    public function kirimNotifikasiAdmin($pesananId)
    {
        $pesan = "Pesanan #$pesananId menunggu verifikasi pembayaran.";
        $stmt  = $this->db->prepare(
            "INSERT INTO notifikasi (pesanan_id, pesan, dibaca, created_at) 
             VALUES (?, ?, 0, NOW())"
        );
        $stmt->bind_param("is", $pesananId, $pesan);
        return $stmt->execute();
    }
}
