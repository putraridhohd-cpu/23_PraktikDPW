<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = $_SESSION['anggota'] ?? [];
?>

<section>
    <h2>Daftar Anggota</h2>
    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo $flash['pesan']; ?></p>
    <?php endif; ?>
    <div class="search-box">
        <label for="search-input">Cari Anggota</label>
        <input type="text" id="search-input" placeholder="Ketik nama anggota...">
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Email</th>
                    <th>Prodi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarAnggota)): ?>
                    <tr>
                        <td colspan="5">Belum ada data anggota. Silakan tambah lewat menu "Tambah Anggota".</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarAnggota as $anggota): ?>
                        <tr>
                            <td><?php echo $anggota['nama']; ?></td>
                            <td><?php echo $anggota['nim']; ?></td>
                            <td><?php echo $anggota['email']; ?></td>
                            <td><?php echo $anggota['prodi']; ?></td>
                            <td>
                                <button type="button">Edit</button>
                                <button type="button" class="btn-hapus">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>