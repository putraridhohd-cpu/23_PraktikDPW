<?php
include_once __DIR__ . '/../includes/header.php';
?>

<main>
    <section style="max-width: 600px; margin: 0 auto; padding: 1rem;">
        <h2>Tambah Buku Baru</h2>

        <!-- Action mengarah ke proses_tambah.php dengan method POST -->
        <form action="proses_tambah.php" method="POST">
            <div style="margin-bottom: 1rem;">
                <label for="judul" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Judul Buku *</label>
                <input type="text" name="judul" id="judul" placeholder="Ketik judul buku..." required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="pengarang" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Pengarang *</label>
                <input type="text" name="pengarang" id="pengarang" placeholder="Nama pengarang..." required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="tahun" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" placeholder="Contoh: 2024" style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="isbn" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">ISBN</label>
                <input type="text" name="isbn" id="isbn" placeholder="Nomor ISBN..." style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="stok" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Jumlah Stok</label>
                <input type="number" name="stok" id="stok" value="0" min="0" style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="kategori" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Kategori</label>
                <select name="kategori" id="kategori" style="width: 100%; padding: 8px; box-sizing: border-box;">
                    <option value="Fiksi">Fiksi</option>
                    <option value="Non-Fiksi">Non-Fiksi</option>
                    <option value="Pelajaran">Pelajaran</option>
                    <option value="Komputer & Teknologi">Komputer & Teknologi</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit" class="btn-edit" style="background-color: #1b5e20; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">Simpan</button>
                <a href="list.php" style="color: #333; text-decoration: none;">Batal</a>
            </div>
        </form>
    </section>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>