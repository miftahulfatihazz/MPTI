<?php
// File: pesan.php

// Panggil header untuk tampilan yang konsisten
require_once 'includes/header.php';

// Ambil parameter dari URL
// ?? adalah Null Coalescing Operator, jalan pintas untuk isset()
$status = $_GET['status'] ?? 'info'; // 'sukses', 'error', 'info'
$pesan = $_GET['pesan'] ?? 'Tidak ada pesan.';
$kembali = $_GET['kembali'] ?? 'index.php'; // Halaman default untuk kembali

// Tentukan kelas dan ikon Bootstrap berdasarkan status
$alert_class = 'alert-info';
$icon_class = 'bi-info-circle-fill';
$title = 'Informasi';

if ($status == 'sukses') {
    $alert_class = 'alert-success';
    $icon_class = 'bi-check-circle-fill';
    $title = 'Berhasil!';
} elseif ($status == 'error') {
    $alert_class = 'alert-danger';
    $icon_class = 'bi-exclamation-triangle-fill';
    $title = 'Terjadi Kesalahan!';
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="alert <?php echo $alert_class; ?>" role="alert">
                <i class="bi <?php echo $icon_class; ?>" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                <h4 class="alert-heading"><?php echo $title; ?></h4>
                <p><?php echo htmlspecialchars(urldecode($pesan)); ?></p>
                <hr>
                <a href="<?php echo htmlspecialchars($kembali); ?>" class="btn btn-primary">
                    <?php 
                        // Teks tombol yang lebih dinamis
                        if (strpos($kembali, 'javascript:history.back()') !== false) {
                            echo 'Kembali ke Form';
                        } elseif ($kembali == 'index.php') {
                            echo 'Kembali ke Beranda';
                        } else {
                            echo 'Kembali';
                        }
                    ?>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>