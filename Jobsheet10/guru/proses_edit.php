
<?php
require_once __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

// VALIDASI ID
if (
    $id === ''
    || !ctype_digit((string) $id)
    || (int) $id < 1
) {
    $errors[] = 'ID guru tidak valid.';
}

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

    if (
        $id !== ''
        && ctype_digit((string) $id)
        && (int) $id > 0
    ) {
        header('Location: edit.php?id=' . urlencode($id));
    } else {
        header('Location: list.php');
    }

    exit;
}

try {
    // PASTIKAN DATA GURU TERSEDIA
    $stmtCek = $pdo->prepare("
        SELECT id
        FROM guru
        WHERE id = :id
    ");

    $stmtCek->execute([
        'id' => (int) $id
    ]);

    if (!$stmtCek->fetchColumn()) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data guru tidak ditemukan.'
        ];

        header('Location: list.php');
        exit;
    }

    // UPDATE DATA
    $stmt = $pdo->prepare("
        UPDATE guru
        SET
            nip = :nip,
            nama = :nama,
            email = :email,
            no_hp = :no_hp
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => (int) $id,
        'nip' => $nip,
        'nama' => $nama,
        'email' => $email,
        'no_hp' => $noHp
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data guru berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    error_log('Gagal memperbarui data guru: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal memperbarui data guru.'
    ];

    header('Location: edit.php?id=' . urlencode($id));
    exit;
}
