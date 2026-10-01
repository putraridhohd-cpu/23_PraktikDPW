-- [BARU-JS10] Jobsheet 10: tabel users (Petugas) untuk autentikasi
-- Jalankan di DBeaver (koneksi simpus_mini23) memakai Alt+X, atau: psql -d simpus_mini23 -f sql/02_users.sql
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'petugas'
);

-- [BARU-JS10] Query verifikasi struktur tabel (untuk screenshot laporan)
SELECT column_name, data_type, is_nullable, column_default
FROM information_schema.columns
WHERE table_name = 'users'
ORDER BY ordinal_position;

-- [BARU-JS10] Query verifikasi constraint: p = PRIMARY KEY, u = UNIQUE (untuk screenshot laporan)
SELECT conrelid::regclass AS tabel, conname, contype
FROM pg_constraint
WHERE conrelid = 'users'::regclass;