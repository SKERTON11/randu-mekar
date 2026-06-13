<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/koneksi.php';

$id = intval($_GET['id'] ?? 0);
if (!$id) { redirect('produk.php'); }

$produk = $conn->query("SELECT * FROM produk WHERE id_produk = $id")->fetch_assoc();
if (!$produk) { redirect('produk.php'); }

$page_title = htmlspecialchars($produk['nama_produk']);

$kategori_icons = ['kasur'=>'🛏️','karpet'=>'🟫','bantal'=>'🟤','guling'=>'🟡','sofa'=>'🛋️','rak_piring'=>'🍽️','kasur_lantai'=>'🛌','lainnya'=>'📦'];

// Produk terkait
$kategori = $produk['kategori'];
$produk_terkait = $conn->query("SELECT * FROM produk WHERE kategori = '$kategori' AND id_produk != $id LIMIT 3");

$pesan_wa = urlencode("Halo Toko Randu Mekar, saya tertarik dengan produk {$produk['nama_produk']}. Apakah masih tersedia?");

include 'config/header.php';
?>

<!-- Page Header Minimal -->
<div style="height:80px;background:var(--cream)"></div>

<!-- Breadcrumb -->
<div style="background:var(--cream);padding:12px 0;border-bottom:1px solid rgba(214,185,140,0.3)">
    <div class="container">
        <nav style="font-size:0.85rem;color:#7A6050">
            <a href="index.php" style="color:var(--coklat-tua);text-decoration:none">Beranda</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;margin:0 6px"></i>
            <a href="produk.php" style="color:var(--coklat-tua);text-decoration:none">Produk</a>
            <i class="bi bi-chevron-right" style="font-size:0.7rem;margin:0 6px"></i>
            <span><?= htmlspecialchars($produk['nama_produk']) ?></span>
        </nav>
    </div>
</div>

<!-- Detail Produk -->
<section class="section-padding" style="padding-top:40px">
    <div class="container">
        <div class="row g-5">
            <!-- Gambar Produk -->
            <div class="col-lg-6">
                <div style="position:sticky;top:100px">
                    <?php if ($produk['gambar'] && file_exists("uploads/" . $produk['gambar'])): ?>
                        <img src="uploads/<?= htmlspecialchars($produk['gambar']) ?>" 
                             alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                             class="detail-img-main" id="mainImg">
                    <?php else: ?>
                        <div style="height:420px;background:linear-gradient(135deg,var(--cream),#E8D5B7);border-radius:20px;display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:6rem;box-shadow:0 10px 40px var(--shadow)">
                            <?= $kategori_icons[$produk['kategori']] ?? '📦' ?>
                            <div style="font-size:1rem;color:var(--coklat-tua);margin-top:16px;font-weight:600">
                                <?= ucwords(str_replace('_', ' ', $produk['kategori'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Gambar 3D jika ada -->
                    <?php if ($produk['gambar_3d'] && file_exists("uploads/" . $produk['gambar_3d'])): ?>
                    <div class="mt-3">
                        <p style="font-size:0.82rem;color:var(--coklat-muda);font-weight:600;letter-spacing:0.5px">TAMPILAN 3D</p>
                        <img src="uploads/<?= htmlspecialchars($produk['gambar_3d']) ?>" 
                             alt="3D <?= htmlspecialchars($produk['nama_produk']) ?>"
                             style="width:100%;border-radius:12px;box-shadow:0 4px 20px var(--shadow);cursor:pointer"
                             onclick="document.getElementById('mainImg').src=this.src">
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Info Produk -->
            <div class="col-lg-6">
                <span class="detail-badge-kategori">
                    <?= $kategori_icons[$produk['kategori']] ?? '📦' ?> <?= strtoupper(str_replace('_', ' ', $produk['kategori'])) ?>
                </span>
                
                <h1 class="detail-title"><?= htmlspecialchars($produk['nama_produk']) ?></h1>
                
                <!-- Rating display -->
                <div style="color:#F59E0B;font-size:1rem;margin-bottom:1rem">
                    ⭐⭐⭐⭐⭐ <span style="color:#7A6050;font-size:0.85rem">(4.9/5 dari pelanggan kami)</span>
                </div>
                
                <div class="detail-price"><?= formatRupiah($produk['harga']) ?></div>
                
                <div style="background:rgba(37,211,102,0.08);border:1px solid rgba(37,211,102,0.2);border-radius:10px;padding:10px 16px;font-size:0.85rem;color:#1a7a3a;margin:1rem 0">
                    ✅ Tersedia &nbsp;|&nbsp; 🚚 Gratis ongkir area Sukoharjo &nbsp;|&nbsp; 💰 Bisa dicicil
                </div>
                
                <div style="margin:1.5rem 0">
                    <h6 style="color:var(--teks-gelap);font-weight:700;margin-bottom:10px">Deskripsi Produk</h6>
                    <p style="color:#7A6050;line-height:1.7;font-size:0.95rem"><?= nl2br(htmlspecialchars($produk['deskripsi'])) ?></p>
                </div>
                
                <div style="display:flex;flex-direction:column;gap:10px;margin-top:2rem">
                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=<?= $pesan_wa ?>" 
                       class="btn-wa" target="_blank" style="justify-content:center;font-size:1rem;padding:16px">
                        <i class="bi bi-whatsapp"></i> Pesan via WhatsApp
                    </a>
                    <a href="produk.php" class="btn-outline-custom" style="justify-content:center">
                        <i class="bi bi-arrow-left"></i> Kembali ke Produk
                    </a>
                </div>
                
                <!-- Info Toko -->
                <div style="background:var(--cream);border-radius:16px;padding:1.5rem;margin-top:2rem">
                    <div style="font-size:0.88rem;font-weight:700;color:var(--teks-gelap);margin-bottom:12px">
                        🏠 Tentang Toko Randu Mekar
                    </div>
                    <div style="font-size:0.82rem;color:#7A6050;line-height:1.6">
                        📍 <?= TOKO_ALAMAT ?><br>
                        🕐 Buka Senin–Sabtu, 08.00–17.00 WIB<br>
                        ✅ Garansi kepuasan pelanggan<br>
                        💳 Pembayaran fleksibel (tunai / transfer / cicilan)
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Produk Terkait -->
        <?php if ($produk_terkait && $produk_terkait->num_rows > 0): ?>
        <div class="mt-5 pt-4" style="border-top:1px solid rgba(214,185,140,0.3)">
            <h4 style="font-size:1.3rem;font-weight:800;color:var(--teks-gelap);margin-bottom:1.5rem">
                Produk Terkait <span style="color:var(--coklat-tua)"><?= ucwords(str_replace('_', ' ', $kategori)) ?></span>
            </h4>
            <div class="row g-4">
                <?php while ($terkait = $produk_terkait->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-card">
                        <div class="product-img-wrapper">
                            <?php if ($terkait['gambar'] && file_exists("uploads/" . $terkait['gambar'])): ?>
                                <img src="uploads/<?= htmlspecialchars($terkait['gambar']) ?>" alt="<?= htmlspecialchars($terkait['nama_produk']) ?>">
                            <?php else: ?>
                                <div class="product-placeholder">
                                    <?= $kategori_icons[$terkait['kategori']] ?? '📦' ?>
                                    <span><?= ucwords(str_replace('_', ' ', $terkait['kategori'])) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="product-body">
                            <div class="product-name"><?= htmlspecialchars($terkait['nama_produk']) ?></div>
                            <div class="product-price"><?= formatRupiah($terkait['harga']) ?></div>
                            <a href="detail_produk.php?id=<?= $terkait['id_produk'] ?>" class="btn-detail">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'config/footer.php'; ?>
