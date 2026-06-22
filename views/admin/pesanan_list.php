<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <h4 class="fw-bold mb-4">Manajemen Pesanan</h4>
    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Nama</th><th>Produk</th><th>Tgl Pesan</th><th>Tgl Ambil</th><th>Total</th><th>Status Bayar</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php
                /** @var array $pesanan */ 
                foreach ($pesanan as $i => $p): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= htmlspecialchars($p['nama_pemesan']) ?><br><small class="text-muted"><?= $p['no_wa'] ?></small></td>
                    <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_pesan'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                    <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                    <td><span class="badge bg-<?= $p['status_bayar']==='lunas'?'success':'warning text-dark' ?>"><?= ucfirst($p['status_bayar']) ?></span></td>
                    <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                    <td>
                        <a href="index.php?page=pesanan&action=detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Detail</a>
                        <a href="index.php?page=pesanan&action=hapus&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus pesanan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($pesanan)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-3">Belum ada pesanan</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
