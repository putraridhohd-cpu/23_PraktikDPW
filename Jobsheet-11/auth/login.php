<?php
// [BARU-JS10] Halaman form Login Petugas (menggantikan login.php statis lama di root proyek)
if (session_status() === PHP_SESSION_NONE) { // [BARU-JS10]
    session_start();
}

if (isset($_SESSION['user_id'])) { // [BARU-JS10] sudah login -> langsung ke Beranda
    header('Location: ../index.php');
    exit;
}

// [BARU-JS10] Ambil flash message (registrasi berhasil / login gagal / "silakan login") lalu hapus
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$page_title = "Login";
include __DIR__ . '/../includes/header.php';
?>

<section class="auth-card"> <!-- [BARU-JS10] kartu form di tengah halaman -->
    <h2>Login Petugas</h2>

    <?php if ($flash): ?>
        <div class="flash flash-<?= htmlspecialchars($flash['type'] ?? 'error'); ?>">
            <?= htmlspecialchars($flash['pesan'] ?? ''); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="proses_login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Masuk</button>
        </div>
    </form>

    <p class="auth-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>