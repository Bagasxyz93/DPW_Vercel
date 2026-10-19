<?php

// =========================================================
// SESSION
// =========================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// =========================================================
// ROOT DAN BASE PATH
// =========================================================

$__jobsheetRoot = dirname(__DIR__);
$__scriptDir = dirname($_SERVER['SCRIPT_FILENAME']);

$__rel = ltrim(
    str_replace(
        '\\',
        '/',
        substr($__scriptDir, strlen($__jobsheetRoot))
    ),
    '/'
);

$base = $__rel === ''
    ? ''
    : str_repeat('../', substr_count($__rel, '/') + 1);


// =========================================================
// JUDUL HALAMAN
// =========================================================

$page_title = $page_title ?? 'Dashboard';

$safePageTitle = htmlspecialchars(
    $page_title,
    ENT_QUOTES,
    'UTF-8'
);


// =========================================================
// INFORMASI PENGGUNA
// =========================================================

$namaPengguna = $_SESSION['nama']
    ?? $_SESSION['username']
    ?? 'Admin';

$rolePengguna = $_SESSION['role']
    ?? 'Administrator';

$safeNamaPengguna = htmlspecialchars(
    $namaPengguna,
    ENT_QUOTES,
    'UTF-8'
);

$safeRolePengguna = htmlspecialchars(
    $rolePengguna,
    ENT_QUOTES,
    'UTF-8'
);

$avatar = strtoupper(
    substr($namaPengguna, 0, 1)
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SIABSEN | <?php echo $safePageTitle; ?></title>

    <link
        rel="stylesheet"
        href="<?php echo htmlspecialchars($base, ENT_QUOTES, 'UTF-8'); ?>assets/css/style.css"
    >
</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="sidebar-brand">
            <h1>SI<span>ABSEN</span></h1>
        </div>

        <nav class="sidebar-menu">

            <p class="sidebar-menu-title">Menu Utama</p>

            <ul>

                <li>
                    <a
                        href="<?php echo $base; ?>index.php"
                        class="<?php echo $page_title === 'Dashboard' ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">⌂</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>siswa/list.php"
                        class="<?php echo $page_title === 'Data Siswa' ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">👨‍🎓</span>
                        <span>Data Siswa</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>guru/list.php"
                        class="<?php echo $page_title === 'Data Guru' ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">👨‍🏫</span>
                        <span>Data Guru</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>program/list.php"
                        class="<?php echo $page_title === 'Data Program' ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">▣</span>
                        <span>Program</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>absensi/list.php"
                        class="<?php echo $page_title === 'Data Absensi' ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">✓</span>
                        <span>Absensi</span>
                    </a>
                </li>

            </ul>

        </nav>

    </aside>


    <!-- AREA KONTEN -->

    <div class="main-content">

        <!-- TOPBAR -->

        <header class="topbar">

            <div class="topbar-left">

                <button
                    type="button"
                    id="nav-toggle-btn"
                    class="nav-toggle-label"
                    aria-label="Buka menu"
                    aria-expanded="false"
                >
                    ☰
                </button>

                <h2 class="topbar-title">
                    <?php echo $safePageTitle; ?>
                </h2>

            </div>


            <div class="topbar-right">

                <div class="topbar-search">
                    <input
                        type="text"
                        placeholder="Cari..."
                        aria-label="Pencarian umum"
                    >
                </div>

                <div class="profile">

                    <div class="profile-avatar">
                        <?php echo htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8'); ?>
                    </div>

                    <div class="profile-info">

                        <span class="profile-name">
                            <?php echo $safeNamaPengguna; ?>
                        </span>

                        <span class="profile-role">
                            <?php echo $safeRolePengguna; ?>
                        </span>

                    </div>

                </div>

            </div>

        </header>


        <!-- KONTEN HALAMAN -->

        <main class="content">