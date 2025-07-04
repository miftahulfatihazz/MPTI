<?php
// File: includes/footer.php (Untuk Pengunjung)

// Ambil data yang dibutuhkan untuk footer dari database
// Pastikan koneksi $conn masih aktif dari header.php
$footer_content_keys = "'footer_description', 'company_address', 'company_whatsapp', 'company_email', 'company_instagram'";
$footer_result = $conn->query("SELECT section_key, content FROM site_content WHERE section_key IN ($footer_content_keys)");

$footer_data = [];
if ($footer_result) {
    while ($row = $footer_result->fetch_assoc()) {
        $footer_data[$row['section_key']] = $row['content'];
    }
}
?>

    </main> <!-- Penutup tag <main> dari header.php -->

    <footer class="footer-section">
        <div class="container">
            <div class="footer-content pt-5 pb-5">
                <div class="row">
                    <!-- Kolom 1: Profil & Logo -->
                    <div class="col-xl-4 col-lg-4 mb-50">
                        <div class="footer-widget">
                            <div class="footer-logo mb-3">
                                <a href="index.php" class="h4 text-white text-decoration-none fw-bold d-flex align-items-center">
                                    <!-- Struktur Logo -->
                                    <span class="logo-circle">B</span>
                                    <!-- Teks di samping Logo -->
                                    <span>Bakso Ikan Sinar Bahari Tasikmalaya</span>
                                </a>
                                </div>    
                                <div class="footer-text">
                                    <p><?php echo htmlspecialchars($footer_data['footer_description'] ?? 'Deskripsi singkat belum diatur.'); ?></p>
                                </div>
                            <div class="footer-social-icon">
                                <span>Follow us</span>
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-pinterest"></i></a>
                                <a href="<?php echo htmlspecialchars($footer_data['company_instagram'] ?? '#'); ?>"><i class="bi bi-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- Kolom 2: Menu Utama -->
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-30">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Menu Utama</h3>
                            </div>
                            <ul>
                                <li><a href="index.php">Beranda</a></li>
                                <li><a href="produk.php">Produk</a></li>
                                <li><a href="mitra.php">Mitra</a></li>
                                <li><a href="testimoni.php">Testimoni</a></li>
                                <li><a href="tentang-kami.php">Tentang Kami</a></li>
                                <li><a href="admin/login.php">Dashboard Admin</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Kolom 3: Kontak -->
                    <div class="col-xl-4 col-lg-4 col-md-6 mb-50">
                        <div class="footer-widget">
                            <div class="footer-widget-heading">
                                <h3>Kontak</h3>
                            </div>
                            <div class="footer-text">
                                <div class="single-cta">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    <div class="cta-text">
                                        <span><?php echo nl2br(htmlspecialchars($footer_data['company_address'] ?? 'Alamat belum diatur.')); ?></span>
                                    </div>
                                </div>
                                <div class="single-cta">
                                    <i class="bi bi-telephone-fill"></i>
                                    <div class="cta-text">
                                        <span><a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $footer_data['company_whatsapp'] ?? ''); ?>">+<?php echo preg_replace('/[^0-9]/', '', $footer_data['company_whatsapp'] ?? ''); ?></a></span>
                                    </div>
                                </div>
                                <div class="single-cta">
                                    <i class="bi bi-envelope-fill"></i>
                                    <div class="cta-text">
                                        <span><a href="mailto:<?php echo htmlspecialchars($footer_data['company_email'] ?? ''); ?>"><?php echo htmlspecialchars($footer_data['company_email'] ?? 'Email belum diatur.'); ?></a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-area">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 text-center">
                        <div class="copyright-text">
                            <p>© <?php echo date('Y'); ?> Bakso Ikan Sinar Bahari Tasikmalaya. Semua hak cipta dilindungi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <?php
    // Menutup koneksi database di akhir semua halaman
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
    ?>
</body>
</html>