
<?php
require_once __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';
$nis = trim($_POST['nis'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$jenisKelamin = $_POST['jenis_kelamin'] ?? '';
$programId = $_POST['program_id'] ?? '';

$errors = [];

// VALIDASI ID
if ($id === '' || !ctype_digit((string) $id) || (int) $id < 1) {
    $errors[] = 'ID siswa tidak valid.';
}

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
    // PASTIKAN SISWA ADA
    $stmtCek = $pdo->prepare("
        SELECT id
        FROM siswa
        WHERE id = :id
    ");

    $stmtCek->execute([
        'id' => (int) $id
    ]);

    if (!$stmtCek->fetchColumn()) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data siswa tidak ditemukan.'
        ];

        header('Location: list.php');
        exit;
    }

    // PASTIKAN PROGRAM ADA
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

        header('Location: edit.php?id=' . urlencode($id));
        exit;
    }

    // UPDATE DATA SISWA
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
        'id' => (int) $id,
        'nis' => $nis,
        'nama' => $nama,
        'jenis_kelamin' => $jenisKelamin,
        'program_id' => (int) $programId
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data siswa berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;

} catch (PDOException $e) {
    error_log('Gagal memperbarui data siswa: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal memperbarui data siswa.'
    ];

    header('Location: edit.php?id=' . urlencode($id));
    exit;
}
