<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <a href="index.php?page=stok" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>
    <h4 class="fw-bold mb-4"><?= isset($bahan) ? 'Edit Bahan' : 'Tambah Bahan Baru' ?></h4>
    <div class="card p-4" style="max-width:500px">
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="index.php?page=stok&action=<?= isset($bahan)?'update':'simpan' ?>">
            <?php if (isset($bahan)): ?>
                <input type="hidden" name="id" value="<?= $bahan['id'] ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Bahan</label>
                <input type="text" name="nama_bahan" class="form-control" value="<?= isset($bahan)?htmlspecialchars($bahan['nama_bahan']):'' ?>" placeholder="cth: Kertas Wrap Pink" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="<?= isset($bahan)?$bahan['jumlah']:0 ?>" min="0" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Satuan</label>
                <select name="satuan" class="form-select">
                    <option value="lembar" <?= (isset($bahan)&&$bahan['satuan']==='lembar')?'selected':'' ?>>Lembar</option>
                    <option value="meter"  <?= (isset($bahan)&&$bahan['satuan']==='meter')?'selected':'' ?>>Meter</option>
                    <option value="buah"   <?= (isset($bahan)&&$bahan['satuan']==='buah')?'selected':'' ?>>Buah</option>
                    <option value="rol"    <?= (isset($bahan)&&$bahan['satuan']==='rol')?'selected':'' ?>>Rol</option>
                    <option value="pack"   <?= (isset($bahan)&&$bahan['satuan']==='pack')?'selected':'' ?>>Pack</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Stok Minimum (peringatan dikirim jika di bawah ini)</label>
                <input type="number" name="stok_minimum" class="form-control" value="<?= isset($bahan)?$bahan['stok_minimum']:5 ?>" min="1">
            </div>
            <button type="submit" class="btn text-white w-100" style="background:#3B4A1F">
                <?= isset($bahan) ? 'Simpan Perubahan' : 'Tambah Bahan' ?>
            </button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
