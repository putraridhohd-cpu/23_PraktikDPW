<?php
// [BARU-JS10] Memproses Login: cari user, verifikasi password, simpan identitas ke session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/csrf.php'; // [BARU-JS11] menyediakan csrf_verify()
require_once __DIR__ . '/../includes/koneksi.php';

// [BARU-JS10] Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

csrf_verify(); // [BARU-JS11] tolak (HTTP 403) jika token CSRF tidak ada/tidak cocok, SEBELUM membaca input & menyentuh database

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // [BARU-JS10] $user harus ada DAN password cocok dengan hash (short-circuit: jika $user false, password_verify tidak dipanggil)
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true); // [BARU-JS10] ganti ID session setelah login (mencegah session fixation)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];
        header('Location: ../index.php');
        exit;
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat login.'];
    header('Location: login.php');
    exit;
}

// [BARU-JS10] Pesan sengaja digabung: tidak membocorkan apakah username atau password yang salah
$_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username atau password salah.'];
header('Location: login.php');
exit;