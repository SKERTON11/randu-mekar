<?php
// FIX: session harus distart sebelum require koneksi
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/koneksi.php';
cekLogin();

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    redirect('admin/produk.php');
}

$produk = $conn->query("SELECT * FROM produk WHERE id_produk = $id")->fetch_assoc();
if (!$produk) {
    $_SESSION['error'] = 'Produk tidak ditemukan!';
    redirect('admin/produk.php');
}

// Hapus file gambar dari server
$uploads_dir = __DIR__ . '/../uploads/';
if ($produk['gambar'] && file_exists($uploads_dir . $produk['gambar'])) {
    unlink($uploads_dir . $produk['gambar']);
}
if ($produk['gambar_3d'] && file_exists($uploads_dir . $produk['gambar_3d'])) {
    unlink($uploads_dir . $produk['gambar_3d']);
}

// Hapus dari database
$stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $_SESSION['sukses'] = "Produk \"{$produk['nama_produk']}\" berhasil dihapus!";
} else {
    $_SESSION['error'] = 'Gagal menghapus produk.';
}
$stmt->close();

redirect('admin/produk.php');
