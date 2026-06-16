<?php
require_once __DIR__ . '/../config/database.php';

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function cariByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
