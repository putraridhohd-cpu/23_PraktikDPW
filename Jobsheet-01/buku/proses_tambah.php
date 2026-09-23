<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok       = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

$errors = [];

if (empty($judul)) {
    $errors[] = "Judul buku wajib diisi.";
}
if (empty($pengarang)) {
    $errors[] = "Nama pengarang wajib diisi.";
}
if (empty($tahun) || !is_numeric($tahun)) {
    $errors[] = "Tahun terbit harus berupa angka.";
}
if (empty($stok) || !is_numeric($stok) || (int)$stok < 0) {
    $errors[] = "Stok harus berupa angka non-negatif.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $_POST;
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) 
         VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)"
    );

    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn,
        'stok'      => (int) $stok,
        'kategori'  => $kategori,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Gagal menyimpan data ke database: " . $e->getMessage()];
    $_SESSION['old']    = $_POST;
    header('Location: tambah.php');
    exit;
}