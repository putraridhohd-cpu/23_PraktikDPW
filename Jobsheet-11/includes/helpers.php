<?php
// [BARU-JS11] Fungsi bantu umum. Di-require dari includes/header.php sehingga tersedia di semua halaman yang memuat header.

// [BARU-JS11] e() = "escape output": ubah karakter khusus HTML (< > & " ') menjadi entity supaya tampil sebagai TEKS, bukan dijalankan sebagai HTML/JavaScript (mencegah XSS).
if (!function_exists('e')) { // [BARU-JS11] pengaman: cegah error "Cannot redeclare e()" bila file ini ter-load dua kali
    function e($value) // [BARU-JS11]
    {
        // [BARU-JS11] (string) + ?? '' -> nilai null/angka tetap aman; ENT_QUOTES -> kutip tunggal & ganda ikut di-escape (penting untuk atribut value="...")
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}