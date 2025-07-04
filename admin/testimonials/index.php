<?php
require_once '../includes/header.php';
require_once '../../config/database.php';

$result = $conn->query("SELECT * FROM testimonials ORDER BY status ASC, submitted_at DESC");
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen Testimoni</h1>
    <ol class="breadcrumb mb-4"><li class="breadcrumb-item active">Setujui atau hapus testimoni dari pelanggan</li></ol>

    <div class="card mb-4">
        <div class="card-header"><i class="bi bi-table me-1"></i>Daftar Testimoni</div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr><th>Nama Pelanggan</th><th>Isi Testimoni</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($testimonial = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($testimonial['customer_name']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($testimonial['message'])); ?></td>
                            <td>
                                <?php if($testimonial['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($testimonial['status'] == 'pending'): ?>
                                    <a href="proses.php?action=approve&id=<?php echo $testimonial['id']; ?>" class="btn btn-success btn-sm"><i class="bi bi-check-lg"></i> Setujui</a>
                                <?php endif; ?>
                                <a href="proses.php?action=delete&id=<?php echo $testimonial['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin?');"><i class="bi bi-trash"></i> Hapus</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">Belum ada testimoni.</td></tr>
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