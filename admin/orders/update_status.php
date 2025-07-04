<?php
// File: admin/orders/update_status.php
require_once '../../config/database.php';

// Pastikan request adalah POST dan aksi yang dikirim benar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {

    // Ambil dan validasi data dari form
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $new_status = isset($_POST['status']) ? $_POST['status'] : '';

    // Daftar status yang diizinkan (sesuai dengan ENUM di database Anda)
    $allowed_statuses = ['menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan'];

    // Lakukan update hanya jika data valid
    if ($order_id > 0 && !empty($new_status) && in_array($new_status, $allowed_statuses)) {
        
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $order_id);
        
        if ($stmt->execute()) {
            // JIKA SUKSES: Perintahkan browser kembali ke index.php
            header("Location: index.php?update=sukses");
            exit();
        } else {
            // JIKA GAGAL: Perintahkan browser kembali ke index.php dengan pesan error
            header("Location: index.php?update=gagal");
            exit();
        }
        
    } else {
        // Jika input tidak valid, kembali dengan pesan error
        header("Location: index.php?update=gagal_input");
        exit();
    }

} else {
    // Jika halaman ini diakses langsung, langsung lempar kembali ke index.php
    header("Location: index.php");
    exit();
}
?>