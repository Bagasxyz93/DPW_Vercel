<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$siswaId = $_POST['siswa_id'] ?? '';
$tanggal = $_POST['tanggal'] ?? '';
$waktuAbsen = $_POST['waktu_absen'] ?? '';
$status = $_POST['status'] ?? '';
$keterangan = trim($_POST['keterangan'] ?? '');

$errors = [];

// Validasi siswa
if ($siswaId === '') {
    $errors[] = "Siswa wajib dipilih.";
}

// Validasi tanggal
if ($tanggal === '') {
    $errors[] = "Tanggal wajib diisi.";
}

// Validasi status
if ($status === '') {
    $errors[] = "Status wajib dipilih.";
}

if (!in_array($status, ['Hadir', 'Izin', 'Sakit', 'Alpha'])) {
    $errors[] = "Status absensi tidak valid.";
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
        INSERT INTO absensi (
            siswa_id,
            tanggal,
            waktu_absen,
            status,
            keterangan
        )
        VALUES (
            :siswa_id,
            :tanggal,
            :waktu_absen,
            :status,
            :keterangan
        )
        RETURNING id
    ");

    $stmt->execute([
        'siswa_id' => $siswaId,
        'tanggal' => $tanggal,
        'waktu_absen' => $waktuAbsen !== ''
            ? $waktuAbsen
            : null,
        'status' => $status,
        'keterangan' => $keterangan !== ''
            ? $keterangan
            : null
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data absensi berhasil ditambahkan.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan absensi: ' . $e->getMessage()
    ];

    header('Location: tambah.php');
    exit;
}