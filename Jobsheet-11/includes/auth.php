<?php
// [BARU-JS10] Guard clause: di-require di baris paling atas setiap halaman yang membutuhkan login,
// SEBELUM header.php mengeluarkan output apa pun, agar header('Location: ...') masih bisa dipanggil.
if (session_status() === PHP_SESSION_NONE) { // [BARU-JS10] cegah session_start() ganda
    session_start();
}

if (!isset($_SESSION['user_id'])) { // [BARU-JS10] belum login -> alihkan ke halaman Login
    // [BARU-JS10] Pesan agar pengunjung tahu kenapa dialihkan (ditampilkan oleh auth/login.php)
    $_SESSION['flash'] = ['type' => 'warning', 'pesan' => 'Silakan login terlebih dahulu.'];
    header('Location: ../auth/login.php');
    exit;
}