<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Berhasil - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>.navbar{background:#3B4A1F!important;} .btn-main{background:#3B4A1F;color:#fff;border:none;} .btn-main:hover{background:#2a3516;color:#fff;}</style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4"><div class="container"><span class="text-white fw-bold">🌸 <?= APP_NAME ?></span></div></nav>
<div class="container d-flex align-items-center justify-content-center" style="min-height:70vh">
    <div class="card p-5 text-center" style="max-width:480px">
        <div style="font-size:4rem" class="mb-3">🎉</div>
        <h4 class="fw-bold mb-2">Pesanan Berhasil Dikirim!</h4>
        <p class="text-muted mb-4">Bukti pembayaranmu sedang kami verifikasi. Admin akan segera memproses pesananmu. Pantau status melalui nomor WhatsApp yang kamu daftarkan.</p>
        <div class="alert alert-success">
            <strong>Terima kasih!</strong><br>
            Kami akan menghubungi kamu via WhatsApp jika pesanan sudah dikonfirmasi.
        </div>
        <a href="index.php?page=katalog" class="btn btn-main w-100 mt-2">Kembali ke Katalog</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
