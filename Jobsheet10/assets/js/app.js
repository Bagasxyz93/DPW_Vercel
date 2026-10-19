
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
   Mendukung .btn-hapus dan .btn-delete
========================================================= */

function initHapusConfirm() {
    document.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-hapus, .btn-delete");

        if (!btn) return;

        // Jangan konfirmasi jika tombol sudah dinonaktifkan.
        if (btn.disabled) {
            e.preventDefault();
            return;
        }

        const yakin = window.confirm(
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

/**
 * Menghapus pesan error yang dibuat oleh validasi JS.
 */
function hapusError(input) {
    if (!input) return;

    const parent = input.parentElement;

    if (!parent) return;

    const error = parent.querySelector(
        ':scope > .error[data-js-error="true"]'
    );

    if (error) {
        error.remove();
    }
}


/**
 * Menampilkan pesan error di bawah input.
 */
function tampilkanError(input, pesan) {
    if (!input) return;

    hapusError(input);

    const span = document.createElement("span");

    span.className = "error";
    span.dataset.jsError = "true";
    span.textContent = pesan;

    input.insertAdjacentElement("afterend", span);
}


/**
 * Memastikan input wajib diisi.
 */
function validasiWajib(input, pesan) {
    if (!input) return true;

    if (input.value.trim() === "") {
        tampilkanError(input, pesan);
        return false;
    }

    hapusError(input);
    return true;
}


/**
 * Memvalidasi format email.
 */
function validasiEmail(input) {
    if (!input || input.value.trim() === "") {
        hapusError(input);
        return true;
    }

    const polaEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!polaEmail.test(input.value.trim())) {
        tampilkanError(input, "Format email tidak valid.");
        return false;
    }

    hapusError(input);
    return true;
}


/**
 * Memasang validasi pada form tambah dan edit.
 */
function initValidasiForm() {
    const forms = document.querySelectorAll(
        "#form-tambah, #form-edit"
    );

    forms.forEach(function (form) {
        // Mencegah pesan error menumpuk saat form dikirim ulang.
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

            if (email) {
                valid = validasiEmail(email) && valid;
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

                const errorPertama = form.querySelector(
                    ".error[data-js-error='true']"
                );

                if (errorPertama) {
                    const inputTerkait =
                        errorPertama.previousElementSibling;

                    if (inputTerkait) {
                        inputTerkait.focus();
                    }
                }
            }
        });

        // Hilangkan error ketika pengguna mulai memperbaiki input.
        form.addEventListener("input", function (e) {
            if (e.target.matches("input, textarea")) {
                hapusError(e.target);
            }
        });

        form.addEventListener("change", function (e) {
            if (e.target.matches("select")) {
                hapusError(e.target);
            }
        });
    });
}


/* =========================================================
   LOGIN: TAMPILKAN / SEMBUNYIKAN PASSWORD
========================================================= */

function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll(
        ".login-toggle-password"
    );

    toggleButtons.forEach(function (button) {
        button.addEventListener("click", function () {
            const wrapper = button.closest(".login-input-wrapper");

            if (!wrapper) return;

            const passwordInput = wrapper.querySelector(
                "input[type='password'], input[type='text']"
            );

            if (!passwordInput) return;

            const isPassword =
                passwordInput.type === "password";

            passwordInput.type = isPassword ? "text" : "password";

            button.textContent = isPassword
                ? "Sembunyikan"
                : "Lihat";

            button.setAttribute(
                "aria-label",
                isPassword
                    ? "Sembunyikan password"
                    : "Tampilkan password"
            );

            button.setAttribute(
                "aria-pressed",
                String(isPassword)
            );
        });
    });
}


/* =========================================================
   VALIDASI RINGAN HALAMAN LOGIN
   Tidak menggantikan validasi autentikasi PHP.
========================================================= */

function initLoginForm() {
    const loginForms = document.querySelectorAll(
        ".login-form"
    );

    loginForms.forEach(function (form) {
        form.addEventListener("submit", function (e) {
            const username = form.querySelector(
                "[name='username'], [name='nis'], [name='email']"
            );

            const password = form.querySelector(
                "[name='password']"
            );

            let valid = true;

            if (username && username.value.trim() === "") {
                username.focus();
                valid = false;
            } else if (password && password.value.trim() === "") {
                password.focus();
                valid = false;
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
    initPasswordToggle();
    initLoginForm();
});
