<?php
require_once '../config/database.php';

$message = '';
$error = '';

// Check if admin table exists
try {
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin'");
    $tableExists = $stmt->fetchColumn();
} catch (Exception $e) {
    $tableExists = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? 'admin');
    $password = $_POST['password'] ?? '';

    if (strlen($password) < 4) {
        $error = 'Password minimal 4 karakter';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Check if user exists
            $stmt = $pdo->prepare("SELECT id_admin FROM admin WHERE username = ?");
            $stmt->execute([$username]);
            $existing = $stmt->fetch();

            if ($existing) {
                $stmt = $pdo->prepare("UPDATE admin SET password = ? WHERE username = ?");
                $stmt->execute([$hash, $username]);
                $message = "Password user <strong>$username</strong> berhasil direset!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                $stmt->execute([$username, $hash]);
                $message = "User <strong>$username</strong> berhasil dibuat!";
            }
        } catch (Exception $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - Koperasi UTM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="admin-login">
        <div class="admin-login-card" style="max-width: 460px;">
            <div class="login-logo">
                <img src="../assets/img/logo-koperasi.png" alt="Koperasi UTM" width="56" height="56" style="border-radius: 8px;">
                <h3>Setup Admin</h3>
                <p>Reset password atau buat user admin baru</p>
            </div>

            <?php if (!$tableExists): ?>
            <div class="alert alert-danger" style="border-radius: 10px; font-size: 14px; padding: 12px 16px;">
                <i class="fas fa-exclamation-triangle me-2"></i>Tabel <strong>admin</strong> tidak ditemukan!<br>
                Jalankan file <code>database/koperasi.sql</code> terlebih dahulu.
            </div>
            <?php endif; ?>

            <?php if ($message): ?>
            <div class="alert alert-success" style="border-radius: 10px; font-size: 14px; padding: 12px 16px;">
                <i class="fas fa-check-circle me-2"></i><?= $message ?>
            </div>
            <?php endif; ?>

            <?php if ($error): ?>
            <div class="alert alert-danger" style="border-radius: 10px; font-size: 14px; padding: 12px 16px;">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size: 14px;">Username</label>
                    <input type="text" name="username" class="form-control" value="admin" style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size: 14px;">Password Baru</label>
                    <input type="text" name="password" class="form-control" placeholder="Masukkan password baru" required style="border-radius: 10px; border: 1px solid rgba(15,81,50,0.1); padding: 10px 14px;">
                </div>
                <button type="submit" class="btn-login mb-3">
                    <i class="fas fa-save me-2"></i>Simpan
                </button>
            </form>

            <div class="text-center">
                <a href="login.php" style="color: var(--primary); font-size: 13px; text-decoration: none;">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>
</body>
</html>
