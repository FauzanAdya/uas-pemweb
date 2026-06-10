<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <h4 class="fw-bold mb-4">Verifikasi Pembayaran</h4>
    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama Pemesan</th><th>No WA</th><th>Total</th><th>Tgl Ambil</th><th>Tipe Bayar</th><th>Tgl Upload</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($pembayaran as $i => $p): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
                    <td><?= $p['no_wa'] ?></td>
                    <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                    <td><span class="badge bg-info text-dark"><?= strtoupper($p['tipe_bayar']) ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['tanggal_upload'])) ?></td>
                    <td><a href="index.php?page=pembayaran&action=verifikasi&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Periksa</a></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($pembayaran)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-3">Tidak ada pembayaran yang menunggu verifikasi</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
