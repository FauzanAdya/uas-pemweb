<?php
$page = $page ?? '';
?>
<div class="sidebar p-3" style="width:240px; min-width:240px;">
    <div class="brand mb-4 px-2"><i class="bi bi-flower1"></i> <?= APP_NAME ?></div>
    <nav class="d-flex flex-column gap-1">
        <a href="index.php?page=dashboard" class="p-2 <?= ($page==='dashboard')?'active':'' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="index.php?page=pesanan" class="p-2 <?= ($page==='pesanan')?'active':'' ?>">
            <i class="bi bi-bag-check"></i> Manajemen Pesanan
        </a>
        <a href="index.php?page=pembayaran" class="p-2 <?= ($page==='pembayaran')?'active':'' ?>">
            <i class="bi bi-credit-card"></i> Manajemen Pembayaran
        </a>
        <a href="index.php?page=katalog_admin" class="p-2 <?= ($page==='katalog_admin')?'active':'' ?>">
            <i class="bi bi-grid"></i> Katalog Produk
        </a>
        <a href="index.php?page=stok" class="p-2 <?= ($page==='stok')?'active':'' ?>">
            <i class="bi bi-box-seam"></i> Stok Bahan
        </a>
        <a href="index.php?page=keuangan" class="p-2 <?= ($page==='keuangan')?'active':'' ?>">
            <i class="bi bi-cash-stack"></i> Rekap Keuangan
        </a>
        <a href="index.php?page=riwayat" class="p-2 <?= ($page==='riwayat')?'active':'' ?>">
            <i class="bi bi-clock-history"></i> Riwayat Pesanan
        </a>
        <hr style="border-color:rgba(255,255,255,0.2)">
        <a href="index.php?page=logout" class="p-2 text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </nav>
</div>

