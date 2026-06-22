<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<div class="flex-grow-1 p-4">
    <a href="index.php?page=pembayaran" class="btn btn-sm btn-outline-secondary mb-3">&larr; Kembali</a>

    <?php
    /** @var array $pembayaran */ 
    $isLunas = $pembayaran['tipe_bayar'] === 'lunas'; ?>

    <div class="d-flex align-items-center gap-2 mb-4">
        <h4 class="fw-bold mb-0">Verifikasi Bukti Pembayaran</h4>
        <?php if ($isLunas): ?>
            <span class="badge bg-success px-3 py-2 fs-6"> LUNAS</span>
        <?php else: ?>
            <span class="badge bg-warning text-dark px-3 py-2 fs-6"> DP 50%</span>
        <?php endif; ?>
    </div>

    <div class="row g-3">
        <!-- Rincian Pesanan -->
        <div class="col-md-5">
            <div class="card p-3 h-100">
                <h6 class="fw-bold mb-3">Rincian Pesanan</h6>
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted">Nama</td><td class="fw-bold"><?= htmlspecialchars($pembayaran['nama_pemesan']) ?></td></tr>
                    <tr><td class="text-muted">No WA</td><td><a href="https://wa.me/<?= $pembayaran['no_wa'] ?>" target="_blank"><?= $pembayaran['no_wa'] ?></a></td></tr>
                    <tr><td class="text-muted">Total Harga</td><td class="fw-bold">Rp <?= number_format($pembayaran['total_harga'],0,',','.') ?></td></tr>
                    <tr><td class="text-muted">Tgl Ambil</td><td><?= date('d/m/Y', strtotime($pembayaran['tanggal_ambil'])) ?></td></tr>
                    <tr><td class="text-muted">Tipe Bayar</td>
                        <td>
                            <?php if ($isLunas): ?>
                                <span class="badge bg-success">LUNAS</span>
                                <div class="small text-muted mt-1">Rp <?= number_format($pembayaran['total_harga'],0,',','.') ?></div>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">DP 50%</span>
                                <div class="small text-muted mt-1">Rp <?= number_format($pembayaran['total_harga']*0.5,0,',','.') ?></div>
                                <div class="small text-danger">Sisa: Rp <?= number_format($pembayaran['total_harga']*0.5,0,',','.') ?> (COD)</div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><td class="text-muted">Tgl Upload</td><td><?= date('d/m/Y H:i', strtotime($pembayaran['tanggal_upload'])) ?></td></tr>
                </table>

                <?php if ($pembayaran['is_custom']): ?>
                <hr>
                <h6 class="fw-bold mb-2 small text-uppercase text-muted">Detail Kustomisasi</h6>
                <table class="table table-borderless table-sm mb-0">
                    <tr><td class="text-muted">Warna Kertas</td><td><?= htmlspecialchars($pembayaran['warna_kertas'] ?? '-') ?></td></tr>
                    <tr><td class="text-muted">Jenis Isi</td><td><?= htmlspecialchars($pembayaran['jenis_isi'] ?? '-') ?></td></tr>
                    <tr><td class="text-muted">Tambahan</td><td><?= htmlspecialchars($pembayaran['tambahan'] ?? '-') ?></td></tr>
                </table>
                <div class="alert alert-info small mt-2 mb-0">
                    ℹ️ Saat dikonfirmasi, stok bahan yang namanya cocok dengan kustomisasi di atas akan otomatis dikurangi 1.
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bukti & Aksi -->
        <div class="col-md-7">
            <div class="card p-3">
                <h6 class="fw-bold mb-3">Bukti Pembayaran QRIS</h6>
                <div class="text-center mb-3">
                    <img src="assets/img/bukti/<?= htmlspecialchars($pembayaran['file_bukti']) ?>"
                         class="img-fluid rounded border" style="max-height:320px; object-fit:contain;">
                </div>

                <?php
                // Siapkan nomor WA pelanggan
                $noWA     = preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $pembayaran['no_wa']));
                $tglAmbil = date('d/m/Y', strtotime($pembayaran['tanggal_ambil']));
                $totalFmt = 'Rp ' . number_format($pembayaran['total_harga'], 0, ',', '.');
                $dpFmt    = 'Rp ' . number_format($pembayaran['total_harga'] * 0.5, 0, ',', '.');

                // Pesan WA jika DITERIMA
                $pesanDiterima = urlencode(
                    "Halo kak *{$pembayaran['nama_pemesan']}* \n\n" .
                    " *Pembayaran kakak sudah kami terima!*\n\n" .
                    " *Detail Pesanan:*\n" .
                    "• Total: {$totalFmt}\n" .
                    ($isLunas
                        ? "• Status: *LUNAS* \n"
                        : "• Status: *DP 50% Diterima* \n• Sisa: {$dpFmt} (dibayar saat ambil)\n"
                    ) .
                    "• Tanggal Ambil: {$tglAmbil}\n\n" .
                    "Pesanan kakak sedang kami proses ya \n" .
                    "Terima kasih sudah memesan! "
                );
                $linkDiterima = "https://wa.me/{$noWA}?text={$pesanDiterima}";
                ?>

                <!-- Konfirmasi: langsung submit, stok otomatis berkurang di controller -->
                <form method="POST" action="index.php?page=pembayaran&action=konfirmasi" class="mb-2">
                    <input type="hidden" name="id" value="<?= $pembayaran['id'] ?>">
                    <button type="submit" class="btn btn-success w-100 fw-bold py-2"
                            onclick="return confirm('Konfirmasi bukti pembayaran <?= $isLunas?'LUNAS':'DP' ?> ini valid?<?= $pembayaran['is_custom'] ? ' Stok bahan terkait akan otomatis berkurang.' : '' ?>')">
                        ✓ Tandai <?= $isLunas ? ' LUNAS DITERIMA' : ' DP DITERIMA' ?>
                    </button>
                </form>

                <!-- Tombol WA setelah konfirmasi diterima -->
                <a href="<?= $linkDiterima ?>" target="_blank"
                   class="btn w-100 fw-bold text-white mb-3"
                   style="background:#25D366">
                    Beritahu Pelanggan — Pembayaran Diterima
                </a>

                <hr>

                <!-- Tolak -->
                <form method="POST" action="index.php?page=pembayaran&action=tolak" id="formTolak">
                    <input type="hidden" name="id" value="<?= $pembayaran['id'] ?>">
                    <button type="submit" class="btn btn-danger w-100 fw-bold mb-2"
                            onclick="return confirm('PERHATIAN: Pesanan ini akan DIHAPUS PERMANEN dari sistem. Lanjutkan?')">
                        ✗ Tolak & Hapus Pesanan
                    </button>
                </form>

                <!-- Tombol WA setelah tolak -->
                <button onclick="kirimWATolak()" class="btn w-100 fw-bold text-white"
                        style="background:#dc3545">
                    Beritahu Pelanggan — Pembayaran Ditolak
                </button>

                <script>
                function kirimWATolak() {
                    const noWA = '<?= $noWA ?>';
                    const nama = '<?= addslashes($pembayaran['nama_pemesan']) ?>';

                    const pesan = encodeURIComponent(
                        `Halo kak *${nama}* \n\n` +
                        `Mohon maaf, bukti pembayaran pesanan kakak tidak dapat kami verifikasi.\n\n` +
                        ` Pesanan kakak telah kami batalkan dari sistem.\n\n` +
                        `Jika ingin memesan kembali, silakan kunjungi website kami dan lakukan pemesanan ulang dengan bukti pembayaran yang valid.\n\n` +
                        `Terima kasih! `
                    );
                    window.open(`https://wa.me/${noWA}?text=${pesan}`, '_blank');
                }
                </script>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
