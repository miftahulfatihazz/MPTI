<?php
session_start();
require_once 'config/database.php';

// ===================================================================
// BAGIAN 1: VALIDASI DATA
// ===================================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Ambil data dari form
$customer_name = trim($_POST['customer_name'] ?? '');
$customer_address = trim($_POST['customer_address'] ?? '');
$customer_whatsapp = preg_replace('/[^0-9]/', '', $_POST['customer_whatsapp'] ?? '');
$payment_method = $_POST['payment_method'] ?? ''; // Ambil metode pembayaran
$cart_items = $_SESSION['cart'] ?? [];

// Daftar metode pembayaran yang diizinkan (untuk keamanan)
$allowed_methods = ['transfer_bank', 'cod'];

// Validasi data, termasuk metode pembayaran
if (empty($customer_name) || empty($customer_address) || empty($customer_whatsapp) || empty($cart_items) || empty($payment_method) || !in_array($payment_method, $allowed_methods)) {
    header("Location: form_pemesanan.php?status=error&pesan=Data+tidak+lengkap+atau+metode+pembayaran+tidak+valid.");
    exit();
}

// ===================================================================
// BAGIAN 2: PERSIAPAN DATA (TIDAK BERUBAH)
// ===================================================================

$total_price = 0;
$order_details_to_insert = [];

foreach ($cart_items as $product_id => $item) {
    $total_price += $item['price'] * $item['quantity'];
    $order_details_to_insert[] = [
        'product_id' => $product_id,
        'name'       => $item['name'],
        'quantity'   => $item['quantity'],
        'price'      => $item['price']
    ];
}

// ===================================================================
// BAGIAN 3: EKSEKUSI DATABASE (PASTIKAN BAGIAN INI BENAR)
// ===================================================================

$conn->begin_transaction();
$order_id = 0;

try {
    // Siapkan query INSERT ke tabel 'orders' dengan kolom 'payment_method'
    $stmt_order = $conn->prepare("INSERT INTO orders (customer_name, customer_address, customer_whatsapp, total_price, payment_method, status) VALUES (?, ?, ?, ?, ?, 'menunggu_pembayaran')");
    
    // Bind parameter, 's' baru untuk payment_method. Total tipe data jadi "sssss"
    $stmt_order->bind_param("sssss", $customer_name, $customer_address, $customer_whatsapp, $total_price, $payment_method);
    
    $stmt_order->execute();
    $order_id = $conn->insert_id;

    // Bagian insert ke 'order_details' tidak berubah
    $stmt_items = $conn->prepare("INSERT INTO order_details (order_id, product_id, product_name, quantity, price_per_item) VALUES (?, ?, ?, ?, ?)");
    foreach ($order_details_to_insert as $detail) {
        $stmt_items->bind_param("iisid", $order_id, $detail['product_id'], $detail['name'], $detail['quantity'], $detail['price']);
        $stmt_items->execute();
    }
    
    $conn->commit();

} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    // Untuk development, tampilkan error. Untuk produksi, log error dan tampilkan pesan umum.
    die("GAGAL MENYIMPAN KE DATABASE. Error: " . $e->getMessage());
}

// ===================================================================
// BAGIAN 4: PENGALIHAN SETELAH SUKSES
// ===================================================================

if ($order_id > 0) {
    unset($_SESSION['cart']);

    if ($payment_method === 'transfer_bank') {
        // Arahkan ke halaman konfirmasi/pembayaran
        header("Location: konfirmasi_pemesanan.php?order_id=" . $order_id);
        exit();
    } elseif ($payment_method === 'cod') {
        // Jika COD, arahkan ke halaman utama dengan pesan sukses COD
        header("Location: index.php?status=cod_sukses");
        exit();
    }
    
} else {
    // Jika gagal membuat pesanan
    header("Location: form_pemesanan.php?status=error&pesan=Gagal+membuat+pesanan.");
    exit();
}
?>