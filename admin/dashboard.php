<?php
$page_title = 'Dashboard';
include 'admin_header.php';

// Statistik
$total_produk   = $conn->query("SELECT COUNT(*) as t FROM produk")->fetch_assoc()['t'];
$total_pesan    = $conn->query("SELECT COUNT(*) as t FROM kontak")->fetch_assoc()['t'];
$pesan_baru     = $conn->query("SELECT COUNT(*) as t FROM kontak WHERE status='baru'")->fetch_assoc()['t'];
$total_unggulan = $conn->query("SELECT COUNT(*) as t FROM produk WHERE unggulan=1")->fetch_assoc()['t'];

// Produk terbaru
$produk_terbaru = $conn->query("SELECT * FROM produk ORDER BY created_at DESC LIMIT 5");

// Kategori distribusi
$kategori_stat = $conn->query("SELECT kategori, COUNT(*) as jumlah FROM produk GROUP BY kategori ORDER BY jumlah DESC");

// Pesan terbaru
$pesan_terbaru = $conn->query("SELECT * FROM kontak ORDER BY created_at DESC LIMIT 5");
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon brown"><i class="bi bi-box-seam"></i></div>
            <div>
                <div class="stat-card-num"><?= $total_produk ?></div>
                <div class="stat-card-label">Total Produk</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon green"><i class="bi bi-envelope"></i></div>
            <div>
                <div class="stat-card-num"><?= $total_pesan ?></div>
                <div class="stat-card-label">Total Pesan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon orange"><i class="bi bi-bell"></i></div>
            <div>
                <div class="stat-card-num"><?= $pesan_baru ?></div>
                <div class="stat-card-label">Pesan Baru</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-card-icon blue"><i class="bi bi-star"></i></div>
            <div>
                <div class="stat-card-num"><?= $total_unggulan ?></div>
                <div class="stat-card-label">Produk Unggulan</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Produk Terbaru -->
    <div class="col-lg-7">
        <div class="admin-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
                <h6 style="font-weight:700;color:var(--teks-gelap);margin:0">📦 Produk Terbaru</h6>
                <a href="produk.php" style="font-size:0.8rem;color:var(--coklat-tua);text-decoration:none">Lihat semua →</a>
            </div>
            <div style="overflow-x:auto">
                <table class="table-custom w-100">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($produk_terbaru && $produk_terbaru->num_rows > 0): ?>
                            <?php while ($p = $produk_terbaru->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600;font-size:0.88rem;color:var(--teks-gelap)"><?= htmlspecialchars(mb_substr($p['nama_produk'], 0, 28)) ?>...</div>
                                    <?php if ($p['unggulan']): ?>
                                    <span style="font-size:0.7rem;background:rgba(245,158,11,0.1);color:#f59e0b;padding:2px 8px;border-radius:50px;font-weight:600">⭐ Unggulan</span>
                                    <?php endif; ?>
                                </td>
                                <td><span style="font-size:0.78rem;color:var(--coklat-muda);font-weight:600"><?= strtoupper(str_replace('_',' ',$p['kategori'])) ?></span></td>
                                <td style="font-weight:700;color:var(--coklat-tua);font-size:0.88rem"><?= formatRupiah($p['harga']) ?></td>
                                <td>
                                    <a href="edit_produk.php?id=<?= $p['id_produk'] ?>" class="btn-icon edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <a href="hapus_produk.php?id=<?= $p['id_produk'] ?>" class="btn-icon delete ms-1" title="Hapus"
                                       data-delete="<?= htmlspecialchars($p['nama_produk']) ?>"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" style="text-align:center;color:#9CA3AF;padding:2rem">Belum ada produk</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Stats -->
    <div class="col-lg-5">
        <!-- Distribusi Kategori -->
        <div class="admin-card mb-4">
            <h6 style="font-weight:700;color:var(--teks-gelap);margin-bottom:1.2rem">📊 Distribusi Kategori</h6>
            <?php
            $icons_k = ['kasur'=>'🛏️','karpet'=>'🟫','bantal'=>'🟤','guling'=>'🟡','sofa'=>'🛋️','rak_piring'=>'🍽️','kasur_lantai'=>'🛌','lainnya'=>'📦'];
            if ($kategori_stat && $kategori_stat->num_rows > 0):
                while ($ks = $kategori_stat->fetch_assoc()):
                    $pct = $total_produk > 0 ? round(($ks['jumlah'] / $total_produk) * 100) : 0;
            ?>
            <div style="margin-bottom:12px">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                    <span style="font-size:0.82rem;font-weight:600;color:var(--teks-gelap)">
                        <?= $icons_k[$ks['kategori']] ?? '📦' ?> <?= ucwords(str_replace('_',' ',$ks['kategori'])) ?>
                    </span>
                    <span style="font-size:0.78rem;color:#9CA3AF"><?= $ks['jumlah'] ?> produk</span>
                </div>
                <div style="background:#F0EBE3;border-radius:50px;height:8px;overflow:hidden">
                    <div style="width:<?= $pct ?>%;height:100%;background:linear-gradient(90deg,var(--coklat-tua),var(--coklat-muda));border-radius:50px;transition:width 0.6s ease"></div>
                </div>
            </div>
            <?php endwhile; endif; ?>
        </div>
        
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mt-2">
    <div class="col-12">
        <div class="admin-card">
            <h6 style="font-weight:700;color:var(--teks-gelap);margin-bottom:1rem">⚡ Aksi Cepat</h6>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
                <a href="tambah_produk.php" class="btn-primary-custom" style="font-size:0.85rem;padding:10px 20px">
                    <i class="bi bi-plus-circle"></i> Tambah Produk
                </a>
                <a href="pesan.php" class="btn-outline-custom" style="font-size:0.85rem;padding:10px 20px">
                    <i class="bi bi-envelope"></i> Baca Pesan
                </a>
                <a href="../index.php" class="btn-outline-custom" target="_blank" style="font-size:0.85rem;padding:10px 20px">
                    <i class="bi bi-globe"></i> Lihat Website
                </a>
                <a href="../produk.php" class="btn-outline-custom" target="_blank" style="font-size:0.85rem;padding:10px 20px">
                    <i class="bi bi-box-seam"></i> Halaman Produk
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Ambil pesan terbaru untuk bagian bawah
$pesan_bawah = $conn->query("SELECT id, nama, email, pesan, status, created_at FROM kontak ORDER BY created_at DESC LIMIT 8");
?>
<div class="row g-4 mt-3">
    <div class="col-12">
        <div class="admin-card">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem">
                <h6 style="font-weight:700;color:var(--teks-gelap);margin:0">✉️ Pesan Terbaru</h6>
                <a href="pesan.php" style="font-size:0.8rem;color:var(--coklat-tua);text-decoration:none">Lihat semua →</a>
            </div>
            <?php if ($pesan_bawah && $pesan_bawah->num_rows > 0): ?>
                <?php while ($pb = $pesan_bawah->fetch_assoc()): ?>
                <div style="padding:10px 0;border-bottom:1px solid rgba(214,185,140,0.12)">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap">
                        <div style="flex:1">
                            <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">
                                <?= htmlspecialchars($pb['nama']) ?>
                                <span style="font-size:0.78rem;color:#9CA3AF">· <?= date('d M Y, H:i', strtotime($pb['created_at'])) ?></span>
                                <?php if ($pb['status'] === 'baru'): ?>
                                <span style="font-size:0.65rem;background:rgba(239,68,68,0.1);color:#ef4444;padding:2px 8px;border-radius:50px;font-weight:700;margin-left:8px">BARU</span>
                                <?php endif; ?>
                            </div>
                            <div style="font-size:0.9rem;color:#9CA3AF;margin-top:6px;line-height:1.6">
                                <?= htmlspecialchars(mb_substr($pb['pesan'],0,200)) ?><?= strlen($pb['pesan'])>200 ? '...' : '' ?>
                            </div>
                        </div>
                        <div style="flex-shrink:0;display:flex;gap:8px;align-items:center">
                            <a href="pesan.php?baca=<?= $pb['id'] ?>" class="btn-outline-custom" style="padding:8px 12px;font-size:0.85rem">Baca</a>
                            <a href="pesan.php?hapus=<?= $pb['id'] ?>" class="btn-icon delete" onclick="return confirm('Hapus pesan ini?')" title="Hapus"><i class="bi bi-trash"></i></a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="text-align:center;color:#9CA3AF;padding:1.2rem">Belum ada pesan</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>
