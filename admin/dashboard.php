<?php
// File: admin/dashboard.php (dengan layout responsif yang lebih baik)

require_once 'includes/header.php';
require_once '../config/database.php';

// --- PENGUMPULAN DATA UNTUK DASHBOARD ---
$new_orders_res = $conn->query("SELECT COUNT(id) as total FROM orders WHERE status = 'menunggu_pembayaran'");
$new_orders_count = $new_orders_res->fetch_assoc()['total'];

$processing_orders_res = $conn->query("SELECT COUNT(id) as total FROM orders WHERE status = 'diproses'");
$processing_orders_count = $processing_orders_res->fetch_assoc()['total'];

$pending_testimonials_res = $conn->query("SELECT COUNT(id) as total FROM testimonials WHERE status = 'pending'");
$pending_testimonials_count = $pending_testimonials_res->fetch_assoc()['total'];

$partner_candidates_res = $conn->query("SELECT COUNT(id) as total FROM partners WHERE status = 'candidate'");
$partner_candidates_count = $partner_candidates_res->fetch_assoc()['total'];

// Data untuk Grafik
$daily_sales_query = "SELECT DATE(order_date) as tanggal, COUNT(id) as jumlah_pesanan FROM orders WHERE order_date >= CURDATE() - INTERVAL 6 DAY GROUP BY DATE(order_date) ORDER BY tanggal ASC";
$daily_res = $conn->query($daily_sales_query);
$daily_labels = [];
$daily_data = [];
while($row = $daily_res->fetch_assoc()) {
    $daily_labels[] = date('d M', strtotime($row['tanggal']));
    $daily_data[] = $row['jumlah_pesanan'];
}

// Data untuk Aktivitas Terbaru
$recent_orders_res = $conn->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ringkasan & Statistik</li>
    </ol>

    <!-- Baris untuk Kartu Ringkasan -->
    <div class="row">
        <!-- [PERUBAHAN] Kelas grid diubah untuk responsivitas yang lebih baik -->
        <!-- Kartu 1: Pesanan Baru Menunggu Verifikasi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $new_orders_count; ?></div>
                            <div class="text-white-75 small">Pesanan Baru Menunggu Verifikasi</div>
                        </div>
                        <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="orders/index.php?status=menunggu_pembayaran">Lihat Detail</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Kartu 2: Pesanan Perlu Diproses -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $processing_orders_count; ?></div>
                            <div class="text-white-75 small">Pesanan Perlu Diproses</div>
                        </div>
                        <i class="bi bi-box-seam fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="orders/index.php?status=diproses">Lihat Detail</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>

        <!-- Kartu 3: Testimoni Menunggu Persetujuan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $pending_testimonials_count; ?></div>
                            <div class="text-white-75 small">Testimoni Menunggu Persetujuan</div>
                        </div>
                        <i class="bi bi-chat-quote fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="testimonials/index.php">Lihat Detail</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
        
        <!-- Kartu 4: Pendaftar Mitra Baru -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fs-3 fw-bold"><?php echo $partner_candidates_count; ?></div>
                            <div class="text-white-75 small">Pendaftar Mitra Baru</div>
                        </div>
                        <i class="bi bi-person-plus fs-1 opacity-50"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="partners/index.php">Lihat Detail</a>
                    <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris untuk Grafik dan Aktivitas Terbaru -->
    <div class="row">
        <!-- [PERUBAHAN] Kelas grid diubah untuk responsivitas -->
        <!-- Kolom Grafik -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-bar-chart-line-fill me-1"></i>Grafik Jumlah Pesanan (7 Hari Terakhir)</div>
                <div class="card-body">
                    <canvas id="dailySalesChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>

        <!-- Kolom Aktivitas Terbaru -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header"><i class="bi bi-clock-history me-1"></i>Aktivitas Pesanan Terbaru</div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php if ($recent_orders_res->num_rows > 0): ?>
                            <?php while ($order = $recent_orders_res->fetch_assoc()): ?>
                                <a href="orders/detail.php?id=<?php echo $order['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span><strong>#<?php echo $order['id']; ?></strong> - <?php echo htmlspecialchars($order['customer_name']); ?></span>
                                    <span class="badge bg-secondary rounded-pill"><?php echo ucwords(str_replace('_', ' ', $order['status'])); ?></span>
                                </a>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="text-center p-3 text-muted">Tidak ada aktivitas terbaru.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPT untuk Chart.js (tidak berubah) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyLabels = <?php echo json_encode($daily_labels); ?>;
    const dailyData = <?php echo json_encode($daily_data); ?>;
    const ctx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySalesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: dailyData,
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
            plugins: { legend: { display: false } }
        }
    });
</script>

<?php
$conn->close();
require_once 'includes/footer.php';
?>