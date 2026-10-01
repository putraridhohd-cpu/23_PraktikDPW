<?php
// Memproses form tambah buku: validasi lalu INSERT ke database
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login (sekaligus memulai session), menggantikan blok session_start manual
require_once __DIR__ . '/../includes/csrf.php'; // [BARU-JS11] menyediakan csrf_verify(); di-require SETELAH auth.php (guard login jalan lebih dulu)
require_once __DIR__ . '/../includes/koneksi.php';

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrf_verify(); // [BARU-JS11] tolak (HTTP 403) jika token CSRF tidak ada/tidak cocok, SEBELUM membaca input & menyentuh database

// Ambil field form buku dan buang spasi di awal/akhir
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

// Validasi: judul dan pengarang wajib diisi
if ($judul === '' || $pengarang === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Judul dan Pengarang wajib diisi!'];
    header('Location: tambah.php');
    exit;
}

try {
    // INSERT ke tabel buku dengan prepared statement
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