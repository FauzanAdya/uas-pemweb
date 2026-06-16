<?php
$produk = $produk ?? [];
?>
<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan - <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar { background: #3B4A1F !important; }
        .btn-main { background: #3B4A1F; color: #fff; border: none; }
        .btn-main:hover { background: #2a3516; color: #fff; }
        #harga-info { background: #f0f4e8; border-radius: 10px; }
    </style>
</head>
<body class="bg-light">
<nav class="navbar py-3 mb-4">
    <div class="container">
        <a href="index.php?page=katalog" class="text-white text-decoration-none">← Kembali ke Katalog</a>
        <span class="text-white fw-bold"> <?= APP_NAME ?></span>
    </div>
</nav>
<div class="container pb-5" style="max-width:700px">
    <h5 class="fw-bold mb-1">Form Pemesanan</h5>
    <p class="text-muted mb-4">Produk: <strong><?= htmlspecialchars($produk['nama']) ?></strong></p>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <form method="POST" action="index.php?page=pesan&action=simpan" id="formPesan">
        <input type="hidden" name="produk_id" value="<?= $produk['id'] ?>">
        <input type="hidden" name="total_harga" id="total_harga_input" value="<?= $produk['harga_dasar'] ?>">

        <!-- Data Diri -->
        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Data Diri</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Pemesan</label>
                <input type="text" name="nama_pemesan" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor WhatsApp</label>
                <input type="text" name="no_wa" class="form-control" placeholder="cth: 08123456789" required>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Detail Pesanan</h6>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pengambilan</label>
                <input type="date" name="tanggal_ambil" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jenis Pengambilan</label>
                <select name="tipe_pengambilan" class="form-select">
                    <option value="ambil">Ambil Sendiri</option>
                    <option value="kirim">Dikirim</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Teks Ucapan (opsional)</label>
                <textarea name="ucapan" class="form-control" rows="2" placeholder="Selamat wisuda, semoga sukses!"></textarea>
            </div>
        </div>

        <!-- Kustomisasi -->
        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Kustomisasi</h6>
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="is_custom" name="is_custom" value="1">
                    <label class="form-check-label fw-semibold" for="is_custom">Saya ingin custom buket (+Rp 15.000)</label>
                </div>
            </div>
            <div id="form-custom" style="display:none">
                <div class="mb-3">
                    <label class="form-label">Warna Kertas Wrap</label>
                    <select name="warna_kertas" class="form-select">
                        <option value="">-- Pilih Warna --</option>
                        <option value="pink">Pink</option>
                        <option value="putih">Putih</option>
                        <option value="biru">Biru</option>
                        <option value="ungu">Ungu</option>
                        <option value="kuning">Kuning</option>
                        <option value="hijau">Hijau</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Isi</label>
                    <select name="jenis_isi" class="form-select">
                        <option value="">-- Pilih Isi --</option>
                        <option value="bunga_artifisial">Bunga Artifisial</option>
                        <option value="bunga_segar">Bunga Segar (+Rp 20.000)</option>
                        <option value="snack">Snack</option>
                        <option value="uang">Uang</option>
                        <option value="kombinasi">Kombinasi</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tambahan Hiasan</label>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="form-check"><input class="form-check-input tambahan" type="checkbox" name="tambahan[]" value="pita_premium"><label class="form-check-label">Pita Premium (+Rp 5.000)</label></div>
                        <div class="form-check"><input class="form-check-input tambahan" type="checkbox" name="tambahan[]" value="coklat"><label class="form-check-label">Coklat (+Rp 10.000)</label></div>
                        <div class="form-check"><input class="form-check-input tambahan" type="checkbox" name="tambahan[]" value="boneka"><label class="form-check-label">Boneka (+Rp 25.000)</label></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kalkulasi Harga -->
        <div id="harga-info" class="p-3 mb-4">
            <div class="d-flex justify-content-between"><span>Harga Dasar</span><span id="txt-dasar">Rp <?= number_format($produk['harga_dasar'],0,',','.') ?></span></div>
            <div class="d-flex justify-content-between"><span>Biaya Kustomisasi</span><span id="txt-custom">Rp 0</span></div>
            <hr>
            <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span id="txt-total" style="color:#3B4A1F">Rp <?= number_format($produk['harga_dasar'],0,',','.') ?></span></div>
            <div class="text-muted small mt-1">DP Minimal (50%): <strong id="txt-dp">Rp <?= number_format($produk['harga_dasar']/2,0,',','.') ?></strong></div>
        </div>

        <button type="submit" class="btn btn-main w-100 py-2 fw-bold fs-5">Lanjut ke Pembayaran →</button>
    </form>
</div>
<script>
const hargaDasar = <?= $produk['harga_dasar'] ?>;
function fmt(n){return 'Rp '+n.toLocaleString('id-ID');}
function hitung(){
    const jumlah  = parseInt(document.getElementById('jumlah').value)||1;
    const isCustom= document.getElementById('is_custom').checked;
    let custom    = isCustom ? 15000 : 0;
    if(isCustom){
        const jenis = document.querySelector('[name="jenis_isi"]').value;
        if(jenis==='bunga_segar') custom+=20000;
        document.querySelectorAll('.tambahan:checked').forEach(cb=>{
            if(cb.value==='pita_premium') custom+=5000;
            if(cb.value==='coklat')       custom+=10000;
            if(cb.value==='boneka')       custom+=25000;
        });
    }
    const dasar = hargaDasar * jumlah;
    const total = dasar + custom;
    document.getElementById('txt-dasar').textContent  = fmt(dasar);
    document.getElementById('txt-custom').textContent = fmt(custom);
    document.getElementById('txt-total').textContent  = fmt(total);
    document.getElementById('txt-dp').textContent     = fmt(total*0.5);
    document.getElementById('total_harga_input').value= total;
}
document.getElementById('is_custom').addEventListener('change',function(){
    document.getElementById('form-custom').style.display=this.checked?'block':'none';
    hitung();
});
document.getElementById('jumlah').addEventListener('input',hitung);
document.querySelectorAll('.tambahan').forEach(cb=>cb.addEventListener('change',hitung));
document.querySelector('[name="jenis_isi"]')?.addEventListener('change',hitung);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
