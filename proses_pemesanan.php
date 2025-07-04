<?php
require 'config/database.php';

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Sanitasi dan Validasi Input
    $customer_name = trim(htmlspecialchars($_POST['customer_name'] ?? ''));
    $customer_address = trim(htmlspecialchars($_POST['customer_address'] ?? ''));
    $customer_whatsapp = preg_replace('/[^0-9]/', '', $_POST['customer_whatsapp'] ?? '');
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $price_per_item = (float)($_POST['price_per_item'] ?? 0.0);

    // Cek jika ada input yang kosong atau tidak valid
    $errors = [];
    if (empty($customer_name)) { $errors[] = "Nama tidak boleh kosong."; }
    if (empty($customer_address)) { $errors[] = "Alamat tidak boleh kosong."; }
    if (empty($customer_whatsapp) || strlen($customer_whatsapp) < 10) { $errors[] = "Nomor WhatsApp tidak valid."; }
    if ($product_id === 0) { $errors[] = "Produk tidak valid."; }
    if ($quantity <= 0) { $errors[] = "Jumlah pesanan harus lebih dari 0."; }
    if ($price_per_item <= 0) { $errors[] = "Harga produk tidak valid."; }

    if (!empty($errors)) {
        // Jika ada error, kembalikan ke halaman sebelumnya dengan pesan error
        $pesan_error = implode("<br>", $errors);
        // Menggunakan javascript:history.back() adalah ide bagus
        header("Location: pesan.php?status=error&pesan=" . urlencode($pesan_error) . "&kembali=javascript:history.back()");
        exit();
    }

    // 2. Hitung Total Harga
    $total_price = $quantity * $price_per_item;

    // 3. Proses Simpan ke Database
    try {
        // Siapkan query INSERT
        $sql = "INSERT INTO orders (customer_name, customer_address, customer_whatsapp, product_id, quantity, total_price, order_date) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conn->prepare($sql);

        // Bind parameter ke query
        // Tipe data: s=string, i=integer, d=double
        $stmt->bind_param("sssidd", $customer_name, $customer_address, $customer_whatsapp, $product_id, $quantity, $total_price);
        
        // Eksekusi query
        $stmt->execute();

        // === INI BAGIAN PALING PENTING ===
        // Ambil ID dari pesanan yang BARU saja dibuat
        $order_id = $conn->insert_id;
        
        // Tutup statement
        $stmt->close();

        // 4. Alihkan ke Halaman Konfirmasi DENGAN menyertakan ID pesanan
        header("Location: konfirmasi_pemesanan.php?order_id=" . $order_id);
        exit();

    } catch (mysqli_sql_exception $exception) {
        // Jika terjadi error pada database
        $pesan_error = "Sistem sedang mengalami gangguan. Silakan coba lagi nanti.";
        // Alihkan ke halaman pesan error umum
        header("Location: pesan.php?status=error&pesan=" . urlencode($pesan_error) . "&kembali=index.php");
        exit();
    }
} else {
    // Jika halaman diakses langsung tanpa POST, alihkan ke beranda
    header("Location: index.php");
    exit();
}
?>