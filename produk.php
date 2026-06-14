<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/koneksi.php';
$page_title = 'Produk';

// Filter kategori
$kategori_filter = isset($_GET['kategori']) ? sanitize($_GET['kategori']) : 'all';
$search = isset($_GET['q']) ? sanitize($_GET['q']) : '';

// Pagination
$per_page = 9;
$page = max(1, intval($_GET['hal'] ?? 1));
$offset = ($page - 1) * $per_page;

// Build query
$where = "WHERE 1=1";
if ($kategori_filter && $kategori_filter !== 'all') {
    $where .= " AND kategori = '$kategori_filter'";
}
if ($search) {
    $where .= " AND (nama_produk LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}

$total = $conn->query("SELECT COUNT(*) as total FROM produk $where")->fetch_assoc()['total'];
$total_pages = ceil($total / $per_page);

$produk = $conn->query("SELECT * FROM produk $where ORDER BY unggulan DESC, created_at DESC LIMIT $per_page OFFSET $offset");

$kategori_list = [
    'all' => ['label' => 'Semua', 'image' => 'Gambar utama.png', 'thumb' => 'filter/all.png'],
    'kasur' => ['label' => 'Kasur', 'image' => 'Kasur.png', 'thumb' => 'filter/kasur.png'],
    'kasur_lantai' => ['label' => 'Kasur Lantai', 'image' => 'kasur lantai.png', 'thumb' => 'filter/kasur-lantai.png'],
    'karpet' => ['label' => 'Karpet', 'image' => 'karpet.png', 'thumb' => 'filter/karpet.png'],
    'bantal' => ['label' => 'Bantal', 'image' => 'Bantal.png', 'thumb' => 'filter/bantal.png'],
    'guling' => ['label' => 'Guling', 'image' => 'Guling.png', 'thumb' => 'filter/guling.png'],
    'sofa' => ['label' => 'Sofa', 'image' => 'sofa.png', 'thumb' => 'filter/sofa.png'],
    'rak_piring' => ['label' => 'Rak Piring', 'image' => 'rak piring.png', 'thumb' => 'filter/rak-piring.png'],
    'lainnya' => ['label' => 'Lainnya', 'image' => 'logo polosan.png', 'thumb' => 'filter/lainnya.png'],
];

include 'config/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <span class="section-badge"><i class="bi bi-bag-check me-1"></i> PRODUK KAMI</span>
        <h1 class="page-title mt-2">Semua <span>Produk</span> Kami</h1>
        <p style="color:#7A6050;margin-top:0.5rem">Temukan kasur, karpet, bantal, guling, sofa dan semua yang anda butuhkan</p>

        <!-- Search Bar -->
        <form method="GET" class="mt-4">
            <div class="search-box">
                <i class="bi bi-search" style="color:var(--coklat-muda);font-size:1.1rem;margin-right:8px"></i>
                <input type="text" name="q" id="searchProduk" placeholder="Cari produk..."
                    value="<?= htmlspecialchars($search) ?>">
                <?php if ($kategori_filter !== 'all'): ?>
                    <input type="hidden" name="kategori" value="<?= $kategori_filter ?>">
                <?php endif; ?>
                <button type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Produk Section -->
<section class="section-padding" style="padding-top:50px">
    <div class="container">

        <!-- Filter Kategori -->
        <div class="d-flex gap-2 flex-wrap justify-content-center mb-4">
            <?php foreach ($kategori_list as $key => $kat): ?>
                <a href="produk.php?kategori=<?= $key ?><?= $search ? '&q=' . $search : '' ?>"
                    class="filter-btn <?= $kategori_filter === $key ? 'active' : '' ?>">
                    <span class="filter-thumb">
                        <img src="assets/images/<?= htmlspecialchars($kat['thumb']) ?>" alt="">
                    </span>
                    <span><?= $kat['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Info hasil -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p style="color:#7A6050;font-size:0.9rem;margin:0">
                Menampilkan <strong><?= $total ?></strong> produk
                <?= $search ? "untuk \"<strong>$search</strong>\"" : "" ?>
                <?= $kategori_filter !== 'all' ? "kategori <strong>{$kategori_list[$kategori_filter]['label']}</strong>" : "" ?>
            </p>
            <?php if ($search || $kategori_filter !== 'all'): ?>
                <a href="produk.php" style="font-size:0.85rem;color:var(--coklat-tua);text-decoration:none">
                    <i class="bi bi-x-circle"></i> Reset Filter
                </a>
            <?php endif; ?>
        </div>

        <!-- Grid Produk -->
        <div class="row g-4">
            <?php if ($produk && $produk->num_rows > 0): ?>
                <?php while ($p = $produk->fetch_assoc()): ?>
                    <div class="col-lg-4 col-md-6 product-item" data-kategori="<?= $p['kategori'] ?>">
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <?php if ($p['gambar'] && file_exists("uploads/" . $p['gambar'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>"
                                        alt="<?= htmlspecialchars($p['nama_produk']) ?>"
                                        loading="lazy">
                                <?php else: ?>
                                    <div class="product-placeholder">
                                        <?php
                                        $placeholder = $kategori_list[$p['kategori']]['image'] ?? $kategori_list['all']['image'];
                                        ?>
                                        <img src="assets/images/<?= rawurlencode($placeholder) ?>" alt="">
                                        <span><?= ucwords(str_replace('_', ' ', $p['kategori'])) ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if ($p['unggulan']): ?>
                                    <span class="badge-unggulan"><i class="bi bi-star-fill me-1"></i>Unggulan</span>
                                <?php endif; ?>
                            </div>
                            <div class="product-body">
                                <div class="product-kategori"><?= strtoupper(str_replace('_', ' ', $p['kategori'])) ?></div>
                                <div class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
                                <div class="product-desc">
                                    <?= htmlspecialchars(mb_substr($p['deskripsi'], 0, 100)) ?>...
                                </div>
                                <div class="product-price"><?= formatRupiah($p['harga']) ?></div>
                                <a href="detail_produk.php?id=<?= $p['id_produk'] ?>" class="btn-detail">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div style="font-size:5rem;color:var(--coklat-muda)">
                        <i class="bi bi-search"></i>
                    </div>
                    <h3 style="color:var(--coklat-tua);margin-top:1rem">Produk tidak ditemukan</h3>
                    <p style="color:#7A6050">Coba kata kunci lain atau lihat semua produk kami.</p>
                    <a href="produk.php" class="btn-primary-custom mt-2">Lihat Semua Produk</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <nav class="mt-5 d-flex justify-content-center">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?hal=<?= $page - 1 ?>&kategori=<?= $kategori_filter ?>&q=<?= urlencode($search) ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?hal=<?= $i ?>&kategori=<?= $kategori_filter ?>&q=<?= urlencode($search) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?hal=<?= $page + 1 ?>&kategori=<?= $kategori_filter ?>&q=<?= urlencode($search) ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</section>

<?php include 'config/footer.php'; ?>