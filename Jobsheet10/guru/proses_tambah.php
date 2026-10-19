
<?php
require_once __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

// VALIDASI NIP
if ($nip === '') {
    $errors[] = 'NIP wajib diisi.';
}

// VALIDASI NAMA
if ($nama === '') {
    $errors[] = 'Nama guru wajib diisi.';
}

// VALIDASI EMAIL
if ($email === '') {
    $errors[] = 'Email wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Format email tidak valid.';
}

// JIKA ADA ERROR
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO guru (
            nip,
            nama,
            email,
            no_hp
        )
        VALUES (
            :nip,
            :nama,
            :email,
            :no_hp
        )
        RETURNING id
    ");

    $stmt->execute([
        'nip' => $nip,
        'nama' => $nama,
        'email' => $email,
        'no_hp' => $noHp
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data guru berhasil ditambahkan.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    error_log('Gagal menambahkan data guru: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan data guru.'
    ];

    header('Location: tambah.php');
    exit;
}
