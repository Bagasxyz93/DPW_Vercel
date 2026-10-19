<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';


// =========================================================
// PASTIKAN REQUEST BERASAL DARI FORM REGISTER
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}


// =========================================================
// AMBIL INPUT
// =========================================================

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasiPassword = $_POST['konfirmasi_password'] ?? '';


// =========================================================
// VALIDASI INPUT
// =========================================================

if (
    $nama === '' ||
    $username === '' ||
    $password === '' ||
    $konfirmasiPassword === ''
) {
    $_SESSION['auth_error'] = 'Semua kolom wajib diisi.';
    header('Location: register.php');
    exit;
}

$panjangNama = function_exists('mb_strlen')
    ? mb_strlen($nama, 'UTF-8')
    : strlen($nama);

if ($panjangNama > 255 || strlen($username) > 50) {
    $_SESSION['auth_error'] = 'Nama atau username terlalu panjang.';
    header('Location: register.php');
    exit;
}

if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
    $_SESSION['auth_error'] =
        'Username harus terdiri dari 3 sampai 50 karakter, menggunakan huruf, angka, titik, garis bawah, atau tanda hubung.';

    header('Location: register.php');
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['auth_error'] = 'Password minimal 8 karakter.';
    header('Location: register.php');
    exit;
}

if ($password !== $konfirmasiPassword) {
    $_SESSION['auth_error'] = 'Konfirmasi password tidak cocok.';
    header('Location: register.php');
    exit;
}


// =========================================================
// SIMPAN AKUN BARU
// =========================================================

try {
    // Periksa apakah username sudah digunakan.
    $stmtCheck = $pdo->prepare(
        'SELECT id FROM public.users WHERE username = :username LIMIT 1'
    );

    $stmtCheck->execute([
        'username' => $username
    ]);

    if ($stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['auth_error'] = 'Username sudah digunakan.';
        header('Location: register.php');
        exit;
    }

    // Hash password sebelum disimpan ke database.
    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $sql = "
        INSERT INTO public.users (nama, username, password)
        VALUES (:nama, :username, :password)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        'nama' => $nama,
        'username' => $username,
        'password' => $passwordHash
    ]);

    // Registrasi berhasil.
    $_SESSION['auth_success'] =
        'Registrasi berhasil. Silakan login menggunakan akun baru.';

    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    // SQLSTATE 23505: nilai melanggar UNIQUE constraint PostgreSQL.
    if ($e->getCode() === '23505') {
        $_SESSION['auth_error'] = 'Username sudah digunakan.';
    } else {
        error_log('Register error: ' . $e->getMessage());

        $_SESSION['auth_error'] =
            'Registrasi gagal karena terjadi kesalahan database.';
    }

    header('Location: register.php');
    exit;
}