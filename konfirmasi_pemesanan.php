<?php
// File: konfirmasi_pemesanan.php
require_once 'includes/header.php';

// Cek apakah 'order_id' ada di URL dan bukan string kosong
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "ID pesanan tidak disediakan.";
    // Mungkin lebih baik redirect ke halaman utama
    // header("Location: index.php");
    exit();
}

// Ambil order_id dan pastikan itu adalah angka
$order_id = (int)$_GET['order_id'];

// Jika setelah diubah jadi angka nilainya 0, berarti tidak valid
if ($order_id === 0) {
    echo "ID pesanan tidak valid.";
    exit;
}

// Ambil data pesanan
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "Pesanan tidak ditemukan.";
    exit; // ← WAJIB!
}
?>

<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses_upload'): ?>
                <div class="alert alert-success">
                    <h4>Terima Kasih!</h4>
                    <p>Bukti pembayaran Anda telah berhasil diunggah. Pesanan Anda akan segera kami verifikasi dan proses.</p>
                    
                    <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
            <?php else: ?>
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h2 class="mt-3">Pemesanan Berhasil!</h2>
                <p class="lead">Terima kasih, <?php echo htmlspecialchars($order['customer_name']); ?>. Pesanan Anda dengan nomor <strong>#<?php echo $order['id']; ?></strong> telah kami terima.</p>
                <?php
                if ($order) {
                    echo "Terima kasih, " . htmlspecialchars($order['customer_name']) . ". ";
                    echo "Pesanan Anda dengan nomor #" . htmlspecialchars($order['id']) . " telah kami terima.";
                } else {
                    echo "Pesanan tidak ditemukan.";
                }
                ?>
                

                <hr>

                <div class="card text-start">
                    <div class="card-header">
                        <h5>Langkah Selanjutnya: Lakukan Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <p>Silakan lakukan pembayaran melalui salah satu metode di bawah ini:</p>
                        <ul>
                            <li><strong>Transfer Bank:</strong> BCA 123456789 a.n. Baso Sinar Bahari</li>
                            <li><strong>QRIS:</strong> [Di sini Anda bisa menaruh gambar QRIS]</li>
                        </ul>
                        <p>Setelah melakukan pembayaran, mohon unggah bukti transfer Anda di bawah ini.</p>
                        
                        <form action="upload_bukti.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label">Unggah Bukti Pembayaran</label>
                                <input class="form-control" type="file" name="payment_proof" id="payment_proof" required>
                            </div>
                            <button type="submit" class="btn btn-success">Konfirmasi Pembayaran</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>