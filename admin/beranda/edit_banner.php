<?php
// File: admin/beranda/edit_banner.php
require_once '../includes/header.php';
require_once '../../config/database.php';

// Ambil data banner yang akan diedit
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM carousels WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$banner = $result->fetch_assoc();

if (!$banner) {
    echo "<div class='alert alert-danger'>Banner tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit();
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Banner</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Beranda</a></li>
        <li class="breadcrumb-item active">Edit: <?php echo htmlspecialchars($banner['title']); ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-pencil-square me-1"></i>
            Formulir Edit Banner
        </div>
        <div class="card-body">
            <form action="proses_edit_banner.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $banner['id']; ?>">
                <input type="hidden" name="old_image" value="<?php echo $banner['image_url']; ?>">

                <div class="mb-3">
                    <label for="title" class="form-label">Judul Banner</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($banner['title']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Singkat (Opsional)</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($banner['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="display_order" class="form-label">Urutan Tampil</label>
                    <input type="number" class="form-control" id="display_order" name="display_order" value="<?php echo $banner['display_order']; ?>" required>
                </div>
                
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="image" class="form-label">Ganti Gambar Banner (Opsional)</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/jpeg, image/png, image/webp">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gambar Saat Ini:</label><br>
                        <img src="../../uploads/banners/<?php echo htmlspecialchars($banner['image_url']); ?>" alt="Gambar saat ini" class="img-fluid img-thumbnail">
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once '../includes/footer.php';
?>