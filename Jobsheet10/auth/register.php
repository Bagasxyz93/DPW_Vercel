
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika sudah login, arahkan ke dashboard.
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | SIABSEN</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

<div class="login-page">
    <main class="login-container register-container">

        <!-- Panel identitas SIABSEN -->
        <section class="login-brand">
            <div class="login-brand-logo">
                <h1>SI<span>ABSEN</span></h1>
            </div>

            <div class="login-brand-content">
                <span class="login-eyebrow">
                    BERGABUNG DENGAN SIABSEN
                </span>

                <h2>Buat Akun Baru</h2>

                <p>
                    Daftarkan akun untuk mulai menggunakan
                    sistem informasi absensi dengan mudah
                    dan terorganisir.
                </p>
            </div>

            <p class="login-brand-footer">
                © <?= date('Y') ?> SIABSEN. All rights reserved.
            </p>
        </section>

        <!-- Panel form register -->
        <section class="login-form-panel">

            <div class="login-heading">
                <h2>Daftar Akun</h2>
                <p>
                    Lengkapi informasi berikut untuk membuat
                    akun SIABSEN baru.
                </p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="auth-message auth-error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form
                class="login-form"
                id="form-register"
                action="proses_register.php"
                method="post"
            >
                <div class="form-group">
                    <label for="nama">Nama lengkap</label>

                    <div class="login-input-wrapper">
                        <span class="login-input-icon" aria-hidden="true">
                            👤
                        </span>

                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            placeholder="Masukkan nama lengkap"
                            required
                            maxlength="255"
                            autocomplete="name"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>

                    <div class="login-input-wrapper">
                        <span class="login-input-icon" aria-hidden="true">
                            @
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Buat username"
                            required
                            minlength="3"
                            maxlength="50"
                            pattern="[A-Za-z0-9_.-]+"
                            title="Gunakan huruf, angka, titik, garis bawah, atau tanda hubung."
                            autocomplete="username"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <div class="login-input-wrapper">
                        <span class="login-input-icon" aria-hidden="true">
                            🔒
                        </span>

                        <input
                            class="has-toggle"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="login-toggle-password"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                        >
                            Lihat
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="konfirmasi_password">
                        Konfirmasi password
                    </label>

                    <div class="login-input-wrapper">
                        <span class="login-input-icon" aria-hidden="true">
                            🔒
                        </span>

                        <input
                            class="has-toggle"
                            type="password"
                            id="konfirmasi_password"
                            name="konfirmasi_password"
                            placeholder="Ulangi password"
                            required
                            minlength="8"
                            autocomplete="new-password"
                        >

                        <button
                            type="button"
                            class="login-toggle-password"
                            aria-label="Tampilkan password"
                            aria-pressed="false"
                        >
                            Lihat
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Buat Akun
                </button>
            </form>

            <p class="login-footer">
                Sudah punya akun?
                <a href="login.php" class="login-link">
                    Login sekarang
                </a>
            </p>

        </section>
    </main>
</div>

</body>
</html>
