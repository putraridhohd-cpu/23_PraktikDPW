<?php
include_once __DIR__ . '/../includes/header.php';
include_once __DIR__ . '/../includes/koneksi.php';

// MODIFIKASI: Menangkap kata kunci pencarian
$keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
?>

<main>
    <section>
        <h2>Daftar Anggota</h2>

        <!-- MODIFIKASI: Form Pencarian Nama / No Anggota -->
        <div class="search-box">
            <label for="keyword">Cari Anggota</label>
            <form action="list.php" method="GET">
                <input type="text" name="keyword" id="keyword" placeholder="Ketik nama atau no anggota..." value="<?= htmlspecialchars($keyword); ?>">
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
                    <?php
                    $data_anggota = [];

                    try {
                        if (isset($pdo)) {
                            // MODIFIKASI: Query PostgreSQL/MySQL menggunakan PDO
                            if (!empty($keyword)) {
                                $stmt = $pdo->prepare("SELECT * FROM anggota WHERE nama ILIKE :kw OR no_anggota ILIKE :kw ORDER BY id DESC");
                                $stmt->execute(['kw' => '%' . $keyword . '%']);
                            } else {
                                $stmt = $pdo->query("SELECT * FROM anggota ORDER BY id DESC");
                            }
                            $data_anggota = $stmt->fetchAll();
                        }
                    } catch (Exception $e) {
                        // Fallback ke pg_query jika PDO tidak aktif
                        if (isset($koneksi) && is_resource($koneksi)) {
                            $res = pg_query($koneksi, "SELECT * FROM anggota ORDER BY id DESC");
                            if ($res) {
                                while ($row = pg_fetch_assoc($res)) {
                                    $data_anggota[] = $row;
                                }
                            }
                        }
                    }

                    if (!empty($data_anggota)) {
                        foreach ($data_anggota as $row) {
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($row['no_anggota'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['nama']); ?></td>
                                <td><?= htmlspecialchars($row['alamat'] ?? '-'); ?></td>
                                <td><?= htmlspecialchars($row['no_hp'] ?? '-'); ?></td>
                                <td>
                                    <!-- MODIFIKASI: Tombol Aksi Edit, Detail, dan Hapus seragam dengan daftar buku -->
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                                    <a href="detail.php?id=<?= $row['id']; ?>" class="btn-detail">Detail</a>
                                    <a href="proses_hapus.php?id=<?= $row['id']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus anggota ini?')">Hapus</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 1.5rem; color: #666;">
                                Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".
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