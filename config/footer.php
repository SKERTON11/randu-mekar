<?php
// =====================================================
// Footer Component - Toko Randu Mekar
// =====================================================
$base_url = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
?>

<!-- FOOTER -->
<footer>
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand">🏠 Toko Randu Mekar</div>
                <p class="footer-tagline">
                    Menyediakan kasur, karpet, bantal, guling, sofa, dan berbagai produk meubel berkualitas sejak 2015. 
                    Harga langsung dari produsen, gratis ongkir area Sukoharjo.
                </p>
                <div class="social-links">
                    <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" class="social-link" target="_blank" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="#" class="social-link" target="_blank" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="social-link" target="_blank" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="social-link" target="_blank" title="TikTok">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>
            
            <!-- Navigasi -->
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Navigasi</div>
                <ul class="footer-links">
                    <li><a href="<?= $base_url ?>index.php"><i class="bi bi-chevron-right"></i> Beranda</a></li>
                    <li><a href="<?= $base_url ?>produk.php"><i class="bi bi-chevron-right"></i> Produk</a></li>
                    <li><a href="<?= $base_url ?>tentang.php"><i class="bi bi-chevron-right"></i> Tentang Kami</a></li>
                    <li><a href="<?= $base_url ?>kontak.php"><i class="bi bi-chevron-right"></i> Kontak</a></li>
                    <li><a href="<?= $base_url ?>login.php"><i class="bi bi-chevron-right"></i> Admin</a></li>
                </ul>
            </div>
            
            <!-- Produk -->
            <div class="col-lg-2 col-md-6 col-6">
                <div class="footer-title">Produk</div>
                <ul class="footer-links">
                    <li><a href="<?= $base_url ?>produk.php?kategori=kasur"><i class="bi bi-chevron-right"></i> Kasur</a></li>
                    <li><a href="<?= $base_url ?>produk.php?kategori=karpet"><i class="bi bi-chevron-right"></i> Karpet</a></li>
                    <li><a href="<?= $base_url ?>produk.php?kategori=bantal"><i class="bi bi-chevron-right"></i> Bantal</a></li>
                    <li><a href="<?= $base_url ?>produk.php?kategori=guling"><i class="bi bi-chevron-right"></i> Guling</a></li>
                    <li><a href="<?= $base_url ?>produk.php?kategori=sofa"><i class="bi bi-chevron-right"></i> Sofa</a></li>
                </ul>
            </div>
            
            <!-- Kontak -->
            <div class="col-lg-4 col-md-6">
                <div class="footer-title">Hubungi Kami</div>
                <ul class="footer-links">
                    <li>
                        <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>" target="_blank">
                            <i class="bi bi-whatsapp" style="color:#25D366"></i>
                            +62 812-XXXX-XXXX
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-geo-alt" style="color:var(--coklat-muda)"></i>
                            <?= TOKO_ALAMAT ?>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-clock" style="color:var(--coklat-muda)"></i>
                            Senin – Sabtu, 08.00 – 17.00 WIB
                        </a>
                    </li>
                </ul>
                <a href="https://wa.me/<?= WHATSAPP_NUMBER ?>?text=Halo%20Toko%20Randu%20Mekar" 
                   class="btn-wa mt-3 d-inline-flex" target="_blank" style="font-size:0.85rem;padding:10px 22px">
                    <i class="bi bi-whatsapp"></i> Chat Sekarang
                </a>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom mt-4">
        <div class="container">
            <p>© <?= date('Y') ?> <strong>Toko Randu Mekar</strong>. Semua hak dilindungi. 
               Dibuat dengan ❤️ untuk pelanggan setia kami.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JS -->
<script src="<?= $base_url ?>assets/js/main.js"></script>

</body>
</html>
