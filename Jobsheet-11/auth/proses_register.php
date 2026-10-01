<?php
// [BARU-JS10] Memproses registrasi: validasi, cek username duplikat, hash password, simpan ke tabel users
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/csrf.php'; // [BARU-JS11] menyediakan csrf_verify()
require_once __DIR__ . '/../includes/koneksi.php';

// [BARU-JS10] Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

csrf_verify(); // [BARU-JS11] tolak (HTTP 403) jika token CSRF tidak ada/tidak cocok, SEBELUM membaca input & menyentuh database

$nama     = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? ''; // [BARU-JS10] password TIDAK di-trim: spasi bisa jadi bagian password

// [BARU-JS10] Validasi server-side (minlength di HTML bisa dilewati, strlen di server tidak)
$errors = [];
if ($nama === '') {
    $errors[] = "Nama wajib diisi.";
}
if ($username === '') {
    $errors[] = "Username wajib diisi.";
}
if (strlen($username) > 50) { // [BARU-JS10] sesuai batas VARCHAR(50) di tabel users
    $errors[] = "Username maksimal 50 karakter.";
}
if (strlen($password) < 6) {
    $errors[] = "Password minimal 6 karakter.";
}

if (!empty($errors)) {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => implode(' ', $errors)];
    header('Location: register.php');
    exit;
}

try {
    // [BARU-JS10] Cek username duplikat supaya pesan error ramah (bukan error database mentah)
    $cek = $pdo->prepare("SELECT id FROM users WHERE username = :username");
    $cek->execute(['username' => $username]);
    if ($cek->fetch()) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Username sudah digunakan.'];
        header('Location: register.php');
        exit;
    }

    // [BARU-JS10] role ditulis langsung 'petugas' (bukan dari input) agar tidak ada yang mendaftar sebagai admin
    $stmt = $pdo->prepare(
        "INSERT INTO users (nama, username, password, role) VALUES (:nama, :username, :password, 'petugas')"
    );
    $stmt->execute([
        'nama'     => $nama,
        'username' => $username,
        'password' => password_hash($password, PASSWORD_DEFAULT), // [BARU-JS10] simpan HASH, bukan password asli
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Registrasi berhasil. Silakan login.'];
    header('Location: login.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat registrasi.'];
    header('Location: register.php');
    exit;
}