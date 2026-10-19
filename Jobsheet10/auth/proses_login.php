<?php

// Jangan keluarkan HTML atau teks sebelum proses selesai.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';


// =========================================================
// PASTIKAN REQUEST BERASAL DARI FORM LOGIN
// =========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}


// =========================================================
// AMBIL INPUT
// =========================================================

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['auth_error'] = 'Username dan password wajib diisi.';
    header('Location: login.php');
    exit;
}


// =========================================================
// PROSES LOGIN
// =========================================================

try {
    $sql = "
        SELECT id, nama, username, password, role
        FROM public.users
        WHERE username = :username
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'username' => $username
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Username tidak ditemukan atau password salah.
    if (
        !$user ||
        !password_verify($password, $user['password'])
    ) {
        $_SESSION['auth_error'] = 'Username atau password salah.';
        header('Location: login.php');
        exit;
    }

    // Perbarui ID session untuk mencegah session fixation.
    session_regenerate_id(true);

    // Simpan identitas pengguna ke session.
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_nama'] = $user['nama'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];

    // Login berhasil.
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    error_log('Login error: ' . $e->getMessage());

    $_SESSION['auth_error'] =
        'Terjadi kesalahan saat mengakses database. Silakan coba lagi.';

    header('Location: login.php');
    exit;
}