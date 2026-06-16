<?php
$katalog = $katalog ?? [];
?>
<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Custom Buket - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>.navbar{background:#3B4A1F!important;} .btn-main{background:#3B4A1F;color:#fff;border:none;}</style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4">
    <div class="container">
        <a href="index.php?page=katalog" class="text-white text-decoration-none">← Katalog</a>
        <span class="text-white fw-bold"> Custom Buket</span>
    </div>
</nav>
<div class="container pb-5" style="max-width:600px">
    <h5 class="fw-bold mb-4">Buat Buket Custom</h5>
    <p class="text-muted">Pilih produk dasar lalu sesuaikan sepenuhnya sesuai keinginanmu.</p>
    <div class="row g-3">
    <?php foreach ($katalog as $k): ?>
        <div class="col-md-6">
            <div class="card p-3 h-100">
                <h6 class="fw-bold"><?= htmlspecialchars($k['nama']) ?></h6>
                <p class="text-muted small"><?= htmlspecialchars($k['deskripsi']) ?></p>
                <div class="fw-bold mb-2" style="color:#3B4A1F">Rp <?= number_format($k['harga_dasar'],0,',','.') ?></div>
                <a href="index.php?page=pesan&id=<?= $k['id'] ?>" class="btn btn-main btn-sm mt-auto">Pilih & Custom</a>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
