<?php
require_once '../includes/header.php';
require_once '../../config/database.php';

// Ambil data teks keunggulan
$stmt_keunggulan = $conn->prepare("SELECT content FROM site_content WHERE section_key = 'product_advantages'");
$stmt_keunggulan->execute();
$keunggulan = $stmt_keunggulan->get_result()->fetch_assoc();
$stmt_keunggulan->close();

// Ambil data semua banner
$result_banners = $conn->query("SELECT * FROM carousels ORDER BY display_order ASC");
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Beranda</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Kelola konten halaman depan</li>
    </ol>
    
    <!-- Pesan Sukses (jika ada) -->
    <?php if (isset($_GET['status'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Aksi berhasil dijalankan!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6">
            <!-- Form untuk Kelola Teks Keunggulan -->
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-card-text me-1"></i>Kelola Teks Keunggulan Produk</div>
                <div class="card-body">
                    <form action="proses_keunggulan.php" method="POST">
                        <div class="mb-3">
                            <label for="keunggulan" class="form-label">Deskripsi singkat manfaat dan keunggulan:</label>
                            <textarea class="form-control" id="keunggulan" name="content" rows="4" required><?php echo htmlspecialchars($keunggulan['content'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Teks</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <!-- Tabel untuk Kelola Banner/Carousel -->
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-images me-1"></i>Kelola Banner Promosi</div>
                <div class="card-body">
                    <a href="tambah_banner.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle me-1"></i>Tambah Banner Baru</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr><th>Urutan</th><th>Gambar</th><th>Judul</th><th>Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php while($banner = $result_banners->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $banner['display_order']; ?></td>
                                <td><img src="../../uploads/banners/<?php echo htmlspecialchars($banner['image_url']); ?>" alt="Banner" width="100"></td>
                                <td><?php echo htmlspecialchars($banner['title']); ?></td>
                                <td>
                                    <a href="edit_banner.php?id=<?php echo $banner['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="hapus_banner.php?id=<?php echo $banner['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?');">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$conn->close();
require_once '../includes/footer.php';
?>