
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tambah.php');
    exit;
}

$siswaId = $_POST['siswa_id'] ?? '';
$tanggal = trim($_POST['tanggal'] ?? '');
$waktuAbsen = trim($_POST['waktu_absen'] ?? '');
$status = trim($_POST['status'] ?? '');
$keterangan = trim($_POST['keterangan'] ?? '');

$errors = [];

// Validasi ID siswa
if (
    !ctype_digit((string) $siswaId) ||
    (int) $siswaId < 1
) {
    $errors[] = 'Siswa wajib dipilih dengan benar.';
}

// Validasi tanggal
$date = DateTime::createFromFormat('!Y-m-d', $tanggal);
$validTanggal = $date && $date->format('Y-m-d') === $tanggal;

if (!$validTanggal) {
    $errors[] = 'Tanggal absensi tidak valid.';
}

// Validasi waktu, jika diisi
if ($waktuAbsen !== '') {
    $validWaktu = preg_match(
        '/^(?:[01]\d|2[0-3]):[0-5]\d$/',
        $waktuAbsen
    );

    if (!$validWaktu) {
        $errors[] = 'Format waktu absensi tidak valid.';
    }
}

// Validasi status
$statusValid = ['Hadir', 'Izin', 'Sakit', 'Alpha'];

if (!in_array($status, $statusValid, true)) {
    $errors[] = 'Status absensi tidak valid.';
}

// Jika ada kesalahan input
if (!empty($errors)) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => implode(' ', $errors)
    ];

    header('Location: tambah.php');
    exit;
}

try {
    // Pastikan siswa yang dipilih benar-benar ada
    $stmtSiswa = $pdo->prepare("
        SELECT id
        FROM siswa
        WHERE id = :id
    ");

    $stmtSiswa->execute([
        'id' => (int) $siswaId
    ]);

    if (!$stmtSiswa->fetchColumn()) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Siswa yang dipilih tidak ditemukan.'
        ];

        header('Location: tambah.php');
        exit;
    }

    // Simpan absensi
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
        'siswa_id' => (int) $siswaId,
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
    error_log('Gagal menambahkan absensi: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data absensi gagal ditambahkan. Silakan periksa data dan coba lagi.'
    ];

    header('Location: tambah.php');
    exit;
}
