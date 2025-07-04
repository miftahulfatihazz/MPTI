<?php
// File: admin/admins/proses_edit.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Update username
    $stmt_user = $conn->prepare("UPDATE admins SET username = ? WHERE id = ?");
    $stmt_user->bind_param("si", $username, $id);
    $stmt_user->execute();
    $stmt_user->close();

    // Cek jika password diisi untuk diubah
    if (!empty($password)) {
        if ($password !== $confirm_password) die("Error: Password baru tidak cocok.");
        if (strlen($password) < 6) die("Error: Password baru minimal harus 6 karakter.");
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt_pass = $conn->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $stmt_pass->bind_param("si", $hashed_password, $id);
        $stmt_pass->execute();
        $stmt_pass->close();
    }
    
    header("Location: index.php");
    $conn->close();
}