<?php
require_once __DIR__ . '/../config/database.php';

class KatalogModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM katalog ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM katalog WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // FK ditambahkan_oleh diisi dari $data['ditambahkan_oleh']
    public function simpan($data) {
        $stmt = $this->db->prepare(
            "INSERT INTO katalog (nama, deskripsi, harga_dasar, kategori, foto, ditambahkan_oleh, created_at) 
             VALUES (?,?,?,?,?,?,NOW())"
        );
        $stmt->bind_param(
            "ssissi",
            $data['nama'], $data['deskripsi'], $data['harga_dasar'],
            $data['kategori'], $data['foto'], $data['ditambahkan_oleh']
        );
        return $stmt->execute();
    }

    public function update($id, $data) {
        $foto = $data['foto'] ?? null;
        if ($foto) {
            $stmt = $this->db->prepare(
                "UPDATE katalog SET nama=?, deskripsi=?, harga_dasar=?, kategori=?, foto=? WHERE id=?"
            );
            $stmt->bind_param("ssissi", $data['nama'], $data['deskripsi'], $data['harga_dasar'], $data['kategori'], $foto, $id);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE katalog SET nama=?, deskripsi=?, harga_dasar=?, kategori=? WHERE id=?"
            );
            $stmt->bind_param("ssisi", $data['nama'], $data['deskripsi'], $data['harga_dasar'], $data['kategori'], $id);
        }
        return $stmt->execute();
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM katalog WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
