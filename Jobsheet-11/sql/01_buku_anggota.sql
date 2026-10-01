CREATE TABLE IF NOT EXISTS buku (
    id SERIAL PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    pengarang VARCHAR(255) NOT NULL,
    tahun INTEGER NOT NULL,
    isbn VARCHAR(50),
    stok INTEGER NOT NULL DEFAULT 0,
    kategori VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS anggota (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    no_anggota VARCHAR(50) NOT NULL UNIQUE,
    alamat VARCHAR(255),
    no_hp VARCHAR(30)
);

SELECT table_name, column_name, data_type, is_nullable, column_default
FROM information_schema.columns
WHERE table_name IN ('buku', 'anggota')
ORDER BY table_name, ordinal_position;

SELECT conrelid::regclass AS tabel, conname, contype
FROM pg_constraint
WHERE conrelid IN ('buku'::regclass, 'anggota'::regclass);

INSERT INTO buku (judul, pengarang, tahun, isbn, stok, kategori) VALUES
('Belajar PHP Dasar',          'Andi Wijaya',  2020, '978-602-0001', 4, 'Komputer & Teknologi'),
('Dasar Basis Data',           'Sari Dewi',    2019, '978-602-0002', 3, 'Pelajaran'),
('Petualangan Senja',          'Raka Pratama', 2018, '978-602-0003', 6, 'Fiksi'),
('Sejarah Nusantara',          'Budi Santoso', 2015, '978-602-0004', 2, 'Non-Fiksi'),
('Algoritma dan Pemrograman',  'Rina Lestari', 2021, '978-602-0005', 5, 'Komputer & Teknologi');

INSERT INTO anggota (nama, no_anggota, alamat, no_hp) VALUES
('Siti Aminah',   '254107020052', 'Jl. Melati No. 5 Sidoarjo',   '081200000001'),
('Budi Hartono',  '254107020053', 'Jl. Mawar No. 12 Surabaya',   '081200000002'),
('Dewi Anggraini','254107020054', 'Jl. Kenanga No. 8 Sidoarjo',  '081200000003'),
('Rizky Pratama', '254107020055', 'Jl. Anggrek No. 3 Gresik',    '081200000004'),
('Nur Aisyah',    '254107020056', 'Jl. Dahlia No. 21 Mojokerto', '081200000005');

SELECT setval(pg_get_serial_sequence('buku', 'id'),    (SELECT MAX(id) FROM buku));
SELECT setval(pg_get_serial_sequence('anggota', 'id'), (SELECT MAX(id) FROM anggota));

select * from buku;

