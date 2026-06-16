<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="flex-grow-1 p-4">

    <h4 class="fw-bold mb-4">Rekap Keuangan</h4>

    <!-- Filter Bulan -->
    <form method="GET" class="d-flex gap-2 mb-4">
        <input type="hidden" name="page" value="keuangan">

        <select name="bulan" class="form-select" style="max-width:160px">
            <?php
            /** @var array $bulan */
            /** @var array $tahun */
            /** @var array $transaksiKeluar */
            /** @var int $totalPemasukan */
            /** @var int $totalPengeluaran */
            /** @var int $keuntungan */
            $namaBulan = [
                '',
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ];

            for ($m = 1; $m <= 12; $m++):
            ?>
                <option value="<?= $m ?>" <?= ($bulan == $m) ? 'selected' : '' ?>>
                    <?= $namaBulan[$m] ?>
                </option>
            <?php endfor; ?>
        </select>

        <select name="tahun" class="form-select" style="max-width:120px">
            <?php for ($y = date('Y'); $y >= date('Y') - 3; $y--): ?>
                <option value="<?= $y ?>" <?= ($tahun == $y) ? 'selected' : '' ?>>
                    <?= $y ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit" class="btn text-white" style="background:#3B4A1F">
            Filter
        </button>
    </form>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="card p-3 border-success">
                <div class="small text-muted">Total Pemasukan</div>
                <div class="fs-4 fw-bold text-success">
                    Rp <?= number_format($totalPemasukan, 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3 border-danger">
                <div class="small text-muted">Total Pengeluaran</div>
                <div class="fs-4 fw-bold text-danger">
                    Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3" style="border-color:#3B4A1F">
                <div class="small text-muted">Keuntungan Bersih</div>
                <div class="fs-4 fw-bold" style="color:#3B4A1F">
                    Rp <?= number_format($keuntungan, 0, ',', '.') ?>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-3">

        <!-- PEMASUKAN -->
        <div class="col-md-6">
            <div class="card">

                <div class="card-header fw-bold text-success">
                    Pemasukan (Pesanan Selesai)
                </div>

                <div class="card-body p-0">

                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (!empty($transaksiMasuk)): ?>

                                <?php foreach ($transaksiMasuk as $t): ?>

                                    <tr>

                                        <td>
                                            <?= htmlspecialchars($t['nama_pemesan'] ?? '-') ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($t['jumlah'] ?? 0, 0, ',', '.') ?>
                                        </td>

                                        <td>
                                            <?= !empty($t['tanggal'])
                                                ? date('d/m/Y', strtotime($t['tanggal']))
                                                : '-' ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        Belum ada pemasukan
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <!-- PENGELUARAN -->
        <div class="col-md-6">

            <div class="card">

                <div class="card-header fw-bold text-danger d-flex justify-content-between">

                    <span>Pengeluaran</span>

                    <button
                        class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modalPengeluaran">
                        + Catat
                    </button>

                </div>

                <div class="card-body p-0">

                    <table class="table table-sm mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>Keterangan</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (!empty($transaksiKeluar)): ?>

                                <?php foreach ($transaksiKeluar as $t): ?>

                                    <tr>

                                        <td>
                                            <?= htmlspecialchars($t['keterangan']) ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($t['jumlah'], 0, ',', '.') ?>
                                        </td>

                                        <td>
                                            <?= date('d/m/Y', strtotime($t['tanggal'])) ?>
                                        </td>

                                        <td>
                                            <a
                                                href="index.php?page=keuangan&action=hapus&id=<?= $t['id'] ?>"
                                                class="text-danger small"
                                                onclick="return confirm('Hapus data ini?')">
                                                Hapus
                                            </a>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada pengeluaran
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL TAMBAH PENGELUARAN -->
<div class="modal fade" id="modalPengeluaran" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Catat Pengeluaran</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <form method="POST"
                  action="index.php?page=keuangan&action=tambah_pengeluaran">

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input
                            type="text"
                            name="keterangan"
                            class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input
                            type="number"
                            name="jumlah"
                            class="form-control"
                            min="1"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="<?= date('Y-m-d') ?>">
                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn text-white"
                        style="background:#3B4A1F">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>