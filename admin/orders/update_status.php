<?php
// File: admin/orders/update_status.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require '../../config/database.php';

$action = $_REQUEST['action'] ?? null;
$order_id = $_REQUEST['id'] ?? $_POST['order_id'] ?? null;

if (!$order_id || !$action) {
    header("Location: index.php");
    exit();
}
$order_id = (int)$order_id;

// Logika untuk menyetujui pembayaran
if ($action == 'approve_payment') {
    $stmt = $conn->prepare("UPDATE orders SET status = 'diproses' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
}
// Logika untuk menolak pembayaran
elseif ($action == 'reject_payment') {
    // Opsional: Hapus bukti pembayaran yang ditolak agar user bisa upload lagi
    // Untuk saat ini, kita hanya ubah statusnya
    $stmt = $conn->prepare("UPDATE orders SET status = 'dibatalkan' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
}
// Logika untuk update status manual dari form
elseif ($action == 'update_status' && isset($_POST['status'])) {
    $new_status = $_POST['status'];
    // Validasi status yang diizinkan
    $allowed_statuses = ['diproses', 'dikirim', 'selesai', 'dibatalkan'];
    if (in_array($new_status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $order_id);
        $stmt->execute();
    }
}

// Redirect kembali ke halaman detail dengan pesan sukses
header("Location: detail.php?id=" . $order_id . "&status=sukses");
exit();
?>