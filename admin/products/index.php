<?php
// File: admin/products/index.php
require_once '../includes/header.php';
require_once '../../config/database.php';

// Ambil semua data produk dari database
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Produk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Kelola daftar produk yang dijual</li>
    </ol>

    <div class="mb-4">
        <a href="tambah.php" class="btn btn-success"><i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru</a>
    </div>

    <!-- Tampilan Grid Kartu Produk -->
    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card h-100">
                    <img src="../../uploads/products/<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?php 
                                // Potong deskripsi agar tidak terlalu panjang
                                $description = htmlspecialchars($product['description']);
                                echo strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                            ?>
                        </p>
                        <h6 class="card-subtitle mb-2 fw-bold">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h6>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="edit.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                            <a href="hapus.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus produk ini?');"><i class="bi bi-trash me-1"></i>Hapus</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Belum ada produk yang ditambahkan. Silakan klik tombol "Tambah Produk Baru" untuk memulai.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>