<?php
// File: admin/orders/index.php
require_once '../includes/header.php';
require_once '../../config/database.php';

// --- Logika untuk Filter ---
$filter_status = $_GET['status'] ?? 'semua'; // Default 'semua'

// Bangun query dasar
$sql = "SELECT * FROM orders";

// Tambahkan kondisi WHERE jika filter dipilih
if ($filter_status != 'semua') {
    $sql .= " WHERE status = ?";
}

$sql .= " ORDER BY order_date DESC";

// Siapkan dan eksekusi query
$stmt = $conn->prepare($sql);
if ($filter_status != 'semua') {
    $stmt->bind_param("s", $filter_status);
}
$stmt->execute();
$result = $stmt->get_result();


// Helper function untuk warna badge status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'menunggu_pembayaran': return 'bg-warning text-dark';
        case 'diproses': return 'bg-primary';
        case 'dikirim': return 'bg-info text-dark';
        case 'selesai': return 'bg-success';
        case 'dibatalkan': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Pemesanan</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daftar pesanan masuk</li>
    </ol>

    <!-- Form Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-filter me-1"></i>
            Filter Pesanan
        </div>
        <div class="card-body">
            <form method="GET" action="index.php">
                <div class="row">
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="semua" <?php if ($filter_status == 'semua') echo 'selected'; ?>>Semua Status</option>
                            <option value="menunggu_pembayaran" <?php if ($filter_status == 'menunggu_pembayaran') echo 'selected'; ?>>Menunggu Pembayaran</option>
                            <option value="diproses" <?php if ($filter_status == 'diproses') echo 'selected'; ?>>Diproses</option>
                            <option value="dikirim" <?php if ($filter_status == 'dikirim') echo 'selected'; ?>>Dikirim</option>
                            <option value="selesai" <?php if ($filter_status == 'selesai') echo 'selected'; ?>>Selesai</option>
                            <option value="dibatalkan" <?php if ($filter_status == 'dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Tabel Daftar Pesanan -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-table me-1"></i>
            Hasil
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID Pesanan</th>
                            <th>Nama Pemesan</th>
                            <th>No. WhatsApp</th>
                            <th>Tanggal Pesan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($order = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                <td><?php echo htmlspecialchars($order['customer_whatsapp']); ?></td>
                                <td><?php echo date('d M Y, H:i', strtotime($order['order_date'])); ?></td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($order['status']); ?>">
                                        <?php echo ucwords(str_replace('_', ' ', $order['status'])); ?>
                                    </span>
                                </td>
                                <td><a href="detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">Lihat Detail</a></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada pesanan yang cocok dengan filter ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once '../includes/footer.php';
?>