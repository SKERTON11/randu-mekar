<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/koneksi.php';

// Kalau sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    redirect('admin/dashboard.php');
}

$error = '';
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (!$username || !$password) {
        $error = 'Username dan password harus diisi!';
    } else {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $admin = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_nama'] = $admin['nama_lengkap'];
            redirect('admin/dashboard.php');
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Toko Randu Mekar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-card">
        <!-- Logo -->
        <div class="text-center mb-4">
            <div style="width:64px;height:64px;margin:0 auto 1rem;">
                <img src="assets/images/logo toko.png" alt="Toko Randu Mekar" style="width:64px;height:64px;border-radius:12px;object-fit:cover;box-shadow:0 8px 20px rgba(139,107,67,0.3);display:block;margin:0 auto">
            </div>
            <h2 style="font-weight:800;color:var(--teks-gelap);font-size:1.4rem">Toko Randu Mekar</h2>
            <p style="color:#7A6050;font-size:0.85rem">Panel Admin — Masuk untuk mengelola toko</p>
        </div>
        
        <?php if ($error): ?>
        <div class="alert-custom alert-error-custom mb-4">
            ❌ <?= $error ?>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label-custom">Username</label>
                <div style="position:relative">
                    <i class="bi bi-person" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--coklat-muda)"></i>
                    <input type="text" name="username" class="form-control-custom" 
                           placeholder="Masukkan username"
                           style="padding-left:40px"
                           value="<?= htmlspecialchars($username) ?>" 
                           required autofocus>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label-custom">Password</label>
                <div style="position:relative">
                    <i class="bi bi-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--coklat-muda)"></i>
                    <input type="password" name="password" id="passwordInput" class="form-control-custom" 
                           placeholder="Masukkan password"
                           style="padding-left:40px;padding-right:44px"
                           required>
                    <button type="button" onclick="togglePassword()" 
                            style="position:absolute;right:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--coklat-muda);cursor:pointer;padding:0">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;font-size:0.95rem;padding:14px">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Dashboard
            </button>
        </form>
        
        <div style="text-align:center;margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(214,185,140,0.2)">
            <a href="index.php" style="color:var(--coklat-tua);text-decoration:none;font-size:0.85rem">
                <i class="bi bi-arrow-left"></i> Kembali ke Website
            </a>
        </div>
        
        <!-- Default login info removed -->
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
</body>
</html>
