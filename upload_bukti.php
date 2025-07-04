<?php
require 'config/database.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = (int)($_POST['order_id'] ?? 0);
    $kembali_link = "konfirmasi_pemesanan.php?order_id=" . $order_id;

    if ($order_id <= 0) { 
        header("Location: pesan.php?status=error&pesan=ID Pesanan tidak valid.&kembali=index.php");
        exit();
    }
    if (!isset($_FILES["payment_proof"]) || $_FILES["payment_proof"]["error"] != 0) {
        header("Location: pesan.php?status=error&pesan=Gagal upload file. Pastikan Anda memilih file.&kembali=" . urlencode($kembali_link));
        exit();
    }
    
    // ... (kode validasi file sama) ...
    // Jika validasi gagal:
    // header("Location: pesan.php?status=error&pesan=Format file tidak diizinkan.&kembali=" . urlencode($kembali_link));
    // exit();

    // ... (kode upload dan update DB) ...
    if ($stmt->execute()) {
        header("Location: konfirmasi_pemesanan.php?order_id=" . $order_id . "&status=sukses_upload");
        exit();
    }
}
?>