<?php
function formatRupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function formatTanggal($tanggal) {
    $bulan = ['','Januari','Februari','Maret','April','Mei','Juni',
              'Juli','Agustus','September','Oktober','November','Desember'];
    $d = date('d', strtotime($tanggal));
    $m = (int)date('m', strtotime($tanggal));
    $y = date('Y', strtotime($tanggal));
    return "$d {$bulan[$m]} $y";
}

function statusBadge($status) {
    $warna = [
        'pending'    => 'warning',
        'diproses'   => 'info',
        'selesai'    => 'success',
        'dibatalkan' => 'danger',
    ];
    $w = $warna[$status] ?? 'secondary';
    return "<span class='badge bg-$w'>" . ucfirst($status) . "</span>";
}
