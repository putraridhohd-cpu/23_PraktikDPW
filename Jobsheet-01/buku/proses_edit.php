<?php
// [BARU] Memproses form edit buku: validasi lalu UPDATE ke database
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
$id        = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
$judul     = trim($_POST['judul'] ?? '');
$pengarang = trim($_POST['pengarang'] ?? '');
$tahun     = trim($_POST['tahun'] ?? '');
$isbn      = trim($_POST['isbn'] ?? '');
$stok      = trim($_POST['stok'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');

// [BARU] Tanpa id, tidak ada baris yang bisa diubah
if (!$id) {
    header('Location: list.php');
    exit;
}

// [BARU] Validasi sama dengan proses_tambah.php; jika gagal kembali ke edit.php?id=... (bukan tambah.php)
if ($judul === '' || $pengarang === '') {
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Judul dan Pengarang wajib diisi!'];
    header('Location: edit.php?id=' . urlencode((string) $id));
    exit;
}

try {
    // [BARU] UPDATE ... SET ... WHERE id = :id  (WHERE WAJIB ada, kalau hilang SEMUA baris ikut berubah)
    $stmt = $pdo->prepare(
        "UPDATE buku
         SET judul = :judul, pengarang = :pengarang, tahun = :tahun,
             isbn = :isbn, stok = :stok, kategori = :kategori
         WHERE id = :id"
    );
    $stmt->execute([
        'judul'     => $judul,
        'pengarang' => $pengarang,
        'tahun'     => (int) $tahun,
        'isbn'      => $isbn,
        'stok'      => max(0, (int) $stok),
        'kategori'  => $kategori,
        'id'        => $id,
    ]);

    // [BARU] rowCount() = 0 berarti id tidak ditemukan di tabel
    if ($stmt->rowCount() === 0) {
        $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Data buku tidak ditemukan.'];
    } else {
        $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Data buku berhasil diperbarui.'];
    }
    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Terjadi kesalahan sistem saat menyimpan perubahan.'];
    header('Location: edit.php?id=' . urlencode((string) $id));
    exit;
}