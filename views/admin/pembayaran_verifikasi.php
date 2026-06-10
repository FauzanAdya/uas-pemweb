<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <a href="index.php?page=pembayaran" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>
    <h4 class="fw-bold mb-4">Verifikasi Bukti Pembayaran</h4>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Rincian Pesanan</h6>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted">Nama</td><td><?= htmlspecialchars($pembayaran['nama_pemesan']) ?></td></tr>
                    <tr><td class="text-muted">No WA</td><td><?= $pembayaran['no_wa'] ?></td></tr>
                    <tr><td class="text-muted">Total Harga</td><td class="fw-bold">Rp <?= number_format($pembayaran['total_harga'],0,',','.') ?></td></tr>
                    <tr><td class="text-muted">Tipe Bayar</td><td><?= strtoupper($pembayaran['tipe_bayar']) ?></td></tr>
                    <tr><td class="text-muted">Tgl Ambil</td><td><?= date('d/m/Y', strtotime($pembayaran['tanggal_ambil'])) ?></td></tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Bukti Pembayaran</h6>
                <img src="assets/img/bukti/<?= $pembayaran['file_bukti'] ?>" class="img-fluid rounded mb-3" style="max-height:300px; object-fit:contain;">
                <form method="POST" action="index.php?page=pembayaran&action=konfirmasi" class="d-inline">
                    <input type="hidden" name="id" value="<?= $pembayaran['id'] ?>">
                    <button type="submit" class="btn btn-success w-100 mb-2" onclick="return confirm('Konfirmasi pembayaran ini valid?')">✓ Konfirmasi Valid</button>
                </form>
                <form method="POST" action="index.php?page=pembayaran&action=tolak">
                    <input type="hidden" name="id" value="<?= $pembayaran['id'] ?>">
                    <input type="text" name="alasan" class="form-control mb-2" placeholder="Alasan penolakan...">
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak pembayaran ini?')">✗ Tolak Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
