
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
$success = $_SESSION['auth_success'] ?? '';

unset($_SESSION['auth_error'], $_SESSION['auth_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | SIABSEN</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="../assets/js/script.js" defer></script>
</head>
<body>

<div class="login-page">
    <main class="login-container">

        <!-- Panel identitas SIABSEN -->
        <section class="login-brand">
            <div class="login-brand-logo">
                <h1>SI<span>ABSEN</span></h1>
            </div>

            <div class="login-brand-content">
                <span class="login-eyebrow">
                    SISTEM INFORMASI ABSENSI
                </span>

                <h2>Kelola Kehadiran dengan Lebih Mudah</h2>

                <p>
                    Satu platform untuk membantu pengelolaan
                    data siswa, program, dan kehadiran secara
                    lebih praktis dan terorganisir.
                </p>
            </div>

            <p class="login-brand-footer">
                © <?= date('Y') ?> SIABSEN. All rights reserved.
            </p>
        </section>

        <!-- Panel form login -->
        <section class="login-form-panel">

            <div class="login-heading">
                <h2>Selamat Datang!</h2>
                <p>
                    Masukkan username dan password untuk
                    melanjutkan ke sistem.
                </p>
            </div>

            <?php if ($error !== ''): ?>
                <div class="auth-message auth-error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="auth-message auth-success" role="status">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form
                class="login-form"
                action="proses_login.php"
                method="post"
            >
                <div class="form-group">
                    <label for="username">Username</label>

                    <div class="login-input-wrapper">
                        <span class="login-input-icon" aria-hidden="true">
                            👤
                        </span>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            placeholder="Masukkan username"
                            required
                            maxlength="50"
                            autocomplete="username"
                            autofocus
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
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
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
                    Masuk ke Sistem
                </button>
            </form>

            <p class="login-footer">
                Belum punya akun?
                <a href="register.php" class="login-link">
                    Daftar sekarang
                </a>
            </p>

        </section>
    </main>
</div>

</body>
</html>
