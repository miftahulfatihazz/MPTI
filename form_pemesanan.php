<?php
// File: form_pemesanan.php (Versi dengan Metode Pembayaran di Ringkasan)

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if (empty($cart_items)) {
    header('Location: keranjang.php');
    exit();
}

require_once 'includes/header.php';

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<div class="container py-5">
    <!-- Form dimulai di sini, membungkus seluruh baris -->
    <form action="proses_pemesanan.php" method="POST">
        <div class="row">
            <!-- Kolom Kiri: Informasi Pelanggan -->
            <div class="col-lg-7">
                <h1 class="mb-4">Formulir Pemesanan</h1>
                <p class="text-muted mb-4">Lengkapi data Anda untuk melanjutkan pesanan. Pesanan akan dikonfirmasi melalui WhatsApp.</p>
                
                <div class="card">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Informasi Pelanggan</h5>
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_whatsapp" class="form-label">Nomor WhatsApp Aktif</label>
                            <input type="tel" class="form-control" id="customer_whatsapp" name="customer_whatsapp" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Alamat Lengkap Pengiriman</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Ringkasan & Metode Pembayaran -->
            <div class="col-lg-5">
                <div class="card bg-light sticky-top" style="top: 100px;">
                    <div class="card-header">
                        <h5 class="mb-0">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <!-- Ringkasan Item Pesanan -->
                        <ul class="list-group list-group-flush">
                            <?php foreach ($cart_items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-light">
                                    <div>
                                        <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?> x <?php echo $item['quantity']; ?></small>
                                    </div>
                                    <span class="text-muted">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <!-- [PINDAH KE SINI] Metode Pembayaran -->
                        <div class="mt-4">
                            <h6 class="mb-3 fw-bold">Pilih Metode Pembayaran</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_transfer" value="transfer_bank" checked>
                                <label class="form-check-label" for="payment_transfer">Transfer Bank</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod">
                                <label class="form-check-label" for="payment_cod">Bayar di Tempat (COD)</label>
                            </div>
                        </div>

                        <!-- Total dan Tombol Submit -->
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold">Total</span>
                                <strong class="fs-5">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></strong>
                            </div>
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="bi bi-whatsapp me-2"></i> Konfirmasi & Kirim Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> <!-- Form ditutup di sini -->
</div>

<?php require_once 'includes/footer.php'; ?>