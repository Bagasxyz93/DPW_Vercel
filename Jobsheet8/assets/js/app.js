// =========================================================
// SIDEBAR TOGGLE
// =========================================================

function initSidebarToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const sidebar = document.querySelector(".sidebar");

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener("click", function () {
        sidebar.classList.toggle("sidebar-open");
    });
}


// =========================================================
// KONFIRMASI HAPUS
// =========================================================

function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus");

        if (!btn) return;

        const yakin = confirm(
            "Yakin ingin menghapus data ini?"
        );

        if (!yakin) {
            e.preventDefault();
        }
    });
}


// =========================================================
// FILTER / PENCARIAN TABEL
// =========================================================

function initTableFilter() {
    const input = document.getElementById("search-input");
    const table = document.querySelector(".table-responsive table");

    if (!input || !table) return;

    input.addEventListener("input", function () {
        const keyword = input.value.toLowerCase().trim();
        const rows = table.querySelectorAll("tbody tr");

        rows.forEach(function (row) {
            const teks = row.textContent.toLowerCase();

            row.style.display = teks.includes(keyword)
                ? ""
                : "none";
        });
    });
}


// =========================================================
// VALIDASI FORM
// =========================================================

function tampilkanError(input, pesan) {
    hapusError(input);

    const span = document.createElement("span");

    span.className = "error";
    span.textContent = pesan;

    input.insertAdjacentElement("afterend", span);
}


function hapusError(input) {
    const next = input.nextElementSibling;

    if (
        next &&
        next.classList.contains("error")
    ) {
        next.remove();
    }
}


function initValidasiForm() {
    const form = document.getElementById("form-tambah");

    if (!form) return;

    form.addEventListener("submit", function (e) {
        let valid = true;


        // ==============================
        // NIS
        // ==============================

        const nis = form.querySelector("[name='nis']");

        if (nis) {
            if (nis.value.trim() === "") {
                tampilkanError(
                    nis,
                    "NIS wajib diisi."
                );

                valid = false;
            } else {
                hapusError(nis);
            }
        }


        // ==============================
        // NAMA
        // ==============================

        const nama = form.querySelector("[name='nama']");

        if (nama) {
            if (nama.value.trim() === "") {
                tampilkanError(
                    nama,
                    "Nama wajib diisi."
                );

                valid = false;
            } else {
                hapusError(nama);
            }
        }


        // ==============================
        // NAMA PROGRAM
        // ==============================

        const namaProgram = form.querySelector(
            "[name='nama_program']"
        );

        if (namaProgram) {
            if (namaProgram.value.trim() === "") {
                tampilkanError(
                    namaProgram,
                    "Nama program wajib diisi."
                );

                valid = false;
            } else {
                hapusError(namaProgram);
            }
        }


        // ==============================
        // JENIS KELAMIN
        // ==============================

        const jenisKelamin = form.querySelector(
            "[name='jenis_kelamin']"
        );

        if (jenisKelamin) {
            if (jenisKelamin.value === "") {
                tampilkanError(
                    jenisKelamin,
                    "Jenis kelamin wajib dipilih."
                );

                valid = false;
            } else {
                hapusError(jenisKelamin);
            }
        }


        // ==============================
        // NIP
        // ==============================

        const nip = form.querySelector("[name='nip']");

        if (nip) {
            if (nip.value.trim() === "") {
                tampilkanError(
                    nip,
                    "NIP wajib diisi."
                );

                valid = false;
            } else {
                hapusError(nip);
            }
        }


        // ==============================
        // EMAIL
        // ==============================

        const email = form.querySelector("[name='email']");

        if (email && email.value.trim() !== "") {
            const emailPattern =
                /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email.value.trim())) {
                tampilkanError(
                    email,
                    "Format email tidak valid."
                );

                valid = false;
            } else {
                hapusError(email);
            }
        }


        // ==============================
        // PROGRAM SISWA
        // ==============================

        const programId = form.querySelector(
            "[name='program_id']"
        );

        if (programId) {
            if (programId.value === "") {
                tampilkanError(
                    programId,
                    "Program wajib dipilih."
                );

                valid = false;
            } else {
                hapusError(programId);
            }
        }


        // ==============================
        // SISWA ABSENSI
        // ==============================

        const siswaId = form.querySelector(
            "[name='siswa_id']"
        );

        if (siswaId) {
            if (siswaId.value === "") {
                tampilkanError(
                    siswaId,
                    "Siswa wajib dipilih."
                );

                valid = false;
            } else {
                hapusError(siswaId);
            }
        }


        // ==============================
        // TANGGAL ABSENSI
        // ==============================

        const tanggal = form.querySelector(
            "[name='tanggal']"
        );

        if (tanggal) {
            if (tanggal.value === "") {
                tampilkanError(
                    tanggal,
                    "Tanggal wajib diisi."
                );

                valid = false;
            } else {
                hapusError(tanggal);
            }
        }


        // ==============================
        // STATUS ABSENSI
        // ==============================

        const status = form.querySelector(
            "[name='status']"
        );

        if (status) {
            if (status.value === "") {
                tampilkanError(
                    status,
                    "Status wajib dipilih."
                );

                valid = false;
            } else {
                hapusError(status);
            }
        }


        // ==============================
        // CEGAH SUBMIT
        // ==============================

        if (!valid) {
            e.preventDefault();
        }
    });
}


// =========================================================
// CLOSE SIDEBAR KETIKA KLIK DI LUAR
// =========================================================

function initSidebarOutsideClick() {
    const sidebar = document.querySelector(".sidebar");
    const toggleBtn = document.getElementById("nav-toggle-btn");

    if (!sidebar || !toggleBtn) return;

    document.addEventListener("click", function (e) {
        if (
            window.innerWidth <= 768 &&
            sidebar.classList.contains("sidebar-open") &&
            !sidebar.contains(e.target) &&
            !toggleBtn.contains(e.target)
        ) {
            sidebar.classList.remove("sidebar-open");
        }
    });
}


// =========================================================
// INITIALIZE
// =========================================================

document.addEventListener("DOMContentLoaded", function () {

    initSidebarToggle();

    initSidebarOutsideClick();

    initHapusConfirm();

    initTableFilter();

    initValidasiForm();

});