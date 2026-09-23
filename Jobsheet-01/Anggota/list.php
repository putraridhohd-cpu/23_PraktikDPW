<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<main>
    <h2>Daftar Anggota</h2>

    <?php if ($flash): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash['type']); ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </div>
    <?php endif; ?>

    <p><a href="tambah.php" class="btn">+ Tambah Anggota Baru</a></p>

    <?php if (empty($daftarAnggota)): ?>
        <p>Belum ada data anggota.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIM (No. Anggota)</th>
                    <th>Email (Alamat)</th>
                    <th>Prodi (No. HP)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($daftarAnggota as $index => $anggota): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($anggota['nama']); ?></td>
                        <td><?php echo htmlspecialchars($anggota['no_anggota']); ?></td>
                        <td><?php echo htmlspecialchars($anggota['alamat']); ?></td>
                        <td><?php echo htmlspecialchars($anggota['no_hp']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>