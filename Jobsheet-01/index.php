<?php
session_start();
require __DIR__ . '/includes/koneksi.php';

$page_title = "Beranda";
include __DIR__ . '/includes/header.php';

$totalBuku    = $pdo->query("SELECT COUNT(*) FROM buku")->fetchColumn();
$totalAnggota = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
?>

<main>
    <section>
        <h2>Selamat Datang di SIMPUS Mini</h2>
        <p>Sistem Informasi Perpustakaan Sederhana untuk mengelola data buku dan anggota.</p>
    </section>

    <section>
        <h2>Ringkasan Dashboard</h2>
        <div style="display: flex; gap: 20px; margin-top: 15px;">
            <article style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; width: 150px;">
                <h3>Total Buku</h3>
                <p style="font-size: 24px; font-weight: bold;"><?php echo $totalBuku; ?></p>
            </article>
            <article style="border: 1px solid #ccc; padding: 15px; border-radius: 5px; width: 150px;">
                <h3>Total Anggota</h3>
                <p style="font-size: 24px; font-weight: bold;"><?php echo $totalAnggota; ?></p>
            </article>
        </div>
    </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>