<?php
// File: admin/products/tambah.php
require_once '../includes/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah Produk Baru</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Produk</a></li>
        <li class="breadcrumb-item active">Tambah Baru</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-egg-fried me-1"></i>
            Formulir Produk
        </div>
        <div class="card-body">
            <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Contoh: 25000" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Produk</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/jpeg, image/png" required>
                    <div class="form-text">Format yang didukung: JPG, PNG. Ukuran gambar disarankan 1:1 (persegi).</div>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>