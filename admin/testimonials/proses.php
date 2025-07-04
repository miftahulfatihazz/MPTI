<?php
// File: admin/testimonials/proses.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require '../../config/database.php';

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = (int)$_GET['id'];

    if ($action == 'approve') {
        // Ubah status menjadi 'approved'
        $stmt = $conn->prepare("UPDATE testimonials SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif ($action == 'delete') {
        // Hapus data dari database
        $stmt = $conn->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    header("Location: index.php?status=sukses");
    exit();
} else {
    header("Location: index.php");
    exit();
}
?>