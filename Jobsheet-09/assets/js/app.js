// ===== 1. Menu Hamburger (Responsive Mobile) =====
function initNavToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const nav = document.querySelector("header nav");
    if (!toggleBtn || !nav) return;
    
    toggleBtn.addEventListener("click", function () {
        nav.classList.toggle("nav-open");
    });
}

// ===== 2. Konfirmasi Hapus Data di Tabel =====
// Modifikasi fungsi init confirm
function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus");
        if (!btn) return;
        const row = btn.closest("tr");
        
        // Mengambil td kedua (nama) jika ada, jika tidak pakai td pertama (judul)
        const cells = row ? row.querySelectorAll("td") : [];
        const namaText = cells.length > 1 && cells[0].textContent.startsWith("A") 
            ? cells[1].textContent 
            : cells[0]?.textContent;

        const yakin = confirm("Yakin ingin menghapus \"" + (namaText || "data ini") + "\"?");
        if (yakin && row) {
            row.remove();
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

// ===== 4. Validasi Form Tambah =====
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