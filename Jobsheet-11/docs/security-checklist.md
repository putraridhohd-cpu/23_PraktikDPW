# Security Checklist — SIMPUS-Mini (Jobsheet 11)

Audit keamanan dasar terhadap kode Jobsheet 7–10.

- **Tanggal audit:** 30 September 2026
- **Auditor:** (Valent absen 23)
- **Cakupan:** seluruh folder `auth/`, `buku/`, `anggota/`, `includes/`, dan file di root proyek.

> Cara membaca: kolom **Sebelum** = kondisi kode Jobsheet 10, kolom **Sesudah** = kondisi kode Jobsheet 11.
> Kotak `[]` di bagian "Bukti pengujian" dicentang **setelah** pengujian benar-benar dijalankan dan hasilnya sesuai.
> Catatan Windows PowerShell: tulis `curl.exe` (bukan `curl`, karena `curl` di PowerShell adalah alias `Invoke-WebRequest`).

## Ringkasan Audit

| # | Kerentanan | Ditemukan di | Sebelum | Sesudah (perbaikan) |
|---|------------|--------------|---------|---------------------|
| 1 | SQL Injection | `auth/`, `buku/`, `anggota/` (semua query) | Sudah memakai prepared statement (`prepare()` + placeholder `:parameter` + `execute([...])`). Tidak ada `$_POST`/`$_GET` yang digabung ke string SQL. | **Diaudit ulang, sudah aman — tidak ada perubahan kode.** |
| 2 | XSS (Cross-Site Scripting) | `includes/header.php`, `auth/login.php`, `auth/register.php`, `buku/*.php`, `anggota/*.php` | Output dibungkus `htmlspecialchars()` yang ditulis manual di tiap tempat (tanpa `ENT_QUOTES` eksplisit). `$page_title` di `<title>` dicetak tanpa escape. | Semua output data dibungkus fungsi `e()` (`includes/helpers.php`, memakai `ENT_QUOTES` + `UTF-8`), termasuk `$page_title`. Tidak tersisa `htmlspecialchars()` langsung di halaman. |
| 3 | CSRF | Semua form `method="post"`: Login, Register, Tambah/Edit/Hapus Buku, Tambah/Edit/Hapus Anggota | Form POST hanya dilindungi pengecekan `REQUEST_METHOD === 'POST'` — situs lain tetap bisa mengirim POST memakai cookie session korban. | Token acak per-sesi (`csrf_token()`) disisipkan lewat `csrf_field()` ke semua form POST dan diverifikasi `csrf_verify()` (dengan `hash_equals()`) di semua `proses_*.php` dan `hapus.php`. Token salah/kosong -> HTTP 403. |
| 4 | Validasi & Sanitasi Input | `auth/proses_register.php`, `buku/proses_*.php`, `anggota/proses_*.php` | Validasi wajib-isi (`=== ''`), `trim()`, `filter_var(..., FILTER_VALIDATE_INT)` untuk id, casting `(int)` untuk tahun/stok. `anggota/proses_tambah.php` memakai `empty()` (nilai `"0"` dianggap kosong). | Diaudit ulang, sudah memadai. Perbaikan kecil: `anggota/proses_tambah.php` kini memakai `=== ''` seperti file lain. `csrf_verify()` juga menolak token berbentuk array (`is_string()`). |
| 5 | Session Fixation | `auth/proses_login.php` | `session_regenerate_id(true)` **sudah ada** tepat setelah `password_verify()` berhasil. | Dipertahankan (tidak diubah). Registrasi tidak perlu (tidak langsung login), logout memakai `session_destroy()`. |
| 6 | Kebocoran pesan error mentah (temuan tambahan) | `anggota/proses_tambah.php` | `die("Gagal menyimpan data anggota: " . $e->getMessage())` menampilkan pesan error PostgreSQL apa adanya ke pengguna (nama tabel, constraint, dst.). | Error dicatat lewat `error_log()`, pengguna hanya melihat pesan umum lewat flash message; duplikat No. Anggota (SQLSTATE `23505`) diberi pesan ramah. |

## Bukti Pengujian

### 1. SQL Injection
- [X] Buka `auth/login.php`, isi username `' OR '1'='1` dan password bebas -> tetap muncul "Username atau password salah."
- [X] (Opsional, DBeaver) jalankan query nomor 3 di `sql/03_verifikasi_keamanan.sql` -> hasil 0 baris, menunjukkan string itu diperlakukan sebagai satu nilai teks, bukan perintah SQL.

### 2. XSS
- [X] Login, tambah buku dengan judul `<script>alert(1)</script>` -> di `buku/list.php` teks tampil apa adanya, **tidak ada** pop-up `alert`.
- [X] Buka `buku/edit.php?id=...` untuk buku tersebut -> isi kolom Judul menampilkan teks yang sama (bukan menjalankan skrip).
- [X] (Opsional, DBeaver) query nomor 1 di `sql/03_verifikasi_keamanan.sql` -> data di database tetap tersimpan mentah; yang dinetralkan adalah **output**-nya.

### 3. CSRF
- [X] Login lewat browser, salin cookie `PHPSESSID` dari DevTools (Application -> Cookies).
- [X] `curl.exe -i -X POST http://localhost:8000/buku/proses_tambah.php -b "PHPSESSID=<isi cookie>" -d "judul=x"` -> **HTTP 403** dan pesan "Permintaan ditolak: token CSRF tidak valid atau kedaluwarsa."
- [X] `View Page Source` pada form Tambah Buku -> ada `<input type="hidden" name="csrf_token" value="...">` berisi 64 karakter heksadesimal.
- [X] Tambah buku lewat form biasa di browser -> tetap berhasil (token valid).

### 4. Validasi & Sanitasi Input
- [X] Kosongkan Judul (hapus atribut `required` lewat DevTools) lalu kirim -> muncul "Judul dan Pengarang wajib diisi!" dari server.
- [X] Tambah anggota dengan No. Anggota `0` -> tidak lagi dianggap kosong.

### 5. Session Fixation
- [X] Catat nilai `PHPSESSID` di halaman Login (sebelum login), lalu login -> nilai `PHPSESSID` **berubah** setelah berhasil login.

### 6. Kebocoran pesan error
- [X] Tambah anggota dengan No. Anggota yang sudah ada -> muncul pesan "No. Anggota sudah dipakai, gunakan nomor lain." (bukan teks error PostgreSQL).

## Catatan Implementasi

- **Urutan guard di halaman proses:** `includes/auth.php` selalu dijalankan **sebelum** `includes/csrf.php` dan `csrf_verify()`. Pengunjung yang belum login langsung dialihkan ke Login (HTTP 302) tanpa sempat memicu pengecekan token. Uji: `curl.exe -i -X POST http://localhost:8000/buku/proses_tambah.php -d "judul=x"` **tanpa** cookie -> 302 ke login, bukan 403.
- **Posisi `csrf_verify()`:** dipanggil setelah pengecekan `REQUEST_METHOD` (akses GET tetap dialihkan ke list) dan sebelum input dibaca / database disentuh.
- **Halaman publik:** `auth/proses_login.php` dan `auth/proses_register.php` tidak memakai guard login (memang untuk pengunjung), tetapi tetap memakai `csrf_verify()`.
- **`login.php` di root proyek:** form statis lama (tanpa token, `action`-nya menunjuk ke file yang tidak ada di root) diganti pengalihan ke `auth/login.php` supaya tidak ada form login kedua yang tidak terlindungi.
- **Diterima / tindak lanjut (di luar cakupan jobsheet ini):**
  - `auth/logout.php` diakses lewat tautan GET — risiko "logout paksa" oleh situs lain tergolong rendah, bisa diperbaiki dengan mengubah logout menjadi form POST + token.
  - `includes/koneksi.php` masih mencetak `$e->getMessage()` saat koneksi database gagal — sebaiknya diganti `error_log()` + pesan umum; kredensial database juga sebaiknya dipindah ke file konfigurasi terpisah yang tidak ikut dibagikan/di-commit.
  - Kolom Tahun yang dikosongkan akan tersimpan sebagai `0` (hasil `(int) ''`) — perlu validasi rentang tahun jika ingin data lebih ketat.
  - Lapisan tambahan yang bisa dipelajari: header `Content-Security-Policy` (terhadap XSS) dan atribut cookie session `SameSite`/`HttpOnly` (terhadap CSRF/pencurian cookie).