<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$programId = $_POST['program_id'] ?? '';

$errors = [];

// Validasi NIS
if ($nis === '') {
    $errors[] = "NIS wajib diisi.";
}

// Validasi nama
if ($nama === '') {
    $errors[] = "Nama siswa wajib diisi.";
}

// Validasi jenis kelamin
if ($jenisKelamin === '') {
    $errors[] = "Jenis kelamin wajib dipilih.";
}

// Validasi program
if ($programId === '') {
    $errors[] = "Program wajib dipilih.";
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
    $stmt = $pdo->prepare(
        "INSERT INTO siswa (
            nis,
            nama,
            jenis_kelamin,
            program_id
        )
        VALUES (
            :nis,
            :nama,
            :jenis_kelamin,
            :program_id
        )
        RETURNING id"
    );

    $stmt->execute([
        'nis' => $nis,
        'nama' => $nama,
        'jenis_kelamin' => $jenisKelamin,
        'program_id' => $programId
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data siswa berhasil ditambahkan.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan siswa: ' . $e->getMessage()
    ];

    header('Location: tambah.php');
    exit;
}