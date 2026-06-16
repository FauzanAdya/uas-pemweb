<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <h4 class="fw-bold mb-4">Riwayat Pesanan Selesai</h4>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Produk</th><th>Tgl Pesan</th><th>Tgl Ambil</th><th>Total</th><th>Custom</th></tr>
                </thead>
                <tbody>
                <?php
                /** @var array $riwayat */  
                foreach ($riwayat as $i => $r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($r['nama_pemesan']) ?><br><small class="text-muted"><?= $r['no_wa'] ?></small></td>
                    <td><?= htmlspecialchars($r['nama_produk']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['tanggal_pesan'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['tanggal_ambil'])) ?></td>
                    <td>Rp <?= number_format($r['total_harga'],0,',','.') ?></td>
                    <td><?= $r['is_custom'] ? '<span class="badge bg-info text-dark">Ya</span>' : '-' ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($riwayat)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada riwayat pesanan selesai</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
