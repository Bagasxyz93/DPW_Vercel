<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

// Validasi NIP
if ($nip === '') {
    $errors[] = "NIP wajib diisi.";
}

// Validasi nama
if ($nama === '') {
    $errors[] = "Nama guru wajib diisi.";
}

// Validasi email
if ($email === '') {
    $errors[] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}

// Jika ada error
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}

try {

    $stmt = $pdo->prepare(
        "INSERT INTO guru (
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
        RETURNING id"
    );

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

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan data guru: ' . $e->getMessage()
    ];

    header('Location: tambah.php');
    exit;
}