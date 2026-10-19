
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$namaProgram = trim($_POST['nama_program'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');

if ($namaProgram === '') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Nama program wajib diisi.'
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
    error_log('Gagal menambahkan program: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Program gagal ditambahkan. Silakan periksa data dan coba lagi.'
    ];

    header('Location: tambah.php');
    exit;
}
