
<?php
require_once __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';

if ($id === '' || !ctype_digit((string) $id) || (int) $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID siswa tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        DELETE FROM siswa
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => (int) $id
    ]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Data siswa berhasil dihapus.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data siswa tidak ditemukan.'
        ];
    }

} catch (PDOException $e) {
    error_log('Gagal menghapus data siswa: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menghapus data siswa.'
    ];
}

header('Location: list.php');
exit;
