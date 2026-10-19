
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';

if (!ctype_digit((string) $id) || (int) $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID program tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

try {
    $stmt = $pdo->prepare("
        DELETE FROM program
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => (int) $id
    ]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['flash'] = [
            'type' => 'success',
            'pesan' => 'Program berhasil dihapus.'
        ];
    } else {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data program tidak ditemukan.'
        ];
    }
} catch (PDOException $e) {
    error_log('Gagal menghapus program: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Program gagal dihapus. Pastikan program tidak sedang digunakan oleh data lain.'
    ];
}

header('Location: list.php');
exit;
