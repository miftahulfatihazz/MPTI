<?php
require_once '../includes/header.php';
require_once '../../config/database.php';

$calon_mitra_res = $conn->query("SELECT * FROM partners WHERE status = 'pending' ORDER BY submission_date DESC");
$mitra_aktif_res = $conn->query("SELECT * FROM partners WHERE status = 'approved' ORDER BY name ASC");
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Mitra</h1>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Verifikasi calon mitra dan kelola mitra aktif</li></ol>

    <div class="card mb-4">
        <div class="card-header bg-warning text-dark"><i class="bi bi-person-plus-fill me-1"></i>Daftar Calon Mitra (Menunggu Verifikasi)</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>Nama</th><th>Telepon</th><th>Email</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($calon_mitra_res->num_rows > 0): ?>
                        <?php while($p = $calon_mitra_res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo htmlspecialchars($p['whatsapp']); ?></td>
                            <td><?php echo htmlspecialchars($p['email']); ?></td>
                            <td><a href="detail.php?id=<?php echo $p['id']; ?>" class="btn btn-info btn-sm">Lihat & Verifikasi</a></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">Tidak ada pendaftar baru.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-success text-white"><i class="bi bi-people-fill me-1"></i>Daftar Mitra Aktif</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead><tr><th>Nama Mitra</th><th>Alamat</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php if ($mitra_aktif_res->num_rows > 0): ?>
                        <?php while($p = $mitra_aktif_res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><?php echo htmlspecialchars($p['address']); ?></td>
                            <td><a href="detail.php?id=<?php echo $p['id']; ?>" class="btn btn-primary btn-sm">Edit Info</a></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">Belum ada mitra aktif.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php
$conn->close();
require_once '../includes/footer.php';
?>