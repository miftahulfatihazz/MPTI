<?php
// File: produk.php

// Panggil header yang berisi menu navigasi dan koneksi DB
require_once 'includes/header.php';

// Ambil semua data produk dari database, urutkan berdasarkan nama
$result = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>

<!-- Menambahkan sedikit style khusus untuk halaman produk -->
<style>
    .product-card {
        /* Efek transisi halus saat mouse hover */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .product-card:hover {
        transform: translateY(-5px); /* Sedikit terangkat ke atas */
        box-shadow: 0 4px 20px rgba(0,0,0,0.15); /* Bayangan lebih jelas */
    }
    .product-image {
        height: 250px;
        width: 100%;
        object-fit: cover; /* Memastikan gambar mengisi area tanpa distorsi */
    }
    .btn-pesan {
        background-color: #fd7e14; 
        color: white;
        font-weight: bold;
        border: none;
    }
    .btn-pesan:hover {
        background-color: #fd7e14;
        color: white;
    }
</style>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1>Produk Unggulan Kami</h1>
        <p class="lead text-muted">Pilihan bakso ikan berkualitas, higienis, dan tanpa pengawet untuk keluarga Anda.</p>
    </div>

    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($product = $result->fetch_assoc()): ?>
            <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card h-100 product-card w-100">
                    <img src="uploads/products/<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text text-muted small flex-grow-1">
                            <?php echo htmlspecialchars($product['description']); ?>
                        </p>
                        <h4 class="text-end fw-bold mb-3">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></h4>
                        
                        <!-- 
                            Tombol ini akan mengarah ke halaman form pemesanan
                            dengan membawa ID produk di URL.
                        -->
                        <a href="form_pemesanan.php?id=<?php echo $product['id']; ?>" class="btn btn-pesan mt-auto">
                            <i class="bi bi-cart me-2"></i> Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <h4 class="alert-heading">Mohon Maaf!</h4>
                    <p>Saat ini belum ada produk yang tersedia. Silakan kembali lagi nanti.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>