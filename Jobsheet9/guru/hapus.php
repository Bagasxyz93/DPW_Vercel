<?php
session_start();

require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID guru tidak ditemukan.'
    ];

    header('Location: list.php');
    exit;
}

try {

    $stmt = $pdo->prepare("
        DELETE FROM guru
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data guru berhasil dihapus.'
    ];

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menghapus data guru: ' . $e->getMessage()
    ];
}

header('Location: list.php');
exit;