<?php
// Memulai session untuk menyimpan flash message
session_start();

// Memanggil file koneksi ke database
require_once '../includes/koneksi.php';

// Memeriksa apakah form dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil data dari form dan menghapus spasi di awal/akhir
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $nama       = trim($_POST['nama'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    // Validasi sederhana: pastikan data utama tidak kosong
    if (empty($no_anggota) || empty($nama)) {
        $_SESSION['flash'] = [
            'type'    => 'danger',
            'message' => 'Nomor Anggota dan Nama wajib diisi!'
        ];
        header('Location: tambah.php');
        exit;
    }

    // MODIFIKASI (Poin 25): Menggunakan try-catch PDOException untuk menangani error UNIQUE constraint
    try {
        // Menyiapkan query insert data anggota
        $sql = "INSERT INTO anggota (no_anggota, nama, email, no_hp) VALUES (:no_anggota, :nama, :email, :no_hp)";
        $stmt = $pdo->prepare($sql);

        // Eksekusi query dengan mengikat parameter
        $stmt->execute([
            ':no_anggota' => $no_anggota,
            ':nama'       => $nama,
            ':email'      => $email,
            ':no_hp'      => $no_hp
        ]);

        // Jika berhasil, set flash message sukses
        $_SESSION['flash'] = [
            'type'    => 'success',
            'message' => 'Data anggota berhasil ditambahkan!'
        ];
        header('Location: list.php');
        exit;

    } catch (PDOException $e) {
        // MODIFIKASI: Cek apakah error disebabkan oleh pelanggaran UNIQUE constraint (kode SQLSTATE 23505 pada PostgreSQL / MySQL)
        if ($e->getCode() === '23505' || stristr($e->getMessage(), 'unique') !== false) {
            $_SESSION['flash'] = [
                'type'    => 'warning',
                'message' => 'No. Anggota sudah dipakai, gunakan nomor lain.'
            ];
        } else {
            // Jika error lain, tampilkan pesan error umum tanpa membocorkan info sistem
            $_SESSION['flash'] = [
                'type'    => 'danger',
                'message' => 'Terjadi kesalahan sistem saat menyimpan data.'
            ];
        }

        // Kembali ke halaman form tambah anggota
        header('Location: tambah.php');
        exit;
    }
} else {
    // Jika diakses tanpa POST, kembalikan ke list anggota
    header('Location: list.php');
    exit;
}