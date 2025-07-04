<?php
require 'config/database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (kode sanitasi sama seperti sebelumnya) ...
    $customer_name = trim(htmlspecialchars($_POST['customer_name'] ?? ''));
    $customer_address = trim(htmlspecialchars($_POST['customer_address'] ?? ''));
    $customer_whatsapp = preg_replace('/[^0-9]/', '', $_POST['customer_whatsapp'] ?? '');
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 0);
    $price_per_item = (float)($_POST['price_per_item'] ?? 0.0);

    $errors = [];
    if (empty($customer_name)) { $errors[] = "Nama tidak boleh kosong."; }
    if (empty($customer_address)) { $errors[] = "Alamat tidak boleh kosong."; }
    if (empty($customer_whatsapp) || strlen($customer_whatsapp) < 10) { $errors[] = "Nomor WhatsApp tidak valid."; }
    // ... (validasi lain) ...

    if (!empty($errors)) {
        // --- PERUBAHAN DI SINI ---
        $pesan_error = implode("<br>", $errors);
        header("Location: pesan.php?status=error&pesan=" . urlencode($pesan_error) . "&kembali=javascript:history.back()");
        exit();
    }

    // ... (kode proses ke database tetap sama) ...
    try {
        // ...
        header("Location: konfirmasi_pemesanan.php?order_id=" . $order_id);
        exit();
    } catch (mysqli_sql_exception $exception) {
        // --- PERUBAHAN DI SINI ---
        $pesan_error = "Sistem sedang mengalami gangguan. Silakan coba lagi nanti.";
        header("Location: pesan.php?status=error&pesan=" . urlencode($pesan_error) . "&kembali=index.php");
        exit();
    }
}
?>