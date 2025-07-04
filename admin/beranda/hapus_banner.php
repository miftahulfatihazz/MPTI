<?php
// File: admin/beranda/hapus_banner.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require '../../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama file gambar untuk dihapus dari server
    $stmt_select = $conn->prepare("SELECT image_url FROM carousels WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result()->fetch_assoc();
    $image_to_delete = $result['image_url'];
    $stmt_select->close();

    // Hapus data dari DB
    $stmt_delete = $conn->prepare("DELETE FROM carousels WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // Hapus file gambar
        $file_path = "../../uploads/banners/" . $image_to_delete;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        header("Location: index.php?status=sukses_banner");
    } else {
        echo "Error: Gagal menghapus banner.";
    }
    $stmt_delete->close();
    $conn->close();
}
?>