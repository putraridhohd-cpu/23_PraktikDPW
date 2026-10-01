<?php
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login (sekaligus memulai session), menggantikan blok session_start manual
require_once __DIR__ . '/../includes/koneksi.php';

// Ambil flash message (hasil edit/hapus) lalu hapus dari session agar tidak muncul dua kali
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Pengaturan pagination: 5 baris per halaman
$perPage = 5;

// Parameter pencarian "q" (sesuai Jobsheet 9)
$keyword = (isset($_GET['q']) && is_string($_GET['q'])) ? trim($_GET['q']) : '';

// Nomor halaman diambil dari URL (?page=2), minimal 1
$page = max(1, (int) ($_GET['page'] ?? 1));

// Nilai awal variabel agar aman jika query gagal
$daftarAnggota = [];
$totalRows     = 0;
$totalPages    = 1;
$errorDb       = null;

try {
    // Hitung total baris (mengikuti pencarian bila ada). Placeholder dibedakan (:kw1, :kw2) agar aman di PDO PostgreSQL.
    if ($keyword !== '') {
        $hitung = $pdo->prepare("SELECT COUNT(*) FROM anggota WHERE nama ILIKE :kw1 OR no_anggota ILIKE :kw2");
        $hitung->execute(['kw1' => '%' . $keyword . '%', 'kw2' => '%' . $keyword . '%']);
    } else {
        $hitung = $pdo->query("SELECT COUNT(*) FROM anggota");
    }
    $totalRows  = (int) $hitung->fetchColumn();
    $totalPages = max(1, (int) ceil($totalRows / $perPage));

    // Cegah nomor halaman melebihi total halaman, lalu hitung OFFSET
    $page   = min($page, $totalPages);
    $offset = ($page - 1) * $perPage;

    // Query data memakai ILIKE (nama atau no_anggota) + ORDER BY + LIMIT/OFFSET
    if ($keyword !== '') {
        $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama ILIKE :kw1 OR no_anggota ILIKE :kw2 ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':kw1', '%' . $keyword . '%');
        $stmt->bindValue(':kw2', '%' . $keyword . '%');
    } else {
        $stmt = $pdo->prepare("SELECT * FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset");
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);   // PARAM_INT wajib untuk LIMIT/OFFSET
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorDb = 'Data anggota gagal dimuat. Periksa koneksi dan tabel database.';
}

// Tambahan query string agar kata kunci pencarian ikut terbawa saat pindah halaman
$queryTambahan = $keyword !== '' ? '&amp;q=' . urlencode($keyword) : '';

$page_title = 'Daftar Anggota'; // Judul pada tab browser
include_once __DIR__ . '/../includes/header.php';
?>

<section>
    <h2>Daftar Anggota</h2>

    <!-- Flash message hasil edit / hapus -->
    <?php if ($flash): ?>
        <div class="flash flash-<?= e($flash['type'] ?? 'success'); ?>"> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
            <?= e($flash['pesan'] ?? ''); ?> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
        </div>
    <?php endif; ?>

    <?php if ($errorDb): ?>
        <div class="flash flash-error"><?= e($errorDb); ?></div> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
    <?php endif; ?>

    <!-- Form pencarian: method GET, field bernama q, id search-input (dipakai filter instan app.js), plus tombol Cari -->
    <div class="search-box">
        <form method="get" action="list.php">
            <span>
                <label for="search-input">Cari Anggota</label>
                <input type="text" id="search-input" name="q" placeholder="Ketik nama atau no anggota..." value="<?= e($keyword); ?>"> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
            </span>
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No Anggota</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($daftarAnggota)): ?>
                    <?php foreach ($daftarAnggota as $row): ?>
                        <tr>
                            <td><?= e($row['no_anggota'] ?? '-'); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['nama'] ?? ''); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['alamat'] ?? '-'); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td><?= e($row['no_hp'] ?? '-'); ?></td> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <td>
                                <!-- Tautan Edit membawa id lewat URL (dibaca $_GET['id'] di edit.php) -->
                                <a href="edit.php?id=<?= (int) $row['id']; ?>" class="btn-edit">Edit</a>

                                <!-- Tombol Hapus adalah <form method="post"> sungguhan, id dibawa lewat input hidden -->
                                <form class="form-hapus" method="post" action="hapus.php" data-nama="<?= e($row['nama'] ?? ''); ?>"> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                                    <input type="hidden" name="id" value="<?= (int) $row['id']; ?>">
                                    <?= csrf_field(); ?> <!-- [BARU-JS11] token CSRF tersembunyi: wajib ada di setiap form POST -->
                                    <button type="submit" class="btn-hapus">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 1.5rem; color: #666;">
                            <?php if ($keyword !== ''): ?>
                                <!-- Pesan khusus jika pencarian tidak menemukan hasil -->
                                Tidak ada anggota dengan kata kunci "<?= e($keyword); ?>". <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                            <?php else: ?>
                                Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Navigasi angka halaman; halaman aktif diberi class "active" -->
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