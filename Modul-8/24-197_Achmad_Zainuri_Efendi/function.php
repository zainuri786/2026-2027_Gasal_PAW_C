<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

function cek_login() {
    if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header("Location: login.php");
        exit;
    }
}

function cek_sudah_login() {
    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
        header("Location: index.php");
        exit;
    }
}

function cek_owner() {
    cek_login();
    if (!isset($_SESSION['level']) || $_SESSION['level'] != 1) {
        echo "<script>
                alert('Akses ditolak! Menu Data Master hanya dapat diakses oleh Owner (Level 1).');
                window.location.href = 'index.php';
              </script>";
        exit;
    }
}

function tampil_navbar($halaman_aktif = '') {
    $level = $_SESSION['level'] ?? 0;
    $nama  = $_SESSION['nama'] ?? 'User';
    $role  = ($level == 1) ? 'Owner' : 'Kasir';
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
      <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">Sistem Penjualan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">
          <ul class="navbar-nav me-auto">
            <!-- Menu Home (Level 1 & Level 2) -->
            <li class="nav-item">
              <a class="nav-link <?= ($halaman_aktif == 'home') ? 'active fw-semibold' : ''; ?>" href="index.php">Home</a>
            </li>

            <!-- Menu Data Master (KHUSUS Level 1 / Owner) -->
            <?php if ($level == 1): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle <?= ($halaman_aktif == 'master') ? 'active fw-semibold' : ''; ?>" href="#" role="button" data-bs-toggle="dropdown">
                Data Master
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="data_barang.php">Data Barang</a></li>
                <li><a class="dropdown-item" href="data_supplier.php">Data Supplier</a></li>
                <li><a class="dropdown-item" href="data_pelanggan.php">Data Pelanggan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="data_user.php">Data User</a></li>
              </ul>
            </li>
            <?php endif; ?>

            <!-- Menu Transaksi & Laporan (Level 1 & Level 2) -->
            <li class="nav-item">
              <a class="nav-link <?= ($halaman_aktif == 'transaksi') ? 'active fw-semibold' : ''; ?>" href="data_transaksi.php">Transaksi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= ($halaman_aktif == 'laporan') ? 'active fw-semibold' : ''; ?>" href="laporan_penjualan.php">Laporan</a>
            </li>
          </ul>

          <div class="d-flex align-items-center text-white gap-3">
            <span>
              <strong><?= htmlspecialchars($nama); ?></strong>
              <span class="badge bg-light text-primary ms-1"><?= $role; ?></span>
            </span>
            <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin logout?')">
              Logout
            </a>
          </div>
        </div>
      </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
}
?>