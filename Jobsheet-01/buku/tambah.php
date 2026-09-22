<?php
$page_title = "Tambah Buku";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <h2>Tambah Buku Baru</h2>
    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
    <?php endif; ?>
    <form id="form-tambah" method="post" action="proses_tambah.php">
        <div class="form-group">
            <label for="judul">Judul Buku <span class="required">*</span></label>
            <input type="text" id="judul" name="judul" required>
        </div>
        <div class="form-group">
            <label for="pengarang">Pengarang <span class="required">*</span></label>
            <input type="text" id="pengarang" name="pengarang" required>
        </div>
        <div class="form-group">
            <label for="tahun">Tahun Terbit</label>
            <input type="number" id="tahun" name="tahun" min="1900" max="2026">
        </div>
        <div class="form-group">
            <label for="isbn">ISBN</label>
            <input type="text" id="isbn" name="isbn">
        </div>
        <div class="form-group">
            <label for="stok">Jumlah Stok</label>
            <input type="number" id="stok" name="stok" min="0" value="1">
        </div>
        <div class="form-group">
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori">
                <option value="">-- Pilih Kategori --</option>
                <option value="Pemrograman">Pemrograman</option>
                <option value="Basis Data">Basis Data</option>
                <option value="Jaringan">Jaringan</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-primary">Simpan</button>
            <button type="reset" class="btn-secondary">Batal</button>
        </div>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>