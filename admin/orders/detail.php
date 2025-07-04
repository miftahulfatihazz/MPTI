<?php
// File: admin/orders/detail.php (KODE YANG BENAR)
require_once '../includes/header.php';
require_once '../../config/database.php';

// Validasi dan ambil ID pesanan
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger m-4'>ID Pesanan tidak valid.</div>";
    require_once '../includes/footer.php';
    exit();
}

$order_id = (int)$_GET['id'];

// Query untuk mengambil data pesanan dan menggabungkannya dengan nama produk
$query = "SELECT o.*, p.name as product_name, p.image_url as product_image 
          FROM orders o
          LEFT JOIN products p ON o.product_id = p.id
          WHERE o.id = ?";
          
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<div class='alert alert-warning m-4'>Pesanan dengan ID #{$order_id} tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit();
}

// Helper function untuk warna badge status pesanan
function getOrderStatusBadge($status) {
    // Sesuaikan dengan ENUM di database Anda
    switch ($status) {
        case 'menunggu_pembayaran': return 'bg-warning text-dark';
        case 'diproses': return 'bg-info';
        case 'dikirim': return 'bg-primary';
        case 'selesai': return 'bg-success'; // Asumsi 'completed' di kode lama = 'selesai'
        case 'dibatalkan': return 'bg-danger'; // Asumsi 'cancelled' = 'dibatalkan'
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Pesanan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Pemesanan</a></li>
        <li class="breadcrumb-item active">Pesanan #<?php echo $order['id']; ?></li>
    </ol>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Status pesanan berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Kolom Kiri: Detail Pelanggan & Produk -->
        <div class="col-lg-7">
            <!-- Detail Produk yang Dipesan -->
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-box-seam me-1"></i>Produk Dipesan</div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <img src="../../uploads/products/<?php echo htmlspecialchars($order['product_image']); ?>" alt="<?php echo htmlspecialchars($order['product_name']); ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem;" class="me-3">
                        <div>
                            <h5 class="mb-1"><?php echo htmlspecialchars($order['product_name']); ?></h5>
                            <p class="mb-1 text-muted">Jumlah: <?php echo htmlspecialchars($order['quantity']); ?> pcs</p>
                            <p class="mb-0 fw-bold">Total Harga: Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Pelanggan -->
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-person-fill me-1"></i>Data Pelanggan</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nama Pelanggan:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></li>
                        <li class="list-group-item"><strong>No. WhatsApp:</strong> <a href="https://wa.me/<?php echo htmlspecialchars($order['customer_whatsapp']); ?>" target="_blank"><?php echo htmlspecialchars($order['customer_whatsapp']); ?></a></li>
                        <li class="list-group-item"><strong>Alamat Pengiriman:</strong><br><p class="mt-2 bg-light p-2 rounded mb-0"><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></p></li>
                        <li class="list-group-item"><strong>Tanggal Pesan:</strong> <?php echo date('d F Y, H:i', strtotime($order['order_date'])); ?></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Status, Bukti Bayar & Aksi -->
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><i class="bi bi-gear-fill me-1"></i>Status & Aksi</div>
                <div class="card-body">
                    <div class="mb-3">
                        Status Saat Ini: 
                        <span class="badge <?php echo getOrderStatusBadge($order['status']); ?> fs-6">
                            <?php echo ucwords(str_replace('_', ' ', $order['status'])); ?>
                        </span>
                    </div>
                    <hr>

                    <!-- Bukti Pembayaran -->
                    <h5 class="card-title">Bukti Pembayaran</h5>
                    <?php if (!empty($order['payment_proof'])): ?>
                        <a href="../../uploads/proofs/<?php echo htmlspecialchars($order['payment_proof']); ?>" target="_blank">
                            <img src="../../uploads/proofs/<?php echo htmlspecialchars($order['payment_proof']); ?>" class="img-fluid rounded" alt="Bukti Pembayaran">
                        </a>
                    <?php else: ?>
                        <div class="alert alert-warning">Pelanggan belum mengunggah bukti pembayaran.</div>
                    <?php endif; ?>
                    
                    <hr>
                    <!-- Form Update Status -->
                    <h5 class="card-title mt-4">Ubah Status Pesanan</h5>
                    <!-- PERUBAHAN DI SINI: action diubah ke update_status.php -->
                   <form action="update_status.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <div class="input-group">
                            <select name="status" class="form-select">
                                <option value="menunggu_pembayaran" <?php if($order['status'] == 'menunggu_pembayaran') echo 'selected'; ?>>Menunggu Pembayaran</option>
                                <option value="diproses" <?php if($order['status'] == 'diproses') echo 'selected'; ?>>Diproses</option>
                                <option value="dikirim" <?php if($order['status'] == 'dikirim') echo 'selected'; ?>>Dikirim</option>
                                <option value="selesai" <?php if($order['status'] == 'selesai') echo 'selected'; ?>>Selesai</option>
                                <option value="dibatalkan" <?php if($order['status'] == 'dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                            </select>
                            <button type="submit" name="action" value="update_status" class="btn btn-primary"><i class="bi bi-box-arrow-in-down me-1"></i> Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>