<?php
// [BARU-JS10] Halaman form Registrasi Petugas
if (session_status() === PHP_SESSION_NONE) { // [BARU-JS10]
    session_start();
}

if (isset($_SESSION['user_id'])) { // [BARU-JS10] sudah login -> tidak perlu registrasi, kembali ke Beranda
    header('Location: ../index.php');
    exit;
}

// [BARU-JS10] Ambil flash message (error validasi dari proses_register.php) lalu hapus agar tidak muncul dua kali
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$page_title = "Registrasi Petugas";
include __DIR__ . '/../includes/header.php';
?>

<section class="auth-card"> <!-- [BARU-JS10] kartu form di tengah halaman -->
    <h2>Registrasi Petugas</h2>

    <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type'] ?? 'error'); ?>">
            <?= htmlspecialchars($flash['pesan'] ?? ''); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="proses_register.php">
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" maxlength="50" required>
        </div>

        <div class="form-group">
            <label for="password">Password (minimal 6 karakter)</label>
            <!-- [BARU-JS10] type="password" menyamarkan ketikan, minlength="6" validasi panjang minimal di browser -->
            <input type="password" id="password" name="password" minlength="6" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Daftar</button>
        </div>
    </form>

    <p class="auth-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>