<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 1. Ambil nama file gambar sebelum menghapus data dari DB
    $stmt_select = $conn->prepare("SELECT image_url FROM products WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $product = $result->fetch_assoc();
    $image_to_delete = $product['image_url'];
    $stmt_select->close();

    // 2. Hapus data dari database
    $stmt_delete = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        // 3. Hapus file gambar dari server
        $file_path = "../../uploads/products/" . $image_to_delete;
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        header("Location: index.php");
        exit();
    } else {
        echo "Error: Gagal menghapus produk.";
    }
    $stmt_delete->close();
}

$conn->close();
?>