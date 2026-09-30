<?php
// [BARU] Memproses form edit anggota: validasi lalu UPDATE ke database
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/koneksi.php';

// [BARU] Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

// [BARU] id diambil dari $_POST (dikirim lewat input hidden), bukan $_GET
$id         = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$no_anggota = trim($_POST['no_anggota'] ?? '');
$nama       = trim($_POST['nama'] ?? '');
$alamat     = trim($_POST['alamat'] ?? '');
$no_hp      = trim($_POST['no_hp'] ?? '');

// [BARU] Tanpa id, tidak ada baris yang bisa diubah
if (!$id) {
    header('Location: list.php');
    exit;
}

// [BARU] Validasi sama dengan proses_tambah.php; jika gagal kembali ke edit.php?id=... (bukan tambah.php)
if ($no_anggota === '' || $nama === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'No Anggota dan Nama wajib diisi!'];
    header('Location: edit.php?id=' . urlencode((string) $id));
    exit;
}

try {
    // [BARU] UPDATE ... SET ... WHERE id = :id  (WHERE WAJIB ada, kalau hilang SEMUA baris ikut berubah)
    $stmt = $pdo->prepare(
        "UPDATE anggota
         SET no_anggota = :no_anggota, nama = :nama, alamat = :alamat, no_hp = :no_hp
         WHERE id = :id"
    );
    $stmt->execute([
        'no_anggota' => $no_anggota,
        'nama'       => $nama,
        'alamat'     => $alamat,
        'no_hp'      => $no_hp,
        'id'         => $id,
    ]);

    // [BARU] rowCount() = 0 berarti id tidak ditemukan di tabel
    if ($stmt->rowCount() === 0) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data anggota tidak ditemukan.'];
    } else {
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data anggota berhasil diperbarui.'];
    }
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    // [BARU] 23505 = pelanggaran UNIQUE (No Anggota sudah dipakai anggota lain)
    if ($e->getCode() === '23505') {
        $_SESSION['flash'] = ['type' => 'warning', 'pesan' => 'No. Anggota sudah dipakai, gunakan nomor lain.'];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat menyimpan perubahan.'];
    }
    header('Location: edit.php?id=' . urlencode((string) $id));
    exit;
}