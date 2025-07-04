<?php
// File: admin/products/proses_edit.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $description = trim(htmlspecialchars($_POST['description'] ?? ''));
    $price = (float)($_POST['price'] ?? 0.0);
    $old_image = $_POST['old_image'];

    if ($id <= 0 || empty($name) || empty($description) || $price <= 0) { die("Data tidak lengkap atau tidak valid."); }
    
    $image_name = $old_image;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0 && !empty($_FILES['image']['name'])) {
        // Logika validasi upload (sama seperti proses_tambah)
        $target_dir = "../../uploads/products/";
        $allowed_types = ['jpg', 'jpeg', 'png'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $file_ext = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed_types)) { die("Hanya format JPG, JPEG, PNG yang diizinkan."); }
        if ($_FILES["image"]["size"] > $max_size) { die("Ukuran file terlalu besar (Maks 2MB)."); }
        if (getimagesize($_FILES["image"]["tmp_name"]) === false) { die("File bukan gambar yang valid."); }
        
        $new_image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $new_image_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $old_image_path = $target_dir . $old_image;
            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }
            $image_name = $new_image_name;
        } else {
            die("Gagal upload gambar baru.");
        }
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_name, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=sukses_edit");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>