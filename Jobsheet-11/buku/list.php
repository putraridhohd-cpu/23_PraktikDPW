<?php
// [MODIFIKASI] Logika PHP (session, koneksi, query) dipindah ke atas file, sebelum HTML dicetak
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

// [BARU] Ambil flash message (hasil tambah/edit/hapus) lalu hapus dari session agar tidak muncul dua kali
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// [BARU] Pengaturan pagination: 5 baris per halaman
$perPage = 5;

// [MODIFIKASI] Parameter pencarian diganti dari "keyword" menjadi "q" (sesuai Jobsheet 9)
$keyword = (isset($_GET['q']) && is_string($_GET['q'])) ? trim($_GET['q']) : '';

// [BARU] Nomor halaman diambil dari URL (?page=2), minimal 1
$page = max(1, (int) ($_GET['page'] ?? 1));

// [BARU] Nilai awal variabel agar aman jika query gagal
$daftarBuku = [];
$totalRows  = 0;
$totalPages = 1;
$errorDb    = null;

try {
    // [BARU] Hitung total baris (mengikuti pencarian bila ada) untuk menentukan jumlah halaman
    if ($keyword !== '') {
        $hitung = $pdo->prepare("SELECT COUNT(*) FROM buku WHERE judul ILIKE :kw");
        $hitung->execute(['kw' => '%' . $keyword . '%']);
    } else {
        $hitung = $pdo->query("SELECT COUNT(*) FROM buku");
    }
    $totalRows  = (int) $hitung->fetchColumn();
    $totalPages = max(1, (int) ceil($totalRows / $perPage));

    // [BARU] Cegah nomor halaman melebihi total halaman, lalu hitung OFFSET
    $page   = min($page, $totalPages);
    $offset = ($page - 1) * $perPage;

    // [MODIFIKASI] Query data memakai ILIKE (pencarian server) + ORDER BY + LIMIT/OFFSET (pagination)
    if ($keyword !== '') {
        $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':kw', '%' . $keyword . '%');
    } else {
        $stmt = $pdo->prepare("SELECT * FROM buku ORDER BY id DESC LIMIT :limit OFFSET :offset");
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);   // [BARU] PARAM_INT wajib untuk LIMIT/OFFSET
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftarBuku = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorDb = 'Data buku gagal dimuat. Periksa koneksi dan tabel database.';
}

// [BARU] Tambahan query string agar kata kunci pencarian ikut terbawa saat pindah halaman
$queryTambahan = $keyword !== '' ? '&amp;q=' . urlencode($keyword) : '';

$page_title = 'Daftar Buku'; // [BARU] Judul pada tab browser
include_once __DIR__ . '/../includes/header.php';
?>

<section>
    <h2>Daftar Buku</h2>

    <!-- [BARU] Flash message hasil tambah / edit / hapus -->
    <?php if ($flash): ?>
        <div class="flash flash-<?= e($flash['type'] ?? 'success'); ?>"> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
            <?= e($flash['pesan'] ?? ''); ?> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
        </div>
    <?php endif; ?>

    <?php if ($errorDb): ?>
        <div class="flash flash-error"><?= e($errorDb); ?></div> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
    <?php endif; ?>

    <!-- [MODIFIKASI] Form pencarian: method GET, field bernama q, id search-input (dipakai filter instan app.js), plus tombol Cari -->
    <div class="search-box">
        <form method="get" action="list.php">
            <span>
                <label for="search-input">Cari Judul Buku</label>
                <input type="text" id="search-input" name="q" placeholder="Ketik judul buku..." value="<?= e($keyword); ?>"> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
            </span>
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>kategori</th>
                    <th>Tahun</th>
                    <th>Stock</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftarBuku)): ?>
                    <?php foreach ($daftarBuku as $row): ?>
                        <tr>
                            <td><?= e($row['judul'] ?? ''); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['pengarang'] ?? ''); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['kategori'] ?? '-'); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['tahun'] ?? ''); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['stok'] ?? ''); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td>
                                <!-- [MODIFIKASI] Tautan Edit membawa id lewat URL (dibaca $_GET['id'] di edit.php) -->
                                <a href="edit.php?id=<?= (int) $row['id']; ?>" class="btn-edit">Edit</a>

                                <!-- [MODIFIKASI] Tombol Hapus kini <form method="post"> sungguhan (bukan link GET), id dibawa lewat input hidden -->
                                <form class="form-hapus" method="post" action="hapus.php" data-nama="<?= e($row['judul'] ?? ''); ?>"> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                                    <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                                    <?= csrf_field(); ?> <!-- [BARU-JS11] token CSRF tersembunyi: wajib ada di setiap form POST -->
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 1.5rem; color: #666;">
                            <?php if ($keyword !== ''): ?>
                                <!-- [BARU] Pesan khusus jika pencarian tidak menemukan hasil -->
                                Tidak ada buku dengan judul "<?= e($keyword); ?>". <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <?php else: ?>
                                Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- [BARU] Navigasi angka halaman; halaman aktif diberi class "active" -->
    <nav class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="list.php?page=<?= $i; ?><?= $queryTambahan; ?>"
               class="<?= $i === $page ? 'active' : ''; ?>"><?= $i; ?></a>
        <?php endfor; ?>
    </nav>
</section>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>