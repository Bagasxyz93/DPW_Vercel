<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? '';
$namaProgram = trim($_POST['nama_program'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');

$errors = [];

if ($id === '') {
    $errors[] = "ID program tidak ditemukan.";
}

if ($namaProgram === '') {
    $errors[] = "Nama program wajib diisi.";
}

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
        UPDATE program
        SET
            nama_program = :nama_program,
            deskripsi = :deskripsi
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id,
        'nama_program' => $namaProgram,
        'deskripsi' => $deskripsi
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data program berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal memperbarui program: ' . $e->getMessage()
    ];

    header('Location: edit.php?id=' . urlencode($id));
    exit;
}