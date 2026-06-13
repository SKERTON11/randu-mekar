<?php
$page_title = 'Edit Produk';
include 'admin_header.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) redirect('admin/produk.php');

$produk = $conn->query("SELECT * FROM produk WHERE id_produk = $id")->fetch_assoc();
if (!$produk) {
    $_SESSION['error'] = 'Produk tidak ditemukan!';
    redirect('admin/produk.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = sanitize($_POST['nama_produk'] ?? '');
    $harga     = intval(str_replace(['.', ',', 'Rp', ' '], '', $_POST['harga'] ?? 0));
    $deskripsi = sanitize($_POST['deskripsi'] ?? '');
    $kategori  = sanitize($_POST['kategori'] ?? 'lainnya');
    $stok      = intval($_POST['stok'] ?? 0);
    $unggulan  = isset($_POST['unggulan']) ? 1 : 0;

    if (!$nama || !$harga) {
        $error = 'Nama produk dan harga wajib diisi!';
    } else {
        $gambar    = $produk['gambar'];
        $gambar_3d = $produk['gambar_3d'];

        // Upload gambar baru jika ada
        if (!empty($_FILES['gambar']['name'])) {
            $upload = uploadGambar($_FILES['gambar']);
            if (isset($upload['error'])) { $error = $upload['error']; }
            else {
                // Hapus gambar lama
                if ($gambar && file_exists("../uploads/$gambar")) unlink("../uploads/$gambar");
                $gambar = $upload['filename'];
            }
        }

        // Upload gambar 3D baru jika ada
        if (!$error && !empty($_FILES['gambar_3d']['name'])) {
            $upload3d = uploadGambar($_FILES['gambar_3d']);
            if (isset($upload3d['error'])) { $error = $upload3d['error']; }
            else {
                if ($gambar_3d && file_exists("../uploads/$gambar_3d")) unlink("../uploads/$gambar_3d");
                $gambar_3d = $upload3d['filename'];
            }
        }

        // Hapus gambar 3D jika di-check
        if (isset($_POST['hapus_gambar_3d']) && $gambar_3d) {
            if (file_exists("../uploads/$gambar_3d")) unlink("../uploads/$gambar_3d");
            $gambar_3d = '';
        }

        if (!$error) {
            $stmt = $conn->prepare("UPDATE produk SET nama_produk=?, harga=?, deskripsi=?, gambar=?, gambar_3d=?, kategori=?, stok=?, unggulan=? WHERE id_produk=?");
            $stmt->bind_param("sissssiii", $nama, $harga, $deskripsi, $gambar, $gambar_3d, $kategori, $stok, $unggulan, $id);
            if ($stmt->execute()) {
                $_SESSION['sukses'] = "Produk \"$nama\" berhasil diperbarui!";
                redirect('admin/produk.php');
            } else {
                $error = 'Gagal memperbarui produk. Coba lagi.';
            }
            $stmt->close();
        }
    }

    // Refresh data
    $produk = $conn->query("SELECT * FROM produk WHERE id_produk = $id")->fetch_assoc();
}

$kats = ['kasur'=>'🛏️ Kasur','kasur_lantai'=>'🛌 Kasur Lantai','karpet'=>'🟫 Karpet','bantal'=>'🟤 Bantal','guling'=>'🟡 Guling','sofa'=>'🛋️ Sofa','rak_piring'=>'🍽️ Rak Piring','lainnya'=>'📦 Lainnya'];
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Breadcrumb -->
        <div style="font-size:0.82rem;color:#9CA3AF;margin-bottom:1.2rem">
            <a href="dashboard.php" style="color:var(--coklat-tua);text-decoration:none">Dashboard</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;margin:0 6px"></i>
            <a href="produk.php" style="color:var(--coklat-tua);text-decoration:none">Kelola Produk</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;margin:0 6px"></i>
            Edit Produk
        </div>

        <?php if ($error): ?>
        <div class="alert-custom alert-error-custom mb-3">❌ <?= $error ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <h5 style="font-weight:800;color:var(--teks-gelap);margin-bottom:0.3rem">✏️ Edit Produk</h5>
            <p style="color:#9CA3AF;font-size:0.85rem;margin-bottom:2rem">
                Mengubah: <strong><?= htmlspecialchars($produk['nama_produk']) ?></strong>
            </p>

            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom">Nama Produk <span style="color:#ef4444">*</span></label>
                        <input type="text" name="nama_produk" class="form-control-custom"
                               value="<?= htmlspecialchars($produk['nama_produk']) ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Harga (Rp) <span style="color:#ef4444">*</span></label>
                        <input type="text" name="harga" id="hargaInput" class="form-control-custom"
                               value="<?= $produk['harga'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Kategori</label>
                        <select name="kategori" class="form-control-custom">
                            <?php foreach ($kats as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $produk['kategori'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label-custom">Stok</label>
                        <input type="number" name="stok" class="form-control-custom" min="0"
                               value="<?= intval($produk['stok']) ?>">
                    </div>
                    <div class="col-md-6" style="display:flex;align-items:flex-end">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px 16px;background:var(--cream);border-radius:12px;width:100%">
                            <input type="checkbox" name="unggulan" value="1" <?= $produk['unggulan'] ? 'checked' : '' ?>
                                   style="width:18px;height:18px;accent-color:var(--coklat-tua);cursor:pointer">
                            <span style="font-size:0.9rem;font-weight:600;color:var(--teks-gelap)">⭐ Tandai sebagai Produk Unggulan</span>
                        </label>
                    </div>

                    <div class="col-12">
                        <label class="form-label-custom">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control-custom" rows="5"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                    </div>

                    <!-- Gambar Utama -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Foto Produk</label>
                        <?php if ($produk['gambar'] && file_exists("../uploads/" . $produk['gambar'])): ?>
                        <div style="margin-bottom:10px">
                            <img src="../uploads/<?= htmlspecialchars($produk['gambar']) ?>"
                                 style="width:100%;max-height:160px;object-fit:cover;border-radius:12px;border:2px solid rgba(214,185,140,0.3)">
                            <p style="font-size:0.75rem;color:#9CA3AF;margin-top:4px">📷 Foto saat ini. Upload baru untuk mengganti.</p>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="gambar" accept="image/*" class="form-control-custom"
                               data-preview="previewGambar" style="padding:10px">
                        <img id="previewGambar" src="" style="display:none;width:100%;max-height:160px;object-fit:cover;border-radius:12px;margin-top:8px">
                    </div>

                    <!-- Gambar 3D -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Foto Tampilan 3D (opsional)</label>
                        <?php if ($produk['gambar_3d'] && file_exists("../uploads/" . $produk['gambar_3d'])): ?>
                        <div style="margin-bottom:10px">
                            <img src="../uploads/<?= htmlspecialchars($produk['gambar_3d']) ?>"
                                 style="width:100%;max-height:160px;object-fit:cover;border-radius:12px;border:2px solid rgba(214,185,140,0.3)">
                            <label style="display:flex;align-items:center;gap:8px;margin-top:8px;cursor:pointer;font-size:0.82rem;color:#ef4444">
                                <input type="checkbox" name="hapus_gambar_3d" value="1" style="accent-color:#ef4444">
                                Hapus foto 3D ini
                            </label>
                        </div>
                        <?php endif; ?>
                        <input type="file" name="gambar_3d" accept="image/*" class="form-control-custom"
                               data-preview="previewGambar3d" style="padding:10px">
                        <img id="previewGambar3d" src="" style="display:none;width:100%;max-height:160px;object-fit:cover;border-radius:12px;margin-top:8px">
                    </div>

                    <!-- Tombol -->
                    <div class="col-12" style="display:flex;gap:10px;margin-top:1rem">
                        <button type="submit" class="btn-primary-custom" style="font-size:0.95rem;padding:14px 28px">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <a href="produk.php" class="btn-outline-custom" style="font-size:0.95rem;padding:14px 28px">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('hargaInput')?.addEventListener('focus', function() {
    this.value = this.value.replace(/\./g, '');
});
</script>

<?php include 'admin_footer.php'; ?>
