<?php
// File: admin/admins/edit.php
require_once '../includes/header.php';
require_once '../../config/database.php';

// Validasi dan ambil data admin
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT id, username FROM admins WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo "<div class='alert alert-danger m-4'>Admin tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit();
}

// Cek jika admin mencoba mengedit dirinya sendiri, untuk memberikan pesan khusus
$is_editing_self = ($_SESSION['admin_id'] == $id);
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Admin</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Admin</a></li>
        <li class="breadcrumb-item active">Edit: <?php echo htmlspecialchars($admin['username']); ?></li>
    </ol>
    
    <?php if ($is_editing_self): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i>
            Anda sedang mengedit profil Anda sendiri.
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-pencil-square me-1"></i>
            Formulir Edit Akun Admin
        </div>
        <div class="card-body">
            <form action="proses_edit.php" method="POST" class="col-lg-8">
                <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                
                <!-- Bagian Username -->
                <fieldset>
                    <legend class="h5">Informasi Akun</legend>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                    </div>
                </fieldset>
                
                <hr class="my-4">

                <!-- Bagian Password -->
                <fieldset>
                    <legend class="h5">Ubah Password</legend>
                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong> Isi kolom di bawah ini hanya jika Anda ingin mengubah password. Biarkan kosong jika tidak.
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" autocomplete="new-password">
                    </div>
                </fieldset>
                
                <hr class="my-4">
                
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Simpan Perubahan</button>
                <a href="index.php" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once '../includes/footer.php';
?>