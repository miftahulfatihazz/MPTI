<?php
// File: admin/admins/hapus.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
require '../../config/database.php';
$id = (int)$_GET['id'];

// Pencegahan agar admin tidak menghapus akunnya sendiri
if ($id == $_SESSION['admin_id']) die("Error: Anda tidak dapat menghapus akun Anda sendiri.");

$stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: index.php");
?>