<?php
// =========================================================
// KONFIGURASI KONEKSI DATABASE (POSTGRESQL & MYSQL)
// =========================================================

/* MODIFIKASI: Disesuaikan dengan database simpus_mini23 dari DBeaver (image_4db135.png) */

$db_driver = 'pgsql'; // Pilihan: 'pgsql' (PostgreSQL DBeaver) atau 'mysqli' (MySQL XAMPP)

$host     = "localhost";
$port     = "5432";
$user     = "postgres";
$pass     = "Postgres080625"; 
$dbname   = "simpus_mini23"; // MODIFIKASI: Diubah dari 'postgres' ke 'simpus_mini23' sesuai DBeaver

try {
    if ($db_driver === 'pgsql') {
        // MODIFIKASI: Inisialisasi PDO PostgreSQL ke database simpus_mini23
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        // MODIFIKASI: Menyediakan pula koneksi pg_connect
        $conn_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$pass}";
        $koneksi = @pg_connect($conn_string);
        $conn = $koneksi;

    } else {
        // MODIFIKASI: Fallback PDO MySQL
        $dsn = "mysql:host={$host};dbname=db_simpus;charset=utf8mb4";
        $pdo = new PDO($dsn, "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $koneksi = mysqli_connect($host, "root", "", "db_simpus");
        $conn = $koneksi;
    }
} catch (PDOException $e) {
    die("Koneksi Database Gagal: " . $e->getMessage());
}
?>