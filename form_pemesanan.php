<?php
// File: form_pemesanan.php
require_once 'includes/header.php';

// Cek apakah ada ID produk yang dikirim via URL
if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit();
}
$product_id = (int)$_GET['id'];

// Ambil data produk dari database
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<div class='alert alert-danger'>Produk tidak ditemukan.</div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Formulir Pemesanan</h1>
            
            <!-- Tampilkan produk yang dipesan -->
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="uploads/products/<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted">Harga Satuan: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pengisian Data -->
            <form action="proses_pemesanan.php" method="POST">
                <!-- Input tersembunyi untuk mengirim ID produk dan harganya -->
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="price_per_item" value="<?php echo $product['price']; ?>">

                <div class="card">
                    <div class="card-header">
                        <h5>Lengkapi Data Anda</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Alamat Lengkap Pengiriman</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="customer_whatsapp" class="form-label">Nomor WhatsApp Aktif</label>
                            <input type="tel" class="form-control" id="customer_whatsapp" name="customer_whatsapp" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah Pesanan</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Lanjutkan ke Pembayaran</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
$stmt->close();
require_once 'includes/footer.php';
?>