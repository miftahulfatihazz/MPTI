<?php
// File: index.php (Versi Dinamis)
require_once 'includes/header.php';

// Ambil data untuk Carousel/Banner
$banners = $conn->query("SELECT * FROM carousels WHERE is_active = 1 ORDER BY display_order ASC");

// Ambil data untuk Teks Keunggulan Utama
$result_keunggulan_text = $conn->query("SELECT content FROM site_content WHERE section_key = 'product_advantages'");
$keunggulan_text = $result_keunggulan_text->fetch_assoc();

// [BARU] Ambil data untuk Kartu Keunggulan
$advantages_result = $conn->query("SELECT title, description, icon_svg FROM advantages WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");

// Ambil 4 produk untuk ditampilkan sebagai unggulan
$featured_products = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 4");

$testimonials = $conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY submitted_at DESC");
?>

<style>
    /* ... (semua style dari sebelumnya tetap sama) ... */
    /* CSS Khusus untuk Hero Carousel */
    .hero-carousel .carousel-item {
        height: 85vh;
        min-height: 500px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .hero-carousel .carousel-caption {
        top: 50%;
        transform: translateY(-50%);
        bottom: auto;
        text-align: left;
        padding: 0 5%;
    }
    .hero-carousel .promo-badge {
        background-color: rgba(253, 126, 20, 0.2);
        color: #fd7e14;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 1rem;
    }
    .hero-carousel h1 {
        font-size: 3.5rem;
        font-weight: 700;
        color: white;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    }
    .hero-carousel p {
        font-size: 1.2rem;
        color: white;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.7);
    }

    /* CSS untuk Bagian Keunggulan */
    .advantages-section, .testimoni {
        background-color: #f8f9fa;
    }
    .feature-card {
        background-color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    .feature-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        background-color: #fd7e14;
        color: white;
        border-radius: 50%;
        font-size: 2rem;
    }
    /* [BARU] Atur ukuran SVG di dalam ikon */
    .feature-icon svg {
        width: 40px;
        height: 40px;
    }

     .btn-outline-primary {
    border-color: #fd7e14;
    color: #fd7e14;
  }

  .btn-outline-primary:hover {
    background-color: #fd7e14;
    color: #fff;
    border-color: #fd7e14;
  }
</style>

<!-- Hero Section dengan Carousel -->
<div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <!-- ... (Bagian Carousel tidak berubah) ... -->
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

<!-- [DIMODIFIKASI] Bagian Mengapa Memilih Kami -->
<div class="advantages-section py-5">
    <div class="container">
        <section class="text-center mb-5">
            <h2 class="display-5 fw-bold">Mengapa Memilih <span style="color: #fd7e14;">Bakso Premium</span> Kami?</h2>
            <p class="lead text-muted mx-auto" style="max-width: 700px;"><?php echo nl2br(htmlspecialchars($keunggulan_text['content'] ?? 'Kami berkomitmen memberikan produk bakso terbaik dengan standar kualitas tinggi untuk kepuasan dan kesehatan keluarga Anda.')); ?></p>
        </section>

        <div class="row g-4 text-center">
            <?php if ($advantages_result && $advantages_result->num_rows > 0): ?>
                <?php while($advantage = $advantages_result->fetch_assoc()): ?>
                <!-- Kartu dinamis akan dibuat di sini -->
                <div class="col-lg-3 col-md-6">
                    <div class="card feature-card h-100 p-4 shadow-sm">
                        <div class="feature-icon mx-auto mb-3">
                            <?php 
                                // Langsung echo SVG dari database.
                                // Pastikan konten SVG di database aman dan dari sumber terpercaya (admin).
                                echo $advantage['icon_svg']; 
                            ?>
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

<!-- Sisa Halaman Beranda -->
<div class="container py-5">
    <!-- ... (Bagian Produk Unggulan tidak berubah) ... -->
    <section class="text-center mb-5">
        <h2 class="display-5 fw-bold"><strong>Produk <span style="color: #fd7e14;">Unggulan</span> Kami</strong></h2>
        <div class="row">
            <?php if ($featured_products && $featured_products->num_rows > 0): ?>
                <?php while($product = $featured_products->fetch_assoc()): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100 text-center mt-4">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text fw-bold">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 pb-3">
                             <a href="form_pemesanan.php?id=<?php echo $product['id']; ?>" class="btn btn-primary" style="background-color: #fd7e14; border-color: #fd7e14;">Pesan Sekarang</a>
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

<div class="testimoni py-4">
  <div class="container">
    <section class="my-5">
      <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Apa Kata Pelanggan Kami</h1>
        <p class="lead text-muted">
          Kepuasan pelanggan adalah prioritas kami. Lihat apa yang mereka katakan tentang bakso kami
        </p>
      </div>

      <?php if ($testimonials && $testimonials->num_rows > 0): ?>
        <div class="row">
          <?php while($testi = $testimonials->fetch_assoc()): ?>
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

      <!-- BUTTON TETAP ADA -->
      <div class="text-center mt-4">
        <a href="testimoni.php" class="btn btn-outline-primary btn-lg">Lihat Semua Testimoni</a>
      </div>

    </section>
  </div>
</div>




<?php
require_once 'includes/footer.php';
?>