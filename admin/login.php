<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

require_once '../config/database.php';

// Auto-create default admin if table is empty
$stmt = $pdo->query("SELECT COUNT(*) FROM admin");
if ($stmt->fetchColumn() == 0) {
    $hash = password_hash('admin', PASSWORD_BCRYPT);
    $pdo->prepare("INSERT INTO admin (username, password) VALUES ('admin', ?)")->execute([$hash]);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = [
                'id_admin' => $admin['id_admin'],
                'username' => $admin['username']
            ];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    } else {
        $error = 'Silakan isi username dan password!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Koperasi UTM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="admin-login">
        <div class="admin-login-card">
            <div class="login-logo">
                <img src="../assets/img/logo-koperasi.png" alt="Koperasi UTM" width="56" height="56" style="border-radius: 8px;">
                <h3>Panel Admin</h3>
                <p>Koperasi Universitas Trunodjoyo Madura</p>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-danger" style="border-radius: 10px; font-size: 14px; padding: 12px 16px;">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: 14px;">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size: 14px;">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>
            </form>

            <div class="text-center mt-4">
                <a href="../index.php" style="color: var(--primary); font-size: 13px; text-decoration: none;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Website
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
