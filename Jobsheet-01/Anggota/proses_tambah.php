<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$nama  = trim($_POST['nama'] ?? '');
$nim   = trim($_POST['nim'] ?? '');
$email = trim($_POST['email'] ?? '');
$prodi = trim($_POST['prodi'] ?? '');

$errors = [];

if (empty($nama)) {
    $errors[] = "Nama lengkap wajib diisi.";
}
if (empty($nim)) {
    $errors[] = "NIM wajib diisi.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email tidak valid.";
}
if (empty($prodi)) {
    $errors[] = "Program studi wajib diisi.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old']    = $_POST;
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (nama, no_anggota, alamat, no_hp) 
         VALUES (:nama, :no_anggota, :alamat, :no_hp)"
    );

    $stmt->execute([
        'nama'       => $nama,
        'no_anggota' => $nim,
        'alamat'     => $email,
        'no_hp'      => $prodi
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.'];
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    $_SESSION['errors'] = ["Gagal menyimpan data ke database: " . $e->getMessage()];
    $_SESSION['old']    = $_POST;
    header('Location: tambah.php');
    exit;
}