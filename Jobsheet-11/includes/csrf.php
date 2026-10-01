<?php
// [BARU-JS11] Proteksi CSRF: token acak per-sesi, disisipkan ke setiap form POST, lalu diverifikasi SEBELUM aksi menyentuh database.
require_once __DIR__ . '/helpers.php'; // [BARU-JS11] csrf_field() memakai e()

if (session_status() === PHP_SESSION_NONE) { // [BARU-JS11] token disimpan di $_SESSION, jadi session harus aktif
    session_start();
}

// [BARU-JS11] Membuat token sekali per sesi; panggilan berikutnya memakai token yang sama.
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) { // [BARU-JS11] belum ada -> buat baru
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // [BARU-JS11] 32 byte acak (aman secara kriptografis) -> 64 karakter heksadesimal
    }
    return $_SESSION['csrf_token'];
}

// [BARU-JS11] Menghasilkan <input type="hidden"> berisi token; dipakai dengan <?= csrf_field(); ?> di dalam setiap <form method="post">.
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

// [BARU-JS11] Membandingkan token kiriman form dengan token di session; jika tidak cocok -> HTTP 403 dan proses dihentikan.
function csrf_verify()
{
    $token = $_POST['csrf_token'] ?? '';
    // [BARU-JS11] is_string() menolak kiriman berbentuk array (csrf_token[]=x) yang bisa memicu error fatal di hash_equals()
    if (!is_string($token) || $token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403); // [BARU-JS11] 403 Forbidden: server paham permintaannya, tapi menolak memprosesnya
        die('Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa.'); // [BARU-JS11] die() menghentikan eksekusi sebelum INSERT/UPDATE/DELETE
    }
}