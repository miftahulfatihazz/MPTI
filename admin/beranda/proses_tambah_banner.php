<?php
// File: admin/beranda/proses_tambah_banner.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $display_order = $_POST['display_order'];

    // Proses upload gambar
    $target_dir = "../../uploads/banners/";
    $image_name = time() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO carousels (title, description, image_url, display_order) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $description, $image_name, $display_order);

        if ($stmt->execute()) {
            header("Location: index.php?status=sukses_banner");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Maaf, terjadi kesalahan saat mengupload file.";
    }
    $conn->close();
}
?>