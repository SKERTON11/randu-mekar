<?php
// =====================================================
// Admin Header - Toko Randu Mekar
// FIX: session hanya distart jika belum ada
// =====================================================
if (session_status() === PHP_SESSION_NONE) session_start();
// Aktifkan output buffering agar pemanggilan header() aman meski ada output
ob_start();
require_once __DIR__ . '/../config/koneksi.php';
cekLogin();

$admin_nama     = $_SESSION['admin_nama']     ?? $_SESSION['admin_username'] ?? 'Admin';
$admin_username = $_SESSION['admin_username'] ?? 'admin';
$current_admin = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? "$page_title | " : "" ?>Admin Randu Mekar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Mobile sidebar fix */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 200;
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0 !important;
            }

            #sidebarToggle {
                display: flex !important;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: 199;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        #sidebarToggle {
            display: none;
        }
    </style>
</head>

<body>

    <!-- Overlay untuk mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-logo">
            <a href="dashboard.php" style="display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit">
                <div style="width:40px;height:40px;flex-shrink:0;display:flex;align-items:center;justify-content:center">
                    <img src="../assets/images/logo polosan.png" alt="Randu Mekar Logo" style="width:40px;height:40px;object-fit:contain">
                </div>
                <div>
                    <div class="admin-logo-text">Randu Mekar</div>
                    <div class="admin-logo-sub">Panel Admin</div>
                </div>
            </a>
        </div>

        <nav class="admin-nav">
            <div style="padding:8px 24px;font-size:0.7rem;font-weight:700;color:rgba(245,239,230,0.35);letter-spacing:1px;margin-top:8px">MENU UTAMA</div>

            <div class="admin-nav-item">
                <a href="dashboard.php" class="admin-nav-link <?= $current_admin === 'dashboard' ? 'active' : '' ?>">
                    <i class="bi bi-speedometer2 admin-nav-icon"></i> Dashboard
                </a>
            </div>
            <div class="admin-nav-item">
                <a href="produk.php" class="admin-nav-link <?= $current_admin === 'produk' ? 'active' : '' ?>">
                    <i class="bi bi-box-seam admin-nav-icon"></i> Kelola Produk
                </a>
            </div>
            <div class="admin-nav-item">
                <a href="tambah_produk.php" class="admin-nav-link <?= $current_admin === 'tambah_produk' ? 'active' : '' ?>">
                    <i class="bi bi-plus-circle admin-nav-icon"></i> Tambah Produk
                </a>
            </div>
            <div class="admin-nav-item">
                <a href="pesan.php" class="admin-nav-link <?= $current_admin === 'pesan' ? 'active' : '' ?>">
                    <i class="bi bi-envelope admin-nav-icon"></i> Pesan Masuk
                    <?php
                    $baru = $conn->query("SELECT COUNT(*) as total FROM kontak WHERE status = 'baru'")->fetch_assoc()['total'];
                    if ($baru > 0): ?>
                        <span style="background:#ef4444;color:#fff;font-size:0.65rem;font-weight:700;padding:2px 7px;border-radius:50px;margin-left:auto"><?= $baru ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <div class="admin-nav-item">
                <a href="settings.php" class="admin-nav-link <?= $current_admin === 'settings' ? 'active' : '' ?>">
                    <i class="bi bi-gear admin-nav-icon"></i> Pengaturan
                </a>
            </div>

            <div style="padding:8px 24px;font-size:0.7rem;font-weight:700;color:rgba(245,239,230,0.35);letter-spacing:1px;margin-top:16px">AKUN</div>

            <div class="admin-nav-item">
                <a href="../index.php" class="admin-nav-link" target="_blank">
                    <i class="bi bi-globe admin-nav-icon"></i> Lihat Website
                </a>
            </div>
            <div class="admin-nav-item">
                <a href="../logout.php" class="admin-nav-link" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-right admin-nav-icon"></i> Logout
                </a>
            </div>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="admin-content">
        <!-- Topbar -->
        <div class="admin-topbar">
            <div style="display:flex;align-items:center;gap:12px">
                <button id="sidebarToggle" onclick="toggleSidebar()"
                    style="background:none;border:none;font-size:1.4rem;color:var(--coklat-tua);cursor:pointer;padding:0">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div style="font-weight:700;color:var(--teks-gelap);font-size:0.95rem"><?= $page_title ?? 'Dashboard' ?></div>
                    <div style="font-size:0.75rem;color:#9CA3AF"><?= date('l, d F Y') ?></div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <div style="text-align:right">
                    <div style="font-weight:600;color:var(--teks-gelap);font-size:0.88rem"><?= htmlspecialchars($admin_nama) ?></div>
                    <div style="font-size:0.72rem;color:#9CA3AF"><?= htmlspecialchars($admin_username) ?></div>
                </div>
                <div style="width:38px;height:38px;background:var(--coklat-tua);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--cream);font-size:1rem">
                    👤
                </div>
            </div>
        </div>

        <!-- Main -->
        <div class="admin-main">