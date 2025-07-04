<?php
// File: admin/partners/detail.php
require_once '../includes/header.php';
require_once '../../config/database.php';

// Validasi dan ambil data mitra
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM partners WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc();

if (!$partner) {
    echo "<div class='alert alert-danger m-4'>Mitra tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit();
}

// Helper function untuk warna badge status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending': return 'bg-warning text-dark';
        case 'approved': return 'bg-success';
        case 'rejected': return 'bg-danger';
        default: return 'bg-secondary';
    }
}
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Detail Mitra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php">Manajemen Mitra</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($partner['name']); ?></li>
    </ol>
    
    <?php if(isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Informasi mitra berhasil diperbarui!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Kolom Kiri: Informasi Detail -->
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Data Pendaftar
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Nama Lengkap:</strong>
                            <span><?php echo htmlspecialchars($partner['name']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>No. Telepon/WhatsApp:</strong>
                            <span><?php echo htmlspecialchars($partner['whatsapp']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Email:</strong>
                            <span><?php echo htmlspecialchars($partner['email']); ?></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Alamat Lengkap:</strong>
                            <p class="mt-2 bg-light p-2 rounded"><?php echo nl2br(htmlspecialchars($partner['address'])); ?></p>
                        </li>
                        <li class="list-group-item">
                            <strong>Link Google Maps:</strong>
                            <?php if (!empty($partner['maps_link'])): ?>
                                <a href="<?php echo htmlspecialchars($partner['maps_link']); ?>" target="_blank" class="d-block mt-2"><?php echo htmlspecialchars($partner['maps_link']); ?></a>
                            <?php else: ?>
                                <span class="d-block mt-2 text-muted"><em>Tidak disediakan</em></span>
                            <?php endif; ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Tanggal Daftar:</strong>
                            <span><?php echo date('d F Y', strtotime($partner['submission_date'])); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Aksi Kontekstual -->
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    Status & Aksi
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        Status Saat Ini: 
                        <span class="badge <?php echo getStatusBadgeClass($partner['status']); ?> fs-6">
                            <?php echo ucwords($partner['status']); ?>
                        </span>
                    </div>
                    <hr>

                    <?php if ($partner['status'] == 'pending'): ?>
                        <h5 class="card-title">Aksi Verifikasi</h5>
                        <p>Tinjau data pendaftar. Setujui untuk menjadikannya mitra aktif atau tolak pendaftaran.</p>
                        <div class="d-grid gap-2">
                            <a href="proses.php?action=approved&id=<?php echo $id; ?>" onclick="return confirm('Anda yakin ingin menyetujui pendaftar ini menjadi mitra aktif?');" class="btn btn-lg btn-success">
                                <i class="bi bi-check-circle me-2"></i>Setujui
                            </a>
                            <a href="proses.php?action=reject&id=<?php echo $id; ?>" onclick="return confirm('Anda yakin ingin menolak pendaftaran ini?');" class="btn btn-lg btn-warning text-dark">
                                <i class="bi bi-x-circle me-2"></i>Tolak
                            </a>
                        </div>
                    <?php elseif ($partner['status'] == 'approved'): ?>
                        <h5 class="card-title">Edit Informasi Mitra</h5>
                        <form action="proses.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat:</label>
                                <textarea name="address" id="address" class="form-control" rows="3"><?php echo htmlspecialchars($partner['address']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="maps_link" class="form-label">Link Google Maps:</label>
                                <input type="url" name="maps_link" id="maps_link" class="form-control" value="<?php echo htmlspecialchars($partner['maps_link']); ?>">
                            </div>
                            <button type="submit" name="action" value="update" class="btn btn-primary"><i class="bi bi-save me-2"></i>Update Info</button>
                            <a href="proses.php?action=delete&id=<?php echo $id; ?>" onclick="return confirm('PERINGATAN: Menghapus mitra aktif tidak dapat diurungkan. Lanjutkan?');" class="btn btn-danger float-end"><i class="bi bi-trash"></i> Hapus Mitra</a>
                        </form>
                    <?php elseif ($partner['status'] == 'rejected'): ?>
                        <div class="alert alert-danger">Pendaftaran ini telah ditolak. Tidak ada aksi lebih lanjut yang dapat dilakukan.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$stmt->close();
$conn->close();
require_once '../includes/footer.php';
?>