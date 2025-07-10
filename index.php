<?php
// File: index.php (Responsif & Notifikasi Lebih Baik)
require_once 'includes/header.php';

// ==========================================================
// [PERUBAHAN 1] Logika Notifikasi Bootstrap
// ==========================================================
$notification_message = '';
$notification_class = '';

if (isset($_GET['status'])) {
    if ($_GET['status'] == 'sukses') {
        // Alur ini seharusnya tidak terjadi lagi, tapi kita siapkan
        $notification_message = '<strong>Terima kasih!</strong> Pesanan Anda telah berhasil dibuat. Silakan periksa halaman konfirmasi untuk instruksi pembayaran.';
        $notification_class = 'alert-success';
    } elseif ($_GET['status'] == 'cod_sukses') {
        $notification_message = '<strong>Pesanan COD Berhasil!</strong> Terima kasih, pesanan Anda akan kami siapkan. Mohon siapkan pembayaran pas saat kurir tiba.';
        $notification_class = 'alert-info';
    }
}

// Ambil data dari database
$banners = $conn->query("SELECT * FROM carousels WHERE is_active = 1 ORDER BY display_order ASC");
$result_keunggulan_text = $conn->query("SELECT content FROM site_content WHERE section_key = 'product_advantages'");
$keunggulan_text = $result_keunggulan_text->fetch_assoc();
$advantages_result = $conn->query("SELECT title, description, icon_svg FROM advantages WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
$featured_products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");
$testimonials = $conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY submitted_at DESC");
?>

<!-- Carousel dibungkus dalam .container -->
<div class="container my-4">
    <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php if ($banners && $banners->num_rows > 0): $i = 0; ?>
                <?php foreach (range(0, $banners->num_rows - 1) as $i): ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo $i; ?>" class="<?php if($i == 0) echo 'active'; ?>" aria-current="<?php if($i == 0) echo 'true'; ?>"></button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="carousel-inner">
            <?php if ($banners && $banners->num_rows > 0): $first = true; ?>
                <?php while($banner = $banners->fetch_assoc()): ?>
                    <div class="carousel-item <?php if($first) { echo 'active'; $first = false; } ?>" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('uploads/banners/<?php echo htmlspecialchars($banner['image_url']); ?>');">
                        <div class="carousel-caption">
                            <span class="promo-badge">Promo Terbatas</span>
                            <h1><?php echo htmlspecialchars($banner['title']); ?></h1>
                            <p><?php echo htmlspecialchars($banner['description']); ?></p>
                            <a href="produk.php" class="btn btn-lg btn-cta mt-3">Pesan Sekarang</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="carousel-item active" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1553641243-1604a5a837d5?q=80&w=2070&auto=format&fit=crop');">
                    <div class="carousel-caption">
                        <h1>Bakso Premium Berkualitas</h1>
                        <p>Dibuat dari bahan-bahan segar pilihan, tanpa pengawet.</p>
                        <a href="produk.php" class="btn btn-lg btn-cta mt-3">Lihat Produk</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
</div>

<!-- ========================================================== -->
<!-- [PERUBAHAN 2] Blok untuk menampilkan notifikasi -->
<!-- ========================================================== -->
<div class="container">
    <?php if (!empty($notification_message)): ?>
        <div class="alert <?php echo $notification_class; ?> alert-dismissible fade show" role="alert">
            <?php echo $notification_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<!-- Bagian Mengapa Memilih Kami -->
<div class="advantages-section py-5">
    <div class="container">
        <section class="text-center mb-5">
            <h2 class="display-5 fw-bold">Mengapa Memilih <span style="color: #fd7e14;">Bakso Premium</span> Kami?</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;"><?php echo nl2br(htmlspecialchars($keunggulan_text['content'] ?? 'Kami berkomitmen memberikan produk bakso terbaik dengan standar kualitas tinggi untuk kepuasan dan kesehatan keluarga Anda.')); ?></p>
        </section>

        <div class="row g-4 text-center">
            <?php if ($advantages_result && $advantages_result->num_rows > 0): ?>
                <?php while($advantage = $advantages_result->fetch_assoc()): ?>
                <!-- Kelas grid ini sudah responsif -->
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100 p-4 shadow-sm">
                        <div class="feature-icon mx-auto mb-3">
                            <?php echo $advantage['icon_svg']; ?>
                        </div>
                        <h5 class="fw-bold"><?php echo htmlspecialchars($advantage['title']); ?></h5>
                        <p class="text-muted small"><?php echo htmlspecialchars($advantage['description']); ?></p>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-muted">Keunggulan produk sedang disiapkan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Produk Unggulan -->
<div class="container py-5">
    <section class="text-center mb-5">
        <h2 class="display-5 fw-bold"><strong>Produk <span style="color: #fd7e14;">Unggulan</span> Kami</strong></h2>
        <div class="row">
            <?php if ($featured_products && $featured_products->num_rows > 0): ?>
                <?php while($product = $featured_products->fetch_assoc()): ?>
                <!-- Kelas grid ini sudah responsif -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100 text-center mt-4">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text fw-bold">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 pb-3">
                             <a href="produk.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="background-color: #fd7e14; border-color: #fd7e14;">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <div class="text-center mt-4">
            <a href="produk.php" class="btn btn-outline-primary btn-lg">Lihat Semua Produk</a>
        </div>
    </section>
</div>

<!-- Testimoni -->
<div class="testimoni py-4">
  <div class="container">
    <section class="my-5">
      <div class="text-center mb-5">
        <h1 class="display-5 fw-bold"><strong>Apa Kata <span style="color: #fd7e14;">Pelanggan</span> Kami</strong></h1>
        <p class="lead text-muted">
          Kepuasan pelanggan adalah prioritas kami. Lihat apa yang mereka katakan tentang bakso kami
        </p>
      </div>

      <?php if ($testimonials && $testimonials->num_rows > 0): ?>
        <div class="row">
          <?php while($testi = $testimonials->fetch_assoc()): ?>
            <!-- Kelas grid ini sudah responsif -->
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="card feature-card h-100 p-4 shadow-sm">
                <div class="card-body">
                  <blockquote class="blockquote mb-0">
                    <p class="testimonial-quote">"<?php echo nl2br(htmlspecialchars($testi['message'])); ?>"</p>
                    <footer class="blockquote-footer mt-2"><?php echo htmlspecialchars($testi['customer_name']); ?></footer>
                  </blockquote>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      <?php else: ?>
        <p class="text-center">Belum ada testimoni untuk ditampilkan.</p>
      <?php endif; ?>

      <div class="text-center mt-4">
        <a href="testimoni.php" class="btn btn-outline-primary btn-lg">Lihat Semua Testimoni</a>
      </div>

    </section>
  </div>
</div>

<?php
require_once 'includes/footer.php';
?>