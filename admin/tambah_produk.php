<?php
$page_title = 'Tambah Produk';
include 'admin_header.php';

$error = '';
$sukses = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = sanitize($_POST['nama_produk'] ?? '');
    $harga   = intval(str_replace(['.', ',', 'Rp', ' '], '', $_POST['harga'] ?? 0));
    $deskripsi = sanitize($_POST['deskripsi'] ?? '');
    $kategori  = sanitize($_POST['kategori'] ?? 'lainnya');
    $stok      = intval($_POST['stok'] ?? 0);
    $unggulan  = isset($_POST['unggulan']) ? 1 : 0;

    if (!$nama || !$harga) {
        $error = 'Nama produk dan harga wajib diisi!';
    } else {
        $gambar    = '';
        $gambar_3d = '';

        // Upload gambar utama
        if (!empty($_FILES['gambar']['name'])) {
            $upload = uploadGambar($_FILES['gambar']);
            if (isset($upload['error'])) { $error = $upload['error']; }
            else { $gambar = $upload['filename']; }
        }

        // Upload gambar 3D
        if (!$error && !empty($_FILES['gambar_3d']['name'])) {
            $upload3d = uploadGambar($_FILES['gambar_3d']);
            if (isset($upload3d['error'])) { $error = $upload3d['error']; }
            else { $gambar_3d = $upload3d['filename']; }
        }

        if (!$error) {
            $stmt = $conn->prepare("INSERT INTO produk (nama_produk, harga, deskripsi, gambar, gambar_3d, kategori, stok, unggulan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssii", $nama, $harga, $deskripsi, $gambar, $gambar_3d, $kategori, $stok, $unggulan);
            if ($stmt->execute()) {
                $_SESSION['sukses'] = "Produk \"$nama\" berhasil ditambahkan!";
                redirect('admin/produk.php');
            } else {
                $error = 'Gagal menyimpan produk. Coba lagi.';
            }
            $stmt->close();
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Breadcrumb -->
        <div style="font-size:0.82rem;color:#9CA3AF;margin-bottom:1.2rem">
            <a href="dashboard.php" style="color:var(--coklat-tua);text-decoration:none">Dashboard</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;margin:0 6px"></i>
            <a href="produk.php" style="color:var(--coklat-tua);text-decoration:none">Kelola Produk</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;margin:0 6px"></i>
            Tambah Produk
        </div>

        <?php if ($error): ?>
        <div class="alert-custom alert-error-custom mb-3">❌ <?= $error ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <h5 style="font-weight:800;color:var(--teks-gelap);margin-bottom:0.3rem">➕ Tambah Produk Baru</h5>
            <p style="color:#9CA3AF;font-size:0.85rem;margin-bottom:2rem">Isi form di bawah untuk menambahkan produk ke toko.</p>

            <form method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <!-- Nama Produk -->
                    <div class="col-12">
                        <label class="form-label-custom">Nama Produk <span style="color:#ef4444">*</span></label>
                        <input type="text" name="nama_produk" class="form-control-custom"
                               placeholder="Contoh: Kasur Springbed Premium 160x200"
                               value="<?= htmlspecialchars($_POST['nama_produk'] ?? '') ?>" required>
                    </div>

                    <!-- Harga & Kategori -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Harga (Rp) <span style="color:#ef4444">*</span></label>
                        <input type="text" name="harga" id="hargaInput" class="form-control-custom"
                               placeholder="Contoh: 2500000"
                               value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Kategori <span style="color:#ef4444">*</span></label>
                        <select name="kategori" class="form-control-custom">
                            <?php
                            $kats = ['kasur'=>'🛏️ Kasur','kasur_lantai'=>'🛌 Kasur Lantai','karpet'=>'🟫 Karpet','bantal'=>'🟤 Bantal','guling'=>'🟡 Guling','sofa'=>'🛋️ Sofa','rak_piring'=>'🍽️ Rak Piring','lainnya'=>'📦 Lainnya'];
                            foreach ($kats as $val => $label):
                                $sel = (($_POST['kategori'] ?? '') === $val) ? 'selected' : '';
                            ?>
                            <option value="<?= $val ?>" <?= $sel ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Stok & Unggulan -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Stok</label>
                        <input type="number" name="stok" class="form-control-custom" min="0"
                               placeholder="0" value="<?= intval($_POST['stok'] ?? 0) ?>">
                    </div>
                    <div class="col-md-6" style="display:flex;align-items:flex-end">
                        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:12px 16px;background:var(--cream);border-radius:12px;width:100%">
                            <input type="checkbox" name="unggulan" value="1" <?= isset($_POST['unggulan']) ? 'checked' : '' ?>
                                   style="width:18px;height:18px;accent-color:var(--coklat-tua);cursor:pointer">
                            <span style="font-size:0.9rem;font-weight:600;color:var(--teks-gelap)">⭐ Tandai sebagai Produk Unggulan</span>
                        </label>
                    </div>

                    <!-- Deskripsi -->
                    <div class="col-12">
                        <label class="form-label-custom">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control-custom" rows="5"
                                  placeholder="Jelaskan detail produk: bahan, ukuran, keunggulan, dll..."><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <!-- Upload Gambar Utama -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Foto Produk (JPG/PNG, max 5MB)</label>
                        <input type="file" name="gambar" accept="image/*" class="form-control-custom"
                               data-preview="previewGambar" style="padding:10px">
                        <div style="margin-top:10px">
                            <img id="previewGambar" src="" alt="Preview" style="display:none;width:100%;max-height:180px;object-fit:cover;border-radius:12px;border:2px solid rgba(214,185,140,0.3)">
                        </div>
                    </div>

                    <!-- Upload Gambar 3D -->
                    <div class="col-md-6">
                        <label class="form-label-custom">Foto Tampilan 3D (opsional)</label>
                        <input type="file" name="gambar_3d" accept="image/*" class="form-control-custom"
                               data-preview="previewGambar3d" style="padding:10px">
                        <div style="margin-top:10px">
                            <img id="previewGambar3d" src="" alt="Preview 3D" style="display:none;width:100%;max-height:180px;object-fit:cover;border-radius:12px;border:2px solid rgba(214,185,140,0.3)">
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="col-12" style="display:flex;gap:10px;margin-top:1rem">
                        <button type="submit" class="btn-primary-custom" style="font-size:0.95rem;padding:14px 28px">
                            <i class="bi bi-check-circle"></i> Simpan Produk
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
// Format harga otomatis
document.getElementById('hargaInput')?.addEventListener('input', function() {
    let val = this.value.replace(/\D/g, '');
    this.value = val ? parseInt(val).toLocaleString('id-ID') : '';
});
document.getElementById('hargaInput')?.addEventListener('blur', function() {
    let val = this.value.replace(/\./g, '');
    this.value = val;
});
</script>

<?php include 'admin_footer.php'; ?>
