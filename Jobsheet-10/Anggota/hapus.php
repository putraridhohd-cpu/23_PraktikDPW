<?php
// [BARU] Menghapus satu anggota. Sengaja HANYA menerima POST agar tidak terpicu lewat link/crawler.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

// [BARU] Tolak akses selain POST (mis. diketik di address bar = GET) sebelum menyentuh database
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// [BARU] id datang dari input hidden pada form Hapus di list.php
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);

if ($id) {
    try {
        // [BARU] DELETE ... WHERE id = :id  (WHERE WAJIB ada, kalau hilang SELURUH tabel terhapus)
        $stmt = $pdo->prepare("DELETE FROM anggota WHERE id = :id");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil dihapus.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Anggota tidak ditemukan.'];
        }
    } catch (PDOException $e) {
        error_log($e->getMessage());
        // [BARU] 23503 = pelanggaran foreign key (anggota masih dipakai tabel lain, mis. peminjaman)
        if ($e->getCode() === '23503') {
            $_SESSION['flash'] = ['type' => 'warning', 'pesan' => 'Anggota tidak bisa dihapus karena masih dipakai data lain.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat menghapus anggota.'];
        }
    }
}

header('Location: list.php');
exit;