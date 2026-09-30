<?php
require __DIR__ . '/../includes/auth.php'; // [MODIFIKASI-JS10] guard login WAJIB di baris pertama, sebelum header.php
include_once __DIR__ . '/../includes/header.php';
?>

<main>
    <section style="max-width: 600px; margin: 0 auto; padding: 1rem;">
        <h2>Tambah Anggota Baru</h2>

        <form action="proses_tambah.php" method="POST">
            <div style="margin-bottom: 1rem;">
                <label for="no_anggota" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">No Anggota *</label>
                <input type="text" name="no_anggota" id="no_anggota" placeholder="Contoh: ANG-001" required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="nama" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Nama Lengkap *</label>
                <input type="text" name="nama" id="nama" placeholder="Ketik nama anggota..." required style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="alamat" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" placeholder="Alamat lengkap..." style="width: 100%; padding: 8px; box-sizing: border-box;"></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="no_hp" style="display:block; margin-bottom: 0.3rem; font-weight: bold;">No HP / WhatsApp</label>
                <input type="text" name="no_hp" id="no_hp" placeholder="08123456789" style="width: 100%; padding: 8px; box-sizing: border-box;">
            </div>

            <div style="display: flex; gap: 10px; align-items: center;">
                <button type="submit" style="background-color: #1b5e20; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px;">Simpan</button>
                <a href="list.php" style="color: #333; text-decoration: none;">Batal</a>
            </div>
        </form>
    </section>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>