<?php
require_once 'function.php';
cek_owner();

$pesan = "";

if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($kon, trim($_POST['username']));
    $nama     = mysqli_real_escape_string($kon, trim($_POST['nama']));
    $alamat   = mysqli_real_escape_string($kon, trim($_POST['alamat']));
    $hp       = mysqli_real_escape_string($kon, trim($_POST['hp']));
    $level    = (int)$_POST['level'];

    if (!empty($_POST['id_user'])) {
        $id_user = (int)$_POST['id_user'];
        if (!empty($_POST['password'])) {
            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query = "UPDATE user SET username='$username', password='$pass_hash', nama='$nama', alamat='$alamat', hp='$hp', level=$level WHERE id_user=$id_user";
        } else {
            $query = "UPDATE user SET username='$username', nama='$nama', alamat='$alamat', hp='$hp', level=$level WHERE id_user=$id_user";
        }
    } else {
        $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "INSERT INTO user (username, password, nama, alamat, hp, level) VALUES ('$username', '$pass_hash', '$nama', '$alamat', '$hp', $level)";
    }

    if (mysqli_query($kon, $query)) {
        header("Location: data_user.php");
        exit;
    } else {
        $pesan = "Gagal menyimpan data user (username mungkin sudah terdaftar).";
    }
}

if (isset($_GET['hapus'])) {
    $id_hapus = (int)$_GET['hapus'];
    if ($id_hapus != $_SESSION['id_user']) {
        mysqli_query($kon, "DELETE FROM user WHERE id_user=$id_hapus");
    }
    header("Location: data_user.php");
    exit;
}

$edit_data = null;
if (isset($_GET['ubah'])) {
    $id_ubah = (int)$_GET['ubah'];
    $edit_data = mysqli_fetch_assoc(mysqli_query($kon, "SELECT * FROM user WHERE id_user=$id_ubah"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data User - Sistem Penjualan</title>
</head>
<body class="bg-light">
    <?php tampil_navbar('master'); ?>
    <div class="container">
        <h4 class="fw-bold mb-3">Data Master User</h4>

        <?php if ($pesan != ""): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($pesan); ?></div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold"><?= $edit_data ? 'Edit User' : 'Tambah User'; ?></div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="id_user" value="<?= $edit_data['id_user'] ?? ''; ?>">
                            <div class="mb-2">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?= $edit_data['username'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Password <?= $edit_data ? '<small class="text-muted">(Kosongkan jika tetap)</small>' : ''; ?></label>
                                <input type="password" name="password" class="form-control" <?= $edit_data ? '' : 'required'; ?>>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" value="<?= $edit_data['nama'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="2"><?= $edit_data['alamat'] ?? ''; ?></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">No. HP</label>
                                <input type="text" name="hp" class="form-control" value="<?= $edit_data['hp'] ?? ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level</label>
                                <select name="level" class="form-select" required>
                                    <option value="1" <?= (($edit_data['level'] ?? '') == 1) ? 'selected' : ''; ?>>1 - Owner</option>
                                    <option value="2" <?= (($edit_data['level'] ?? 2) == 2) ? 'selected' : ''; ?>>2 - Kasir</option>
                                </select>
                            </div>
                            <button type="submit" name="simpan" class="btn btn-primary btn-sm">Simpan</button>
                            <?php if ($edit_data): ?>
                                <a href="data_user.php" class="btn btn-secondary btn-sm">Batal</a>
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
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>HP</th>
                                    <th>Level</th>
                                    <th width="130">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                $res = mysqli_query($kon, "SELECT * FROM user ORDER BY id_user ASC");
                                while ($row = mysqli_fetch_assoc($res)):
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['hp']); ?></td>
                                    <td>
                                        <?= ($row['level'] == 1) 
                                            ? '<span class="badge bg-primary">1 - Owner</span>' 
                                            : '<span class="badge bg-secondary">2 - Kasir</span>'; ?>
                                    </td>
                                    <td>
                                        <a href="?ubah=<?= $row['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <?php if ($row['id_user'] != $_SESSION['id_user']): ?>
                                            <a href="?hapus=<?= $row['id_user']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus user ini?')">Hapus</a>
                                        <?php endif; ?>
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