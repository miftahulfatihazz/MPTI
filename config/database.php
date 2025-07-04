<?php
// File: config/database.php

$host = 'localhost';
$user = 'root';
$password = ''; // Kosongkan jika password XAMPP Anda kosong
$database = 'db_sinar_bahari'; // Pastikan nama ini sama persis dengan database Anda

// Membuat koneksi menggunakan MySQLi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Mengatur karakter set agar tidak ada masalah dengan karakter aneh
$conn->set_charset("utf8mb4");

?>