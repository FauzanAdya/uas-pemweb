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

    public function simpan($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO stok_bahan (nama_bahan, jumlah, satuan, stok_minimum) VALUES (?,?,?,?)"
        );
        $stmt->bind_param("sisi", $data['nama_bahan'], $data['jumlah'], $data['satuan'], $data['stok_minimum']);
        return $stmt->execute();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare(
            "UPDATE stok_bahan SET nama_bahan=?, jumlah=?, satuan=?, stok_minimum=? WHERE id=?"
        );
        $stmt->bind_param("sisii", $data['nama_bahan'], $data['jumlah'], $data['satuan'], $data['stok_minimum'], $id);
        return $stmt->execute();
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM stok_bahan WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
