<?php
// File: proses_mitra.php
require 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));
    $email = trim(filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL));
    $whatsapp = preg_replace('/[^0-9]/', '', trim($_POST['whatsapp'] ?? ''));
    $address = trim(htmlspecialchars($_POST['address'] ?? ''));

    $errors = [];
    if (empty($name)) { $errors[] = "Nama wajib diisi."; }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = "Format email tidak valid."; }
    if (empty($whatsapp) || strlen($whatsapp) < 10 || strlen($whatsapp) > 15) { $errors[] = "Nomor telepon tidak valid."; }
    if (empty($address)) { $errors[] = "Alamat wajib diisi."; }

    if (!empty($errors)) {
        $pesan_error = implode("<br>", $errors);
        header("Location: pesan.php?status=error&pesan=" . urlencode($pesan_error) . "&kembali=javascript:history.back()");
        exit();
    }
    
    // Pastikan nama variabel di sini ($stmt)
    $stmt = $conn->prepare("INSERT INTO partners (name, email, whatsapp, address, status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("ssss", $name, $email, $whatsapp, $address);

    // dan nama variabel di sini ($stmt) sama persis
    if ($stmt->execute()) {
        header("Location: mitra.php?status=sukses_daftar#gabung-mitra");
        exit();
    } else {
        header("Location: pesan.php?status=error&pesan=Gagal mengirim pendaftaran. Silakan coba lagi.&kembali=javascript:history.back()");
        exit();
    }
} else {
    header("Location: mitra.php");
    exit();
}
?>