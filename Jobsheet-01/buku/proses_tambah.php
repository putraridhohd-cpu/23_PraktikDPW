<?php
// [MODIFIKASI] File ini sebelumnya berisi kode ANGGOTA (no_anggota, email). Diganti dengan kode BUKU.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// [MODIFIKASI] Ambil field form buku dan buang spasi di awal/akhir
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

// [MODIFIKASI] Validasi: judul dan pengarang wajib diisi
if ($judul === '' || $pengarang === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Judul dan Pengarang wajib diisi!'];
    header('Location: tambah.php');
    exit;
}

try {
    // [MODIFIKASI] INSERT ke tabel buku dengan prepared statement
    $stmt = $pdo->prepare(
        "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori)
         VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
    );
    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn,
        'stok'      => max(0, (int) $stok),
        'kategori'  => $kategori,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat menyimpan buku.'];
    header('Location: tambah.php');
    exit;
}