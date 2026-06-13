<?php
$page_title = 'Pengaturan Admin';
include 'admin_header.php';

$error = '';
$success = '';

// Ambil data admin saat ini
$admin_id = $_SESSION['admin_id'];
$admin = $conn->query("SELECT * FROM admin WHERE id = $admin_id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = sanitize($_POST['nama_lengkap'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$nama_lengkap || !$username) {
        $error = 'Nama lengkap dan username wajib diisi.';
    } elseif ($username !== $admin['username']) {
        $check = $conn->prepare("SELECT id FROM admin WHERE username = ? AND id != ?");
        $check->bind_param('si', $username, $admin_id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $error = 'Username sudah digunakan oleh akun lain.';
        }
        $check->close();
    }

    if (!$error && $current_password && (!$new_password || !$confirm_password)) {
        $error = 'Lengkapi password baru dan konfirmasi jika ingin mengganti password.';
    }

    if (!$error && $current_password) {
        if (!password_verify($current_password, $admin['password'])) {
            $error = 'Password lama tidak sesuai.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Konfirmasi password baru tidak cocok.';
        }
    }

    if (!$error) {
        if ($current_password) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin SET nama_lengkap = ?, username = ?, password = ? WHERE id = ?");
            $stmt->bind_param('sssi', $nama_lengkap, $username, $hashed, $admin_id);
        } else {
            $stmt = $conn->prepare("UPDATE admin SET nama_lengkap = ?, username = ? WHERE id = ?");
            $stmt->bind_param('ssi', $nama_lengkap, $username, $admin_id);
        }
        if ($stmt->execute()) {
            $_SESSION['admin_username'] = $username;
            $_SESSION['admin_nama'] = $nama_lengkap;
            $success = 'Pengaturan berhasil disimpan.';
            $admin['nama_lengkap'] = $nama_lengkap;
            $admin['username'] = $username;
        } else {
            $error = 'Gagal menyimpan pengaturan. Coba lagi.';
        }
        $stmt->close();
    }
}
?>

<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
        <div>
            <h5 style="font-weight:800;color:var(--teks-gelap);margin:0">⚙️ Pengaturan Admin</h5>
            <p style="color:#9CA3AF;margin:0.5rem 0 0;font-size:0.95rem">Kelola username, nama lengkap, dan password akun admin Anda.</p>
        </div>
    </div>

    <?php if ($error): ?>
    <div class="alert-custom alert-error-custom mb-4">❌ <?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="alert-custom alert-success-custom mb-4">✅ <?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-custom">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control-custom" value="<?= htmlspecialchars($admin['nama_lengkap']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Username</label>
                <input type="text" name="username" class="form-control-custom" value="<?= htmlspecialchars($admin['username']) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label-custom">Password Lama (kosongkan jika tidak ganti)</label>
                <input type="password" name="current_password" class="form-control-custom" placeholder="Masukkan password lama">
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Password Baru</label>
                <input type="password" name="new_password" class="form-control-custom" placeholder="Masukkan password baru">
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Konfirmasi Password Baru</label>
                <input type="password" name="confirm_password" class="form-control-custom" placeholder="Ulangi password baru">
            </div>
            <div class="col-12" style="text-align:right;">
                <button type="submit" class="btn-primary-custom" style="padding:12px 24px;">Simpan Perubahan</button>
            </div>
        </div>
    </form>
</div>
