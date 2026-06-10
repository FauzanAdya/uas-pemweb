<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <h4 class="fw-bold mb-4">Dashboard Admin</h4>
    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>

    <!-- Kartu Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card p-3 text-white" style="background:#3B4A1F">
                <div class="small">Pesanan Hari Ini</div>
                <div class="fs-3 fw-bold"><?= $pesananHariIni ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-white bg-warning text-dark">
                <div class="small">Belum Lunas</div>
                <div class="fs-3 fw-bold"><?= count($belumLunas) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-white bg-danger">
                <div class="small">Segera Dikirim</div>
                <div class="fs-3 fw-bold"><?= count($segeraDikirim) ?></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-white bg-success">
                <div class="small">Keuntungan Bulan Ini</div>
                <div class="fs-5 fw-bold">Rp <?= number_format($rekapBulanIni['keuntungan'],0,',','.') ?></div>
            </div>
        </div>
    </div>

    <!-- Daftar Semua Pesanan Urut -->
    <div class="card mb-4">
        <div class="card-header fw-bold">Daftar Pesanan (Urut dari Pertama Masuk)</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>Nama Pemesan</th><th>Produk</th>
                        <th>Tgl Pesan</th><th>Tgl Ambil</th><th>Total</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($semuaPesanan as $i => $p): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
                        <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                        <td><?= date('d/m/Y', strtotime($p['tanggal_pesan'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                        <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                        <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                        <td>
                            <a href="index.php?page=pesanan&action=detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($semuaPesanan)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-3">Belum ada pesanan</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pesanan Segera Dikirim -->
    <?php if (!empty($segeraDikirim)): ?>
    <div class="card">
        <div class="card-header fw-bold text-danger">⚠ Pesanan Harus Segera Dikirim (3 Hari ke Depan)</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Produk</th><th>Tgl Ambil</th><th>No WA</th></tr>
                </thead>
                <tbody>
                <?php foreach ($segeraDikirim as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
                        <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                        <td class="fw-bold text-danger"><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                        <td><a href="https://wa.me/<?= $p['no_wa'] ?>" target="_blank"><?= $p['no_wa'] ?></a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
