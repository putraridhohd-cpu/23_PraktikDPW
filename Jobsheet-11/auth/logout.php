<?php
// [BARU-JS10] Mengakhiri sesi login
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_SESSION = [];   // [BARU-JS10] kosongkan seluruh data session
session_destroy(); // [BARU-JS10] hancurkan session di server
header('Location: login.php');
exit;