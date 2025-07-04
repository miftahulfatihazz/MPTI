<?php
// File: tentang-kami.php (Versi Final yang Sudah Diperbaiki)
require_once 'includes/header.php';
// require_once 'admin/settings.php';

// Ambil semua data yang relevan dari database
$result = $conn->query("SELECT * FROM site_content WHERE section_key LIKE 'company_%' OR section_key IN ('visi', 'misi')");

// Olah data agar mudah digunakan
$content = [];
while ($row = $result->fetch_assoc()) {
    $content[$row['section_key']] = $row['content'];
}

// Fungsi helper untuk format nomor WA
function format_whatsapp_link($number) {
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

function format_whatsapp_display($number) {
    $clean_number = preg_replace('/[^0-9]/', '', $number);
    if (substr($clean_number, 0, 2) === '62') {
        $clean_number = '0' . substr($clean_number, 2);
    }
    // Format menjadi 0812-3456-7890 atau format lain yang sesuai
    return preg_replace("/(\d{4})(\d{4})(\d*)/", "$1-$2-$3", $clean_number);
}
?>

<!-- Bagian Header Halaman -->
 <div class="about-header text-center">
    <div class="container">
        <h2 class="fw-bold"><span style="color: #fd7e14;">Tentang</span> Kami</h2>
        <p class="lead text-muted mt-3">
            <?php 
            echo nl2br(htmlspecialchars($content['company_profile_long'] ?? 'Mengenal lebih dekat Bakso Premium Indonesia - perjalanan, visi misi, dan komitmen kami dalam menghadirkan bakso berkualitas tinggi untuk keluarga Indonesia.')); 
            ?>
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
                // Logika untuk menampilkan gambar yang diupload atau gambar default
                $image_filename = $content['company_image'] ?? 'placeholder.jpg';
                $image_path = 'uploads/site/' . $image_filename;

                // Tentukan gambar default
                $default_image_src = 'https://images.unsplash.com/photo-1594212699903-ec8a3eBF49c7?q=80&w=1974&auto=format&fit=crop';
                
                // Cek apakah file gambar ada di server dan bukan placeholder
                if ($image_filename != 'placeholder.jpg' && file_exists($image_path)) {
                    $image_src = $image_path;
                } else {
                    $image_src = $default_image_src;
                }
                ?>
                <img src="<?php echo $image_src; ?>" alt="Tentang Sinar Bahari" class="img-fluid rounded shadow-sm">
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
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($content['misi'] ?? 'Misi belum diatur.')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <!-- Kontak & Lokasi Section -->
    <div class="row">
        <div class="col-lg-5 mb-4">
            <h3 class="mb-3">Hubungi Kami</h3>
            <ul class="list-unstyled">
                <li class="mb-3"><i class="bi bi-geo-alt-fill me-2"></i><strong>Alamat:</strong><br><?php echo nl2br(htmlspecialchars($content['company_address'] ?? '')); ?></li>
                <li class="mb-3"><i class="bi bi-whatsapp me-2"></i><strong>WhatsApp:</strong><br><a href="<?php echo format_whatsapp_link($content['company_whatsapp'] ?? ''); ?>"><?php echo format_whatsapp_display($content['company_whatsapp'] ?? ''); ?></a></li>
                <li class="mb-3"><i class="bi bi-envelope-fill me-2"></i><strong>Email:</strong><br><a href="mailto:<?php echo htmlspecialchars($content['company_email'] ?? ''); ?>"><?php echo htmlspecialchars($content['company_email'] ?? ''); ?></a></li>
                <li class="mb-3"><i class="bi bi-instagram me-2"></i><strong>Instagram:</strong><br><a href="<?php echo htmlspecialchars($content['company_instagram'] ?? ''); ?>" target="_blank"><?php echo '@' . basename(parse_url($content['company_instagram'] ?? '', PHP_URL_PATH)); ?></a></li>
            </ul>
        </div>
        <div class="col-lg-7">
             <iframe src="<?php echo htmlspecialchars($content['company_maps_embed'] ?? ''); ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded shadow-sm"></iframe>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>