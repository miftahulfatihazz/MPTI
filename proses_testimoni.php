<?php
// File: proses_testimoni.php
require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = trim(htmlspecialchars($_POST['customer_name'] ?? ''));
    $content = trim(htmlspecialchars($_POST['content'] ?? ''));

    if (empty($customer_name) || empty($content)) {
        header("Location: pesan.php?status=error&pesan=Nama dan isi testimoni tidak boleh kosong.&kembali=javascript:history.back()");
        exit();
    }
    
    // Pastikan nama variabel di sini ($stmt)
    $stmt = $conn->prepare("INSERT INTO testimonials (customer_name, message, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("ss", $customer_name, $content);

    // dan nama variabel di sini ($stmt) sama persis
    if ($stmt->execute()) {
        header("Location: testimoni.php?status=sukses");
        exit();
    } else {
        header("Location: pesan.php?status=error&pesan=Gagal menyimpan testimoni. Silakan coba lagi.&kembali=javascript:history.back()");
        exit();
    }
} else {
    header("Location: testimoni.php");
    exit();
}
?>