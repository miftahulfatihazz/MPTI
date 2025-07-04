<?php
// File: admin/proses_settings.php (Versi Perbaikan)
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->begin_transaction();
    try {
        // --- PROSES UPLOAD GAMBAR 'company_image' ---
        if (isset($_FILES['company_image']) && $_FILES['company_image']['error'] == 0 && !empty($_FILES['company_image']['name'])) {
            $target_dir = "../uploads/site/";
            $old_image = $_POST['old_company_image'];

            // Validasi file
            // ... (tambahkan validasi tipe, ukuran, dll. di sini jika perlu) ...

            $new_image_name = "company_image_" . time() . "." . strtolower(pathinfo(basename($_FILES["company_image"]["name"]), PATHINFO_EXTENSION));
            $target_file = $target_dir . $new_image_name;

            if (move_uploaded_file($_FILES["company_image"]["tmp_name"], $target_file)) {
                if (!empty($old_image) && $old_image != 'placeholder.jpg' && file_exists($target_dir . $old_image)) {
                    unlink($target_dir . $old_image);
                }
                
                $stmt_img = $conn->prepare("UPDATE site_content SET content = ? WHERE section_key = 'company_image'");
                $stmt_img->bind_param("s", $new_image_name);
                $stmt_img->execute();
                $stmt_img->close();
            } else {
                throw new Exception("Gagal memindahkan file upload.");
            }
        }

        // --- PROSES UPDATE DATA TEKS LAINNYA ---
        $stmt_text = $conn->prepare("UPDATE site_content SET content = ? WHERE section_key = ?");
        $text_fields = ['company_profile_short', 'visi', 'misi', 'company_address', 'company_whatsapp', 'company_email', 'company_instagram', 'company_maps_embed'];

        foreach ($text_fields as $field) {
            if (isset($_POST[$field])) {
                $value = htmlspecialchars($_POST[$field], ENT_QUOTES, 'UTF-8');
                $stmt_text->bind_param("ss", $value, $field);
                $stmt_text->execute();
            }
        }
        $stmt_text->close();

        // Jika semua berhasil
        $conn->commit();
        header("Location: settings.php?status=sukses");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        // Redirect ke halaman error yang bagus
        // Untuk sekarang, kita gunakan die() untuk debug
        die("Terjadi kesalahan: " . $e->getMessage());
    }
}
?>