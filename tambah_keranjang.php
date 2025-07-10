<?php
// Selalu mulai session di awal
session_start();

// Panggil file koneksi database
require_once 'config/database.php'; // Ganti 'koneksi.php' dengan nama file koneksi Anda

// Jika tidak ada ID produk, alihkan kembali ke halaman produk
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: produk.php');
    exit();
}

$product_id = (int)$_GET['id'];



// Ambil detail produk dari database untuk memastikan produk ada
$stmt = $conn->prepare("SELECT id, name, price, image_url FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if ($product) {
    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Cek apakah produk sudah ada di keranjang
    if (isset($_SESSION['cart'][$product_id])) {
        // Jika sudah ada, tambahkan quantity-nya
        $_SESSION['cart'][$product_id]['quantity']++;
    } else {
        // Jika belum ada, tambahkan sebagai item baru
        $_SESSION['cart'][$product_id] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image_url' => $product['image_url'],
            'quantity' => 1 // Jumlah awal
        ];
    }
}

// Alihkan pengguna ke halaman keranjang
header('Location: keranjang.php');
exit();
?>