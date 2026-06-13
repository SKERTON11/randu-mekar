<?php
// =====================================================
// Header Component - Toko Randu Mekar
// =====================================================
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$base_url = (strpos($_SERVER['PHP_SELF'], '/admin/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Toko Randu Mekar - Menyediakan Kasur, Karpet, Bantal, Guling dan Produk Meubel Berkualitas">
    <meta name="keywords" content="kasur, karpet, bantal, guling, sofa, meubel, Sukoharjo">
    <title><?= isset($page_title) ? "$page_title | " : "" ?>Toko Randu Mekar</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $base_url ?>assets/images/favicon.ico">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $base_url ?>assets/css/style.css">
</head>

<body>

    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loader-content">
            <div class="loader-logo">🏠 Randu Mekar</div>
            <div class="loader-spinner"></div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="<?= $base_url ?>index.php">
                <div class="brand-icon">🏠</div>
                <div class="brand-text">
                    <span class="brand-name">Randu Mekar</span>
                    <span class="brand-sub">Meubel & Furniture</span>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <i class="bi bi-list" style="font-size:1.4rem;color:var(--coklat-tua)"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'index' ? 'active' : '' ?>" href="<?= $base_url ?>index.php">
                            <i class="bi bi-house me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'produk' ? 'active' : '' ?>" href="<?= $base_url ?>produk.php">
                            <i class="bi bi-grid me-1"></i>Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'tentang' ? 'active' : '' ?>" href="<?= $base_url ?>tentang.php">
                            <i class="bi bi-info-circle me-1"></i>Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $current_page === 'kontak' ? 'active' : '' ?>" href="<?= $base_url ?>kontak.php">
                            <i class="bi bi-envelope me-1"></i>Kontak
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn-primary-custom" href="<?= $base_url ?>admin/dashboard.php" style="font-size:0.82rem;padding:10px 20px;">
                            <i class="bi bi-speedometer2 me-1"></i>Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>