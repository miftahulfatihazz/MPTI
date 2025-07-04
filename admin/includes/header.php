<?php
// File: admin/includes/header.php
session_start();

// Redirect ke login jika session tidak ada
if (!isset($_SESSION['admin_id'])) {
    // Kita perlu tahu path relatif dari file yang memanggil header ini
    // Untuk simpelnya, kita asumsikan path root admin adalah satu level di atas
    header("Location: /MPTI/admin/login.php"); 
    exit();
}

// Menentukan halaman aktif untuk memberikan style pada menu
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Sinar Bahari</title>
    <link rel="icon" type="image/png" href="/path/to/favicon.png">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40; /* Dark background */
            padding: 1rem;
            flex-shrink: 0;
        }
        .sidebar a {
            color: #adb5bd; /* Light grey text */
            text-decoration: none;
            display: block;
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.5rem;
        }
        .sidebar a:hover {
            background-color: #495057; /* Slightly lighter on hover */
            color: #fff;
        }
        .sidebar a.active {
            background-color: #0d6efd; /* Bootstrap primary blue for active */
            color: #fff;
        }
        .sidebar .logout {
            position: absolute;
            bottom: 1rem;
            width: calc(250px - 2rem);
        }
        .main-content {
            flex-grow: 1;
            padding: 2rem;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4 class="text-white text-center mb-4">Admin Sinar Bahari</h4>
        
        <!-- Navigasi Utama -->
        <a href="/MPTI/admin/dashboard.php" class="<?php if($current_page == 'dashboard.php') echo 'active'; ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="/MPTI/admin/orders/index.php" class="<?php if($current_dir == 'orders') echo 'active'; ?>">
            <i class="bi bi-box-seam"></i> Manajemen Pesanan
        </a>
        <a href="/MPTI/admin/products/index.php" class="<?php if($current_dir == 'products') echo 'active'; ?>">
            <i class="bi bi-egg-fried"></i> Manajemen Produk
        </a>
        <a href="/MPTI/admin/beranda/index.php" class="<?php if($current_dir == 'beranda') echo 'active'; ?>">
            <i class="bi bi-house-door"></i> Manajemen Beranda
        </a>
        <a href="/MPTI/admin/testimonials/index.php" class="<?php if($current_dir == 'testimonials') echo 'active'; ?>">
            <i class="bi bi-chat-left-text"></i> Manajemen Testimoni
        </a>
        <a href="/MPTI/admin/partners/index.php" class="<?php if($current_dir == 'partners') echo 'active'; ?>">
            <i class="bi bi-people"></i> Manajemen Mitra
        </a>
        <hr class="text-white-50">
        
        <!-- Navigasi Pengaturan -->
        <a href="/MPTI/admin/admins/index.php" class="<?php if($current_dir == 'admins') echo 'active'; ?>">
            <i class="bi bi-person-gear"></i> Manajemen Admin
        </a>
         <a href="/MPTI/admin/statistics.php" class="<?php if($current_page == 'statistics.php') echo 'active'; ?>">
            <i class="bi bi-bar-chart-line"></i> Statistik
        </a>

        <!-- TAMBAHKAN BARIS DI BAWAH INI -->
        <a href="/MPTI/admin/settings.php" class="<?php if($current_page == 'settings.php') echo 'active'; ?>">
            <i class="bi bi-gear-fill"></i> Pengaturan Website
        </a>

        <!-- Tombol Logout -->
        <a href="/MPTI/admin/logout.php" class="logout bg-danger text-white">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <div class="main-content">