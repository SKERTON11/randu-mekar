<?php
// =====================================================
// Konfigurasi Koneksi Database
// Toko Randu Mekar
// =====================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'randu_mekar');
define('SITE_URL', 'http://localhost/randu-mekar');
define('WHATSAPP_NUMBER', '6287752287106'); // Ganti dengan nomor WA toko
define('WHATSAPP_DISPLAY', '+62 877-5228-7106');
define('TOKO_NAMA', 'Toko Randu Mekar');
define('TOKO_ALAMAT', 'Dusun I, Tawang, Kec. Weru, Kabupaten Sukoharjo, Jawa Tengah');
// Social handles (used for display labels)
define('INSTAGRAM_HANDLE', 'muhammaddp_11');
define('FACEBOOK_HANDLE', '1M3wfjiN9G');
define('TIKTOK_HANDLE', '@opetalamak');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<div style="font-family:sans-serif;text-align:center;padding:50px;color:#8B6B43;">
        <h2>⚠️ Koneksi Database Gagal</h2>
        <p>' . $conn->connect_error . '</p>
        <p>Pastikan XAMPP/MySQL sudah aktif dan database <strong>randu_mekar</strong> sudah dibuat.</p>
    </div>');
}

$conn->set_charset('utf8mb4');

// Helper: Format Rupiah
function formatRupiah($angka)
{
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Helper: Sanitize input
function sanitize($data)
{
    global $conn;
    return htmlspecialchars(strip_tags(trim($conn->real_escape_string($data))));
}

// Helper: Redirect (FIX - absolute path agar bekerja dari subfolder apapun)
function redirect($url)
{
    // Jika URL relatif dan tidak dimulai dengan http, buat jadi absolute dari root project
    if (!preg_match('/^https?:\/\//', $url) && $url[0] !== '/') {
        $script = $_SERVER['SCRIPT_NAME'];
        $dir    = dirname($script);
        // Kalau dari dalam admin/, naik satu level ke root project
        if (strpos($script, '/admin/') !== false) {
            $base = dirname($dir); // naik dari /admin ke /randu-mekar
        } else {
            $base = $dir;
        }
        $base = rtrim($base, '/');
        $url  = $base . '/' . ltrim($url, '/');
    }
    header("Location: $url");
    exit();
}

// Helper: Cek login admin
function cekLogin()
{
    if (!isset($_SESSION['admin_id'])) {
        redirect('login.php');
    }
}

// Helper: Upload gambar — selalu simpan ke /uploads di root project
function uploadGambar($file, $folder = 'uploads/')
{
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        return ['error' => 'Format file tidak didukung. Gunakan JPG, JPEG, PNG, atau WEBP.'];
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        return ['error' => 'Ukuran file maksimal 5MB.'];
    }

    // __DIR__ adalah /config, jadi naik satu level ke root project
    $namaFile = uniqid('img_') . '.' . $ext;
    $tujuan   = __DIR__ . '/../' . ltrim($folder, '/') . $namaFile;

    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        return ['success' => true, 'filename' => $namaFile];
    }

    return ['error' => 'Gagal mengupload file. Periksa izin folder uploads/.'];
}
