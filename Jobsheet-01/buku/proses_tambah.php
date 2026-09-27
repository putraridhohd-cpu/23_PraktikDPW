<?php
include_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul'] ?? '');
    $pengarang = trim($_POST['pengarang'] ?? '');
    $tahun     = !empty($_POST['tahun']) ? (int)$_POST['tahun'] : date('Y');
    $isbn      = trim($_POST['isbn'] ?? '');
    $stok      = !empty($_POST['stok']) ? (int)$_POST['stok'] : 0;
    $kategori  = trim($_POST['kategori'] ?? 'Fiksi');

    if (empty($judul) || empty($pengarang)) {
        die("Judul dan Pengarang wajib diisi! <a href='tambah.php'>Kembali</a>");
    }

    try {
        if (isset($pdo)) {
            // MODIFIKASI: Query Insert PostgreSQL/MySQL via PDO Prepared Statement
            $sql = "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) 
                    VALUES (:judul, :pengarang, :tahun, :isbn, :stok, :kategori)";
            
            $stmt = $pdo->prepare($sql);
            $simpan = $stmt->execute([
                ':judul'     => $judul,
                ':pengarang' => $pengarang,
                ':tahun'     => $tahun,
                ':isbn'      => $isbn,
                ':stok'      => $stok,
                ':kategori'  => $kategori
            ]);

            if ($simpan) {
                header("Location: list.php");
                exit();
            }
        } else if (isset($koneksi) && is_resource($koneksi)) {
            // Fallback pg_query
            $query = "INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) 
                      VALUES ($1, $2, $3, $4, $5, $6)";
            $res = pg_query_params($koneksi, $query, [$judul, $pengarang, $tahun, $isbn, $stok, $kategori]);
            if ($res) {
                header("Location: list.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        die("Gagal menyimpan data ke PostgreSQL: " . $e->getMessage() . " <br><a href='tambah.php'>Kembali</a>");
    }
} else {
    header("Location: list.php");
    exit();
}
?>