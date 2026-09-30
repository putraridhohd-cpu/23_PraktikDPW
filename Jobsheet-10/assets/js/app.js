// ===== 1. Menu Hamburger (Responsive Mobile) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;

    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== 2. Konfirmasi Hapus Data (Form Hapus) =====
// Tombol Hapus berada di dalam <form class="form-hapus" method="post">
// yang benar-benar mengirim request ke server. Konfirmasi dilakukan pada event "submit"
// supaya bisa dibatalkan (preventDefault) SEBELUM data terkirim ke server.
function initHapusConfirm() {
    document.addEventListener("submit", function (e) {
        const form = e.target;

        if (!form.classList.contains("form-hapus")) return;

        const row = form.closest("tr");

        const nama = (form.dataset.nama || (row ? row.querySelector("td")?.textContent : "") || "data ini").trim();

        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");

        if (!yakin) {
            e.preventDefault();
        }
    });
}

// ===== 3. Filter / Pencarian Tabel Real-Time =====
function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");
    if (!input || !table) return;

    input.addEventListener("keyup", function () {
        const keyword = input.value.toLowerCase();
        const rows = table.querySelectorAll("tbody tr");
        rows.forEach(function (row) {
            const teks = row.textContent.toLowerCase();
            row.style.display = teks.includes(keyword) ? "" : "none";
        });
    });
}

// ===== 4. Validasi Form Tambah / Edit + Konfirmasi Ekstra sebelum Update =====
function tampilkanError(input, pesan) {
    hapusError(input);
    const span = document.createElement("span");
    span.className = "error";
    span.textContent = pesan;
    input.insertAdjacentElement("afterend", span);
}

function hapusError(input) {
    const next = input.nextElementSibling;
    if (next && next.classList.contains("error")) {
        next.remove();
    }
}

function initValidasiForm() {
    const form = document.getElementById("form-tambah");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;
        const judul = form.querySelector("[name='judul'], [name='nama']");

        if (judul && judul.value.trim() === "") {
            tampilkanError(judul, "Field ini wajib diisi.");
            valid = false;
        } else if (judul) {
            hapusError(judul);
        }

        // [MODIFIKASI] Kalau validasi gagal, hentikan di sini (jangan lanjut ke pengecekan konfirmasi update di bawah)
        if (!valid) {
            e.preventDefault();
            return;
        }

        // [BARU] Ide Latihan Tambahan no.23: konfirmasi ekstra sebelum menyimpan perubahan (Update).
        // Hanya berlaku untuk form Edit (diberi class "form-edit" di edit.php) — form Tambah tidak perlu konfirmasi ini,
        // karena Tambah tidak menimpa data lama, sedangkan Update mengganti data yang sudah ada.
        if (form.classList.contains("form-edit")) {
            const yakin = confirm("Yakin ingin menyimpan perubahan data ini?");
            if (!yakin) {
                e.preventDefault(); // [BARU] batalkan submit kalau pengguna menekan Cancel
            }
        }
    });
}

// Inisialisasi semua fungsi saat DOM selesai dimuat
document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initHapusConfirm();
    initTableFilter();
    initValidasiForm();
});