<?php
require_once '../includes/header.php';
require_once '../../config/database.php';
$result = $conn->query("SELECT id, username, created_at FROM admins");
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Admin</h1>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Kelola akun yang dapat mengakses dashboard</li></ol>

    <div class="mb-4"><a href="tambah.php" class="btn btn-success"><i class="bi bi-person-plus me-1"></i>Tambah Admin Baru</a></div>

    <div class="card mb-4">
        <div class="card-header"><i class="bi bi-table me-1"></i>Daftar Akun Admin</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-light"><tr><th>ID</th><th>Username</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php while($admin = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $admin['id']; ?></td>
                        <td><?php echo htmlspecialchars($admin['username']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $admin['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                <a href="hapus.php?id=<?php echo $admin['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?');">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$conn->close();
require_once '../includes/footer.php';
?>