# Wireframe & User Flow — SIMPUS-Mini

## Aktor
- **Tamu:** Hanya bisa melihat katalog buku (Beranda, Daftar Buku) tanpa login[cite: 1, 2].
- **Petugas:** Login untuk mengakses seluruh fitur CRUD dan transaksi peminjaman/pengembalian[cite: 1, 2].

---

## User Flow

### 1. Peminjaman Buku
[Petugas Login] -> [Dashboard] -> [Pilih menu "Peminjaman Baru"] -> [Pilih Anggota] -> [Pilih Buku (stok > 0)] -> [Simpan] -> [Stok buku berkurang 1] -> [Kembali ke Dashboard][cite: 1, 2]

### 2. Pengembalian Buku
[Dashboard] -> [Menu "Pengembalian"] -> [Cari transaksi aktif (anggota/buku)] -> [Tandai "Dikembalikan"] -> [Stok buku bertambah 1] -> [Kembali ke Dashboard][cite: 1, 2]

---

## Wireframe Tampilan

### 1. Halaman Login
+--------------------------------------+
|              SIMPUS-Mini             |
|--------------------------------------|
|                                      |
|        [ Login Petugas ]             |
|                                      |
|   Username : [______________]        |
|   Password : [______________]        |
|                                      |
|          [   Masuk   ]               |
|                                      |
|   Belum punya akun? Daftar di sini   |
+--------------------------------------+[cite: 1, 2]

### 2. Dashboard Petugas
+-----------------------------------------------------+
| SIMPUS-Mini      Beranda | Buku | Anggota | Peminjaman | (Nama Petugas) Logout |
|-------------------------------------------------------|
|  [Total Buku]   [Total Anggota]   [Sedang Dipinjam]    |
|                                                         |
|  Aksi Cepat:                                           |
|  [ + Peminjaman Baru ]   [ + Pengembalian ]            |
|                                                         |
|  Transaksi Terbaru                                     |
|  --------------------------------------------------    |
|  Anggota | Buku | Tgl Pinjam | Status                  |
+-----------------------------------------------------+[cite: 1, 2]

### 3. Form Peminjaman
+--------------------------------------+
|  Form Peminjaman Buku                |
|--------------------------------------|
|  Anggota : [ dropdown pilih anggota ]|
|  Buku    : [ dropdown, hanya stok>0 ]|
|  Tanggal Pinjam : [ auto: hari ini ] |
|                                      |
|          [  Simpan Peminjaman  ]     |
+--------------------------------------+[cite: 1]

### 4. Form Pengembalian
+--------------------------------------+
|  Pengembalian Buku                   |
|--------------------------------------|
|  Cari transaksi aktif:               |
|  [ nama anggota / judul buku ______ ]|
|                                      |
|  Anggota | Buku | Tgl Pinjam | [Kembalikan] |
+--------------------------------------+[cite: 1]

### 5. Riwayat Peminjaman per Anggota
+--------------------------------------+
|  Riwayat Peminjaman — Siti Aminah    |
|--------------------------------------|
|  Buku            | Pinjam   | Kembali | Status      |
|  Laskar Pelangi   | 01/07    | 10/07   | Selesai     |
|  Bumi Manusia      | 15/07    | -       | Dipinjam    |
+--------------------------------------+[cite: 1]

---

## Catatan Desain
- Warna aksen, tipografi navbar, dan gaya tabel/kartu mengikuti `assets/css/style.css` yang sudah dibangun[cite: 1, 2].
- Navbar akan ditambah menu Peminjaman dan indikator status login mulai diimplementasikan pada Jobsheet 10[cite: 1, 2].
- Buku dengan stok habis tidak boleh muncul di form peminjaman[cite: 1, 2].