<?php
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login (sekaligus memulai session), menggantikan blok session_start manual
require_once __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$buku) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data buku tidak ditemukan.'];
    header('Location: list.php');
    exit;
}

$daftarKategori = ['Fiksi', 'Non-Fiksi', 'Pelajaran', 'Komputer & Teknologi', 'Lainnya'];
if (!empty($buku['kategori']) && !in_array($buku['kategori'], $daftarKategori, true)) {
    $daftarKategori[] = $buku['kategori'];
}

$page_title = 'Edit Buku';
include_once __DIR__ . '/../includes/header.php';
?>

<section style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <h2>Edit Buku</h2>

    <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type'] ?? 'error'); ?>">
            <?= htmlspecialchars($flash['pesan'] ?? ''); ?>
        </div>
    <?php endif; ?>

    <!-- class="form-edit" membuat app.js meminta konfirmasi ekstra sebelum Update -->
    <form id="form-tambah" class="form-edit" method="post" action="proses_edit.php">
        <input type="hidden" name="id" value="<?= (int) $buku['id']; ?>">

        <div style="margin-bottom: 1rem;">
            <label for="judul" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Judul Buku *</label>
            <input type="text" name="judul" id="judul" value="<?= htmlspecialchars($buku['judul'] ?? ''); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="pengarang" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Pengarang *</label>
            <input type="text" name="pengarang" id="pengarang" value="<?= htmlspecialchars($buku['pengarang'] ?? ''); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="tahun" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Tahun Terbit</label>
            <input type="number" name="tahun" id="tahun" value="<?= htmlspecialchars($buku['tahun'] ?? ''); ?>" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="isbn" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">ISBN</label>
            <input type="text" name="isbn" id="isbn" value="<?= htmlspecialchars($buku['isbn'] ?? ''); ?>" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="stok" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Jumlah Stok</label>
            <input type="number" name="stok" id="stok" min="0" value="<?= htmlspecialchars($buku['stok'] ?? 0); ?>" style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="kategori" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Kategori</label>
            <select name="kategori" id="kategori" style="width: 100%; padding: 8px; box-sizing: border-box;">
                <?php foreach ($daftarKategori as $kat): ?>
                    <option value="<?= htmlspecialchars($kat); ?>" <?= ($buku['kategori'] ?? '') === $kat ? 'selected' : ''; ?>><?= htmlspecialchars($kat); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <button type="submit" style="background-color: #1b5e20; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">Simpan Perubahan</button>
            <a href="list.php" style="color: #333; text-decoration: none;">Batal</a>
        </div>
    </form>
</section>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>