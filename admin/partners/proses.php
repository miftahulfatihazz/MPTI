<?php
// File: admin/partners/proses.php
session_start();
if (!isset($_SESSION['admin_id'])) { header("Location: ../login.php"); exit(); }
require '../../config/database.php';

// Cek aksi dari GET (untuk link) atau POST (untuk form)
$action = $_REQUEST['action'] ?? null;
$id = $_REQUEST['id'] ?? null;

if (!$action || !$id) {
    header("Location: index.php");
    exit();
}
$id = (int)$id;

// Aksi dari link (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action == 'approved') {
        $stmt = $conn->prepare("UPDATE partners SET status = 'approved' WHERE id = ?");
        $stmt->bind_param("i", $id);
    } elseif ($action == 'rejected') {
        $stmt = $conn->prepare("UPDATE partners SET status = 'rejected' WHERE id = ?");
        $stmt->bind_param("i", $id);
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM partners WHERE id = ?");
        $stmt->bind_param("i", $id);
    }
    
    if (isset($stmt)) {
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php?status=sukses");
    exit();
}

// Aksi dari form (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action == 'update') {
        $address = $_POST['address'];
        $maps_link = $_POST['maps_link'];

        $stmt = $conn->prepare("UPDATE partners SET address = ?, maps_link = ? WHERE id = ?");
        $stmt->bind_param("ssi", $address, $maps_link, $id);
        $stmt->execute();
        $stmt->close();
    }
    // Arahkan kembali ke halaman detail setelah update
    header("Location: detail.php?id=" . $id . "&status=sukses");
    exit();
}
?>