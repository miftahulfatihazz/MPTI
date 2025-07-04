<?php
// File: admin/beranda/proses_keunggulan.php

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}

require '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];
    $section_key = 'product_advantages';

    $stmt = $conn->prepare("UPDATE site_content SET content = ? WHERE section_key = ?");
    $stmt->bind_param("ss", $content, $section_key);

    $stmt = $conn->prepare("
        INSERT INTO site_content (section_key, content) 
        VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE content = VALUES(content)
    ");
    $stmt->bind_param("ss", $section_key, $content);

    if ($stmt->execute()) {
        header("Location: index.php?status=sukses_keunggulan");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>