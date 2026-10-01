<?php
// Menghapus satu anggota. Sengaja HANYA menerima POST agar tidak terpicu lewat link/crawler.
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login (sekaligus memulai session), menggantikan blok session_start manual
require_once __DIR__ . '/../includes/csrf.php'; // [BARU-JS11] menyediakan csrf_verify(); di-require SETELAH auth.php (guard login jalan lebih dulu)
require_once __DIR__ . '/../includes/koneksi.php';

// Tolak akses selain POST (mis. diketik di address bar = GET) sebelum menyentuh database
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrf_verify(); // [BARU-JS11] tolak (HTTP 403) jika token CSRF tidak ada/tidak cocok, SEBELUM membaca input & menyentuh database

// id datang dari input hidden pada form Hapus di list.php
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

if ($id) {
    try {
        // DELETE ... WHERE id = :id  (WHERE WAJIB ada, kalau hilang SELURUH tabel terhapus)
        $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota tidak ditemukan.'];
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        // 23503 = pelanggaran foreign key (anggota masih dipakai tabel lain, mis. peminjaman)
        if ($e->getCode() === '23503') {
            $_SESSION['flash'] = ['type' => 'warning', 'pesan' => 'Anggota tidak bisa dihapus karena masih dipakai data lain.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat menghapus anggota.'];
        }
    }
}

header('Location: list.php');
exit;