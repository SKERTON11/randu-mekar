<?php
$page_title = 'Kelola Produk';
include 'admin_header.php';

// Flash message
$sukses = $_SESSION['sukses'] ?? '';
$error  = $_SESSION['error']  ?? '';
unset($_SESSION['sukses'], $_SESSION['error']);

// Filter & Search
$search = sanitize($_GET['q'] ?? '');
$kategori_filter = sanitize($_GET['kategori'] ?? 'all');
$per_page = 10;
$page = max(1, intval($_GET['hal'] ?? 1));
$offset = ($page - 1) * $per_page;

$where = "WHERE 1=1";
if ($search) $where .= " AND (nama_produk LIKE '%$search%')";
if ($kategori_filter !== 'all') $where .= " AND kategori = '$kategori_filter'";

$total = $conn->query("SELECT COUNT(*) as t FROM produk $where")->fetch_assoc()['t'];
$total_pages = ceil($total / $per_page);
$res = $conn->query("SELECT * FROM produk $where ORDER BY created_at DESC LIMIT $per_page OFFSET $offset");
// fetch rows into array so we can render both table (desktop) and cards (mobile)
$produk_rows = [];
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) $produk_rows[] = $r;
}
?>

<?php if ($sukses): ?>
<div class="alert-custom alert-success-custom auto-dismiss mb-3">✅ <?= $sukses ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert-custom alert-error-custom auto-dismiss mb-3">❌ <?= $error ?></div>
<?php endif; ?>

<!-- Toolbar -->
<div class="admin-card mb-4">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:center;justify-content:space-between">
        <form method="GET" style="display:flex;gap:8px;flex:1;min-width:220px">
            <div style="position:relative;flex:1">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--coklat-muda)"></i>
                <input type="text" name="q" class="form-control-custom" placeholder="Cari produk..." style="padding-left:36px" value="<?= htmlspecialchars($search) ?>">
            </div>
            <select name="kategori" class="form-control-custom" style="max-width:160px" onchange="this.form.submit()">
                <option value="all" <?= $kategori_filter === 'all' ? 'selected' : '' ?>>Semua Kategori</option>
                <option value="kasur" <?= $kategori_filter === 'kasur' ? 'selected' : '' ?>>Kasur</option>
                <option value="kasur_lantai" <?= $kategori_filter === 'kasur_lantai' ? 'selected' : '' ?>>Kasur Lantai</option>
                <option value="karpet" <?= $kategori_filter === 'karpet' ? 'selected' : '' ?>>Karpet</option>
                <option value="bantal" <?= $kategori_filter === 'bantal' ? 'selected' : '' ?>>Bantal</option>
                <option value="guling" <?= $kategori_filter === 'guling' ? 'selected' : '' ?>>Guling</option>
                <option value="sofa" <?= $kategori_filter === 'sofa' ? 'selected' : '' ?>>Sofa</option>
                <option value="rak_piring" <?= $kategori_filter === 'rak_piring' ? 'selected' : '' ?>>Rak Piring</option>
                <option value="lainnya" <?= $kategori_filter === 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
            </select>
            <button type="submit" class="btn-primary-custom" style="padding:10px 18px;font-size:0.85rem">Cari</button>
        </form>
        <a href="tambah_produk.php" class="btn-primary-custom" style="font-size:0.85rem;padding:10px 18px;white-space:nowrap">
            <i class="bi bi-plus-circle"></i> Tambah Produk
        </a>
    </div>
</div>

<!-- Tabel -->
<div class="admin-card">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.2rem">
        <h6 style="font-weight:700;color:var(--teks-gelap);margin:0">📦 Daftar Produk (<?= $total ?>)</h6>
    </div>
    <div style="overflow-x:auto">
        <table class="table-custom w-100">
            <thead>
                <tr>
                    <th style="width:40px">No</th>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Unggulan</th>
                    <th>Tanggal</th>
                    <th style="width:90px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($produk_rows)):
                    $no = $offset + 1;
                    foreach ($produk_rows as $p): ?>
                <tr>
                    <td style="color:#9CA3AF;font-size:0.82rem"><?= $no++ ?></td>
                    <td>
                        <?php if ($p['gambar'] && file_exists("../uploads/" . $p['gambar'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;border:2px solid rgba(214,185,140,0.3)">
                        <?php else: ?>
                            <div style="width:48px;height:48px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.4rem">
                                <?php $icons=['kasur'=>'🛏️','karpet'=>'🟫','bantal'=>'🟤','guling'=>'🟡','sofa'=>'🛋️','rak_piring'=>'🍽️','kasur_lantai'=>'🛌','lainnya'=>'📦']; echo $icons[$p['kategori']] ?? '📦'; ?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="font-weight:600;color:var(--teks-gelap);font-size:0.88rem"><?= htmlspecialchars(mb_substr($p['nama_produk'],0,35)) ?><?= mb_strlen($p['nama_produk']) > 35 ? '...' : '' ?></div>
                    </td>
                    <td><span style="font-size:0.75rem;background:rgba(139,107,67,0.1);color:var(--coklat-tua);padding:3px 10px;border-radius:50px;font-weight:600"><?= ucwords(str_replace('_',' ',$p['kategori'])) ?></span></td>
                    <td style="font-weight:700;color:var(--coklat-tua);font-size:0.88rem;white-space:nowrap"><?= formatRupiah($p['harga']) ?></td>
                    <td>
                        <?php if ($p['unggulan']): ?>
                            <span style="font-size:0.72rem;background:rgba(245,158,11,0.1);color:#f59e0b;padding:3px 10px;border-radius:50px;font-weight:700">⭐ Ya</span>
                        <?php else: ?>
                            <span style="font-size:0.72rem;color:#9CA3AF">—</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:0.78rem;color:#9CA3AF;white-space:nowrap"><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <div style="display:flex;gap:4px">
                            <a href="edit_produk.php?id=<?= $p['id_produk'] ?>" class="btn-icon edit" title="Edit Produk">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="hapus_produk.php?id=<?= $p['id_produk'] ?>" class="btn-icon delete" title="Hapus Produk"
                               data-delete="<?= htmlspecialchars($p['nama_produk']) ?>">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:#9CA3AF">
                        <div style="font-size:3rem">📦</div>
                        <div style="margin-top:8px">Belum ada produk<?= $search ? " untuk \"$search\"" : "" ?></div>
                        <a href="tambah_produk.php" class="btn-primary-custom mt-3" style="font-size:0.85rem;padding:10px 20px">
                            <i class="bi bi-plus-circle"></i> Tambah Produk
                        </a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile card view -->
    <div class="product-list-mobile" style="display:none;margin-top:16px">
        <?php if (!empty($produk_rows)):
            foreach ($produk_rows as $p): ?>
                <div class="admin-card" style="margin-bottom:12px;display:flex;gap:12px;align-items:flex-start">
                    <div style="width:64px;flex-shrink:0">
                        <?php if ($p['gambar'] && file_exists("../uploads/" . $p['gambar'])): ?>
                            <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>" style="width:64px;height:64px;object-fit:cover;border-radius:8px;border:2px solid rgba(214,185,140,0.3)">
                        <?php else: ?>
                            <div style="width:64px;height:64px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.6rem">
                                <?php $icons=['kasur'=>'🛏️','karpet'=>'🟫','bantal'=>'🟤','guling'=>'🟡','sofa'=>'🛋️','rak_piring'=>'🍽️','kasur_lantai'=>'🛌','lainnya'=>'📦']; echo $icons[$p['kategori']] ?? '📦'; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px">
                            <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($p['nama_produk']) ?></div>
                            <div style="display:flex;gap:6px">
                                <a href="edit_produk.php?id=<?= $p['id_produk'] ?>" class="btn-icon edit" title="Edit Produk">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="hapus_produk.php?id=<?= $p['id_produk'] ?>" class="btn-icon delete" title="Hapus Produk" data-delete="<?= htmlspecialchars($p['nama_produk']) ?>">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div style="margin-top:6px;display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                            <div style="font-size:0.85rem;color:#9CA3AF"><?= ucwords(str_replace('_',' ',$p['kategori'])) ?></div>
                            <div style="font-weight:700;color:var(--coklat-tua)"><?= formatRupiah($p['harga']) ?></div>
                        </div>
                        <div style="margin-top:8px;font-size:0.82rem;color:#9CA3AF"><?= date('d M Y', strtotime($p['created_at'])) ?></div>
                    </div>
                </div>
        <?php endforeach; else: ?>
            <div class="admin-card" style="text-align:center;color:#9CA3AF">Belum ada produk</div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="d-flex justify-content-center mt-3">
        <ul class="pagination">
            <?php if ($page > 1): ?>
            <li class="page-item"><a class="page-link" href="?hal=<?= $page-1 ?>&q=<?= urlencode($search) ?>&kategori=<?= $kategori_filter ?>"><i class="bi bi-chevron-left"></i></a></li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?hal=<?= $i ?>&q=<?= urlencode($search) ?>&kategori=<?= $kategori_filter ?>"><?= $i ?></a>
            </li>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
            <li class="page-item"><a class="page-link" href="?hal=<?= $page+1 ?>&q=<?= urlencode($search) ?>&kategori=<?= $kategori_filter ?>"><i class="bi bi-chevron-right"></i></a></li>
            <?php endif; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<?php include 'admin_footer.php'; ?>
