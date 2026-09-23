<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$page_title = "Daftar Buku";
include __DIR__ . '/../includes/header.php';

$daftarBuku = $pdo->query("SELECT * FROM buku ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<main>
    <h2>Daftar Buku</h2>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </div>
    <?php endif; ?>

    <p><a href="tambah.php" class="btn">+ Tambah Buku Baru</a></p>

    <?php if (empty($daftarBuku)): ?>
        <p>Belum ada data buku.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>ISBN</th>
                    <th>Stok</th>
                    <th>Kategori</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarBuku as $index => $buku): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($buku['judul']); ?></td>
                        <td><?php echo htmlspecialchars($buku['pengarang']); ?></td>
                        <td><?php echo htmlspecialchars($buku['tahun']); ?></td>
                        <td><?php echo htmlspecialchars($buku['isbn'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars($buku['stok']); ?></td>
                        <td><?php echo htmlspecialchars($buku['kategori'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>