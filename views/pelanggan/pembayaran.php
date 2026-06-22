<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar{background:#3B4A1F!important;}
        .btn-main{background:#3B4A1F;color:#fff;border:none;}
        .btn-main:hover{background:#2a3516;color:#fff;}
        .mode-card{border:2px solid #dee2e6;border-radius:12px;cursor:pointer;transition:.2s;}
        .mode-card.selected{border-color:#3B4A1F;background:#f0f4e8;}
        .qris-img{max-width:260px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.12);}
    </style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4"><div class="container"><span class="text-white fw-bold"> <?= APP_NAME ?></span></div></nav>
<div class="container pb-5" style="max-width:580px">
    <h5 class="fw-bold mb-1">Pembayaran Pesanan</h5>
    <p class="text-muted mb-4">Pesanan #<?= $_GET['id'] ?? '-' ?></p>

    <!-- Pilih Mode Bayar -->
    <div class="mb-4">
        <label class="form-label fw-semibold mb-2">Pilih Mode Pembayaran</label>
        <div class="row g-2">
            <div class="col-6">
                <div class="mode-card p-3 text-center selected" id="card-dp" onclick="pilihMode('dp')">
                    <div style="font-size:1.8rem"></div>
                    <div class="fw-bold mt-1">DP 50%</div>
                    <div class="text-muted small">Bayar setengah dulu</div>
                </div>
            </div>
            <div class="col-6">
                <div class="mode-card p-3 text-center" id="card-lunas" onclick="pilihMode('lunas')">
                    <div style="font-size:1.8rem"></div>
                    <div class="fw-bold mt-1">Lunas</div>
                    <div class="text-muted small">Bayar penuh sekarang</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Info QRIS -->
    <div class="card p-4 text-center mb-4">
        <h6 class="fw-bold mb-3">Scan QRIS untuk Membayar</h6>
        <?php 
        $qrisFile = __DIR__ . '/../../assets/img/qris.png';
        $qrisExt  = file_exists(__DIR__ . '/../../assets/img/qris.jpg') ? 'jpg' : 'png';
        ?>
        <img src="assets/img/qris.<?= $qrisExt ?>" alt="QRIS Toko Buket" class="qris-img mb-3">
        <div class="alert alert-warning mb-0">
            <strong>⚠ Wajib bayar via QRIS</strong><br>
            <span class="small">Pelunasan sisa bisa dilakukan COD saat pengambilan</span>
        </div>
    </div>

    <!-- Nominal -->
    <div class="card p-3 mb-4" style="background:#f0f4e8;border:none">
        <div class="d-flex justify-content-between">
            <span>Mode Bayar</span>
            <span class="fw-bold" id="txt-mode">DP 50%</span>
        </div>
        <div class="d-flex justify-content-between mt-1">
            <span>Yang Harus Dibayar</span>
            <span class="fw-bold fs-5" style="color:#3B4A1F" id="txt-nominal">-</span>
        </div>
    </div>

    <a id="btn-upload" href="index.php?page=pesan&action=upload&id=<?= $_GET['id'] ?? '' ?>&mode=dp" class="btn btn-main w-100 fw-bold py-2 fs-5">
        Sudah Bayar? Upload Bukti →
    </a>
</div>

<script>
let modeTerpilih = 'dp';
// Ambil total dari session (diteruskan lewat URL atau hidden input)
// Untuk demo, kita tampilkan placeholder
function pilihMode(mode){
    modeTerpilih = mode;
    document.getElementById('card-dp').classList.toggle('selected', mode==='dp');
    document.getElementById('card-lunas').classList.toggle('selected', mode==='lunas');
    document.getElementById('txt-mode').textContent = mode==='dp' ? 'DP 50%' : 'Lunas 100%';
    document.getElementById('txt-nominal').textContent = mode==='dp' ? 'Bayar 50% dari total' : 'Bayar penuh';
    const id = new URLSearchParams(window.location.search).get('id');
    document.getElementById('btn-upload').href = 
        `index.php?page=pesan&action=upload&id=${id}&mode=${mode}`;
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
