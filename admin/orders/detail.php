<?php
// File: admin/partners/detail.php (Versi Desain Baru)
require_once '../includes/header.php';
require_once '../../config/database.php';

// Validasi dan ambil data mitra
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Tampilkan pesan error jika ID tidak valid
    echo "<div class='alert alert-danger m-4'>ID Mitra tidak valid.</div>";
    require_once '../includes/footer.php';
    exit();
}
$id = (int)$_GET['id'];
$stmt = $conn->prepare("SELECT * FROM partners WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$partner = $result->fetch_assoc();
$stmt->close();

if (!$partner) {
    echo "<div class='alert alert-danger m-4'>Mitra dengan ID #{$id} tidak ditemukan.</div>";
    require_once '../includes/footer.php';
    exit();
}

// Helper function untuk warna badge status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'candidate': return 'bg-warning text-dark';
        case 'active': return 'bg-success';
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
                            <strong>Nama Lengkap/Toko:</strong>
                            <span><?php echo htmlspecialchars($partner['name']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>No. Telepon/WhatsApp:</strong>
                            <span><?php echo htmlspecialchars($partner['phone']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Email:</strong>
                            <span><?php echo htmlspecialchars($partner['email']); ?></span>
                        </li>
                        <li class="list-group-item">
                            <strong>Alamat Lengkap:</strong>
                            <p class="mt-2 bg-light p-2 rounded mb-0"><?php echo nl2br(htmlspecialchars($partner['address'])); ?></p>
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

                    <?php if ($partner['status'] == 'candidate'): ?>
                        <h5 class="card-title">Aksi Verifikasi</h5>
                        <p class="text-muted">Tinjau data pendaftar. Setujui untuk menjadikannya mitra aktif atau tolak pendaftaran.</p>
                        <div class="d-grid gap-2">
                            <a href="proses.php?action=approve&id=<?php echo $id; ?>" onclick="return confirm('Anda yakin ingin menyetujui pendaftar ini menjadi mitra aktif?');" class="btn btn-lg btn-success">
                                <i class="bi bi-check-circle me-2"></i>Setujui
                            </a>
                            <a href="proses.php?action=reject&id=<?php echo $id; ?>" onclick="return confirm('Anda yakin ingin menolak pendaftaran ini?');" class="btn btn-lg btn-warning text-dark">
                                <i class="bi bi-x-circle me-2"></i>Tolak
                            </a>
                        </div>
                    <?php elseif ($partner['status'] == 'active'): ?>
                        <h5 class="card-title">Edit Informasi Mitra</h5>
                        <form action="proses.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <div class="mb-3">
                                <label for="address" class="form-label">Alamat:</label>
                                <textarea name="address" id="address" class="form-control" rows="3"><?php echo htmlspecialchars($partner['address']); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="maps_link" class="form-label">Link Google Maps:</label>
                                <input type="url" name="maps_link" id="maps_link" class="form-control" value="<?php echo htmlspecialchars($partner['maps_link']); ?>" placeholder="https://maps.app.goo.gl/...">
                            </div>
                            <button type="submit" name="action" value="update" class="btn btn-primary"><i class="bi bi-save me-2"></i>Update Info</button>
                            <a href="proses.php?action=delete&id=<?php echo $id; ?>" onclick="return confirm('PERINGATAN: Menghapus mitra aktif tidak dapat diurungkan. Lanjutkan?');" class="btn btn-outline-danger float-end"><i class="bi bi-trash"></i> Hapus</a>
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
$conn->close();
require_once '../includes/footer.php';
?>