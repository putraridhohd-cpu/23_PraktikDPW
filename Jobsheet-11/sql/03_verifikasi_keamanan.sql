-- [BARU-JS11] Jobsheet 11: query verifikasi keamanan (untuk screenshot laporan).
-- [BARU-JS11] TIDAK ada perubahan struktur tabel di Jobsheet 11 (token CSRF disimpan di $_SESSION PHP, bukan di database).
-- [BARU-JS11] Jalankan SATU PER SATU di DBeaver (koneksi simpus_mini23): blok-kan query, lalu Ctrl+Enter.

-- [BARU-JS11] 1) Bukti XSS: payload tersimpan MENTAH di database. Yang dinetralkan adalah OUTPUT-nya (lewat e()), bukan datanya.
--    Jalankan setelah menambah buku berjudul <script>alert(1)</script> lewat form Tambah Buku.
SELECT id, judul, pengarang
FROM buku
WHERE judul LIKE '%<script>%';

-- [BARU-JS11] 2) Bukti password tersimpan sebagai HASH (bukan teks asli): hash bcrypt diawali $2y$ dan panjangnya 60 karakter.
SELECT id, username, role, LEFT(password, 7) AS awal_hash, LENGTH(password) AS panjang_hash
FROM users;

-- [BARU-JS11] 3) Bukti prepared statement: string ' OR '1'='1 dicari sebagai SATU nilai teks utuh -> tidak ada username yang cocok (0 baris).
--    (Tanda kutip di dalam literal SQL ditulis dobel: '' )
SELECT id, username
FROM users
WHERE username = ''' OR ''1''=''1';

-- [BARU-JS11] 4) Bersihkan data uji XSS setelah screenshot selesai (hapus tanda -- di depan baris DELETE untuk menjalankannya).
-- DELETE FROM buku WHERE judul LIKE '%<script>%';