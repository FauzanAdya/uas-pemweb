<?php
require_once __DIR__ . '/../config/database.php';

class StokModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM stok_bahan ORDER BY nama_bahan ASC")->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM stok_bahan WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getStokMenuipis() {
        return $this->db->query(
            "SELECT * FROM stok_bahan WHERE jumlah <= stok_minimum ORDER BY jumlah ASC"
        )->fetch_all(MYSQLI_ASSOC);
    }

    // FK diupdate_oleh diisi dari $data['diupdate_oleh']
    public function simpan($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO stok_bahan (nama_bahan, kode_bahan, jumlah, satuan, stok_minimum, diupdate_oleh, updated_at) 
             VALUES (?,?,?,?,?,?,NOW())"
        );
        $stmt->bind_param(
            "ssisii",
            $data['nama_bahan'], $data['kode_bahan'], $data['jumlah'],
            $data['satuan'], $data['stok_minimum'], $data['diupdate_oleh']
        );
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare(
            "UPDATE stok_bahan 
             SET nama_bahan=?, kode_bahan=?, jumlah=?, satuan=?, stok_minimum=?, diupdate_oleh=?, updated_at=NOW() 
             WHERE id=?"
        );
        $stmt->bind_param(
            "ssisiii",
            $data['nama_bahan'], $data['kode_bahan'], $data['jumlah'],
            $data['satuan'], $data['stok_minimum'], $data['diupdate_oleh'], $id
        );
        return $stmt->execute();
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM stok_bahan WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Kurangi stok bahan
    public function kurangiStok($id, $jumlahDipakai, $adminId) {
        $stmt = $this->db->prepare(
            "UPDATE stok_bahan 
             SET jumlah = GREATEST(jumlah - ?, 0), diupdate_oleh = ?, updated_at = NOW() 
             WHERE id = ?"
        );
        $stmt->bind_param("iii", $jumlahDipakai, $adminId, $id);
        return $stmt->execute();
    }

    // Cari bahan berdasarkan kode_bahan exact match
    public function cariByKode($kode) {
        $stmt = $this->db->prepare(
            "SELECT * FROM stok_bahan WHERE kode_bahan = ?"
        );
        $stmt->bind_param("s", $kode);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Kurangi otomatis berdasarkan kode — dipanggil saat konfirmasi pesanan custom
    public function kurangiOtomatisByKataKunci($kodeList, $adminId) {
    $bahanDikurangi = [];
    
    // DEBUG: simpan log ke file
    file_put_contents(
        __DIR__ . '/../debug_stok.txt',
        date('Y-m-d H:i:s') . " | kodeList: " . json_encode($kodeList) . "\n",
        FILE_APPEND
    );
    
    foreach ($kodeList as $kode) {
        $kode = strtolower(trim($kode));
        if (empty($kode)) continue;

        $cocok = $this->cariByKode($kode);
        
        // DEBUG: log hasil pencarian
        file_put_contents(
            __DIR__ . '/../debug_stok.txt',
            "  kode: $kode | cocok: " . json_encode($cocok) . "\n",
            FILE_APPEND
        );
        
        foreach ($cocok as $bahan) {
            if ($bahan['jumlah'] > 0) {
                $this->kurangiStok($bahan['id'], 1, $adminId);
                $bahanDikurangi[] = $bahan['nama_bahan'];
            }
        }
    }
    
    // DEBUG: log hasil akhir
    file_put_contents(
        __DIR__ . '/../debug_stok.txt',
        "  hasil berkurang: " . json_encode($bahanDikurangi) . "\n\n",
        FILE_APPEND
    );
    
    return $bahanDikurangi;
    }
}