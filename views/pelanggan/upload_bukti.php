<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Bukti - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar{background:#3B4A1F!important;}
        .btn-main{background:#3B4A1F;color:#fff;border:none;}
        .btn-main:hover{background:#2a3516;color:#fff;}
        .upload-area{border:2px dashed #3B4A1F;border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:.2s;}
        .upload-area:hover{background:#f0f4e8;}
        .badge-dp{background:#ffc107;color:#000;}
        .badge-lunas{background:#198754;color:#fff;}
    </style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4"><div class="container"><span class="text-white fw-bold"> <?= APP_NAME ?></span></div></nav>
<div class="container pb-5" style="max-width:560px">
    <?php 
    $mode      = $_GET['mode'] ?? 'dp';
    $pesananId = $_GET['id']   ?? '';
    ?>
    <div class="d-flex align-items-center gap-2 mb-4">
        <h5 class="fw-bold mb-0">Upload Bukti Pembayaran</h5>
        <span class="badge <?= $mode==='lunas'?'badge-lunas':'badge-dp' ?> px-3 py-2">
            <?= $mode==='lunas' ? 'LUNAS' : 'DP 50%' ?>
        </span>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card p-4">
        <form method="POST" action="index.php?page=pembayaran_pelanggan" enctype="multipart/form-data">
            <input type="hidden" name="pesanan_id" value="<?= htmlspecialchars($pesananId) ?>">
            <input type="hidden" name="tipe_bayar"  value="<?= htmlspecialchars($mode) ?>">

            <!-- Info Mode -->
            <div class="alert <?= $mode==='lunas'?'alert-success':'alert-warning' ?> mb-4">
                <?php if ($mode === 'lunas'): ?>
                    <strong> Pembayaran Lunas</strong><br>
                    <span class="small">Kamu memilih bayar penuh via QRIS. Tidak ada sisa pembayaran saat pengambilan.</span>
                <?php else: ?>
                    <strong>💳 Pembayaran DP 50%</strong><br>
                    <span class="small">Sisa 50% dapat dibayar saat pengambilan (COD) atau transfer sebelumnya.</span>
                <?php endif; ?>
            </div>

            <!-- Upload Area -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Bukti Transfer QRIS</label>
                <div class="upload-area" onclick="document.getElementById('file-input').click()">
                    <div style="font-size:2.5rem">📷</div>
                    <div class="fw-semibold mt-2">Klik untuk pilih foto</div>
                    <div class="text-muted small">JPG, PNG, atau PDF — maks 2MB</div>
                    <div id="nama-file" class="mt-2 text-success small"></div>
                </div>
                <input type="file" id="file-input" name="bukti_bayar" accept="image/*,.pdf" required style="display:none"
                    onchange="document.getElementById('nama-file').textContent = this.files[0]?.name ?? ''">
            </div>

            <button type="submit" class="btn btn-main w-100 fw-bold py-2">
                Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
