
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    $_SESSION['auth_error'] = 'Username dan password wajib diisi.';
    header('Location: login.php');
    exit;
}

try {
    $sql = "
        SELECT id, nama, username, password, role
        FROM public.users
        WHERE username = :username
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['username' => $username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['auth_error'] = 'Username atau password salah.';
        header('Location: login.php');
        exit;
    }

    // Mencegah session fixation.
    session_regenerate_id(true);

    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_nama'] = $user['nama'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_role'] = $user['role'];

    // Arahkan ke dashboard setelah login berhasil.
    header('Location: ../index.php');
    exit;

} catch (PDOException $e) {
    error_log('Login error: ' . $e->getMessage());

    $_SESSION['auth_error'] = 'Terjadi kesalahan saat mengakses database.';
    header('Location: login.php');
    exit;
}
