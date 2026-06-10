<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Bukti - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>.navbar{background:#3B4A1F!important;} .btn-main{background:#3B4A1F;color:#fff;border:none;} .btn-main:hover{background:#2a3516;color:#fff;}</style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4"><div class="container"><span class="text-white fw-bold">🌸 <?= APP_NAME ?></span></div></nav>
<div class="container pb-5" style="max-width:560px">
    <h5 class="fw-bold mb-4">Upload Bukti Pembayaran</h5>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="card p-4">
        <form method="POST" action="index.php?page=pembayaran_pelanggan" enctype="multipart/form-data">
            <input type="hidden" name="pesanan_id" value="<?= $_GET['id'] ?? '' ?>">
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe Pembayaran</label>
                <select name="tipe_bayar" class="form-select">
                    <option value="dp">DP (50%)</option>
                    <option value="lunas">Lunas (100%)</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Bukti Transfer / QRIS</label>
                <input type="file" name="bukti_bayar" class="form-control" accept="image/*,.pdf" required>
                <div class="form-text">Format: JPG, PNG, atau PDF. Maks 2MB.</div>
            </div>
            <button type="submit" class="btn btn-main w-100 fw-bold py-2">Kirim Bukti Pembayaran</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
