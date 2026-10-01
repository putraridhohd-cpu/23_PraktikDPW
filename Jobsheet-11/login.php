<?php
// [MODIFIKASI-JS11] File lama: form login statis tanpa token CSRF, action-nya menunjuk ke proses_login.php yang tidak ada di root.
// [MODIFIKASI-JS11] Diganti pengalihan ke halaman Login resmi (auth/login.php) supaya tidak ada form login kedua yang tidak terlindungi.
header('Location: auth/login.php'); // [MODIFIKASI-JS11]
exit; // [MODIFIKASI-JS11]