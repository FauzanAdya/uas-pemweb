<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>.navbar{background:#3B4A1F!important;} .btn-main{background:#3B4A1F;color:#fff;border:none;} .btn-main:hover{background:#2a3516;color:#fff;}</style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4"><div class="container"><span class="text-white fw-bold">🌸 <?= APP_NAME ?></span></div></nav>
<div class="container pb-5" style="max-width:560px">
    <div class="card p-4 text-center">
        <div style="font-size:2.5rem">💳</div>
        <h5 class="fw-bold mb-1">Informasi Pembayaran</h5>
        <p class="text-muted">Pesanan #<?= $pesananId ?? '-' ?></p>
        <div class="alert alert-info text-start">
            <strong>Transfer ke:</strong><br>
            Bank BCA: <strong>1234567890</strong> a.n Toko Buket<br>
            atau scan QRIS di bawah ini
        </div>
        <div class="bg-light rounded p-4 mb-3">
            <div style="font-size:4rem">📱</div>
            <div class="text-muted small">QRIS Toko Buket</div>
        </div>
        <div class="fw-bold fs-5 mb-4" style="color:#3B4A1F">DP Minimal 50% dari total pesanan</div>
        <a href="index.php?page=pesan&action=upload&id=<?= $pesananId ?>" class="btn btn-main w-100 fw-bold">
            Sudah Bayar? Upload Bukti →
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
