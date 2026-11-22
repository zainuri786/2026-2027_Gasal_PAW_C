<?php
require_once 'function.php';
cek_owner();

$pesan = "";

// Tambah atau Edit Pelanggan
if (isset($_POST['simpan'])) {
    $id     = mysqli_real_escape_string($kon, $_POST['id']);
    $nama   = mysqli_real_escape_string($kon, $_POST['nama']);
    $jk     = mysqli_real_escape_string($kon, $_POST['jenis_kelamin']);
    $telp   = mysqli_real_escape_string($kon, $_POST['telp']);
    $alamat = mysqli_real_escape_string($kon, $_POST['alamat']);

    if (isset($_POST['edit'])) {
        $query = "UPDATE pelanggan SET nama='$nama', jenis_kelamin='$jk', telp='$telp', alamat='$alamat' WHERE id='$id'";
    } else {
        $query = "INSERT INTO pelanggan (id, nama, jenis_kelamin, telp, alamat) VALUES ('$id', '$nama', '$jk', '$telp', '$alamat')";
    }

    if (mysqli_query($kon, $query)) {
        header("Location: data_pelanggan.php");
        exit;
    } else {
        $pesan = "Data gagal disimpan. Pastikan ID pelanggan belum digunakan.";
    }
}

// Hapus Pelanggan
if (isset($_GET['hapus'])) {
    $id = mysqli_real_escape_string($kon, $_GET['hapus']);
    $cek = mysqli_query($kon, "SELECT * FROM transaksi WHERE pelanggan_id='$id'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Pelanggan tidak dapat dihapus karena masih memiliki riwayat transaksi!";
    } else {
        mysqli_query($kon, "DELETE FROM pelanggan WHERE id='$id'");
        header("Location: data_pelanggan.php");
        exit;
    }
}

$edit_data = null;
if (isset($_GET['ubah'])) {
    $id_ubah = mysqli_real_escape_string($kon, $_GET['ubah']);
    $edit_data = mysqli_fetch_assoc(mysqli_query($kon, "SELECT * FROM pelanggan WHERE id='$id_ubah'"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pelanggan - Sistem Penjualan</title>
</head>
<body class="bg-light">
    <?php tampil_navbar('master'); ?>
    <div class="container">
        <h4 class="fw-bold mb-3">Data Master Pelanggan</h4>

        <?php if ($pesan != ""): ?>
            <div class="alert alert-warning"><?= htmlspecialchars($pesan); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">
                        <?= $edit_data ? 'Edit Pelanggan' : 'Tambah Pelanggan'; ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <?php if ($edit_data): ?>
                                <input type="hidden" name="edit" value="1">
                            <?php endif; ?>
                            <div class="mb-2">
                                <label class="form-label">ID Pelanggan</label>
                                <input type="text" name="id" class="form-control" value="<?= $edit_data['id'] ?? ''; ?>" <?= $edit_data ? 'readonly' : 'required'; ?>>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" value="<?= $edit_data['nama'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="L" <?= (($edit_data['jenis_kelamin'] ?? '') == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                                    <option value="P" <?= (($edit_data['jenis_kelamin'] ?? '') == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                                </select>
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
                                <a href="data_pelanggan.php" class="btn btn-secondary btn-sm">Batal</a>
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
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>JK</th>
                                    <th>Telp</th>
                                    <th>Alamat</th>
                                    <th width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $res = mysqli_query($kon, "SELECT * FROM pelanggan ORDER BY id ASC");
                                while ($row = mysqli_fetch_assoc($res)):
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['jenis_kelamin']); ?></td>
                                    <td><?= htmlspecialchars($row['telp']); ?></td>
                                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                                    <td>
                                        <a href="?ubah=<?= urlencode($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?hapus=<?= urlencode($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus pelanggan ini?')">Hapus</a>
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