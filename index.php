<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/koneksi.php';
$page_title = 'Beranda';
include 'config/header.php';

// Ambil produk unggulan
$sql_unggulan = "SELECT * FROM produk WHERE unggulan = 1 LIMIT 3";
$produk_unggulan = $conn->query($sql_unggulan);

// Ambil total produk untuk statistik
$total_produk = $conn->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
?>

<!-- ===== HERO SECTION ===== -->
<section class="hero-section" id="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-badge">✨ Meubel Berkualitas Sejak 2015</span>
                <h1 class="hero-title">
                    Kenyamanan<br><span>Rumah Dimulai</span><br>di Sini
                </h1>
                <p class="hero-desc">
                    Toko Randu Mekar menyediakan kasur, karpet, bantal, guling, sofa dan berbagai produk
                    meubel berkualitas dengan harga langsung dari produsen. Gratis ongkir area Sukoharjo!
                </p>
                <div class="hero-buttons">
                    <a href="produk.php" class="btn-primary-custom">
                        <i class="bi bi-grid-fill"></i> Lihat Produk
                    </a>
                    <a href="kontak.php" class="btn-outline-custom">
                        <i class="bi bi-chat-dots"></i> Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-illustration">
                    <div class="hero-canvas-container">
                        <img src="assets/images/Gambar utama.png" alt="Ilustrasi Randu Mekar" style="width:100%;border-radius:20px;display:block;" />
                        <div style="text-align:center;margin-top:12px;">
                            <span style="font-size:0.82rem;color:var(--coklat-muda);font-weight:500">
                                🛏️ Kasur Premium &nbsp;|&nbsp; 🛋️ Sofa Modern &nbsp;|&nbsp; 🏠 Meubel Lengkap
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATISTIK ===== -->
<section class="stats-section">
    <div class="container">
        <div class="row g-0">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number" data-counter="9"><?= date('Y') - 2015 ?></span>
                    <div class="stat-label">Tahun Berpengalaman</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number" data-counter="<?= $total_produk ?>"><?= $total_produk ?>+</span>
                    <div class="stat-label">Produk Tersedia</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number" data-counter="500">500+</span>
                    <div class="stat-label">Pelanggan Puas</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <span class="stat-number">4.9</span>
                    <div class="stat-label">⭐ Rating Toko</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PRODUK UNGGULAN ===== -->
<section class="section-padding" id="produk-unggulan" style="background:var(--abu-muda)">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">🌟 PILIHAN TERBAIK</span>
            <h2 class="section-title">Produk <span>Unggulan</span> Kami</h2>
            <div class="divider-line"></div>
            <p class="section-desc">Koleksi produk terlaris yang dipilih khusus untuk kenyamanan dan kualitas terbaik di harga yang terjangkau.</p>
        </div>

        <div class="row g-4">
            <?php if ($produk_unggulan && $produk_unggulan->num_rows > 0): ?>
                <?php $loop_delay = 0;
                while ($p = $produk_unggulan->fetch_assoc()): ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php $loop_delay += 100;
                                                                                        echo $loop_delay; ?>">
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <?php if ($p['gambar'] && file_exists("uploads/" . $p['gambar'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
                                <?php else: ?>
                                    <div class="product-placeholder">
                                        <?php
                                        $icons = ['kasur' => '🛏️', 'karpet' => '🟫', 'bantal' => '🟤', 'guling' => '🟡', 'sofa' => '🛋️', 'rak_piring' => '🍽️', 'kasur_lantai' => '🛌'];
                                        echo $icons[$p['kategori']] ?? '📦';
                                        ?>
                                        <span><?= ucwords(str_replace('_', ' ', $p['kategori'])) ?></span>
                                    </div>
                                <?php endif; ?>
                                <span class="badge-unggulan">⭐ Unggulan</span>
                            </div>
                            <div class="product-body">
                                <div class="product-kategori"><?= strtoupper(str_replace('_', ' ', $p['kategori'])) ?></div>
                                <div class="product-name"><?= htmlspecialchars($p['nama_produk']) ?></div>
                                <div class="product-desc"><?= htmlspecialchars($p['deskripsi']) ?></div>
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
                    <div style="font-size:4rem">🛏️</div>
                    <p style="color:var(--coklat-muda);font-size:1.1rem;margin-top:1rem">Produk unggulan belum tersedia.</p>
                    <a href="produk.php" class="btn-primary-custom mt-2">Lihat Semua Produk</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <a href="produk.php" class="btn-outline-custom">
                <i class="bi bi-grid me-1"></i> Lihat Semua Produk
            </a>
        </div>
    </div>
</section>

<!-- ===== KEUNGGULAN ===== -->
<section class="section-padding" id="keunggulan">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">💎 KEUNGGULAN KAMI</span>
            <h2 class="section-title">Mengapa Memilih <span>Randu Mekar?</span></h2>
            <div class="divider-line"></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <div class="feature-title">Gratis Ongkir</div>
                    <p class="feature-desc">Nikmati layanan gratis ongkos kirim untuk seluruh wilayah Sukoharjo dan sekitarnya. Pesan sekarang, kami antar ke rumah Anda!</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <div class="feature-title">9 Tahun Berpengalaman</div>
                    <p class="feature-desc">Berdiri sejak 2015, kami telah melayani ratusan pelanggan dengan produk meubel berkualitas. Pengalaman kami adalah jaminan kepercayaan Anda.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <div class="feature-title">Harga Produsen</div>
                    <p class="feature-desc">Dapatkan harga terbaik langsung dari produsen tanpa perantara. Kualitas premium dengan harga yang lebih hemat untuk budget Anda.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card">
                    <div class="feature-icon">✅</div>
                    <div class="feature-title">Produk Berkualitas</div>
                    <p class="feature-desc">Setiap produk kami telah melalui seleksi ketat untuk memastikan kualitas terbaik. Garansi kepuasan pelanggan adalah prioritas kami.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-icon">💬</div>
                    <div class="feature-title">Layanan Responsif</div>
                    <p class="feature-desc">Tim kami siap membantu Anda melalui WhatsApp. Konsultasi gratis untuk memilih produk yang tepat sesuai kebutuhan dan budget Anda.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-card">
                    <div class="feature-icon">🔄</div>
                    <div class="feature-title">Bisa Dicicil</div>
                    <p class="feature-desc">Tidak perlu khawatir dengan budget. Kami menyediakan opsi pembayaran cicilan yang fleksibel untuk memudahkan Anda berbelanja.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="cta-section">
    <div class="container text-center position-relative">
        <div data-aos="fade-up">
            <span style="background:rgba(245,239,230,0.15);color:var(--cream);padding:5px 16px;border-radius:50px;font-size:0.8rem;font-weight:600;letter-spacing:1px;border:1px solid rgba(245,239,230,0.2)">
                🛒 BELANJA SEKARANG
            </span>
            <h2 class="cta-title mt-3">Tertarik Mencoba Produk Kami?</h2>
            <p class="cta-desc">Hubungi kami sekarang untuk konsultasi gratis dan dapatkan penawaran terbaik. Tim kami siap membantu Anda menemukan produk yang sempurna!</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Halo%20Toko%20Randu%20Mekar%2C%20saya%20tertarik%20dengan%20produk%20Anda"
                    class="btn-wa" target="_blank">
                    <i class="bi bi-whatsapp"></i> Chat via WhatsApp
                </a>
                <a href="produk.php" class="btn-outline-custom" style="border-color:var(--cream);color:var(--cream)">
                    <i class="bi bi-grid"></i> Lihat Produk
                </a>
            </div>
        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>