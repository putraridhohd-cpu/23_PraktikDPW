<?php
include_once __DIR__ . '/includes/header.php';
include_once __DIR__ . '/includes/koneksi.php';

// MODIFIKASI: Menghitung total data buku dan anggota secara aman
$total_buku = 0;
$total_anggota = 0;

try {
    if (isset($pdo)) {
        $stmt_buku = $pdo->query("SELECT COUNT(*) FROM buku");
        $total_buku = $stmt_buku->fetchColumn();

        $stmt_anggota = $pdo->query("SELECT COUNT(*) FROM anggota");
        $total_anggota = $stmt_anggota->fetchColumn();
    }
} catch (Exception $e) {
    // Abaikan jika tabel belum terisi/dibuat
}
?>

<main>
    <section class="hero-section" style="padding: 2rem 0;">
        <h2>Selamat Datang di SIMPUS-Mini</h2>
        <p>Sistem Informasi Manajemen Perpustakaan Sederhana.</p>

        <div style="display: flex; gap: 1.5rem; margin-top: 2rem; flex-wrap: wrap;">
            <div style="background: #e8f5e9; border-left: 5px solid #2e7d32; padding: 1.5rem; border-radius: 6px; flex: 1; min-width: 200px;">
                <h3 style="margin: 0 0 0.5rem 0; color: #2e7d32;">Total Buku</h3>
                <p style="font-size: 2rem; font-weight: bold; margin: 0;"><?= $total_buku; ?></p>
            </div>
            <div style="background: #e3f2fd; border-left: 5px solid #1565c0; padding: 1.5rem; border-radius: 6px; flex: 1; min-width: 200px;">
                <h3 style="margin: 0 0 0.5rem 0; color: #1565c0;">Total Anggota</h3>
                <p style="font-size: 2rem; font-weight: bold; margin: 0;"><?= $total_anggota; ?></p>
            </div>
        </div>
    </section>
</main>

<?php
include_once __DIR__ . '/includes/footer.php';
?>