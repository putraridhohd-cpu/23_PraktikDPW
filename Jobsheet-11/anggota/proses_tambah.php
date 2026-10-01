<?php
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login WAJIB di baris pertama (sekaligus memulai session)
include_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_anggota = trim($_POST['no_anggota'] ?? '');
    $nama       = trim($_POST['nama'] ?? '');
    $alamat     = trim($_POST['alamat'] ?? '');
    $no_hp      = trim($_POST['no_hp'] ?? '');

    if (empty($no_anggota) || empty($nama)) {
        die("No Anggota dan Nama wajib diisi! <a href='tambah.php'>Kembali</a>");
    }

    try {
        if (isset($pdo)) {
            $sql = "INSERT INTO anggota (no_anggota, nama, alamat, no_hp) 
                    VALUES (:no_anggota, :nama, :alamat, :no_hp)";
            
            $stmt = $pdo->prepare($sql);
            $simpan = $stmt->execute([
                ':no_anggota' => $no_anggota,
                ':nama'       => $nama,
                ':alamat'     => $alamat,
                ':no_hp'      => $no_hp
            ]);

            if ($simpan) {
                header("Location: list.php");
                exit();
            }
        }
    } catch (PDOException $e) {
        die("Gagal menyimpan data anggota: " . $e->getMessage() . " <br><a href='tambah.php'>Kembali</a>");
    }
} else {
    header("Location: list.php");
    exit();
}
?>