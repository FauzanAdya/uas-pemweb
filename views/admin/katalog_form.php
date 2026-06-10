<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <a href="index.php?page=katalog_admin" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>
    <h4 class="fw-bold mb-4"><?= isset($produk) ? 'Edit Produk' : 'Tambah Produk Baru' ?></h4>
    <div class="card p-4" style="max-width:600px">
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?page=katalog_admin&action=<?= isset($produk)?'update':'simpan' ?>" enctype="multipart/form-data">
            <?php if (isset($produk)): ?>
                <input type="hidden" name="id" value="<?= $produk['id'] ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= isset($produk)?htmlspecialchars($produk['nama']):'' ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="bunga"  <?= (isset($produk)&&$produk['kategori']==='bunga')?'selected':'' ?>>Buket Bunga</option>
                    <option value="snack"  <?= (isset($produk)&&$produk['kategori']==='snack')?'selected':'' ?>>Buket Snack</option>
                    <option value="uang"   <?= (isset($produk)&&$produk['kategori']==='uang')?'selected':'' ?>>Buket Uang</option>
                    <option value="custom" <?= (isset($produk)&&$produk['kategori']==='custom')?'selected':'' ?>>Custom</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga Dasar (Rp)</label>
                <input type="number" name="harga_dasar" class="form-control" value="<?= isset($produk)?$produk['harga_dasar']:'' ?>" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"><?= isset($produk)?htmlspecialchars($produk['deskripsi']):'' ?></textarea>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Produk</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <?php if (isset($produk) && $produk['foto']): ?>
                    <img src="assets/img/<?= $produk['foto'] ?>" class="mt-2 rounded" style="height:80px; object-fit:cover;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn text-white w-100" style="background:#3B4A1F">
                <?= isset($produk) ? 'Simpan Perubahan' : 'Tambah Produk' ?>
            </button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
