<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/PembayaranModel.php';

$model = new PembayaranModel();

// Ambil data pembayaran id terakhir
$db = getDB();
$lastId = $db->query("SELECT MAX(id) as id FROM pembayaran")->fetch_assoc()['id'];

echo "<b>ID Pembayaran terakhir: $lastId</b><br><br>";

$data = $model->getById($lastId);

echo "<b>Hasil getById():</b><br>";
echo "is_custom: " . var_export($data['is_custom'] ?? 'TIDAK ADA', true) . "<br>";
echo "warna_kertas: " . var_export($data['warna_kertas'] ?? 'TIDAK ADA', true) . "<br>";
echo "jenis_isi: " . var_export($data['jenis_isi'] ?? 'TIDAK ADA', true) . "<br>";
echo "tambahan: " . var_export($data['tambahan'] ?? 'TIDAK ADA', true) . "<br>";