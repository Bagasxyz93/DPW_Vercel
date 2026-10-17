<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$namaProgram = trim($_POST['nama_program'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');

$errors = [];

// Validasi nama program
if ($namaProgram === '') {
    $errors[] = "Nama program wajib diisi.";
}

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
        INSERT INTO program (
            nama_program,
            deskripsi
        )
        VALUES (
            :nama_program,
            :deskripsi
        )
        RETURNING id
    ");

    $stmt->execute([
        'nama_program' => $namaProgram,
        'deskripsi' => $deskripsi
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Program berhasil ditambahkan.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan program: ' . $e->getMessage()
    ];

    header('Location: tambah.php');
    exit;
}