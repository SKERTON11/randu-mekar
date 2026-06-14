// =====================================================
// Toko Randu Mekar - Main JavaScript
// =====================================================

(function () {
    const loadingScreen = document.getElementById('loading-screen');
    if (loadingScreen) {
        let loaderHidden = false;
        const hideLoadingScreen = () => {
            if (loaderHidden) return;
            loaderHidden = true;
            loadingScreen.classList.add('hidden');
            setTimeout(() => { loadingScreen.remove(); }, 600);
        };

        window.addEventListener('load', () => {
            setTimeout(hideLoadingScreen, 400);
        }, { once: true });

        setTimeout(hideLoadingScreen, 1200);
    }
})();

document.addEventListener('DOMContentLoaded', function () {

    // ===== NAVBAR SCROLL EFFECT =====
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        });
    }

    // ===== SMOOTH SCROLL =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ===== COUNTER ANIMATION =====
    function animateCounter(el, target, duration = 2000) {
        let start = 0;
        const step = Math.ceil(target / (duration / 16));
        const timer = setInterval(() => {
            start += step;
            if (start >= target) {
                start = target;
                clearInterval(timer);
            }
            el.textContent = start.toLocaleString('id-ID');
        }, 16);
    }

    const counters = document.querySelectorAll('[data-counter]');
    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.counted) {
                    entry.target.dataset.counted = true;
                    animateCounter(entry.target, parseInt(entry.target.dataset.counter));
                }
            });
        }, { threshold: 0.5 });
        counters.forEach(c => observer.observe(c));
    }

    // ===== AOS ANIMATION (Manual - no library) =====
    const aosElements = document.querySelectorAll('[data-aos]');
    const aosObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const delay = entry.target.dataset.aosDelay || 0;
                setTimeout(() => {
                    entry.target.classList.add('aos-animate');
                    entry.target.style.opacity = 1;
                }, parseInt(delay));
                aosObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    aosElements.forEach(el => {
        el.style.opacity = 0;
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        aosObserver.observe(el);
    });

    // ===== PRODUCT FILTER =====
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const kategori = btn.dataset.filter;
            productCards.forEach(card => {
                if (kategori === 'all' || card.dataset.kategori === kategori) {
                    card.style.display = 'block';
                    card.style.animation = 'fadeInUp 0.4s ease';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // ===== SEARCH PRODUK =====
    const searchInput = document.getElementById('searchProduk');
    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            productCards.forEach(card => {
                const name = card.querySelector('.product-name')?.textContent.toLowerCase() || '';
                card.style.display = name.includes(query) ? 'block' : 'none';
            });
        });
    }

    // ===== AUTO DISMISS ALERT =====
    document.querySelectorAll('.auto-dismiss').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });

    // ===== ADMIN SIDEBAR TOGGLE (Mobile) =====
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.querySelector('.admin-sidebar');
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', () => {
            adminSidebar.classList.toggle('show');
        });
    }

    // ===== IMAGE PREVIEW UPLOAD =====
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.dataset.preview;
            const preview = document.getElementById(previewId);
            if (preview && this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // ===== DELETE CONFIRMATION =====
    document.querySelectorAll('[data-delete]').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const nama = this.dataset.delete;
            const url = this.href;
            if (confirm(`Yakin ingin menghapus "${nama}"? Tindakan ini tidak dapat dibatalkan.`)) {
                window.location.href = url;
            }
        });
    });

    // ===== CANVAS HERO ILLUSTRATION =====
    const canvas = document.getElementById('heroCanvas');
    if (canvas) {
        drawRoomIllustration(canvas);
    }
});

// ===== CANVAS ROOM ILLUSTRATION =====
function drawRoomIllustration(canvas) {
    canvas.width = 420;
    canvas.height = 320;
    const ctx = canvas.getContext('2d');
    const c = {
        cream: '#F5EFE6',
        tan: '#D6B98C',
        brown: '#8B6B43',
        dark: '#5C4027',
        wall: '#EDE0CC',
        floor: '#C9A87C',
        white: '#FFFFFF',
        shadow: 'rgba(92,64,39,0.15)'
    };

    // Background wall
    ctx.fillStyle = c.wall;
    ctx.fillRect(0, 0, 420, 320);

    // Floor
    ctx.fillStyle = c.floor;
    ctx.fillRect(0, 210, 420, 110);

    // Floor pattern
    ctx.strokeStyle = 'rgba(139,107,67,0.15)';
    ctx.lineWidth = 1;
    for (let x = 0; x < 420; x += 40) {
        ctx.beginPath();
        ctx.moveTo(x, 210);
        ctx.lineTo(x, 320);
        ctx.stroke();
    }

    // Wall decoration line
    ctx.fillStyle = c.tan;
    ctx.fillRect(0, 205, 420, 8);

    // === BED ===
    // Headboard
    ctx.fillStyle = c.dark;
    ctx.beginPath();
    roundRect(ctx, 50, 130, 200, 90, 10);
    ctx.fill();

    // Bed base
    ctx.fillStyle = c.brown;
    ctx.beginPath();
    roundRect(ctx, 45, 190, 210, 30, 6);
    ctx.fill();

    // Mattress
    ctx.fillStyle = c.cream;
    ctx.beginPath();
    roundRect(ctx, 55, 160, 190, 50, 8);
    ctx.fill();

    // Mattress stripe
    ctx.strokeStyle = 'rgba(214,185,140,0.4)';
    ctx.lineWidth = 2;
    for (let i = 65; i < 240; i += 15) {
        ctx.beginPath();
        ctx.moveTo(i, 162);
        ctx.lineTo(i, 208);
        ctx.stroke();
    }

    // Pillow 1
    ctx.fillStyle = c.white;
    ctx.shadowColor = c.shadow;
    ctx.shadowBlur = 8;
    ctx.beginPath();
    roundRect(ctx, 65, 163, 60, 40, 8);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Pillow 2
    ctx.fillStyle = c.white;
    ctx.shadowColor = c.shadow;
    ctx.shadowBlur = 8;
    ctx.beginPath();
    roundRect(ctx, 140, 163, 60, 40, 8);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Blanket
    const grad = ctx.createLinearGradient(55, 175, 55, 210);
    grad.addColorStop(0, c.tan);
    grad.addColorStop(1, c.brown);
    ctx.fillStyle = grad;
    ctx.beginPath();
    roundRect(ctx, 55, 178, 190, 30, [0, 0, 8, 8]);
    ctx.fill();

    // Bed legs
    ctx.fillStyle = c.dark;
    ctx.fillRect(55, 218, 12, 12);
    ctx.fillRect(233, 218, 12, 12);

    // === NIGHTSTAND ===
    ctx.fillStyle = c.brown;
    ctx.shadowColor = c.shadow;
    ctx.shadowBlur = 10;
    roundRect(ctx, 270, 180, 70, 50, 8);
    ctx.fill();
    ctx.shadowBlur = 0;

    // Drawer
    ctx.fillStyle = c.dark;
    ctx.fillRect(278, 192, 54, 20);
    ctx.fillStyle = c.tan;
    ctx.beginPath();
    ctx.arc(305, 202, 4, 0, Math.PI * 2);
    ctx.fill();

    // Table top
    ctx.fillStyle = c.dark;
    ctx.beginPath();
    roundRect(ctx, 263, 175, 84, 10, 4);
    ctx.fill();

    // === LAMP ===
    // Lamp base
    ctx.fillStyle = c.tan;
    ctx.beginPath();
    ctx.ellipse(305, 173, 12, 4, 0, 0, Math.PI * 2);
    ctx.fill();

    // Lamp pole
    ctx.strokeStyle = c.tan;
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.moveTo(305, 173);
    ctx.lineTo(305, 145);
    ctx.stroke();

    // Lamp shade
    ctx.fillStyle = c.cream;
    ctx.beginPath();
    ctx.moveTo(286, 148);
    ctx.lineTo(324, 148);
    ctx.lineTo(318, 135);
    ctx.lineTo(292, 135);
    ctx.closePath();
    ctx.fill();
    ctx.strokeStyle = c.tan;
    ctx.lineWidth = 1.5;
    ctx.stroke();

    // Lamp glow
    const glow = ctx.createRadialGradient(305, 148, 0, 305, 148, 45);
    glow.addColorStop(0, 'rgba(245,239,230,0.5)');
    glow.addColorStop(1, 'rgba(245,239,230,0)');
    ctx.fillStyle = glow;
    ctx.beginPath();
    ctx.arc(305, 148, 45, 0, Math.PI * 2);
    ctx.fill();

    // === WINDOW ===
    ctx.fillStyle = 'rgba(180,210,240,0.4)';
    ctx.beginPath();
    roundRect(ctx, 330, 50, 70, 90, 6);
    ctx.fill();

    ctx.strokeStyle = c.dark;
    ctx.lineWidth = 3;
    ctx.beginPath();
    roundRect(ctx, 330, 50, 70, 90, 6);
    ctx.stroke();

    // Window cross
    ctx.beginPath();
    ctx.moveTo(365, 52); ctx.lineTo(365, 138);
    ctx.moveTo(332, 95); ctx.lineTo(398, 95);
    ctx.stroke();

    // Window curtains
    ctx.fillStyle = c.cream;
    ctx.beginPath();
    ctx.moveTo(328, 44); ctx.lineTo(345, 44); ctx.lineTo(338, 142); ctx.lineTo(328, 142);
    ctx.fill();
    ctx.beginPath();
    ctx.moveTo(402, 44); ctx.lineTo(385, 44); ctx.lineTo(392, 142); ctx.lineTo(402, 142);
    ctx.fill();

    // Curtain rod
    ctx.strokeStyle = c.dark;
    ctx.lineWidth = 4;
    ctx.beginPath();
    ctx.moveTo(322, 44); ctx.lineTo(408, 44);
    ctx.stroke();

    // === RUG ===
    const rugGrad = ctx.createRadialGradient(150, 280, 0, 150, 280, 120);
    rugGrad.addColorStop(0, 'rgba(214,185,140,0.5)');
    rugGrad.addColorStop(0.7, 'rgba(214,185,140,0.2)');
    rugGrad.addColorStop(1, 'rgba(214,185,140,0)');
    ctx.fillStyle = rugGrad;
    ctx.beginPath();
    ctx.ellipse(150, 285, 120, 30, 0, 0, Math.PI * 2);
    ctx.fill();

    // === DECORATIVE PLANTS (potted) ===
    // Pot
    ctx.fillStyle = c.tan;
    ctx.beginPath();
    ctx.moveTo(20, 230); ctx.lineTo(40, 210); ctx.lineTo(40, 230); ctx.closePath();
    ctx.fill();
    ctx.beginPath();
    roundRect(ctx, 10, 228, 30, 18, 3);
    ctx.fill();
    // Leaves
    ctx.fillStyle = '#6B8C5A';
    for (let i = 0; i < 5; i++) {
        ctx.save();
        ctx.translate(25, 228);
        ctx.rotate((-0.6 + i * 0.3) * Math.PI);
        ctx.beginPath();
        ctx.ellipse(0, -14, 5, 14, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }

    // Brand watermark
    ctx.fillStyle = 'rgba(139,107,67,0.3)';
    ctx.font = 'bold 11px Poppins, sans-serif';
    ctx.textAlign = 'right';
    ctx.fillText('🏠 Toko Randu Mekar', 415, 315);
}

function roundRect(ctx, x, y, w, h, r) {
    if (typeof r === 'number') r = [r, r, r, r];
    ctx.beginPath();
    ctx.moveTo(x + r[0], y);
    ctx.lineTo(x + w - r[1], y);
    ctx.quadraticCurveTo(x + w, y, x + w, y + r[1]);
    ctx.lineTo(x + w, y + h - r[2]);
    ctx.quadraticCurveTo(x + w, y + h, x + w - r[2], y + h);
    ctx.lineTo(x + r[3], y + h);
    ctx.quadraticCurveTo(x, y + h, x, y + h - r[3]);
    ctx.lineTo(x, y + r[0]);
    ctx.quadraticCurveTo(x, y, x + r[0], y);
    ctx.closePath();
}
