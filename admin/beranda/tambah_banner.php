<?php
// File: admin/beranda/tambah_banner.php
require_once '../includes/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Banner Baru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Beranda</a></li>
        <li class="breadcrumb-item active">Tambah Banner</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-images me-1"></i>
            Formulir Banner Promosi
        </div>
        <div class="card-body">
            <form action="proses_tambah_banner.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Banner</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Contoh: Promo Paket Hemat" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Singkat (Opsional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Contoh: Beli 3 gratis 1 semua varian!"></textarea>
                </div>
                <div class="mb-3">
                    <label for="display_order" class="form-label">Urutan Tampil</label>
                    <input type="number" class="form-control" id="display_order" name="display_order" value="0" required>
                    <div class="form-text">Angka lebih kecil akan tampil lebih dulu.</div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Banner</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/jpeg, image/png, image/webp" required>
                    <div class="form-text">Disarankan menggunakan gambar landscape (melebar) dengan rasio 16:9.</div>
                </div>
                
                <hr>
                <button type="submit" class="btn btn-primary">Simpan Banner</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>