<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Kelola Katalog Produk</h4>
        <a href="index.php?page=katalog_admin&action=tambah" class="btn text-white" style="background:#3B4A1F">+ Tambah Produk</a>
    </div>
    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php
    /** @var array $katalog */ 
    endif; ?>
    <div class="row g-3">
    <?php foreach ($katalog as $k): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <?php if ($k['foto']): ?>
                    <img src="assets/img/<?= $k['foto'] ?>" class="card-img-top" style="height:200px; object-fit:cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:200px; font-size:3rem;">🌸</div>
                <?php endif; ?>
                <div class="card-body">
                    <h6 class="fw-bold"><?= htmlspecialchars($k['nama']) ?></h6>
                    <p class="text-muted small"><?= htmlspecialchars($k['deskripsi']) ?></p>
                    <div class="fw-bold text-success mb-3">Rp <?= number_format($k['harga_dasar'],0,',','.') ?></div>
                    <div class="d-flex gap-2">
                        <a href="index.php?page=katalog_admin&action=edit&id=<?= $k['id'] ?>" class="btn btn-sm btn-outline-primary flex-fill">Edit</a>
                        <a href="index.php?page=katalog_admin&action=hapus&id=<?= $k['id'] ?>" class="btn btn-sm btn-outline-danger flex-fill" onclick="return confirm('Hapus produk ini?')">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if (empty($katalog)): ?>
        <div class="col-12 text-center text-muted py-5">Belum ada produk di katalog</div>
    <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
