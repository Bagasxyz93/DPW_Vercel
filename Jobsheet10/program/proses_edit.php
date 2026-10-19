
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list.php');
    exit;
}

$id = $_POST['id'] ?? '';
$namaProgram = trim($_POST['nama_program'] ?? '');
$deskripsi = trim($_POST['deskripsi'] ?? '');

if (!ctype_digit((string) $id) || (int) $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID program tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

$id = (int) $id;

if ($namaProgram === '') {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Nama program wajib diisi.'
    ];

    header('Location: edit.php?id=' . $id);
    exit;
}

try {
    // Pastikan program tersedia
    $stmtCek = $pdo->prepare("
        SELECT id
        FROM program
        WHERE id = :id
    ");

    $stmtCek->execute([
        'id' => $id
    ]);

    if (!$stmtCek->fetchColumn()) {
        $_SESSION['flash'] = [
            'type' => 'error',
            'pesan' => 'Data program tidak ditemukan.'
        ];

        header('Location: list.php');
        exit;
    }

    // Perbarui program
    $stmt = $pdo->prepare("
        UPDATE program
        SET
            nama_program = :nama_program,
            deskripsi = :deskripsi
        WHERE id = :id
    ");

    $stmt->execute([
        'id' => $id,
        'nama_program' => $namaProgram,
        'deskripsi' => $deskripsi
    ]);

    $_SESSION['flash'] = [
        'type' => 'success',
        'pesan' => 'Data program berhasil diperbarui.'
    ];

    header('Location: list.php');
    exit;
} catch (PDOException $e) {
    error_log('Gagal memperbarui program: ' . $e->getMessage());

    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Gagal memperbarui program. Silakan periksa data dan coba lagi.'
    ];

    header('Location: edit.php?id=' . $id);
    exit;
}
