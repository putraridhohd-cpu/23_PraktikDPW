<?php
$page_title = "Tambah Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Tambah Anggota Baru</h2>
    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
    <?php endif; ?>
    <form id="form-tambah" method="post" action="proses_tambah.php">
        <div class="form-group">
            <label for="nama">Nama Lengkap <span class="required">*</span></label>
            <input type="text" id="nama" name="nama" required>
        </div>
        <div class="form-group">
            <label for="nim">NIM <span class="required">*</span></label>
            <input type="text" id="nim" name="nim" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="prodi">Program Studi</label>
            <input type="text" id="prodi" name="prodi">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan</button>
            <button type="reset" class="btn-secondary">Batal</button>
        </div>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>