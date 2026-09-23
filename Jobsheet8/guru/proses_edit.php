<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? '';
$nip = trim($_POST['nip'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$noHp = trim($_POST['no_hp'] ?? '');

$errors = [];

// Validasi ID
if ($id === '') {
    $errors[] = "ID guru tidak ditemukan.";
}

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

    header('Location: edit.php?id=' . urlencode($id));
    exit;
}

try {

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
        'id' => $id,
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

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal memperbarui data guru: ' . $e->getMessage()
    ];

    header('Location: edit.php?id=' . urlencode($id));
    exit;
}