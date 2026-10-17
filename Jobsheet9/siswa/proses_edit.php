<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_POST['id'] ?? '';
$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$programId = $_POST['program_id'] ?? '';

$errors = [];

if ($id === '') {
    $errors[] = "ID siswa tidak ditemukan.";
}

if ($nis === '') {
    $errors[] = "NIS wajib diisi.";
}

if ($nama === '') {
    $errors[] = "Nama siswa wajib diisi.";
}

if ($jenisKelamin === '') {
    $errors[] = "Jenis kelamin wajib dipilih.";
}

if ($programId === '') {
    $errors[] = "Program wajib dipilih.";
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
        UPDATE siswa
        SET
            nis = :nis,
            nama = :nama,
            jenis_kelamin = :jenis_kelamin,
            program_id = :program_id
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id,
        'nis' => $nis,
        'nama' => $nama,
        'jenis_kelamin' => $jenisKelamin,
        'program_id' => $programId
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data siswa berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal memperbarui data siswa: ' . $e->getMessage()
    ];

    header('Location: edit.php?id=' . urlencode($id));
    exit;
}