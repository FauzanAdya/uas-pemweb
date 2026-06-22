<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php
/** @var int $pesananHariIni */
/** @var array $belumLunas */
/** @var array $segeraDikirim */
/** @var array $rekapBulanIni */
/** @var array $semuaPesanan */
?>
<div class="flex-grow-1 p-4">
    <h4 class="fw-bold mb-4">Dashboard Admin</h4>
    <?php 
    if (!empty($_SESSION['sukses'])): ?>
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
            <div class="card p-3 bg-warning text-dark">
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
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Pemesan</th>
                        <th>Produk</th>
                        <th>Tgl Pesan</th>
                        <th>Tgl Ambil</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Konfirmasi WA</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($semuaPesanan as $i => $p): 
                    // Buat pesan WA otomatis
                    $noWA  = preg_replace('/^0/', '62', $p['no_wa']);
                    $noWA  = preg_replace('/[^0-9]/', '', $noWA);
                    $tglAmbil = date('d/m/Y', strtotime($p['tanggal_ambil']));
                    $total    = 'Rp ' . number_format($p['total_harga'], 0, ',', '.');
                    $dp       = 'Rp ' . number_format($p['total_harga'] * 0.5, 0, ',', '.');

                    $pesanWA = urlencode(
                        "Halo kak *{$p['nama_pemesan']}* \n\n" .
                        "Pesanan buket kakak sudah kami terima!\n\n" .
                        " *Detail Pesanan:*\n" .
                        "• Produk: {$p['nama_produk']}\n" .
                        "• Total: {$total}\n" .
                        "• DP Minimal (50%): {$dp}\n" .
                        "• Tanggal Ambil: {$tglAmbil}\n\n" .
                        "Silakan lakukan pembayaran DP minimal 50% via QRIS yang kami kirimkan ya kak \n\n" .
                        "Terima kasih sudah memesan! "
                    );
                    $linkWA = "https://wa.me/{$noWA}?text={$pesanWA}";
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <div class="fw-semibold"><?= htmlspecialchars($p['nama_pemesan']) ?></div>
                        <!-- Link WA dengan pesan konfirmasi otomatis -->
                        <a href="<?= $linkWA ?>" target="_blank" 
                           class="badge text-decoration-none mt-1"
                           style="background:#25D366;color:#fff;font-size:11px;"
                           title="Klik untuk konfirmasi pesanan via WA">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                            </svg>
                            <?= $p['no_wa'] ?>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_pesan'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['tanggal_ambil'])) ?></td>
                    <td>Rp <?= number_format($p['total_harga'],0,',','.') ?></td>
                    <td><span class="badge badge-<?= $p['status'] ?>"><?= ucfirst($p['status']) ?></span></td>
                    <td>
                        <a href="<?= $linkWA ?>" target="_blank" 
                           class="btn btn-sm fw-semibold text-white"
                           style="background:#25D366;font-size:12px;">
                             Konfirmasi
                        </a>
                    </td>
                    <td>
                        <a href="index.php?page=pesanan&action=detail&id=<?= $p['id'] ?>" 
                           class="btn btn-sm btn-outline-secondary">Detail</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($semuaPesanan)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-3">Belum ada pesanan</td></tr>
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
            <table class="table mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Produk</th><th>Tgl Ambil</th><th>Konfirmasi WA</th></tr>
                </thead>
                <tbody>
                <?php foreach ($segeraDikirim as $p):
                    $noWA2 = preg_replace('/[^0-9]/', '', preg_replace('/^0/', '62', $p['no_wa']));
                    $tgl2  = date('d/m/Y', strtotime($p['tanggal_ambil']));
                    $pesanSiap = urlencode(
                        "Halo kak *{$p['nama_pemesan']}* \n\n" .
                        "Buket pesanan kakak sudah siap! \n\n" .
                        " Tanggal Ambil: {$tgl2}\n" .
                        " Produk: {$p['nama_produk']}\n\n" .
                        "Mohon datang tepat waktu ya kak \n" .
                        "Terima kasih sudah memesan di toko kami! "
                    );
                ?>
                <tr>
                    <td class="fw-semibold"><?= htmlspecialchars($p['nama_pemesan']) ?></td>
                    <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                    <td class="fw-bold text-danger"><?= $tgl2 ?></td>
                    <td>
                        <a href="https://wa.me/<?= $noWA2 ?>?text=<?= $pesanSiap ?>" target="_blank"
                           class="btn btn-sm fw-semibold text-white" style="background:#25D366">
                            Ingatkan Pengambilan
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
