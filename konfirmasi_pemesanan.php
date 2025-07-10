<?php
// File: konfirmasi_pemesanan.php (BISA MENANGANI TRANSFER & COD)
require_once 'includes/header.php';

// 1. Validasi dan ambil ID pesanan
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    echo "<div class='alert alert-danger m-4'>ID Pesanan tidak valid.</div>";
    require_once 'includes/footer.php';
    exit();
}
$order_id = (int)$_GET['order_id'];

// 2. Ambil data pesanan, TERMASUK 'payment_method'
$stmt = $conn->prepare("SELECT id, customer_name, total_price, payment_proof, payment_method FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "<div class='alert alert-warning m-4'>Pesanan dengan ID #{$order_id} tidak ditemukan.</div>";
    require_once 'includes/footer.php';
    exit();
}
?>

<!-- CSS Kustom -->
<style>
    .payment-amount { color: #fd7e14; font-weight: 700; }
    .card-step { position: relative; overflow: hidden; }
    .card-step::before { position: absolute; top: -20px; right: -20px; font-size: 6rem; font-weight: 800; color: rgba(0, 0, 0, 0.05); z-index: 0; }
    .card-step.step-1::before { content: '1'; }
    .card-step.step-2::before { content: '2'; }
    .card-body, .card-header { position: relative; z-index: 1; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Pesan Sukses Awal (Sama untuk semua) -->
            <div class="text-center mb-5">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                <h2 class="mt-3">Pemesanan Berhasil!</h2>
                <p class="lead text-muted">Terima kasih, <?php echo htmlspecialchars($order['customer_name']); ?>. Pesanan Anda dengan nomor <strong>#<?php echo $order['id']; ?></strong> telah kami terima.</p>
            </div>
            
            <!-- ==================================================================== -->
            <!-- LOGIKA UTAMA: Tampilkan konten berdasarkan metode pembayaran -->
            <!-- ==================================================================== -->
            
            <?php if ($order['payment_method'] == 'cod'): ?>
                <!-- TAMPILAN KHUSUS UNTUK COD -->
                <div class="card text-center shadow-sm">
                    <div class="card-header text-white" style="background-color: #fd7e14;">
                        <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Pesanan Anda Sedang Disiapkan</h5>
                    </div>
                    <div class="card-body p-4">
                        <p class="fs-5">Mohon siapkan uang pas sejumlah:</p>
                        <h1 class="display-4 payment-amount">
                            Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?>
                        </h1>
                        <p class="mt-3">Pembayaran akan dilakukan langsung kepada kurir kami saat pesanan tiba di alamat Anda.</p>
                        <hr>
                        <a href="index.php" class="btn btn-primary">Kembali ke Beranda</a>
                    </div>
                </div>

            <?php else: // Asumsikan selain COD adalah Transfer Bank ?>
                <!-- TAMPILAN UNTUK TRANSFER BANK (yang sudah ada sebelumnya) -->

                <?php if (!empty($order['payment_proof'])): ?>
                    <!-- Tampilan JIKA BUKTI SUDAH DIUNGGAH -->
                    <div class="alert alert-success text-center p-4 shadow-sm">
                        <h4 class="alert-heading"><i class="bi bi-patch-check-fill"></i> Konfirmasi Diterima!</h4>
                        <p>Terima kasih, kami telah menerima bukti pembayaran Anda. Pesanan Anda akan segera kami verifikasi dan proses.</p>
                        <hr>
                        <a href="index.php" class="btn btn-primary mt-3">Kembali ke Beranda</a>
                    </div>
                <?php else: ?>
                    <!-- Tampilan JIKA BUKTI BELUM DIUNGGAH -->
                    <div class="card mb-4 shadow-sm card-step step-1">
                        <div class="card-header text-white" style="background-color: #fd7e14;"><h5 class="mb-0"><i class="bi bi-wallet2 me-2"></i>Langkah 1: Lakukan Pembayaran</h5></div>
                        <div class="card-body">
                            <p class="text-center">Silakan lakukan pembayaran sejumlah:</p>
                            <h1 class="text-center display-4 payment-amount">Rp <?php echo number_format($order['total_price'], 0, ',', '.'); ?></h1>
                            <p class="text-center text-muted small">Pastikan jumlah yang ditransfer sesuai hingga digit terakhir.</p>
                            <hr>
                            <p>Transfer ke salah satu rekening berikut:</p>
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div><strong>Bank BCA:</strong> <span id="bca-account">1234567890</span> (a.n. Baso Sinar Bahari)</div>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('#bca-account')"><i class="bi bi-clipboard"></i> Salin</button>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div><strong>Bank Mandiri:</strong> <span id="mandiri-account">0987654321</span> (a.n. Baso Sinar Bahari)</div>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard('#mandiri-account')"><i class="bi bi-clipboard"></i> Salin</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card shadow-sm card-step step-2">
                        <div class="card-header text-white" style="background-color: #fd7e14;"><h5 class="mb-0"><i class="bi bi-cloud-arrow-up-fill me-2"></i>Langkah 2: Konfirmasi Pembayaran</h5></div>
                        <div class="card-body">
                            <p>Setelah melakukan pembayaran, mohon unggah bukti transfer Anda di bawah ini untuk kami verifikasi.</p>
                            <form action="upload_bukti.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                                <div class="mb-3"><label for="payment_proof" class="form-label">Pilih Bukti Pembayaran (JPG, PNG, PDF)</label><input class="form-control" type="file" name="payment_proof" id="payment_proof" required></div>
                                <button type="submit" class="btn w-100 btn-lg text-white" style="background-color: #fd7e14;"><i class="bi bi-send-check-fill me-2"></i> Unggah & Selesaikan Konfirmasi</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- JavaScript untuk fungsi 'Salin' -->
<script>
function copyToClipboard(element) {
    var text = document.querySelector(element).innerText;
    navigator.clipboard.writeText(text).then(function() {
        alert("Nomor rekening " + text + " berhasil disalin!");
    }, function(err) {
        alert("Gagal menyalin nomor rekening.");
    });
}
</script>

<?php
require_once 'includes/footer.php';
?>