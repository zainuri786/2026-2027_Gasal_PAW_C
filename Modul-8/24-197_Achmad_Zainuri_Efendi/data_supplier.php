<?php
require_once 'function.php';
cek_owner();

if (isset($_POST['simpan'])) {
    $nama   = mysqli_real_escape_string($kon, $_POST['nama']);
    $telp   = mysqli_real_escape_string($kon, $_POST['telp']);
    $alamat = mysqli_real_escape_string($kon, $_POST['alamat']);

    if (!empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        mysqli_query($kon, "UPDATE supplier SET nama='$nama', telp='$telp', alamat='$alamat' WHERE id=$id");
    } else {
        mysqli_query($kon, "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')");
    }
    header("Location: data_supplier.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id = (int)$_GET['hapus'];
    mysqli_query($kon, "DELETE FROM supplier WHERE id=$id");
    header("Location: data_supplier.php");
    exit;
}

$edit_data = null;
if (isset($_GET['ubah'])) {
    $id_ubah = (int)$_GET['ubah'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($kon, "SELECT * FROM supplier WHERE id=$id_ubah"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Supplier - Sistem Penjualan</title>
</head>
<body class="bg-light">
    <?php tampil_navbar('master'); ?>
    <div class="container">
        <h4 class="fw-bold mb-3">Data Master Supplier</h4>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold"><?= $edit_data ? 'Edit Supplier' : 'Tambah Supplier'; ?></div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $edit_data['id'] ?? ''; ?>">
                            <div class="mb-2">
                                <label class="form-label">Nama Supplier</label>
                                <input type="text" name="nama" class="form-control" value="<?= $edit_data['nama'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No. Telp</label>
                                <input type="text" name="telp" class="form-control" value="<?= $edit_data['telp'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2" required><?= $edit_data['alamat'] ?? ''; ?></textarea>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan</button>
                            <?php if ($edit_data): ?>
                                <a href="data_supplier.php" class="btn btn-secondary btn-sm">Batal</a>
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
                                    <th>Nama Supplier</th>
                                    <th>Telp</th>
                                    <th>Alamat</th>
                                    <th width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $res = mysqli_query($kon, "SELECT * FROM supplier ORDER BY id ASC");
                                while ($row = mysqli_fetch_assoc($res)):
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['telp']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td>
                                        <a href="?ubah=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus supplier ini?')">Hapus</a>
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