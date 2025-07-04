<?php
// File: mitra.php
require_once 'includes/header.php';

// Ambil data mitra yang aktif untuk ditampilkan
$partners = $conn->query("SELECT * FROM partners WHERE status = 'approved' ORDER BY name ASC");
?>

<!-- =======================
Header Halaman Kemitraan
======================== -->
<div class="page-header-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Program <span style="color: #fd7e14;">Kemitraan</span></h1>
        <p class="lead text-muted mt-3">Bergabunglah dengan jaringan mitra Bakso Premium dan raih kesuksesan bersama.<br>Peluang bisnis menguntungkan dengan dukungan penuh dari kami.</p>
    </div>
</div>


<div class="container py-5">
    <ul class="nav nav-pills nav-fill mb-4 custom-pills-container" id="mitraTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#daftar-mitra-pane" type="button" role="tab" aria-controls="daftar-mitra-pane" aria-selected="true">
                <i class="bi bi-geo-alt-fill me-2"></i> Daftar Mitra
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="join-tab" data-bs-toggle="tab" data-bs-target="#gabung-mitra-pane" type="button" role="tab" aria-controls="gabung-mitra-pane" aria-selected="false">
                <i class="bi bi-building-up me-2"></i> Gabung Mitra
            </button>
        </li>
    </ul>

    <!-- 2. Konten untuk setiap Tab -->
    <div class="tab-content pt-4" id="mitraTabContent">

        <!-- KONTEN 1: Daftar Mitra Aktif -->
        <div class="tab-pane fade show active" id="daftar-mitra-pane" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
            <section class="text-center mb-5">
                <h2 class="fw-bold">Lokasi <span style="color: #fd7e14;">Mitra Kami</span></h2>
                <p class="text-muted">Temukan outlet mitra Bakso Premium terdekat di lokasi Anda.</p>
            </section>

            <div class="row g-4">
                <?php if ($partners && $partners->num_rows > 0): ?>
                    <?php while($partner = $partners->fetch_assoc()): ?>
                        <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                            <div class="card h-100 product-card w-100 shadow-sm d-flex flex-column">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold"><?php echo htmlspecialchars($partner['name']); ?></h5>
                                    <p class="mb-2"><i class="bi bi-geo-alt me-1"></i> <?php echo htmlspecialchars($partner['address']); ?></p>
                                    <p class="mb-3"><i class="bi bi-whatsapp me-1"></i> <?php echo htmlspecialchars($partner['whatsapp']); ?></p>
                                    <a href="https://maps.google.com/?q=<?php echo urlencode($partner['address']); ?>" target="_blank" class="btn btn-outline-primary btn-icon-swap mt-auto">
                                        <span class="icon-default"><i class="bi bi-pin-map me-2"></i></span>
                                        <span class="icon-hover"><i class="bi bi-send-fill me-2"></i></span>
                                        Lihat di Maps
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">Belum ada mitra aktif yang terdaftar saat ini.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- KONTEN 2: Form Pendaftaran Mitra Baru -->
        <div class="tab-pane fade" id="gabung-mitra-pane" role="tabpanel" aria-labelledby="join-tab" tabindex="0">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Formulir <span style="color: #fd7e14;">Pendaftaran</span></h2>
                        <p class="lead text-muted">Kembangkan bisnis Anda bersama kami dan raih keuntungan bersama.</p>
                    </div>
                    
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses_daftar'): ?>
                        <div class="alert alert-success"><strong>Pendaftaran Berhasil!</strong> Tim kami akan segera meninjau data Anda dan menghubungi Anda. Terima kasih!</div>
                    <?php endif; ?>

                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5">
                             <form action="proses_mitra.php" method="POST">
                                <div class="row">
                                    <div class="col-md-6 mb-3"><label for="name" class="form-label">Nama Anda/Toko</label><input type="text" class="form-control" id="name" name="name" required></div>
                                    <div class="col-md-6 mb-3"><label for="email" class="form-label">Email</label><input type="email" class="form-control" id="email" name="email" required></div>
                                </div>
                                <div class="mb-3"><label for="whatsapp" class="form-label">Nomor Telepon/WhatsApp</label><input type="tel" class="form-control" id="whatsapp" name="whatsapp" required></div>
                                <div class="mb-3"><label for="address" class="form-label">Alamat Lengkap</label><textarea class="form-control" id="address" name="address" rows="3" required></textarea></div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-cta btn-lg">Kirim Pendaftaran</button>
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