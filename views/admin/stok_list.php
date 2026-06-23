<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Manajemen Stok Bahan</h4>
        <a href="index.php?page=stok&action=tambah" class="btn text-white" style="background:#3B4A1F">+ Tambah Bahan</a>
    </div>

    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($stokMenuipis)): ?>
    <div class="alert alert-warning">
        <strong>⚠ Peringatan!</strong> Periksa ketersediaan stok bahan berikut:
        <?php foreach($stokMenuipis as $s): ?>
            <span class="badge <?= $s['jumlah'] == 0 ? 'bg-dark' : 'bg-warning text-dark' ?> ms-1">
                <?= htmlspecialchars($s['nama_bahan']) ?> 
                (<?= $s['jumlah'] == 0 ? 'Habis' : $s['jumlah'] . ' ' . $s['satuan'] ?>)
            </span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Bahan</th>
                        <th>Kode Bahan</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Stok Minimum</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                /** @var array $stok */ 
                foreach ($stok as $i => $s): 
                    // Menentukan warna background baris tabel berdasarkan sisa stok
                    $bg_class = '';
                    if ($s['jumlah'] == 0) {
                        $bg_class = 'table-danger'; // Merah jika habis total
                    } elseif ($s['jumlah'] <= $s['stok_minimum']) {
                        $bg_class = 'table-warning'; // Kuning jika menipis
                    }
                ?>
                <tr class="<?= $bg_class ?>">
                    <td><?= $i+1 ?></td>
                    <td class="fw-semibold"><?= htmlspecialchars($s['nama_bahan']) ?></td>
                    <td><code><?= htmlspecialchars($s['kode_bahan'] ?? '-') ?></code></td>
                    <td class="fw-bold"><?= $s['jumlah'] ?></td>
                    <td><?= $s['satuan'] ?></td>
                    <td><?= $s['stok_minimum'] ?></td>
                    <td>
                        <?php if ($s['jumlah'] == 0): ?>
                            <span class="badge bg-dark">Habis</span>
                        <?php elseif ($s['jumlah'] <= $s['stok_minimum']): ?>
                            <span class="badge bg-danger">Menipis</span>
                        <?php else: ?>
                            <span class="badge bg-success">Aman</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?page=stok&action=edit&id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="index.php?page=stok&action=hapus&id=<?= $s['id'] ?>"
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Hapus bahan ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($stok)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-3">Belum ada data stok bahan</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>