<?php
// Memproses form tambah anggota: validasi lalu INSERT ke database
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login WAJIB di baris pertama (sekaligus memulai session)
require_once __DIR__ . '/../includes/csrf.php'; // [BARU-JS11] menyediakan csrf_verify(); di-require SETELAH auth.php (guard login jalan lebih dulu)
require_once __DIR__ . '/../includes/koneksi.php'; // [MODIFIKASI-JS11] include_once -> require_once (koneksi wajib ada; gagal = berhenti)

// [MODIFIKASI-JS11] Hanya menerima POST (sebelumnya memakai if/else besar; kini pola guard yang sama dengan file proses lainnya)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

csrf_verify(); // [BARU-JS11] tolak (HTTP 403) jika token CSRF tidak ada/tidak cocok, SEBELUM membaca input & menyentuh database

$no_anggota = trim($_POST['no_anggota'] ?? '');
$nama       = trim($_POST['nama'] ?? '');
$alamat     = trim($_POST['alamat'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

// [MODIFIKASI-JS11] === '' menggantikan empty() (empty("0") bernilai true, sehingga No. Anggota "0" salah dianggap kosong); pesan lewat flash, bukan die()
if ($no_anggota === '' || $nama === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No Anggota dan Nama wajib diisi!'];
    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare(
        "INSERT INTO anggota (no_anggota, nama, alamat, no_hp)
         VALUES (:no_anggota, :nama, :alamat, :no_hp)"
    );
    $stmt->execute([
        'no_anggota' => $no_anggota,
        'nama'       => $nama,
        'alamat'     => $alamat,
        'no_hp'      => $no_hp,
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Anggota berhasil ditambahkan.']; // [BARU-JS11] umpan balik sukses, seperti pada modul buku
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage()); // [MODIFIKASI-JS11] detail error hanya ke log server; TIDAK lagi dicetak ke pengguna (sebelumnya die() + getMessage())
    // [BARU-JS11] 23505 = pelanggaran UNIQUE (No Anggota sudah dipakai anggota lain)
    if ($e->getCode() === '23505') {
        $_SESSION['flash'] = ['type' => 'warning', 'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat menyimpan anggota.'];
    }
    header('Location: tambah.php');
    exit;
}