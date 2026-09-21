<?php
session_start();
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID siswa tidak ditemukan.'
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
        'id' => $id
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data siswa berhasil dihapus.'
    ];

} catch (PDOException $e) {

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal menghapus data siswa: ' . $e->getMessage()
    ];
}

header('Location: list.php');
exit;