
/* =========================================================
   SIDEBAR TOGGLE
========================================================= */

function initSidebarToggle() {
    const toggleBtn = document.getElementById("nav-toggle-btn");
    const sidebar = document.querySelector(".sidebar");

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener("click", function () {
        const isOpen = sidebar.classList.toggle("sidebar-open");

        toggleBtn.setAttribute("aria-expanded", String(isOpen));
    });
}


/* =========================================================
   TUTUP SIDEBAR KETIKA KLIK DI LUAR
========================================================= */

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
            toggleBtn.setAttribute("aria-expanded", "false");
        }
    });
}


/* =========================================================
   KONFIRMASI HAPUS
   Mendukung tombol/link .btn-hapus dan .btn-delete
========================================================= */

function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus, .btn-delete");

        if (!btn) return;

        const yakin = confirm(
            "Yakin ingin menghapus data ini?"
        );

        if (!yakin) {
            e.preventDefault();
        }
    });
}


/* =========================================================
   VALIDASI FORM
========================================================= */

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


function validasiWajib(input, pesan) {
    if (!input) return true;

    if (input.value.trim() === "") {
        tampilkanError(input, pesan);
        return false;
    }

    hapusError(input);
    return true;
}


function initValidasiForm() {
    const forms = document.querySelectorAll(
        "#form-tambah, #form-edit"
    );

    forms.forEach(function (form) {
        form.addEventListener("submit", function (e) {
            let valid = true;

            // NIS
            const nis = form.querySelector("[name='nis']");
            if (nis) {
                valid = validasiWajib(
                    nis,
                    "NIS wajib diisi."
                ) && valid;
            }

            // Nama siswa atau guru
            const nama = form.querySelector("[name='nama']");
            if (nama) {
                valid = validasiWajib(
                    nama,
                    "Nama wajib diisi."
                ) && valid;
            }

            // Nama program
            const namaProgram = form.querySelector(
                "[name='nama_program']"
            );
            if (namaProgram) {
                valid = validasiWajib(
                    namaProgram,
                    "Nama program wajib diisi."
                ) && valid;
            }

            // Jenis kelamin
            const jenisKelamin = form.querySelector(
                "[name='jenis_kelamin']"
            );
            if (jenisKelamin) {
                valid = validasiWajib(
                    jenisKelamin,
                    "Jenis kelamin wajib dipilih."
                ) && valid;
            }

            // NIP
            const nip = form.querySelector("[name='nip']");
            if (nip) {
                valid = validasiWajib(
                    nip,
                    "NIP wajib diisi."
                ) && valid;
            }

            // Email
            const email = form.querySelector("[name='email']");
            if (email && email.value.trim() !== "") {
                const polaEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!polaEmail.test(email.value.trim())) {
                    tampilkanError(
                        email,
                        "Format email tidak valid."
                    );
                    valid = false;
                } else {
                    hapusError(email);
                }
            }

            // Program siswa
            const programId = form.querySelector(
                "[name='program_id']"
            );
            if (programId) {
                valid = validasiWajib(
                    programId,
                    "Program wajib dipilih."
                ) && valid;
            }

            // Siswa pada form absensi
            const siswaId = form.querySelector(
                "[name='siswa_id']"
            );
            if (siswaId) {
                valid = validasiWajib(
                    siswaId,
                    "Siswa wajib dipilih."
                ) && valid;
            }

            // Tanggal absensi
            const tanggal = form.querySelector(
                "[name='tanggal']"
            );
            if (tanggal) {
                valid = validasiWajib(
                    tanggal,
                    "Tanggal wajib diisi."
                ) && valid;
            }

            // Status absensi
            const status = form.querySelector(
                "[name='status']"
            );
            if (status) {
                valid = validasiWajib(
                    status,
                    "Status wajib dipilih."
                ) && valid;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    });
}


/* =========================================================
   INITIALIZE
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    initSidebarToggle();
    initSidebarOutsideClick();
    initHapusConfirm();
    initValidasiForm();
});
