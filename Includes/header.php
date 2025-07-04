<?php
// File: includes/header.php (Final & Lengkap dengan Perbaikan Logo)
require 'config/database.php';

// Ambil nomor whatsapp dari DB untuk ditampilkan di header
$whatsapp_result = $conn->query("SELECT content FROM site_content WHERE section_key = 'company_whatsapp'");
// Ambil nomornya, jika tidak ada, gunakan nomor placeholder
$whatsapp_number = $whatsapp_result->fetch_assoc()['content'] ?? '6281234567890';

// Ambil halaman saat ini untuk menandai menu aktif
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakso Premium Sinar Bahari</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts (Opsional, untuk tipografi yang lebih baik) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ===================================================================
           1. General & Typography
        =================================================================== */
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* ===================================================================
           2. Navigation Bar (Navbar)
        =================================================================== */
        .navbar {
            padding-top: 1rem;
            padding-bottom: 1rem;
            transition: all 0.3s;
        }

        .navbar-brand {
            font-weight: 700;
            color: #151414;
        }

        .navbar-brand .logo-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background-color: #fd7e14;
            color: white;
            border-radius: 50%;
            margin-right: 0.5rem;
            font-weight: 700;
            font-size: 1rem;
        }

        .nav-link {
            font-weight: 500;
            position: relative;
            margin: 0 0.5rem;
            color: #555;
        }

        .nav-link.active {
            color: #fd7e14 !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 5px;
            right: 5px;
            height: 2px;
            background-color: #fd7e14;
        }

        /* ===================================================================
           3. Buttons
        =================================================================== */
        .btn-cta {
            background-color: #fd7e14;
            color: white;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            border: 2px solid #fd7e14;
        }

        .btn-cta:hover {
            background-color: #e86a04;
            color: white;
            border-color: #e86a04;
        }

        .btn-admin {
            border: 1px solid #dee2e6;
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            color: #555;
        }

        .btn-admin:hover {
            background-color: #f8f9fa;
        }

        .btn-outline-primary {
            --bs-btn-color: #fd7e14;
            --bs-btn-border-color: #fd7e14;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #fd7e14;
            --bs-btn-hover-border-color: #fd7e14;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #e46d0a;
            --bs-btn-active-border-color: #d16409;
        }

        .btn-icon-swap .icon-hover {
            display: none;
        }

        .btn-icon-swap:hover .icon-default {
            display: none;
        }

        .btn-icon-swap:hover .icon-hover {
            display: inline-block;
        }

        /* ===================================================================
           4. Page Headers (Tentang Kami, Mitra, dll)
        =================================================================== */
        .about-header,
        .testimonial-header,
        .page-header-section {
            background-color: #FFFBF5; /* Warna krem/kuning sangat muda */
            padding: 4rem 0;
        }
        
        .page-header-section {
            padding-bottom: 6rem; /* Khusus untuk Mitra agar ada ruang overlap */
        }


        /* ===================================================================
           5. Components & Widgets
        =================================================================== */
        /* Custom Pills (digunakan di Testimoni & Mitra) */
        .testimonial-pills-container,
        .custom-pills-container {
            background-color: #f1f3f5;
            border-radius: 0.75rem;
            padding: 0.25rem;
            border: 1px solid #dee2e6;
        }

        .testimonial-pills-container .nav-link,
        .custom-pills-container .nav-link {
            border: 0;
            border-radius: 0.5rem;
            color: #495057;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .testimonial-pills-container .nav-link.active,
        .custom-pills-container .nav-link.active {
            background-color: #ffffff;
            color: #212529;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
        }

        .testimonial-pills-container .nav-link:not(.active):hover,
        .custom-pills-container .nav-link:not(.active):hover {
            color: #212529;
        }

        /* Visi Misi & Tentang Kami */
        .image-container {
            position: relative;
        }
        
        .image-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background-color: #fd7e14;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .mission-list {
            list-style: none;
            padding-left: 0;
        }
        
        .mission-list li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }
        
        .mission-list .bi-check-circle-fill {
            color: #fd7e14;
            margin-right: 0.75rem;
            margin-top: 0.15rem;
        }
        
        .vm-icon {
            display: inline-flex;
            width: 45px;
            height: 45px;
            background-color: #fd7e14;
            color: white;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        /* Testimonial Card */
        .testimonial-card {
            border-left: 5px solid #0d6efd;
        }
        
        .testimonial-quote {
            font-style: italic;
        }

        /* ===================================================================
           6. Footer
        =================================================================== */
        .footer-section {
            background: #151414;
            color: white;
        }

        .footer-logo .logo-circle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: #ffc107;
            color: #151414;
            border-radius: 50%;
            margin-right: 0.5rem;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .footer-widget-heading h3 {
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 40px;
            position: relative;
        }

        .footer-widget-heading h3::before {
            content: "";
            position: absolute;
            left: 0;
            bottom: -15px;
            height: 2px;
            width: 50px;
            background: #ffc107;
        }

        .footer-widget ul {
            padding: 0;
            list-style: none;
        }

        .footer-widget ul li {
            margin-bottom: 12px;
        }

        .footer-widget ul li a {
            color: #878787;
            text-decoration: none;
        }

        .footer-widget ul li a:hover {
            color: #ffc107;
        }

        .footer-text p {
            margin-bottom: 14px;
            font-size: 14px;
            color: #7e7e7e;
            line-height: 28px;
        }

        .footer-social-icon span {
            color: #fff;
            display: block;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-social-icon a {
            color: #fff;
            font-size: 16px;
            margin-right: 15px;
        }

        .footer-social-icon i {
            height: 40px;
            width: 40px;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            border: 1px solid #fff;
            display: inline-block;
            transition: all 0.3s ease-in-out;
        }

        .footer-social-icon a:hover i {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #151414;
        }
        
        .single-cta {
            display: flex;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        
        .single-cta i {
            font-size: 20px;
            color: #ffc107;
            flex-shrink: 0;
            margin-top: 5px;
        }

        .single-cta .cta-text {
            padding-left: 15px;
        }

        .single-cta .cta-text span {
            color: #757575;
        }

        .single-cta .cta-text span a {
            color: #757575;
            text-decoration: none;
        }

        .single-cta .cta-text span a:hover {
            color: #ffc107;
        }

        .copyright-area {
            background: #202020;
            padding: 25px 0;
        }

        .copyright-text p {
            margin: 0;
            font-size: 14px;
            color: #878787;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
            <div class="container">
                <a class="navbar-brand fs-4" href="index.php">
                    <span class="logo-circle">B</span>BaksoPremium
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a class="nav-link <?php if($current_page == 'index.php') echo 'active'; ?>" href="index.php">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($current_page == 'produk.php') echo 'active'; ?>" href="produk.php">Produk</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($current_page == 'mitra.php') echo 'active'; ?>" href="mitra.php">Mitra</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($current_page == 'testimoni.php') echo 'active'; ?>" href="testimoni.php">Testimoni</a></li>
                        <li class="nav-item"><a class="nav-link <?php if($current_page == 'tentang-kami.php') echo 'active'; ?>" href="tentang-kami.php">Tentang Kami</a></li>
                    </ul>
                    <div class="d-flex align-items-center">
                        <a href="https://wa.me/<?php echo $whatsapp_number; ?>" class="text-dark text-decoration-none me-3 d-none d-lg-block">
                            <i class="bi bi-telephone-fill me-1"></i><?php echo $whatsapp_number; ?>
                        </a>
                        <a href="admin/login.php" class="btn btn-sm btn-admin me-2">Admin</a>
                        <a href="produk.php" class="btn btn-sm btn-cta">Pesan Sekarang</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>