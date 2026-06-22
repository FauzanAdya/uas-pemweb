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
                <input type="text" name="nama_bahan" class="form-control"
                       value="<?= isset($bahan)?htmlspecialchars($bahan['nama_bahan']):'' ?>"
                       placeholder="cth: Kertas Wrap Pink" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Bahan</label>
                <select name="kode_bahan" class="form-select" required>
                    <option value="">-- Pilih Kode Bahan --</option>
                    <optgroup label="Warna Kertas">
                        <option value="pink"   <?= (isset($bahan)&&$bahan['kode_bahan']==='pink')?'selected':'' ?>>pink</option>
                        <option value="putih"  <?= (isset($bahan)&&$bahan['kode_bahan']==='putih')?'selected':'' ?>>putih</option>
                        <option value="biru"   <?= (isset($bahan)&&$bahan['kode_bahan']==='biru')?'selected':'' ?>>biru</option>
                        <option value="ungu"   <?= (isset($bahan)&&$bahan['kode_bahan']==='ungu')?'selected':'' ?>>ungu</option>
                        <option value="kuning" <?= (isset($bahan)&&$bahan['kode_bahan']==='kuning')?'selected':'' ?>>kuning</option>
                        <option value="hijau"  <?= (isset($bahan)&&$bahan['kode_bahan']==='hijau')?'selected':'' ?>>hijau</option>
                    </optgroup>
                    <optgroup label="Jenis Isi">
                        <option value="bunga_artifisial" <?= (isset($bahan)&&$bahan['kode_bahan']==='bunga_artifisial')?'selected':'' ?>>bunga_artifisial</option>
                        <option value="bunga_segar"      <?= (isset($bahan)&&$bahan['kode_bahan']==='bunga_segar')?'selected':'' ?>>bunga_segar</option>
                        <option value="snack"     <?= (isset($bahan)&&$bahan['kode_bahan']==='snack')?'selected':'' ?>>snack</option>
                        <option value="uang"      <?= (isset($bahan)&&$bahan['kode_bahan']==='uang')?'selected':'' ?>>uang</option>
                        <option value="kombinasi" <?= (isset($bahan)&&$bahan['kode_bahan']==='kombinasi')?'selected':'' ?>>kombinasi</option>
                    </optgroup>
                    <optgroup label="Tambahan Hiasan">
                        <option value="pita_premium" <?= (isset($bahan)&&$bahan['kode_bahan']==='pita_premium')?'selected':'' ?>>pita_premium</option>
                        <option value="coklat"       <?= (isset($bahan)&&$bahan['kode_bahan']==='coklat')?'selected':'' ?>>coklat</option>
                        <option value="boneka"       <?= (isset($bahan)&&$bahan['kode_bahan']==='boneka')?'selected':'' ?>>boneka</option>
                    </optgroup>
                </select>
                <div class="form-text">Kode harus sesuai dengan pilihan kustomisasi di form pemesanan pelanggan.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number" name="jumlah" class="form-control"
                       value="<?= isset($bahan)?$bahan['jumlah']:0 ?>" min="0" required>
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
                <label class="form-label fw-semibold">Stok Minimum</label>
                <input type="number" name="stok_minimum" class="form-control"
                       value="<?= isset($bahan)?$bahan['stok_minimum']:5 ?>" min="1">
                <div class="form-text">Peringatan akan muncul jika stok di bawah angka ini.</div>
            </div>

            <button type="submit" class="btn text-white w-100" style="background:#3B4A1F">
                <?= isset($bahan) ? 'Simpan Perubahan' : 'Tambah Bahan' ?>
            </button>
        </form>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
