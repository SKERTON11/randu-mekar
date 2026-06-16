<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once 'config/koneksi.php';
$page_title = 'Kontak';

// Proses form kontak
$pesan_sukses = '';
$pesan_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = sanitize($_POST['nama'] ?? '');
    $whatsapp = sanitize($_POST['whatsapp'] ?? '');
    $whatsapp = preg_replace('/[^0-9+]/', '', $whatsapp);
    $pesan = sanitize($_POST['pesan'] ?? '');
    if (!$nama || !$whatsapp || !$pesan) {
        $pesan_error = 'Semua field harus diisi!';
    } elseif (!preg_match('/^\+?[0-9]{7,20}$/', $whatsapp)) {
        $pesan_error = 'Nomor WhatsApp tidak valid. Masukkan angka lengkap dengan kode negara.';
    } else {
        // Email field removed; store empty string for compatibility
        $sql = "INSERT INTO kontak (nama, email, whatsapp, pesan) VALUES ('$nama', '', '$whatsapp', '$pesan')";
        if ($conn->query($sql)) {
            $pesan_sukses = 'Pesan Anda telah terkirim! Kami akan menghubungi Anda segera.';
        } else {
            $pesan_error = 'Gagal mengirim pesan. Coba lagi.';
        }
    }
}

include 'config/header.php';
?>

<!-- Page Header -->
<div class="page-header">
    <div class="container text-center">
        <span class="section-badge">📞 HUBUNGI KAMI</span>
        <h1 class="page-title mt-2">Kami Siap <span>Membantu Anda</span></h1>
        <p style="color:#7A6050;margin-top:0.5rem">Konsultasi gratis untuk produk yang tepat sesuai kebutuhan Anda</p>
    </div>
</div>

<section class="section-padding" style="padding-top:60px">
    <div class="container">

        <!-- Kartu Kontak -->
        <div class="row g-4 mb-5">
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="contact-card d-block text-decoration-none" target="_blank">
                    <div class="contact-icon" style="background:linear-gradient(135deg,rgba(37,211,102,0.1),rgba(37,211,102,0.2))">
                        <i class="bi bi-whatsapp" style="color:#25D366;font-size:1.5rem"></i>
                    </div>
                    <div style="font-weight:700;color:var(--teks-gelap);margin-bottom:4px">WhatsApp</div>
                    <div style="font-size:0.85rem;color:#7A6050"><?= WHATSAPP_DISPLAY ?></div>
                    <div style="font-size:0.78rem;color:var(--coklat-muda);margin-top:6px">Klik untuk chat langsung →</div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                <a href="https://www.instagram.com/muhammaddp_11?igsh=OXAyano1NHJnaWt3" class="contact-card d-block text-decoration-none" target="_blank">
                    <div class="contact-icon" style="background:linear-gradient(135deg,rgba(225,48,108,0.1),rgba(225,48,108,0.2))">
                        <i class="bi bi-instagram" style="color:#E1306C;font-size:1.5rem"></i>
                    </div>
                    <div style="font-weight:700;color:var(--teks-gelap);margin-bottom:4px">Instagram</div>
                    <div style="font-size:0.85rem;color:#7A6050"><?= INSTAGRAM_HANDLE ?></div>
                    <div style="font-size:0.78rem;color:var(--coklat-muda);margin-top:6px">Follow kami →</div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                <a href="https://www.facebook.com/share/1M3wfjiN9G/" class="contact-card d-block text-decoration-none" target="_blank">
                    <div class="contact-icon" style="background:linear-gradient(135deg,rgba(24,119,242,0.1),rgba(24,119,242,0.2))">
                        <i class="bi bi-facebook" style="color:#1877F2;font-size:1.5rem"></i>
                    </div>
                    <div style="font-weight:700;color:var(--teks-gelap);margin-bottom:4px">Facebook</div>
                    <div style="font-size:0.85rem;color:#7A6050"><?= FACEBOOK_HANDLE ?></div>
                    <div style="font-size:0.78rem;color:var(--coklat-muda);margin-top:6px">Like halaman kami →</div>
                </a>
            </div>
            <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                <a href="https://tiktok.com/@opetalamak" class="contact-card d-block text-decoration-none" target="_blank">
                    <div class="contact-icon" style="background:linear-gradient(135deg,rgba(0,0,0,0.08),rgba(0,0,0,0.12))">
                        <i class="bi bi-tiktok" style="color:#000;font-size:1.5rem"></i>
                    </div>
                    <div style="font-weight:700;color:var(--teks-gelap);margin-bottom:4px">TikTok</div>
                    <div style="font-size:0.85rem;color:#7A6050"><?= TIKTOK_HANDLE ?></div>
                    <div style="font-size:0.78rem;color:var(--coklat-muda);margin-top:6px">Ikuti kami →</div>
                </a>
            </div>
        </div>

        <!-- Form + Maps -->
        <div class="row g-5">
            <!-- Form Kontak -->
            <div class="col-lg-5" data-aos="fade-right">
                <div style="background:var(--putih);border-radius:24px;padding:2.5rem;box-shadow:0 4px 20px var(--shadow);border:1px solid rgba(214,185,140,0.2)">
                    <h4 style="font-weight:800;color:var(--teks-gelap);margin-bottom:0.5rem">Kirim Pesan</h4>
                    <p style="color:#7A6050;font-size:0.88rem;margin-bottom:2rem">Tim kami akan membalas dalam 1x24 jam</p>

                    <?php if ($pesan_sukses): ?>
                        <div class="alert-custom alert-success-custom auto-dismiss" style="margin-bottom:1.5rem">
                            ✅ <?= $pesan_sukses ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($pesan_error): ?>
                        <div class="alert-custom alert-error-custom auto-dismiss" style="margin-bottom:1.5rem">
                            ❌ <?= $pesan_error ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label-custom">Nama Lengkap *</label>
                            <input type="text" name="nama" class="form-control-custom"
                                placeholder="Masukkan nama Anda" required
                                value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>">
                        </div>
                        <!-- Email removed: contact via WhatsApp only -->
                        <div class="mb-3">
                            <label class="form-label-custom">WhatsApp *</label>
                            <input type="text" name="whatsapp" class="form-control-custom"
                                placeholder="6281234567890" required
                                value="<?= htmlspecialchars($_POST['whatsapp'] ?? '') ?>">
                            <div style="font-size:0.78rem;color:#7A6050;margin-top:6px">Tuliskan nomor WhatsApp lengkap menggunakan awalan 62. CNTH:6287752287105.</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-custom">Pesan *</label>
                            <textarea name="pesan" class="form-control-custom" rows="5"
                                placeholder="Tuliskan pesan atau pertanyaan Anda..." required><?= htmlspecialchars($_POST['pesan'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;font-size:0.95rem;padding:14px">
                            <i class="bi bi-send"></i> Kirim Pesan
                        </button>
                    </form>

                    <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(214,185,140,0.2)">
                        <p style="font-size:0.82rem;color:#7A6050">Atau chat langsung via</p>
                        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="btn-wa" target="_blank" style="font-size:0.88rem;padding:10px 24px">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </div>

            <!-- Info & Maps -->
            <div class="col-lg-7" data-aos="fade-left">
                <!-- Info Alamat -->
                <div style="background:var(--cream);border-radius:20px;padding:2rem;margin-bottom:1.5rem">
                    <h5 style="font-weight:700;color:var(--teks-gelap);margin-bottom:1.5rem">📍 Kunjungi Toko Kami</h5>
                    <div style="display:grid;gap:1rem">
                        <div style="display:flex;gap:12px;align-items:flex-start">
                            <div style="width:38px;height:38px;background:var(--putih);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px var(--shadow)">
                                📍
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Alamat</div>
                                <div style="font-size:0.85rem;color:#7A6050"><?= TOKO_ALAMAT ?></div>
                            </div>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start">
                            <div style="width:38px;height:38px;background:var(--putih);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px var(--shadow)">
                                🕐
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Jam Operasional</div>
                                <div style="font-size:0.85rem;color:#7A6050">Senin – Sabtu: 08.00 – 20.00 WIB<br>Minggu: 09.00 – 20.00 WIB</div>
                            </div>
                        </div>
                        <div style="display:flex;gap:12px;align-items:flex-start">
                            <div style="width:38px;height:38px;background:var(--putih);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px var(--shadow)">
                                📞
                            </div>
                            <div>
                                <div style="font-weight:600;color:var(--teks-gelap);font-size:0.9rem">Telepon / WA</div>
                                <div style="font-size:0.85rem;color:#7A6050"><?= WHATSAPP_DISPLAY ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Embed -->
                <div style="border-radius:20px;overflow:hidden;box-shadow:0 4px 20px var(--shadow)">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.4089448520976!2d110.75928016345198!3d-7.756325699999997!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a3900144a5c03%3A0xbc5558b699dfc170!2sJl.%20Randu%20Mekar%20No%2015%2C%20Sukoharjo!5e0!3m2!1sid!2sid!4v1718371200000"
                        width="100%"
                        height="350"
                        style="border:0;display:block"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <p style="font-size:0.78rem;color:#7A6050;text-align:center;margin-top:8px">
                </p>
            </div>
        </div>
    </div>
</section>

<?php include 'config/footer.php'; ?>