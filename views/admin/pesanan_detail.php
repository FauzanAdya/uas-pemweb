<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; 
/** @var array $pesanan */?>
<div class="flex-grow-1 p-4">
    <a href="index.php?page=pesanan" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>
    <h4 class="fw-bold mb-4">Detail Pesanan #<?= $pesanan['id'] ?></h4>
    <?php if (!empty($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Informasi Pemesan</h6>
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted">Nama</td><td><?= htmlspecialchars($pesanan['nama_pemesan']) ?></td></tr>
                    <tr><td class="text-muted">No WA</td><td><a href="https://wa.me/<?= $pesanan['no_wa'] ?>"><?= $pesanan['no_wa'] ?></a></td></tr>
                    <tr><td class="text-muted">Produk</td><td><?= htmlspecialchars($pesanan['nama_produk']) ?></td></tr>
                    <tr><td class="text-muted">Jumlah</td><td><?= $pesanan['jumlah'] ?></td></tr>
                    <tr><td class="text-muted">Ucapan</td><td><?= htmlspecialchars($pesanan['ucapan']) ?></td></tr>
                    <tr><td class="text-muted">Custom</td><td><?= $pesanan['is_custom'] ? 'Ya' : 'Tidak' ?></td></tr>
                    <?php if ($pesanan['is_custom']): ?>
                    <tr><td class="text-muted">Warna Kertas</td><td><?= $pesanan['warna_kertas'] ?></td></tr>
                    <tr><td class="text-muted">Jenis Isi</td><td><?= $pesanan['jenis_isi'] ?></td></tr>
                    <tr><td class="text-muted">Tambahan</td><td><?= $pesanan['tambahan'] ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="text-muted">Tgl Pesan</td><td><?= date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])) ?></td></tr>
                    <tr><td class="text-muted">Tgl Ambil</td><td><?= date('d/m/Y', strtotime($pesanan['tanggal_ambil'])) ?></td></tr>
                    <tr><td class="text-muted">Pengambilan</td><td><?= ucfirst($pesanan['tipe_pengambilan']) ?></td></tr>
                    <tr><td class="text-muted">Total</td><td class="fw-bold">Rp <?= number_format($pesanan['total_harga'],0,',','.') ?></td></tr>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Ubah Status Pesanan</h6>
                <form method="POST" action="index.php?page=pesanan&action=status">
                    <input type="hidden" name="id" value="<?= $pesanan['id'] ?>">
                    <div class="mb-3">
                        <label class="form-label">Status Saat Ini: <span class="badge badge-<?= $pesanan['status'] ?>"><?= ucfirst($pesanan['status']) ?></span></label>
                        <select name="status" class="form-select">
                            <option value="pending"    <?= $pesanan['status']==='pending'?'selected':'' ?>>Pending</option>
                            <option value="diproses"   <?= $pesanan['status']==='diproses'?'selected':'' ?>>Diproses</option>
                            <option value="selesai"    <?= $pesanan['status']==='selesai'?'selected':'' ?>>Selesai</option>
                            <option value="dibatalkan" <?= $pesanan['status']==='dibatalkan'?'selected':'' ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn w-100 text-white" style="background:#3B4A1F">Simpan Perubahan Status</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
