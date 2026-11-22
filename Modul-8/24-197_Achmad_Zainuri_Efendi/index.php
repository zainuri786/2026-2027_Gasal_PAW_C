<?php
require_once 'function.php';
cek_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home - Sistem Penjualan</title>
</head>
<body class="bg-light">
    <?php tampil_navbar('home'); ?>

    <div class="container">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="fw-bold">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']); ?>!</h4>
                <p class="text-muted">
                    Anda login dengan hak akses: 
                    <strong><?= ($_SESSION['level'] == 1) ? 'Level 1 (Owner)' : 'Level 2 (Kasir)'; ?></strong>
                </p>
                <hr>
                <div class="d-flex flex-wrap gap-2">
                    <?php if ($_SESSION['level'] == 1): ?>
                        <a href="data_barang.php" class="btn btn-outline-primary">Kelola Data Barang</a>
                        <a href="data_supplier.php" class="btn btn-outline-primary">Kelola Data Supplier</a>
                        <a href="data_pelanggan.php" class="btn btn-outline-primary">Kelola Data Pelanggan</a>
                        <a href="data_user.php" class="btn btn-outline-primary">Kelola Data User</a>
                    <?php endif; ?>
                    <a href="data_transaksi.php" class="btn btn-primary">Buka Transaksi</a>
                    <a href="laporan_penjualan.php" class="btn btn-success">Buka Laporan Penjualan</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>