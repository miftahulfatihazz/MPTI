<?php
// File: admin/beranda/proses_edit_banner.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $display_order = $_POST['display_order'];
    $old_image = $_POST['old_image'];
    
    $image_name = $old_image; // Default pakai gambar lama

    // Cek jika ada gambar baru
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../uploads/banners/";
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Hapus gambar lama
            if (file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }
        }
    }

    $stmt = $conn->prepare("UPDATE carousels SET title = ?, description = ?, image_url = ?, display_order = ? WHERE id = ?");
    $stmt->bind_param("sssii", $title, $description, $image_name, $display_order, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=sukses_banner");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>