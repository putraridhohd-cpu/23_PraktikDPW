<?php
// [BARU] Halaman form edit buku. Semua pengecekan + redirect dilakukan SEBELUM header.php mencetak HTML.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

// [BARU] Ambil flash message (mis. error validasi dari proses_edit.php)
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// [BARU] Ambil id dari URL (edit.php?id=3) dan pastikan berupa bilangan bulat
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: list.php');
    exit;
}

// [BARU] Ambil data lama: SELECT ... WHERE id = :id, fetch() hanya satu baris
$stmt = $pdo->prepare("SELECT * FROM buku WHERE id = :id");
$stmt->execute(['id' => $id]);
$buku = $stmt->fetch(PDO::FETCH_ASSOC);

// [BARU] Jika id tidak ada di database, kembali ke daftar buku
if (!$buku) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data buku tidak ditemukan.'];
    header('Location: list.php');
    exit;
}

// [BARU] Daftar kategori (sama dengan tambah.php + "Lainnya" yang ada di database)
$daftarKategori = ['Fiksi', 'Non-Fiksi', 'Pelajaran', 'Komputer & Teknologi', 'Lainnya'];
// [BARU] Jika kategori di database tidak ada di daftar, tambahkan agar nilainya tidak hilang saat disimpan
if (!empty($buku['kategori']) && !in_array($buku['kategori'], $daftarKategori, true)) {
    $daftarKategori[] = $buku['kategori'];
}

$page_title = 'Edit Buku';
include_once __DIR__ . '/../includes/header.php';
?>

<section style="max-width: 600px; margin: 0 auto; padding: 1rem;">
    <h2>Edit Buku</h2>

    <!-- [BARU] Tampilkan flash message bila ada -->
    <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type'] ?? 'error'); ?>">
            <?= htmlspecialchars($flash['pesan'] ?? ''); ?>
        </div>
    <?php endif; ?>

    <!-- [BARU] id="form-tambah" dipakai ulang agar validasi JavaScript app.js juga berlaku di form edit -->
    <form id="form-tambah" method="post" action="proses_edit.php">
        <!-- [BARU] Input tersembunyi: membawa id buku ke proses_edit.php tanpa terlihat pengguna -->
        <input type="hidden" name="id" value="<?= (int) $buku['id']; ?>">

        <div style="margin-bottom: 1rem;">
            <label for="judul" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Judul Buku *</label>
            <!-- [BARU] Atribut value diisi data lama -->
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
                <!-- [BARU] Opsi dibuat dengan foreach; atribut selected dipasang pada kategori yang cocok dengan data lama -->
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