<?php
// File: tentang-kami.php
require_once 'includes/header.php';
require_once 'config/database.php';

// Ambil semua data yang relevan dari database
$result = $conn->query("SELECT * FROM site_settings WHERE setting_key LIKE 'company_%' OR setting_key IN ('visi', 'misi')");
if (!$result) {
    die("Error: Gagal mengambil data dari site_settings: " . $conn->error);
}

// Olah data agar mudah digunakan
$content = [];
while ($row = $result->fetch_assoc()) {
    $content[$row['setting_key']] = $row['setting_value'];
}

// Fungsi helper untuk format nomor WA
function format_whatsapp_link($number)
{
    if (empty($number)) return '#';
    $clean_number = preg_replace('/[^0-9]/', '', $number);
    if (substr($clean_number, 0, 2) !== '62') {
        if (substr($clean_number, 0, 1) === '0') {
            $clean_number = '62' . substr($clean_number, 1);
        } else {
            $clean_number = '62' . $clean_number;
        }
    }
    return 'https://wa.me/' . $clean_number;
}

function format_whatsapp_display($number)
{
    if (empty($number)) return 'Nomor belum diatur';
    $clean_number = preg_replace('/[^0-9]/', '', $number);
    if (substr($clean_number, 0, 2) === '62') {
        $clean_number = '0' . substr($clean_number, 2);
    }
    return preg_replace("/(\d{4})(\d{4})(\d*)/", "$1-$2-$3", $clean_number);
}
?>

<!-- Bagian Header Halaman -->
<div class="about-header text-center">
    <div class="container">
        <h2 class="fw-bold"><span style="color: #fd7e14;">Tentang</span> Kami</h2>
        <p class="lead text-muted mt-3">

            'Mengenal lebih dekat Bakso Ikan Sinar Bahari Tasikmalaya - perjalanan, visi misi, dan komitmen kami dalam menghadirkan bakso berkualitas tinggi untuk keluarga

        </p>
    </div>
</div>
<div class="bg-light">
    <div class="container py-5">
        <div class="row h-100 align-items-center py-5">
            <div class="col-lg-6">
                <h3 class="fw-bold">Profil <span style="color: #fd7e14;">Perusahaan</span></h3>
                <p class="lead text-muted mb-0"><?php echo nl2br(htmlspecialchars($content['company_profile_short'] ?? 'Profil singkat perusahaan belum diatur.')); ?></p>
            </div>
            <!-- Kolom untuk Gambar -->
            <div class="col-lg-6 d-none d-lg-block">
                <?php
                $image_filename = !empty($content['company_image']) && $content['company_image'] != 'placeholder.jpg' ? $content['company_image'] : 'placeholder.jpg';
                $image_path = 'uploads/site/' . $image_filename;
                $default_image_src = 'https://images.unsplash.com/photo-1594212699903-ec8a3eBF49c7?q=80&w=1974&auto=format&fit=crop';
                $image_src = ($image_filename != 'placeholder.jpg' && file_exists($image_path)) ? $image_path : $default_image_src;
                ?>
                <img src="<?php echo htmlspecialchars($image_src); ?>" alt="Tentang Sinar Bahari" class="img-fluid rounded shadow-sm">
            </div>
        </div>
    </div>
</div>
<div class="bg-light py-5">
    <div class="container">
        <!-- Judul Section -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">Visi & <span style="color: #fd7e14;">Misi</span> Kami</h2>
            <p class="lead text-muted">Komitmen kami dalam menghadirkan produk berkualitas dan membangun<br>kepercayaan pelanggan di seluruh Indonesia.</p>
        </div>
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="vm-icon me-3"><i class="bi bi-eye-fill text"></i></span>
                                <h4 class="fw-bold mb-0">Visi Perusahaan</h4>
                            </div>

                            <p class="card-text"><?php echo nl2br(htmlspecialchars($content['visi'] ?? 'Visi belum diatur.')); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <span class="vm-icon me-3"><i class="bi bi-bullseye"></i></span>
                                <h4 class="fw-bold mb-0">Misi Perusahaan</h4>
                            </div>
                            <div class="d-flex align-items-start">
                                <span class="me-3"><i class="bi bi-check-circle-fill" style="color: #fd7e14;"></i></span>
                                <p class="card-text" style="text-align: left;"><?php echo nl2br(htmlspecialchars($content['misi'] ?? 'Misi belum diatur.')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kontak & Lokasi Section -->
<div class="bg-light py-5">
    <div class="container">

        <div class="contact-page-container">
            <div class="text-center mb-5">
                <h2 class="fw-bolder">Hubungi <span style="color: #fd7e14;">Kami</span></h2>
                <p class="lead text-muted px-3">Kami siap melayani dan menjawab semua pertanyaan Anda. Jangan ragu untuk<br>menghubungi kami melalui berbagai channel yang tersedia.</p>
            </div>
            <div>
                <div class="container py-5">
                    <div class="row">
                        <!-- Kolom Informasi Kontak -->
                        <div class="col-lg-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4 class="fw-bold mb-4">Informasi Kontak</h4>

                                    <!-- Alamat Kantor -->
                                    <div class="d-flex mb-4">
                                        <div class="contact-icon-circle"><i class="bi bi-geo-alt"></i></div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">Alamat Kantor</h6>
                                            <p class="text-muted small mb-2">
                                                <?php echo nl2br(htmlspecialchars($content['company_address'] ?? 'Alamat belum diatur.')); ?>
                                            </p>
                                            <!-- Tautan Peta: Asumsi ada URL peta terpisah. Jika tidak, Anda bisa menautkan ke Google Maps dengan query alamat. -->
                                            <a href="#" class="btn-maps"><i class="bi bi-send"></i> Lihat di Maps</a>
                                        </div>
                                    </div>

                                    <!-- Telepon -->
                                    <?php if (!empty($content['company_whatsapp'])): ?>
                                        <div class="d-flex mb-4">
                                            <div class="contact-icon-circle"><i class="bi bi-telephone"></i></div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fw-bold">Telepon</h6>
                                                <p class="text-muted small mb-0"><?php echo htmlspecialchars(format_whatsapp_display($content['company_whatsapp'])); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Email -->
                                    <?php if (!empty($content['company_email'])): ?>
                                        <div class="d-flex mb-4">
                                            <div class="contact-icon-circle"><i class="bi bi-envelope"></i></div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fw-bold">Email</h6>
                                                <a href="mailto:<?php echo htmlspecialchars($content['company_email']); ?>" class="text-muted small mb-0 text-decoration-none"><?php echo htmlspecialchars($content['company_email']); ?></a>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Jam Operasional (Konten statis sesuai gambar) -->
                                    <div class="d-flex">
                                        <div class="contact-icon-circle"><i class="bi bi-clock"></i></div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="fw-bold">Jam Operasional</h6>
                                            <p class="text-muted small mb-0">
                                                Senin - Jumat: 08:00 - 17:00<br>
                                                Sabtu: 08:00 - 15:00<br>
                                                Minggu: Tutup
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Media Sosial & Quick Actions -->
                        <div class="col-lg-6">
                            <!-- Card Media Sosial -->
                            <div class="card mb-4">
                                <div class="card-body p-4">
                                    <h4 class="fw-bold mb-4">Media Sosial</h4>

                                    <!-- WhatsApp Link -->
                                    <?php if (!empty($content['company_whatsapp'])): ?>
                                        <a href="<?php echo format_whatsapp_link($content['company_whatsapp']); ?>" target="_blank" class="social-link d-flex justify-content-between align-items-center p-3 rounded-3 text-decoration-none text-dark mb-3">
                                            <div class="d-flex align-items-center">
                                                <div class="social-icon-circle bg-light-green me-3"><i class="bi bi-whatsapp"></i></div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">WhatsApp</h6>
                                                    <p class="text-muted small mb-0">Chat langsung dengan kami</p>
                                                </div>
                                            </div>
                                            <i class="bi bi-box-arrow-up-right text-muted"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Instagram Link -->
                                    <?php if (!empty($content['company_instagram'])): ?>
                                        <a href="<?php echo htmlspecialchars($content['company_instagram']); ?>" target="_blank" class="social-link d-flex justify-content-between align-items-center p-3 rounded-3 text-decoration-none text-dark">
                                            <div class="d-flex align-items-center">
                                                <div class="social-icon-circle bg-light-pink me-3"><i class="bi bi-instagram"></i></div>
                                                <div>
                                                    <h6 class="fw-bold mb-0">Instagram</h6>
                                                    <p class="text-muted small mb-0">Follow untuk update terbaru</p>
                                                </div>
                                            </div>
                                            <i class="bi bi-box-arrow-up-right text-muted"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Card Quick Actions (Konten statis sesuai gambar) -->
                            <div class="card">
                                <div class="card-body p-4">
                                    <h4 class="fw-bold mb-4">Quick Actions</h4>
                                    <div class="d-grid gap-3">
                                        <a href="produk.php" class="btn btn-outline-warning">Lihat Produk Kami</a>
                                        <a href="mitra.php" class="btn btn-outline-warning">Program Kemitraan</a>
                                        <a href="testimoni.php" class="btn btn-outline-warning">Testimoni Pelanggan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Peta Google Maps tidak ada di desain gambar, jadi saya nonaktifkan. Anda bisa mengaktifkannya kembali jika perlu. -->
                    <?php /* if (!empty($content['company_maps_embed'])): ?>
        <div class="row mt-5">
            <div class="col-12">
                 <iframe src="<?php echo htmlspecialchars($content['company_maps_embed']); ?>" width="100%" height="400" style="border:0; border-radius: 0.75rem;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="shadow-sm"></iframe>
            </div>
        </div>
        <?php endif; */ ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php
// Remove $conn->close() to allow footer.php to use $conn
require_once 'includes/footer.php';
?>