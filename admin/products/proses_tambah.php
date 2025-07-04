<?php
// File: admin/products/proses_tambah.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi dan Validasi
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $description = trim(htmlspecialchars($_POST['description'] ?? ''));
    $price = (float)($_POST['price'] ?? 0.0);

    if (empty($name) || empty($description) || $price <= 0) { die("Nama, deskripsi, dan harga harus diisi dengan benar."); }
    if (!isset($_FILES["image"]) || $_FILES["image"]["error"] != 0) { die("Gambar produk wajib diupload."); }

    // Validasi Upload
    $target_dir = "../../uploads/products/";
    $allowed_types = ['jpg', 'jpeg', 'png'];
    $max_size = 2 * 1024 * 1024; // 2MB

    $file_ext = strtolower(pathinfo(basename($_FILES["image"]["name"]), PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_types)) { die("Hanya format JPG, JPEG, PNG yang diizinkan."); }
    if ($_FILES["image"]["size"] > $max_size) { die("Ukuran file terlalu besar (Maks 2MB)."); }
    if (getimagesize($_FILES["image"]["tmp_name"]) === false) { die("File bukan gambar yang valid."); }

    $image_name = time() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image_name);

        if ($stmt->execute()) {
            header("Location: index.php?status=sukses_tambah");
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