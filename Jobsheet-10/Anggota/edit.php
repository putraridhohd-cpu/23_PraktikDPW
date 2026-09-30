<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
$stmt->execute(['id' => $id]);
$anggota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$anggota) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data anggota tidak ditemukan.'];
    header('Location: list.php');
    exit;
}

$page_title = 'Edit Anggota';
include_once __DIR__ . '/../includes/header.php';
?>

<section style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <h2>Edit Anggota</h2>

    <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type'] ?? 'error'); ?>">
            <?= htmlspecialchars($flash['pesan'] ?? ''); ?>
        </div>
    <?php endif; ?>

    <!-- [MODIFIKASI] Ditambahkan class="form-edit" agar app.js tahu form ini perlu konfirmasi ekstra sebelum Update (Latihan no.23) -->
    <form id="form-tambah" class="form-edit" method="post" action="proses_edit.php">
        <input type="hidden" name="id" value="<?= (int) $anggota['id']; ?>">

        <div style="margin-bottom: 1rem;">
            <label for="no_anggota" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">No Anggota *</label>
            <input type="text" name="no_anggota" id="no_anggota" value="<?= htmlspecialchars($anggota['no_anggota'] ?? ''); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="nama" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Nama Lengkap *</label>
            <input type="text" name="nama" id="nama" value="<?= htmlspecialchars($anggota['nama'] ?? ''); ?>" required style="width: 100%; padding: 8px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label for="alamat" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3" style="width: 100%; padding: 8px; box-sizing: border-box;"><?= htmlspecialchars($anggota['alamat'] ?? ''); ?></textarea>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <label for="no_hp" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">No HP / WhatsApp</label>
            <input type="text" name="no_hp" id="no_hp" value="<?= htmlspecialchars($anggota['no_hp'] ?? ''); ?>" style="width: 100%; padding: 8px; box-sizing: border-box;">
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