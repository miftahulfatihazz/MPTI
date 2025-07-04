<?php
// File: admin/settings.php
require_once __DIR__ . '/../config/database.php';
require_once 'includes/header.php';


// Ambil semua data dari site_content
$result = $conn->query("SELECT * FROM site_content");
$settings = [];
while ($row = $result->fetch_assoc()) {
    $settings[$row['section_key']] = $row['content'];
}
?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Pengaturan Website</h1>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses'): ?>
    <div class="alert alert-success">Perubahan berhasil disimpan!</div>
    <?php endif; ?>

        <form action="proses_settings.php" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Profil, Visi & Misi</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Profil Singkat Perusahaan</label>
                            <textarea name="company_profile_short" class="form-control" rows="4"><?php echo htmlspecialchars($settings['company_profile_short'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar Halaman "Tentang Kami"</label>
                            <div class="row">
                                <div class="col-9">
                                    <input type="file" name="company_image" class="form-control">
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
                                    <!-- Input tersembunyi untuk menyimpan nama gambar lama -->
                                    <input type="hidden" name="old_company_image" value="<?php echo htmlspecialchars($settings['company_image'] ?? ''); ?>">
                                </div>
                                <div class="col-3">
                                    <?php if (!empty($settings['company_image']) && $settings['company_image'] != 'placeholder.jpg'): ?>
                                        <img src="../uploads/site/<?php echo htmlspecialchars($settings['company_image']); ?>" class="img-fluid img-thumbnail" alt="Gambar saat ini">
                                    <?php else: ?>
                                        <div class="img-thumbnail text-center p-2 text-muted">No Image</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Visi</label>
                            <textarea name="visi" class="form-control" rows="3"><?php echo htmlspecialchars($settings['visi'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Misi</label>
                            <textarea name="misi" class="form-control" rows="5"><?php echo htmlspecialchars($settings['misi'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">Info Kontak & Lokasi</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="company_address" class="form-control" rows="3"><?php echo htmlspecialchars($settings['company_address'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor WhatsApp (Format: +6281234567890)</label>
                            <input type="text" name="company_whatsapp" class="form-control" value="<?php echo htmlspecialchars($settings['company_whatsapp'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <input type="email" name="company_email" class="form-control" value="<?php echo htmlspecialchars($settings['company_email'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link Instagram</label>
                            <input type="text" name="company_instagram" class="form-control" value="<?php echo htmlspecialchars($settings['company_instagram'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link Embed Google Maps</label>
                            <textarea name="company_maps_embed" class="form-control" rows="4"><?php echo htmlspecialchars($settings['company_maps_embed'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg">Simpan Semua Perubahan</button>
    </form>
</div>
<?php
$conn->close();
require_once 'includes/footer.php';
?>