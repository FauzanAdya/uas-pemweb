<?php
echo "<b>Path folder:</b> " . __DIR__ . "<br><br>";
echo "<b>Path controller:</b> " . realpath(__DIR__ . '/controllers/PembayaranController.php') . "<br><br>";

$isi = file_get_contents(__DIR__ . '/controllers/PembayaranController.php');

echo strpos($isi, 'kurangiOtomatisByKataKunci') !== false 
    ? "<span style='color:green;font-size:18px'>✅ kurangiOtomatisByKataKunci ADA di file</span>" 
    : "<span style='color:red;font-size:18px'>❌ kurangiOtomatisByKataKunci TIDAK ADA di file</span>";
echo "<br><br>";

echo strpos($isi, 'bahan_dipakai') !== false 
    ? "<span style='color:red;font-size:18px'>❌ bahan_dipakai masih ada (versi lama)</span>" 
    : "<span style='color:green;font-size:18px'>✅ bahan_dipakai sudah tidak ada</span>";
echo "<br><br>";

echo strpos($isi, 'json_decode') !== false 
    ? "<span style='color:green;font-size:18px'>✅ json_decode ADA di file</span>" 
    : "<span style='color:red;font-size:18px'>❌ json_decode TIDAK ADA di file</span>";