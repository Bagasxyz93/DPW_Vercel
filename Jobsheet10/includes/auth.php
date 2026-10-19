
<?php

// Mulai session jika belum aktif.
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Periksa apakah pengguna sudah login.
if (empty($_SESSION['user_id'])) {

    // Simpan pesan untuk ditampilkan di halaman login.
    $_SESSION['auth_error'] = 'Silakan login terlebih dahulu.';

    // Tentukan root URL proyek.
    $scriptName = str_replace(
        '\\',
        '/',
        $_SERVER['SCRIPT_NAME'] ?? ''
    );

    // Hapus nama file dari path, lalu mundur ke root proyek
    // berdasarkan folder tempat halaman berada.
    $scriptDirectory = dirname($scriptName);
    $folder = basename($scriptDirectory);

    $subfolders = ['siswa', 'guru', 'program', 'absensi'];

    if (in_array($folder, $subfolders, true)) {
        $rootUrl = dirname($scriptDirectory);
    } elseif ($folder === 'auth') {
        $rootUrl = dirname($scriptDirectory);
    } else {
        $rootUrl = $scriptDirectory;
    }

    $rootUrl = rtrim($rootUrl, '/');

    // Redirect ke halaman login.
    header('Location: ' . $rootUrl . '/auth/login.php');
    exit;
}
