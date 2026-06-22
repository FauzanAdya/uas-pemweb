<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/StokModel.php';
require_once __DIR__ . '/models/PembayaranModel.php';

$stokModel = new StokModel();

echo "<h3>1. Semua Data Stok Bahan</h3>";
$semua = $stokModel->getAll();
echo "<table border='1' cellpadding='8'><tr><th>ID</th><th>Nama Bahan</th><th>Jumlah</th><th>Satuan</th></tr>";
foreach ($semua as $b) {
    echo "<tr><td>{$b['id']}</td><td>{$b['nama_bahan']}</td><td>{$b['jumlah']}</td><td>{$b['satuan']}</td></tr>";
}
echo "</table>";

echo "<hr><h3>2. Test Pencarian Kata Kunci</h3>";
echo "<form method='GET'>";
echo "Kata kunci: <input type='text' name='kata' value='" . ($_GET['kata'] ?? '') . "'>";
echo " <button type='submit'>Cari</button>";
echo "</form>";

if (!empty($_GET['kata'])) {
    $hasil = $stokModel->cariByNamaMengandung($_GET['kata']);
    echo "<p>Hasil pencarian untuk '<strong>{$_GET['kata']}</strong>':</p>";
    if (empty($hasil)) {
        echo "<p style='color:red'>TIDAK ADA YANG COCOK</p>";
    } else {
        echo "<table border='1' cellpadding='8'><tr><th>ID</th><th>Nama Bahan</th><th>Jumlah</th></tr>";
        foreach ($hasil as $h) {
            echo "<tr><td>{$h['id']}</td><td>{$h['nama_bahan']}</td><td>{$h['jumlah']}</td></tr>";
        }
        echo "</table>";
    }
}

echo "<hr><h3>3. Cek Data Pembayaran Terakhir (untuk lihat is_custom, warna_kertas, dll)</h3>";
$db = getDB();
$result = $db->query("SELECT pb.id, pb.pesanan_id, pb.status_verifikasi, p.is_custom, p.warna_kertas, p.jenis_isi, p.tambahan 
                       FROM pembayaran pb 
                       LEFT JOIN pesanan p ON pb.pesanan_id = p.id 
                       ORDER BY pb.id DESC LIMIT 5");
echo "<table border='1' cellpadding='8'><tr><th>ID Bayar</th><th>Pesanan ID</th><th>Status</th><th>is_custom</th><th>warna_kertas</th><th>jenis_isi</th><th>tambahan</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    foreach ($row as $val) {
        echo "<td>" . var_export($val, true) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "<hr><h3>4. Test Manual Kurangi Stok (pakai ID bahan)</h3>";
echo "<form method='POST'>";
echo "ID Bahan: <input type='number' name='id_bahan'> ";
echo "<button type='submit' name='test_kurangi'>Kurangi 1 Stok</button>";
echo "</form>";

if (isset($_POST['test_kurangi'])) {
    $idBahan = (int)$_POST['id_bahan'];
    $hasil = $stokModel->kurangiStok($idBahan, 1, 1);
    echo "<p>Hasil kurangiStok(): " . ($hasil ? "<span style='color:green'>SUKSES</span>" : "<span style='color:red'>GAGAL</span>") . "</p>";
    echo "<p>Cek error MySQL: " . $db->error . "</p>";
}
