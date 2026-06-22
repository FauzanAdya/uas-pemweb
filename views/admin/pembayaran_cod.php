<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <a href="index.php?page=pembayaran" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>
    <h4 class="fw-bold mb-4">Input Pelunasan COD</h4>

    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card p-4" style="max-width:560px">

        <?php if (isset($pesanan)): ?>
        <!-- Jika dari tombol spesifik pesanan -->
        <div class="alert alert-info mb-4">
            <strong>Pesanan dari:</strong> <?= htmlspecialchars($pesanan['nama_pemesan']) ?><br>
            <strong>Total:</strong> Rp <?= number_format($pesanan['total_harga'],0,',','.') ?><br>
            <strong>Sisa yang harus dibayar:</strong>
            <span class="fw-bold text-danger fs-5"> Rp <?= number_format($pesanan['total_harga']*0.5,0,',','.') ?></span>
        </div>
        <?php endif; ?>

        <form method="POST" action="index.php?page=pembayaran&action=simpan_cod">

            <!-- Pilih Pesanan (jika tidak dari tombol spesifik) -->
            <?php
            /** @var array $pesananBelumLunas */ 
            if (!isset($pesanan)): ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Pesanan</label>
                <select name="pesanan_id" class="form-select" required id="selectPesanan" onchange="updateSisa(this)">
                    <option value="">-- Pilih Nama Pemesan --</option>
                    <?php foreach ($pesananBelumLunas as $p): ?>
                        <option value="<?= $p['id'] ?>" 
                                data-sisa="<?= $p['total_harga']*0.5 ?>"
                                data-nama="<?= htmlspecialchars($p['nama_pemesan']) ?>">
                            <?= htmlspecialchars($p['nama_pemesan']) ?> — Sisa Rp <?= number_format($p['total_harga']*0.5,0,',','.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="info-sisa" class="mt-2 text-danger fw-bold" style="display:none"></div>
            </div>
            <?php else: ?>
            <input type="hidden" name="pesanan_id" value="<?= $pesanan['id'] ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah Diterima (Rp)</label>
                <input type="number" name="jumlah_cod" id="jumlah_cod" class="form-control form-control-lg" 
                       value="<?= isset($pesanan) ? $pesanan['total_harga']*0.5 : '' ?>" 
                       min="0" placeholder="Masukkan jumlah uang diterima" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Pelunasan</label>
                <input type="date" name="tanggal_cod" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Catatan (opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="cth: Pelunasan saat pengambilan">
            </div>

            <!-- Konfirmasi Status -->
            <div class="p-3 rounded mb-4" style="background:#f0f4e8">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="konfirmasiCod" required>
                    <label class="form-check-label fw-semibold" for="konfirmasiCod">
                        Saya konfirmasi uang sudah diterima secara tunai
                    </label>
                </div>
            </div>

            <button type="submit" class="btn w-100 fw-bold py-2 text-white" style="background:#3B4A1F">
                Tandai Lunas (COD)
            </button>
        </form>
    </div>
</div>

<script>
function updateSisa(sel){
    const opt = sel.options[sel.selectedIndex];
    const sisa = opt.dataset.sisa;
    const info = document.getElementById('info-sisa');
    if(sisa){
        info.style.display = 'block';
        info.textContent = 'Sisa yang harus dibayar: Rp ' + parseInt(sisa).toLocaleString('id-ID');
        document.getElementById('jumlah_cod').value = sisa;
    } else {
        info.style.display = 'none';
    }
}
</script>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
