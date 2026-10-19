
<?php
require_once __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$programId = $_POST['program_id'] ?? '';

$errors = [];

// VALIDASI NIS
if ($nis === '') {
    $errors[] = 'NIS wajib diisi.';
}

// VALIDASI NAMA
if ($nama === '') {
    $errors[] = 'Nama siswa wajib diisi.';
}

// VALIDASI JENIS KELAMIN
if (!in_array($jenisKelamin, ['L', 'P'], true)) {
    $errors[] = 'Jenis kelamin wajib dipilih.';
}

// VALIDASI PROGRAM
if (
    $programId === ''
    || !ctype_digit((string) $programId)
    || (int) $programId < 1
) {
    $errors[] = 'Program wajib dipilih.';
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
    // PASTIKAN PROGRAM TERSEDIA
    $stmtProgram = $pdo->prepare("
        SELECT id
        FROM program
        WHERE id = :program_id
    ");

    $stmtProgram->execute([
        'program_id' => (int) $programId
    ]);

    if (!$stmtProgram->fetchColumn()) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Program yang dipilih tidak ditemukan.'
        ];

        header('Location: tambah.php');
        exit;
    }

    // SIMPAN DATA SISWA
    $stmt = $pdo->prepare("
        INSERT INTO siswa (
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
        RETURNING id
    ");

    $stmt->execute([
        'nis' => $nis,
        'nama' => $nama,
        'jenis_kelamin' => $jenisKelamin,
        'program_id' => (int) $programId
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data siswa berhasil ditambahkan.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    error_log('Gagal menambahkan data siswa: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menambahkan data siswa.'
    ];

    header('Location: tambah.php');
    exit;
}
