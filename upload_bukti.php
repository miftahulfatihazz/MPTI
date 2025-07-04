<?php
require 'config/database.php';

// Pastikan request adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Validasi Input Dasar
    $order_id = (int)($_POST['order_id'] ?? 0);
    $kembali_link = "konfirmasi_pemesanan.php?order_id=" . $order_id;

    if ($order_id <= 0) { 
        header("Location: pesan.php?status=error&pesan=ID Pesanan tidak valid.&kembali=index.php");
        exit();
    }

    if (!isset($_FILES["payment_proof"]) || $_FILES["payment_proof"]["error"] != 0) {
        header("Location: pesan.php?status=error&pesan=Gagal mengunggah file. Pastikan Anda sudah memilih file.&kembali=" . urlencode($kembali_link));
        exit();
    }

    // 2. Validasi File
    $file = $_FILES["payment_proof"];
    $allowed_extensions = ["jpg", "jpeg", "png", "pdf"];
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        header("Location: pesan.php?status=error&pesan=Format file tidak diizinkan. Hanya JPG, JPEG, PNG, dan PDF yang diperbolehkan.&kembali=" . urlencode($kembali_link));
        exit();
    }
    
    if ($file["size"] > 5000000) { // Batas 5MB
        header("Location: pesan.php?status=error&pesan=Ukuran file terlalu besar. Maksimal 5MB.&kembali=" . urlencode($kembali_link));
        exit();
    }

    // 3. Proses Upload File
    $upload_dir = 'uploads/proofs/';
    // Buat direktori jika belum ada
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Buat nama file yang unik untuk menghindari penimpaan
    $new_file_name = "proof_" . $order_id . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $new_file_name;

    // Pindahkan file dari temporary location ke folder tujuan
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
        header("Location: pesan.php?status=error&pesan=Terjadi kesalahan saat menyimpan file. Silakan coba lagi.&kembali=" . urlencode($kembali_link));
        exit();
    }

    // 4. Update Database
try {
    // PERUBAHAN DI SINI:
    // Menggunakan nama kolom yang sudah kita pastikan: 'payment_proof'
    // Mengubah status menjadi 'diproses' agar sesuai dengan ENUM Anda
    $sql = "UPDATE orders SET payment_proof = ?, status = 'diproses' WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_file_name, $order_id);
    
    // Eksekusi query
    if ($stmt->execute()) {
        header("Location: konfirmasi_pemesanan.php?order_id=" . $order_id . "&status=sukses_upload");
        exit();
    } else {
        throw new Exception("Gagal memperbarui database.");
    }
} catch (Exception $e) {
    // Kembalikan ke kode asli setelah masalah selesai
    $pesan_error = "Sistem sedang mengalami gangguan. Gagal memperbarui pesanan.";
    header("Location: pesan.php?status=error&pesan=" . urlencode($pesan_error) . "&kembali=" . urlencode($kembali_link));
    exit();
}

} else {
    // Jika halaman diakses langsung, alihkan ke beranda
    header("Location: index.php");
    exit();
}
?>