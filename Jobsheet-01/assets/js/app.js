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
// [MODIFIKASI] Tombol Hapus sekarang berada di dalam <form class="form-hapus" method="post">
// yang benar-benar mengirim request ke hapus.php. Konfirmasi dilakukan pada event "submit"
// supaya bisa dibatalkan (preventDefault) SEBELUM data terkirim ke server.
function initHapusConfirm() {
    // [MODIFIKASI] Event delegation memakai "submit" (sebelumnya "click")
    document.addEventListener("submit", function (e) {
        const form = e.target;

        // [BARU] Hanya tangani form Hapus; form lain (cari, tambah, edit) dibiarkan normal
        if (!form.classList.contains("form-hapus")) return;

        const row = form.closest("tr");

        // [MODIFIKASI] Nama diambil dari atribut data-nama pada form; cadangannya sel <td> pertama
        const nama = (form.dataset.nama || (row ? row.querySelector("td")?.textContent : "") || "data ini").trim();

        const yakin = confirm("Yakin ingin menghapus \"" + nama + "\"?");

        // [MODIFIKASI] Logika dibalik: kalau pengguna menekan Cancel, batalkan pengiriman form.
        // Kalau OK, form lanjut submit ke hapus.php (row.remove() tidak dipakai lagi).
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

// ===== 4. Validasi Form Tambah / Edit =====
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

        if (!valid) {
            e.preventDefault(); // Mencegah form terkirim jika kosong
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