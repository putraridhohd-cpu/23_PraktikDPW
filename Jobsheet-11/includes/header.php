<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/helpers.php'; // [BARU-JS11] fungsi e() (anti-XSS) tersedia di semua halaman yang memuat header
require_once __DIR__ . '/csrf.php';    // [BARU-JS11] csrf_token(), csrf_field(), csrf_verify() tersedia di semua halaman
$sudahLogin = isset($_SESSION['user_id']); // [BARU-JS10] status login disimpan sekali, dipakai berulang di bawah

$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);
$__rel = ltrim(str_replace('\\', '/', substr($__scriptDir, strlen($__jobsheetRoot))), '/');
$base = $__rel === '' ? '' : str_repeat('../', substr_count($__rel, '/') + 1);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMPUS-Mini<?php echo isset($page_title) ? ' | ' . e($page_title) : ''; ?></title> <!-- [MODIFIKASI-JS11] judul halaman dibungkus e() -->
    <link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
</head>
<body>
    <header>
        <h1>SIMPUS-Mini</h1>
        <button type="button" id="nav-toggle-btn" class="nav-toggle-label" aria-label="Menu">&#9776;</button>
        <nav>
            <ul>
                <li><a href="<?php echo $base; ?>index.php">Beranda</a></li>
                <li><a href="<?php echo $base; ?>buku/list.php">Daftar Buku</a></li>
                <?php if ($sudahLogin): ?> <!-- [MODIFIKASI-JS10] menu di bawah ini hanya muncul untuk Petugas yang login -->
                <li><a href="<?php echo $base; ?>buku/tambah.php">Tambah Buku</a></li>
                <li><a href="<?php echo $base; ?>anggota/list.php">Daftar Anggota</a></li>
                <li><a href="<?php echo $base; ?>anggota/tambah.php">Tambah Anggota</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- [BARU-JS10] Status login di pojok kanan header -->
        <div class="auth-status">
            <?php if ($sudahLogin): ?>
                <span><?php echo e($_SESSION['nama'] ?? ''); ?></span> <!-- [MODIFIKASI-JS11] output di-escape dengan e() -->
                <a href="<?php echo $base; ?>auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="<?php echo $base; ?>auth/login.php">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <main>