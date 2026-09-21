<?php
session_start();

// Prefix relatif ke root proyek
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

$page_title = $page_title ?? 'Dashboard';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        SIABSEN<?php echo $page_title ? ' | ' . htmlspecialchars($page_title) : ''; ?>
    </title>

    <link
        rel="stylesheet"
        href="<?php echo $base; ?>assets/css/style.css"
    >
</head>

<body>

<div class="dashboard-layout">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar">

        <div class="sidebar-brand">
            <h1>
                SI<span>ABSEN</span>
            </h1>
        </div>

        <nav class="sidebar-menu">

            <p class="sidebar-menu-title">
                Menu Utama
            </p>

            <ul>

                <li>
                    <a
                        href="<?php echo $base; ?>index.php"
                        class="<?php echo ($page_title === 'Dashboard') ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">⌂</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>siswa/list.php"
                        class="<?php echo ($page_title === 'Data Siswa') ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">👨‍🎓</span>
                        <span>Data Siswa</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>guru/list.php"
                        class="<?php echo ($page_title === 'Data Guru') ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">👨‍🏫</span>
                        <span>Data Guru</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>program/list.php"
                        class="<?php echo ($page_title === 'Data Program') ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">▣</span>
                        <span>Program</span>
                    </a>
                </li>

                <li>
                    <a
                        href="<?php echo $base; ?>absensi/list.php"
                        class="<?php echo ($page_title === 'Data Absensi') ? 'active' : ''; ?>"
                    >
                        <span class="menu-icon">✓</span>
                        <span>Absensi</span>
                    </a>
                </li>

            </ul>

        </nav>

    </aside>


    <!-- =====================================================
         AREA KONTEN
    ====================================================== -->

    <div class="main-content">

        <!-- =================================================
             TOPBAR
        ================================================== -->

        <header class="topbar">

            <div class="topbar-left">

                <button
                    type="button"
                    id="nav-toggle-btn"
                    class="nav-toggle-label"
                    aria-label="Buka menu"
                >
                    ☰
                </button>

                <h2 class="topbar-title">
                    <?php echo htmlspecialchars($page_title); ?>
                </h2>

            </div>


            <div class="topbar-right">

                <div class="topbar-search">
                    <input
                        type="text"
                        placeholder="Cari..."
                    >
                </div>

                <div class="profile">

                    <div class="profile-avatar">
                        A
                    </div>

                    <div class="profile-info">

                        <span class="profile-name">
                            Admin
                        </span>

                        <span class="profile-role">
                            Administrator
                        </span>

                    </div>

                </div>

            </div>

        </header>


        <!-- =================================================
             KONTEN HALAMAN
        ================================================== -->

        <main class="content">