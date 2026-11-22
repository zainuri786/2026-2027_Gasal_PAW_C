<?php
require_once 'function.php';
cek_owner();

$pesan = "";

if (isset($_POST['simpan'])) {
    $kode        = mysqli_real_escape_string($kon, $_POST['kode_barang']);
    $nama        = mysqli_real_escape_string($kon, $_POST['nama_barang']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $supplier_id = (int)$_POST['supplier_id'];

    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        $query = "UPDATE barang SET kode_barang='$kode', nama_barang='$nama', harga=$harga, stok=$stok, supplier_id=$supplier_id WHERE id=$id";
    } else {
        $query = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES ('$kode', '$nama', $harga, $stok, $supplier_id)";
    }

    if (mysqli_query($kon, $query)) {
        header("Location: data_barang.php");
        exit;
    } else {
        $pesan = "Gagal menyimpan data barang.";
    }
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    $cek = mysqli_query($kon, "SELECT * FROM transaksi_detail WHERE barang_id=$id");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Barang tidak dapat dihapus karena sudah tercatat pada transaksi!";
    } else {
        mysqli_query($kon, "DELETE FROM barang WHERE id=$id");
        header("Location: data_barang.php");
        exit;
    }
}

$edit_data = null;
if (isset($_GET['ubah'])) {
    $id_ubah = (int)$_GET['ubah'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($kon, "SELECT * FROM barang WHERE id=$id_ubah"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Barang - Sistem Penjualan</title>
</head>
<body class="bg-light">
    <?php tampil_navbar('master'); ?>
    <div class="container">
        <h4 class="fw-bold mb-3">Data Master Barang</h4>

        <?php if ($pesan != ""): ?>
            <div class="alert alert-warning"><?= htmlspecialchars($pesan); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold"><?= $edit_data ? 'Edit Barang' : 'Tambah Barang'; ?></div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?? ''; ?>">
                            <div class="mb-2">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" class="form-control" value="<?= $edit_data['kode_barang'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" value="<?= $edit_data['nama_barang'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" value="<?= $edit_data['harga'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" value="<?= $edit_data['stok'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Supplier</label>
                                <select name="supplier_id" class="form-select">
                                    <option value="0">-- Pilih Supplier --</option>
                                    <?php
                                    $sup = mysqli_query($kon, "SELECT * FROM supplier ORDER BY nama ASC");
                                    while ($s = mysqli_fetch_assoc($sup)):
                                    ?>
                                    <option value="<?= $s['id']; ?>" <?= (($edit_data['supplier_id'] ?? '') == $s['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($s['nama']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan</button>
                            <?php if ($edit_data): ?>
                                <a href="data_barang.php" class="btn btn-secondary btn-sm">Batal</a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Supplier</th>
                                    <th width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $res = mysqli_query($kon, "SELECT barang.*, supplier.nama AS nama_supplier FROM barang LEFT JOIN supplier ON barang.supplier_id = supplier.id ORDER BY barang.id ASC");
                                while ($row = mysqli_fetch_assoc($res)):
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['kode_barang']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                    <td><?= $row['stok']; ?></td>
                                    <td><?= htmlspecialchars($row['nama_supplier'] ?? '-'); ?></td>
                                    <td>
                                        <a href="?ubah=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus barang ini?')">Hapus</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>