<?php
// File: admin/statistics.php
require_once 'includes/header.php';
require_once '../config/database.php';

// --- PENGUMPULAN DATA UNTUK STATISTIK ---

// 1. Data untuk KPI Cards
// Hanya hitung pesanan yang sudah terverifikasi atau selesai
$valid_statuses = "'diproses', 'dikirim', 'selesai'";

// Total Pendapatan
$revenue_res = $conn->query("
    SELECT SUM(od.quantity * od.price_per_item) as total_revenue
    FROM order_details od
    JOIN orders o ON od.order_id = o.id
    WHERE o.status IN ($valid_statuses)
");
$total_revenue = $revenue_res->fetch_assoc()['total_revenue'] ?? 0;

// Total Pesanan Valid
$orders_res = $conn->query("SELECT COUNT(id) as total_orders FROM orders WHERE status IN ($valid_statuses)");
$total_orders_count = $orders_res->fetch_assoc()['total_orders'] ?? 0;

// Rata-rata Nilai Pesanan
$average_order_value = ($total_orders_count > 0) ? $total_revenue / $total_orders_count : 0;

// 2. Data untuk Grafik Penjualan Harian (7 hari terakhir)
$daily_sales_query = "
    SELECT DATE(order_date) as tanggal, COUNT(id) as jumlah_pesanan
    FROM orders
    WHERE order_date >= CURDATE() - INTERVAL 6 DAY AND status IN ($valid_statuses)
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

// 3. Data untuk Grafik Produk Paling Laku (Top 5)
$top_products_query = "
    SELECT p.name, SUM(od.quantity) as total_terjual
    FROM order_details od
    JOIN products p ON od.product_id = p.id
    JOIN orders o ON od.order_id = o.id
    WHERE o.status IN ($valid_statuses)
    GROUP BY p.id, p.name
    ORDER BY total_terjual DESC
    LIMIT 5
";
$top_products_res = $conn->query($top_products_query);
$product_labels = [];
$product_data = [];
$bestselling_product_name = "Tidak ada";
if ($top_products_res->num_rows > 0) {
    $first_row = true;
    while($row = $top_products_res->fetch_assoc()) {
        if ($first_row) {
            $bestselling_product_name = $row['name'];
            $first_row = false;
        }
        $product_labels[] = $row['name'];
        $product_data[] = $row['total_terjual'];
    }
}

$conn->close();
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Statistik</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Laporan Visual Penjualan</li>
    </ol>

    <!-- Baris untuk KPI Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-primary border-4 h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Total Pendapatan</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-cash-coin fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Total Pesanan Sukses</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo $total_orders_count; ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-cart-check-fill fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Rata-rata Per Pesanan</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">Rp <?php echo number_format($average_order_value, 0, ',', '.'); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-graph-up-arrow fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-warning border-4 h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Produk Terlaris</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"><?php echo htmlspecialchars($bestselling_product_name); ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-star-fill fs-2 text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris untuk Grafik -->
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary">Grafik Pesanan Harian (7 Hari Terakhir)</h6></div>
                <div class="card-body"><canvas id="dailySalesChart"></canvas></div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 fw-bold text-primary">Top 5 Produk Terlaris</h6></div>
                <div class="card-body"><canvas id="topProductsChart"></canvas></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data dari PHP
    const dailyLabels = <?php echo json_encode($daily_labels); ?>;
    const dailyData = <?php echo json_encode($daily_data); ?>;
    const productLabels = <?php echo json_encode($product_labels); ?>;
    const productData = <?php echo json_encode($product_data); ?>;

    // Grafik Penjualan Harian
    new Chart(document.getElementById('dailySalesChart'), {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: dailyData,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: { scales: { y: { ticks: { stepSize: 1 } } } }
    });

    // Grafik Produk Paling Laku
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: productLabels,
            datasets: [{
                label: 'Total Terjual',
                data: productData,
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
            }]
        },
        options: { indexAxis: 'y', plugins: { legend: { display: false } } }
    });
</script>

<?php require_once 'includes/footer.php'; ?>