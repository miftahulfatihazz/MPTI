<?php
// File: admin/products/edit.php
require_once '../includes/header.php';
require_once '../../config/database.php';

// Ambil data produk yang akan diedit
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<div class='alert alert-danger'>Produk tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit();
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Produk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Produk</a></li>
        <li class="breadcrumb-item active">Edit: <?php echo htmlspecialchars($product['name']); ?></li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-pencil-square me-1"></i>
            Formulir Edit Produk
        </div>
        <div class="card-body">
            <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="old_image" value="<?php echo $product['image_url']; ?>">

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="price" name="price" value="<?php echo $product['price']; ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="image" class="form-label">Ganti Gambar Produk (Opsional)</label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/jpeg, image/png">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Gambar Saat Ini:</label><br>
                        <img src="../../uploads/products/<?php echo htmlspecialchars($product['image_url']); ?>" alt="Gambar saat ini" class="img-thumbnail" width="150">
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