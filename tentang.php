<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/koneksi.php';
$page_title = 'Tentang Kami';
include 'config/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <span class="section-badge">🏠 TENTANG KAMI</span>
        <h1 class="page-title mt-2">Cerita <span>Randu Mekar</span></h1>
        <p style="color:#7A6050;margin-top:0.5rem">Perjalanan kami membangun kepercayaan sejak 2015</p>
    </div>
</div>

<!-- Tentang Section -->
<section class="section-padding" style="padding-top:60px">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <span class="section-badge">📖 SEJARAH KAMI</span>
                <h2 class="section-title mt-2">Berawal dari <span>Impian Sederhana</span></h2>
                <div class="divider-line" style="margin-left:0"></div>
                <p style="color:#7A6050;line-height:1.8;margin-bottom:1rem">
                    Toko Randu Mekar didirikan pada tahun 2015 oleh keluarga yang memiliki passion besar terhadap
                    kenyamanan hunian. Berawal dari toko kecil di Sukoharjo, kami terus berkembang menjadi
                    toko meubel terpercaya yang melayani ratusan pelanggan.
                </p>
                <p style="color:#7A6050;line-height:1.8;margin-bottom:1.5rem">
                    Dengan pengalaman lebih dari 11 tahun, kami memahami kebutuhan setiap pelanggan dan berkomitmen
                    untuk memberikan produk terbaik dan berkualitas dengan harga yang terjangkau. Kepercayaan pelanggan adalah
                    modal utama kami untuk terus berkembang.
                </p>

                <div class="row g-3">
                    <div class="col-6">
                        <div style="background:var(--cream);border-radius:16px;padding:1.5rem;text-align:center">
                            <div style="font-size:2rem;font-weight:800;color:var(--coklat-tua)"><?= date('Y') - 2015 ?>+</div>
                            <div style="font-size:0.82rem;color:#7A6050;font-weight:500">Tahun Berpengalaman</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:var(--cream);border-radius:16px;padding:1.5rem;text-align:center">
                            <div style="font-size:2rem;font-weight:800;color:var(--coklat-tua)">500+</div>
                            <div style="font-size:0.82rem;color:#7A6050;font-weight:500">Pelanggan Puas</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6" data-aos="fade-left">
                <!-- Static Illustration -->
                <div style="background:var(--cream);border-radius:24px;padding:2rem;box-shadow:0 10px 40px var(--shadow)">
                    <img src="assets/images/Gambar utama.png" alt="Ilustrasi Randu Mekar" style="width:100%;border-radius:16px;display:block;" />
                    <div style="text-align:center;margin-top:1rem;font-size:0.85rem;color:var(--coklat-muda);font-weight:600">
                        🏠 Toko Randu Mekar — Sukoharjo, Jawa Tengah
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visi Misi -->
<section class="section-padding" style="background:var(--abu-muda);padding-top:60px">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="section-badge">🎯 VISI & MISI</span>
            <h2 class="section-title mt-2">Tujuan & <span>Komitmen</span> Kami</h2>
            <div class="divider-line"></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-card text-center">
                    <div class="feature-icon" style="font-size:2rem">👁️</div>
                    <div class="feature-title" style="font-size:1.2rem">Visi Kami</div>
                    <p class="feature-desc" style="font-size:0.9rem">
                        Menjadi toko meubel terpercaya dan terdepan di Sukoharjo yang memberikan solusi
                        terbaik untuk kenyamanan hunian masyarakat dengan produk berkualitas dan layanan prima.
                    </p>
                </div>
            </div>

            <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-card">
                    <div class="feature-title" style="font-size:1.2rem;text-align:center;margin-bottom:1.5rem">🎯 Misi Kami</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div style="display:flex;gap:12px;align-items:flex-start">
                                <div style="width:32px;height:32px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.9rem">✅</div>
                                <div>
                                    <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Produk Berkualitas</div>
                                    <div style="font-size:0.82rem;color:#7A6050;margin-top:2px">Menyediakan produk meubel pilihan dengan standar kualitas terbaik</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="display:flex;gap:12px;align-items:flex-start">
                                <div style="width:32px;height:32px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.9rem">💰</div>
                                <div>
                                    <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Harga Terjangkau</div>
                                    <div style="font-size:0.82rem;color:#7A6050;margin-top:2px">Memberikan harga terbaik langsung dari produsen tanpa biaya tambahan</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="display:flex;gap:12px;align-items:flex-start">
                                <div style="width:32px;height:32px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.9rem">💬</div>
                                <div>
                                    <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Pelayanan Terbaik</div>
                                    <div style="font-size:0.82rem;color:#7A6050;margin-top:2px">Memberikan pelayanan ramah, responsif, dan profesional kepada setiap pelanggan</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="display:flex;gap:12px;align-items:flex-start">
                                <div style="width:32px;height:32px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:0.9rem">🚚</div>
                                <div>
                                    <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Pengiriman Tepat Waktu</div>
                                    <div style="font-size:0.82rem;color:#7A6050;margin-top:2px">Mengantarkan produk ke rumah pelanggan dengan aman dan tepat waktu</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Timeline -->
<section class="section-padding" style="padding-top:60px">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5" data-aos="fade-up">
                    <span class="section-badge">📅 PERJALANAN KAMI</span>
                    <h2 class="section-title mt-2">Timeline <span>Randu Mekar</span></h2>
                    <div class="divider-line"></div>
                </div>

                <div class="timeline" data-aos="fade-up">
                    <div class="timeline-item">
                        <div class="timeline-year">2015</div>
                        <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">🏪 Pendirian Toko</div>
                        <p class="timeline-text">Toko Randu Mekar resmi dibuka di Sukoharjo. Memulai dengan produk kasur, guling, bantal dan perabotan lain dengan pilihan berkualitas.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2017</div>
                        <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">📈 Ekspansi Produk</div>
                        <p class="timeline-text">Menambah lini produk sofa, karpet, dan guling. Pelanggan mulai meluas ke area Solo Raya.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2018</div>
                        <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">🌟 Menjadi Toko terpercaya di Kecamatan Weru</div>
                        <p class="timeline-text">Menjadi toko terpercaya dikawasan kecamatan weru dan memiliki berbagai mitra dan memulai layanan antar gratis untuk area Sukoharjo.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2021</div>
                        <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">📱 Go Digital</div>
                        <p class="timeline-text">Membuka layanan pemesanan via WhatsApp dan media sosial. Menjadikan Jangkauan toko semakin luas.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year">2025</div>
                        <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">🚀 Website Resmi</div>
                        <p class="timeline-text">Meluncurkan website resmi untuk memudahkan pelanggan melihat produk secara online.</p>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-year"><?= date('Y') ?></div>
                        <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem">💪 Terus Berkembang</div>
                        <p class="timeline-text">500+ pelanggan puas dan terus berinovasi untuk memberikan pengalaman belanja meubel terbaik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container text-center">
        <h2 class="cta-title">Tertarik Berbelanja di Randu Mekar?</h2>
        <p class="cta-desc">Kunjungi toko kami atau hubungi via WhatsApp untuk konsultasi produk gratis!</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="btn-wa" target="_blank">
                <i class="bi bi-whatsapp"></i> Chat WhatsApp
            </a>
            <a href="produk.php" class="btn-outline-custom" style="border-color:var(--cream);color:var(--cream)">
                <i class="bi bi-grid"></i> Lihat Produk
            </a>
        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>