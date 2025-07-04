<?php
// File: admin/admins/proses_tambah.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi
    if ($password !== $confirm_password) die("Error: Password dan konfirmasi password tidak cocok.");
    if (strlen($password) < 6) die("Error: Password minimal harus 6 karakter.");

    // Cek jika username sudah ada
    $stmt_check = $conn->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) die("Error: Username sudah digunakan.");
    $stmt_check->close();

    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: Gagal menyimpan admin baru.";
    }
    $stmt->close();
    $conn->close();
}