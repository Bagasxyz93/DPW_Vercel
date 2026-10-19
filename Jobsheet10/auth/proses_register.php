
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$nama = trim($_POST['nama'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasiPassword = $_POST['konfirmasi_password'] ?? '';

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

if (mb_strlen($nama) > 255 || strlen($username) > 50) {
    $_SESSION['auth_error'] = 'Nama atau username terlalu panjang.';
    header('Location: register.php');
    exit;
}

if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
    $_SESSION['auth_error'] =
        'Username minimal 3 karakter dan hanya boleh berisi huruf, angka, titik, garis bawah, atau tanda hubung.';
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

try {
    // Cek username sebelum menyimpan.
    $stmtCheck = $pdo->prepare(
        'SELECT id FROM public.users WHERE username = :username LIMIT 1'
    );
    $stmtCheck->execute(['username' => $username]);

    if ($stmtCheck->fetch(PDO::FETCH_ASSOC)) {
        $_SESSION['auth_error'] = 'Username sudah digunakan.';
        header('Location: register.php');
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

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

    $_SESSION['auth_success'] =
        'Registrasi berhasil. Silakan login menggunakan akun baru.';
    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    // PostgreSQL: 23505 berarti pelanggaran UNIQUE constraint.
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
