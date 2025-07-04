<?php
// File: testimoni.php
require_once 'includes/header.php';

// Ambil semua testimoni yang sudah disetujui (approved)
$testimonials = $conn->query("SELECT * FROM testimonials WHERE status = 'approved' ORDER BY submitted_at DESC");
?>

<!-- Bagian Header -->
<div class="page-header-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Testimoni <span style="color: #fd7e14;">Pelanggan</span></h1>
        <p class="lead text-muted mt-3">Dengarkan pengalaman pelanggan kami yang telah merasakan kelezatan dan kualitas<br>bakso premium. Bagikan juga pengalaman Anda!</p>
    </div>
</div>

<div class="container py-5">

    <!-- 1. Navigasi Tabs -->
    <ul class="nav nav-pills nav-fill mb-4 testimonial-pills-container" id="testimonialTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#testimonial-list-pane" type="button" role="tab" aria-controls="testimonial-list-pane" aria-selected="true">
                <i class="bi bi-chat-square-quote-fill me-2"></i> Testimoni Pelanggan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <!-- Tombol untuk menampilkan form pengiriman -->
            <button class="nav-link" id="submit-tab" data-bs-toggle="tab" data-bs-target="#submit-form-pane" type="button" role="tab" aria-controls="submit-form-pane" aria-selected="false">
                <i class="bi bi-pencil-square me-2"></i> Kirim Testimoni
            </button>
        </li>
    </ul>

    <!-- 2. Konten untuk setiap Tab -->
    <div class="tab-content" id="testimonialTabContent">

        <!-- KONTEN 1: Daftar Testimoni yang Ada -->
        <div class="tab-pane fade show active" id="testimonial-list-pane" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Apa Kata <span style="color: #fd7e14;">Pelanggan</span> Kami?</h2>
                <p class="lead text-muted">Kepuasan pelanggan adalah prioritas utama kami. Berikut testimoni dari pelanggan yang telah merasakan kualitas produk kami.</p>
            </div>
            
            <div class="row">
                <?php if ($testimonials && $testimonials->num_rows > 0): ?>
                    <?php while($testi = $testimonials->fetch_assoc()): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card testimonial-card h-100">
                            <div class="card-body d-flex flex-column">
                                <blockquote class="blockquote mb-0 flex-grow-1">
                                    <p class="testimonial-quote">"<?php echo nl2br(htmlspecialchars($testi['message'])); ?>"</p>
                                    <footer class="blockquote-footer mt-2"><?php echo htmlspecialchars($testi['customer_name']); ?></footer>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">Belum ada testimoni. Jadilah yang pertama memberikan ulasan!</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- KONTEN 2: Form untuk Mengirim Testimoni Baru -->
        <div class="tab-pane fade" id="submit-form-pane" role="tabpanel" aria-labelledby="submit-tab" tabindex="0">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header text-white" style="background-color: #fd7e14;">
                            <h4 class="mb-0">Bagikan Pengalaman Anda</h4>
                        </div>
                        <div class="card-body p-4">
                            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
                                <div class="alert alert-success">Terima kasih! Testimoni Anda telah kami terima dan akan ditampilkan setelah disetujui admin.</div>
                            <?php endif; ?>
                            <form action="proses_testimoni.php" method="POST">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Anda</label>
                                    <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Ulasan Anda</label>
                                    <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-cta btn-lg">Kirim Testimoni</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>

<!-- Pastikan Anda memuat Bootstrap JS di footer.php agar tabs berfungsi -->
<!-- Contoh di footer.php: <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.x.x/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- Saya juga menambahkan ikon dari Bootstrap Icons, pastikan Anda juga memuat CSS-nya jika ingin ikon tampil -->
<!-- Contoh di header.php: <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> -->