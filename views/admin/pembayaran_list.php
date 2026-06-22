<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <h4 class="fw-bold mb-4">Manajemen Pembayaran</h4>

    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>

    <!-- Tab DP vs Lunas -->
    <ul class="nav nav-tabs mb-3" id="tabPembayaran">
        <li class="nav-item">
            <a class="nav-link active" href="#tab-dp" data-bs-toggle="tab">
                <span class="badge bg-warning text-dark me-1">DP</span> Menunggu Verifikasi DP
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tab-lunas" data-bs-toggle="tab">
                <span class="badge bg-success me-1">LUNAS</span> Menunggu Verifikasi Lunas
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tab-cod" data-bs-toggle="tab">
                <span class="badge bg-info text-dark me-1">COD</span> Pelunasan COD
            </a>
        </li>
    </ul>

    <div class="tab-content">

        <!-- TAB DP -->
        <div class="tab-pane fade show active" id="tab-dp">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Nama Pemesan</th><th>No WA</th><th>Total</th><th>Tgl Ambil</th><th>Tgl Upload</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php
                        /** @var array $pembayaran */ 
                        $dataDP = array_filter($pembayaran, fn($p) => $p['tipe_bayar'] === 'dp');
                        foreach ($dataDP as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
                            <td><a href="https://wa.me/<?= $p['no_wa'] ?>" target="_blank"><?= $p['no_wa'] ?></a></td>
                            <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($p['tanggal_upload'])) ?></td>
                            <td>
                                <a href="index.php?page=pembayaran&action=verifikasi&id=<?= $p['id'] ?>" class="btn btn-sm btn-warning text-dark">
                                    Periksa DP
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($dataDP)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada pembayaran DP yang menunggu</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB LUNAS -->
        <div class="tab-pane fade" id="tab-lunas">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Nama Pemesan</th><th>No WA</th><th>Total</th><th>Tgl Ambil</th><th>Tgl Upload</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php 
                        $dataLunas = array_filter($pembayaran, fn($p) => $p['tipe_bayar'] === 'lunas');
                        foreach ($dataLunas as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
                            <td><a href="https://wa.me/<?= $p['no_wa'] ?>" target="_blank"><?= $p['no_wa'] ?></a></td>
                            <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($p['tanggal_upload'])) ?></td>
                            <td>
                                <a href="index.php?page=pembayaran&action=verifikasi&id=<?= $p['id'] ?>" class="btn btn-sm btn-success">
                                     Periksa Lunas
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($dataLunas)): ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada pembayaran lunas yang menunggu</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB COD -->
        <div class="tab-pane fade" id="tab-cod">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted small">Pesanan dengan sisa pelunasan yang belum dibayar (COD saat ambil)</span>
                <a href="index.php?page=pembayaran&action=form_cod" class="btn btn-sm text-white" style="background:#3B4A1F">+ Input Pelunasan COD</a>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Nama Pemesan</th><th>Total</th><th>Sudah DP</th><th>Sisa Lunas</th><th>Tgl Ambil</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($pesananBelumLunas)): ?>
                        <?php foreach ($pesananBelumLunas as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($p['nama_pemesan']) ?><br><small class="text-muted"><?= $p['no_wa'] ?></small></td>
                            <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                            <td><span class="badge bg-warning text-dark">DP 50%</span><br>Rp <?= number_format($p['total_harga']*0.5,0,',','.') ?></td>
                            <td class="fw-bold text-danger">Rp <?= number_format($p['total_harga']*0.5,0,',','.') ?></td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                            <td>
                                <a href="index.php?page=pembayaran&action=form_cod&pesanan_id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-success">
                                     Input COD
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada sisa pelunasan COD</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
