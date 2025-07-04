<?php
// File: admin/proses_login.php

// Selalu mulai session di awal
session_start();

// Panggil file koneksi database
require '../config/database.php';

// Pastikan form disubmit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gunakan prepared statements untuk mencegah SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User ditemukan, sekarang verifikasi password
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            // Password cocok! Buat session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            // Arahkan ke dashboard admin
            header("Location: dashboard.php");
            exit();
        } else {
            // Password tidak cocok
            header("Location: login.php?error=1");
            exit();
        }
    } else {
        // User tidak ditemukan
        header("Location: login.php?error=1");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // Jika file diakses langsung tanpa POST, kembalikan ke login
    header("Location: login.php");
    exit();
}
?>