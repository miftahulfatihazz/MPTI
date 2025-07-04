<?php
// File: admin/dashboard.php

// Panggil header. Ini harus paling atas.
require_once 'includes/header.php'; 
require_once '../config/database.php'; // Path ke DB dari root admin

// --- PENGUMPULAN DATA UNTUK DASHBOARD ---

// 1. Data untuk Summary Cards
$new_orders_res = $conn->query("SELECT COUNT(id) as total FROM orders WHERE status = 'menunggu_pembayaran'");
$new_orders_count = $new_orders_res->fetch_assoc()['total'];

$processing_orders_res = $conn->query("SELECT COUNT(id) as total FROM orders WHERE status = 'diproses'");
$processing_orders_count = $processing_orders_res->fetch_assoc()['total'];

$pending_testimonials_res = $conn->query("SELECT COUNT(id) as total FROM testimonials WHERE status = 'pending'");
$pending_testimonials_count = $pending_testimonials_res->fetch_assoc()['total'];

$partner_candidates_res = $conn->query("SELECT COUNT(id) as total FROM partners WHERE status = 'candidate'");
$partner_candidates_count = $partner_candidates_res->fetch_assoc()['total'];

// 2. Data untuk Grafik Penjualan Harian (7 hari terakhir)
$daily_sales_query = "
    SELECT DATE(order_date) as tanggal, COUNT(id) as jumlah_pesanan
    FROM orders
    WHERE order_date >= CURDATE() - INTERVAL 6 DAY
    GROUP BY DATE(order_date)
    ORDER BY tanggal ASC
";
$daily_res = $conn->query($daily_sales_query);
$daily_labels = [];
$daily_data = [];
while($row = $daily_res->fetch_assoc()) {
    $daily_labels[] = date('d M', strtotime($row['tanggal']));
    $daily_data[] = $row['jumlah_pesanan'];
}

// 3. Data untuk Aktivitas Terbaru (5 pesanan terakhir)
$recent_orders_res = $conn->query("SELECT * FROM orders ORDER BY order_date DESC LIMIT 5");

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ringkasan & Statistik</li>
    </ol>

    <!-- Baris untuk Summary Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-warning text-white h-100"> <!-- Tambahkan h-100 -->
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <i class="bi bi-clock-history fs-1"></i>
                    <div class="text-end">
                        <div class="fs-3 fw-bold"><?php echo $new_orders_count; ?></div>
                        <div>Pesanan Baru Menunggu Verifikasi</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="orders/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-primary text-white h-100"> <!-- Tambahkan h-100 -->
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <i class="bi bi-box-seam fs-1"></i>
                    <div class="text-end">
                        <div class="fs-3 fw-bold"><?php echo $processing_orders_count; ?></div>
                        <div>Pesanan Perlu Diproses</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="orders/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-success text-white h-100"> <!-- Tambahkan h-100 -->
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <i class="bi bi-chat-left-text fs-1"></i>
                    <div class="text-end">
                        <div class="fs-3 fw-bold"><?php echo $pending_testimonials_count; ?></div>
                        <div>Testimoni Menunggu Persetujuan</div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link" href="testimonials/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="bi bi-arrow-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card bg-danger text-white h-100"> <!-- Tambahkan h-100 -->
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <i class="bi bi-person-plus fs-1"></i>
                    <div class="text-end">
                        <div class="fs-3 fw-bold"><?php echo $partner_candidates_count; ?></div>
                        <div>Pendaftar Mitra Baru</div>
                    </div>
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
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-bar-chart-line-fill me-1"></i>
                    Grafik Jumlah Pesanan (7 Hari Terakhir)
                </div>
                <div class="card-body"><canvas id="dailySalesChart" width="100%" height="40"></canvas></div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-clock-fill me-1"></i>
                    Aktivitas Pesanan Terbaru
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php if ($recent_orders_res->num_rows > 0): ?>
                            <?php while ($order = $recent_orders_res->fetch_assoc()): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="orders/detail.php?id=<?php echo $order['id']; ?>" class="text-decoration-none">
                                            <strong>#<?php echo $order['id']; ?></strong> - <?php echo htmlspecialchars($order['customer_name']); ?>
                                        </a>
                                    </div>
                                    <span class="badge bg-info rounded-pill"><?php echo str_replace('_', ' ', $order['status']); ?></span>
                                </li>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <li class="list-group-item">Tidak ada aktivitas terbaru.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- SCRIPT untuk Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari PHP diubah ke format JSON untuk JavaScript
    const dailyLabels = <?php echo json_encode($daily_labels); ?>;
    const dailyData = <?php echo json_encode($daily_data); ?>;

    // Inisialisasi Grafik
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
                tension: 0.4, // Membuat garis lebih melengkung
                fill: true
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Hanya tampilkan angka bulat di sumbu Y
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Sembunyikan legenda
                }
            }
        }
    });
</script>

<?php
$conn->close();
require_once 'includes/footer.php'; // Panggil footer
?>