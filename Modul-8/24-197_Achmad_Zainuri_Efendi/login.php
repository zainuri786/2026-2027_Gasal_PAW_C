<?php
require_once 'function.php';
cek_sudah_login();

$pesan = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($kon, trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $pesan = "Username dan password tidak boleh kosong!";
    } else {
        $query = mysqli_query($kon, "SELECT * FROM user WHERE username = '$username' LIMIT 1");
        if (mysqli_num_rows($query) === 1) {
            $user = mysqli_fetch_assoc($query);

            if ($password === $user['password'] || md5($password) === $user['password'] || password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['login']    = true;
                $_SESSION['id_user']  = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama']     = $user['nama'];
                $_SESSION['level']    = (int)$user['level'];

                header("Location: index.php");
                exit;
            } else {
                $pesan = "Password salah!";
            }
        } else {
            $pesan = "Username tidak ditemukan!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Sistem Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-sm border-0" style="width: 100%; max-width: 390px;">
        <div class="card-body p-4">
            <h4 class="text-center fw-bold text-primary mb-1">Sistem Penjualan</h4>
            <p class="text-center text-muted small mb-4">Masuk sesuai akun Owner atau Kasir</p>

            <?php if ($pesan != ""): ?>
                <div class="alert alert-danger py-2 small"><?= htmlspecialchars($pesan); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100 fw-semibold">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
