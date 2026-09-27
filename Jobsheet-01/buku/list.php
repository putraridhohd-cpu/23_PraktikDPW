<?php
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/koneksi.php';

// Menangkap kata kunci pencarian
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
?>

<main>
    <section>
        <h2>Daftar Buku</h2>

        <!-- Form Pencarian Judul Buku -->
        <div class="search-box">
            <label for="keyword">Cari Judul Buku</label>
            <form action="list.php" method="GET">
                <input type="text" name="keyword" id="keyword" placeholder="Ketik judul buku..." value="<?= htmlspecialchars($keyword); ?>">
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
                    <?php
                    $data_buku = [];

                    try {
                        if (isset($pdo)) {
                            // MODIFIKASI: Menggunakan PDO PostgreSQL/MySQL secara konsisten
                            if (!empty($keyword)) {
                                $stmt = $pdo->prepare("SELECT * FROM buku WHERE judul ILIKE :kw ORDER BY id DESC");
                                $stmt->execute(['kw' => '%' . $keyword . '%']);
                            } else {
                                $stmt = $pdo->query("SELECT * FROM buku ORDER BY id DESC");
                            }
                            $data_buku = $stmt->fetchAll();
                        }
                    } catch (Exception $e) {
                        // Fallback jika kueri mengalami error
                    }

                    if (!empty($data_buku)) {
                        foreach ($data_buku as $row) {
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($row['judul']); ?></td>
                                <td><?= htmlspecialchars($row['pengarang']); ?></td>
                                <td><?= htmlspecialchars($row['kategori'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['tahun']); ?></td>
                                <td><?= htmlspecialchars($row['stok']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="detail.php?id=<?= $row['id']; ?>" class="btn-detail">Detail</a>
                                    <a href="proses_hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 1.5rem; color: #666;">
                                Belum ada data buku. Silakan tambah lewat menu "Tambah Buku".
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>