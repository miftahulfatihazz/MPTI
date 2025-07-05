<?php
// File: admin/proses_settings.php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require '../config/database.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    file_put_contents('debug.log', "Form submitted at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    file_put_contents('debug.log', print_r($_POST, true) . "\n", FILE_APPEND);
    file_put_contents('debug.log', print_r($_FILES, true) . "\n", FILE_APPEND);

    $conn->begin_transaction();
    try {
        // --- PROSES UPLOAD GAMBAR 'company_image' ---
        if (isset($_FILES['company_image']) && $_FILES['company_image']['error'] == 0 && !empty($_FILES['company_image']['name'])) {
            $target_dir = "../uploads/site/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 10 * 1024 * 1024; // 2MB
            $file_type = mime_content_type($_FILES['company_image']['tmp_name']);
            $file_size = $_FILES['company_image']['size'];
            $file_ext = strtolower(pathinfo($_FILES['company_image']['name'], PATHINFO_EXTENSION));

            if (!in_array($file_type, $allowed_types) || $file_size > $max_size) {
                throw new Exception("File tidak valid. Hanya JPG, PNG, atau GIF dengan ukuran maksimal 2MB yang diizinkan.");
            }

            $new_image_name = "company_image_" . time() . "." . $file_ext;
            $target_file = $target_dir . $new_image_name;

            if (!move_uploaded_file($_FILES['company_image']['tmp_name'], $target_file)) {
                throw new Exception("Gagal memindahkan file upload.");
            }

            $old_image = $_POST['old_company_image'] ?? '';
            if (!empty($old_image) && $old_image != 'placeholder.jpg' && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }

            $stmt_img = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt_img->bind_param("sss", $key, $new_image_name, $new_image_name);
            $key = 'company_image';
            $stmt_img->execute();
            $stmt_img->close();
        }

        // --- PROSES UPDATE DATA TEKS LAINNYA ---
        $stmt_text = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $text_fields = ['company_profile_short', 'visi', 'misi', 'company_address', 'company_whatsapp', 'company_email', 'company_instagram', 'company_maps_embed'];

        foreach ($text_fields as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                throw new Exception("Field $field tidak boleh kosong.");
            }
            if ($field == 'company_whatsapp' && !preg_match("/^\+[0-9]{10,15}$/", $_POST[$field])) {
                throw new Exception("Nomor WhatsApp tidak valid. Gunakan format: +6281234567890");
            }
            if ($field == 'company_email' && !filter_var($_POST[$field], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Alamat email tidak valid.");
            }
            $value = htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8');
            $stmt_text->bind_param("sss", $field, $value, $value);
            $stmt_text->execute();
        }
        $stmt_text->close();

        // Jika semua berhasil
        $conn->commit();
        header("Location: settings.php?status=sukses");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        file_put_contents('debug.log', "Error: " . $e->getMessage() . "\n", FILE_APPEND);
        header("Location: settings.php?status=error&message=" . urlencode($e->getMessage()));
        exit();
    }
}
$conn->close();
