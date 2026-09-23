<?php

$page_title = "Dashboard";

include __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/koneksi.php';


// =========================================================
// STATISTIK DASHBOARD
// =========================================================

// Total siswa
$totalSiswa = $pdo
    ->query("SELECT COUNT(*) FROM siswa")
    ->fetchColumn();


// Total guru
$totalGuru = $pdo
    ->query("SELECT COUNT(*) FROM guru")
    ->fetchColumn();


// Total program
$totalProgram = $pdo
    ->query("SELECT COUNT(*) FROM program")
    ->fetchColumn();


// Total siswa yang hadir hari ini
$hadirHariIni = $pdo
    ->query("
        SELECT COUNT(*)
        FROM absensi
        WHERE tanggal = CURRENT_DATE
        AND status = 'Hadir'
    ")
    ->fetchColumn();


// =========================================================
// ABSENSI HARI INI
// =========================================================

$absensiHariIni = $pdo
    ->query("
        SELECT
            absensi.waktu_absen,
            absensi.status,
            siswa.nis,
            siswa.nama,
            program.nama_program
        FROM absensi
        INNER JOIN siswa
            ON absensi.siswa_id = siswa.id
        LEFT JOIN program
            ON siswa.program_id = program.id
        WHERE absensi.tanggal = CURRENT_DATE
        ORDER BY absensi.waktu_absen DESC
    ")
    ->fetchAll(PDO::FETCH_ASSOC);


// =========================================================
// PROGRAM
// =========================================================

$daftarProgram = $pdo
    ->query("
        SELECT
            program.id,
            program.nama_program,
            program.deskripsi,
            COUNT(siswa.id) AS jumlah_siswa
        FROM program
        LEFT JOIN siswa
            ON siswa.program_id = program.id
        GROUP BY
            program.id,
            program.nama_program,
            program.deskripsi
        ORDER BY program.id DESC
        LIMIT 5
    ")
    ->fetchAll(PDO::FETCH_ASSOC);

?>

<!-- =====================================================
     DASHBOARD
====================================================== -->

<div class="page-header">

    <div>
        <h2>Dashboard</h2>

        <p>
            Selamat datang di Sistem Informasi Absensi.
        </p>
    </div>

</div>


<!-- =====================================================
     STATISTIK
====================================================== -->

<div class="stats-grid">

    <!-- Total Siswa -->

    <div class="stat-card">

        <div class="stat-info">

            <h3>Total Siswa</h3>

            <p>
                <?php echo $totalSiswa; ?>
            </p>

        </div>

        <div class="stat-icon">
            👨‍🎓
        </div>

    </div>


    <!-- Total Guru -->

    <div class="stat-card">

        <div class="stat-info">

            <h3>Total Guru</h3>

            <p>
                <?php echo $totalGuru; ?>
            </p>

        </div>

        <div class="stat-icon">
            👨‍🏫
        </div>

    </div>


    <!-- Total Program -->

    <div class="stat-card">

        <div class="stat-info">

            <h3>Total Program</h3>

            <p>
                <?php echo $totalProgram; ?>
            </p>

        </div>

        <div class="stat-icon">
            ▣
        </div>

    </div>


    <!-- Hadir Hari Ini -->

    <div class="stat-card">

        <div class="stat-info">

            <h3>Hadir Hari Ini</h3>

            <p>
                <?php echo $hadirHariIni; ?>
            </p>

        </div>

        <div class="stat-icon">
            ✓
        </div>

    </div>

</div>


<!-- =====================================================
     DASHBOARD CONTENT
====================================================== -->

<div class="dashboard-grid">


    <!-- =================================================
         ABSENSI HARI INI
    ================================================== -->

    <div class="card">

        <div class="card-header">

            <h3>Absensi Hari Ini</h3>

            <a href="absensi/list.php">
                Lihat Semua
            </a>

        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>No</th>

                        <th>Waktu</th>

                        <th>NIS</th>

                        <th>Nama Siswa</th>

                        <th>Program</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (empty($absensiHariIni)): ?>

                        <tr>

                            <td colspan="6">
                                Belum ada absensi hari ini.
                            </td>

                        </tr>

                    <?php else: ?>

                        <?php foreach ($absensiHariIni as $index => $absensi): ?>

                            <tr>

                                <td>
                                    <?php echo $index + 1; ?>
                                </td>

                                <td>
                                    <?php
                                    echo $absensi['waktu_absen']
                                        ? htmlspecialchars($absensi['waktu_absen'])
                                        : '-';
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $absensi['nis']
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $absensi['nama']
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $absensi['nama_program'] ?? '-'
                                    );
                                    ?>
                                </td>

                                <td>

                                    <?php

                                    $statusClass = match (
                                        $absensi['status']
                                    ) {
                                        'Hadir' => 'badge-hadir',
                                        'Izin' => 'badge-izin',
                                        'Sakit' => 'badge-sakit',
                                        'Alpha' => 'badge-alpha',
                                        default => ''
                                    };

                                    ?>

                                    <span
                                        class="badge <?php echo $statusClass; ?>"
                                    >
                                        <?php
                                        echo htmlspecialchars(
                                            $absensi['status']
                                        );
                                        ?>
                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- =================================================
         PROGRAM
    ================================================== -->

    <div class="card">

        <div class="card-header">

            <h3>Program</h3>

            <a href="program/list.php">
                Lihat Semua
            </a>

        </div>


        <?php if (empty($daftarProgram)): ?>

            <p>
                Belum ada program.
            </p>

        <?php else: ?>

            <?php foreach ($daftarProgram as $program): ?>

                <div
                    style="
                        padding: 14px 0;
                        border-bottom: 1px solid #edf0f3;
                    "
                >

                    <div
                        style="
                            display: flex;
                            justify-content: space-between;
                            align-items: center;
                            gap: 10px;
                        "
                    >

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $program['nama_program']
                            );
                            ?>
                        </strong>

                        <span
                            style="
                                font-size: 0.75rem;
                                color: #8995a3;
                            "
                        >
                            <?php
                            echo $program['jumlah_siswa'];
                            ?>
                            siswa
                        </span>

                    </div>


                    <?php if (!empty($program['deskripsi'])): ?>

                        <p
                            style="
                                margin-top: 5px;
                                font-size: 0.78rem;
                                color: #8995a3;
                            "
                        >
                            <?php
                            echo htmlspecialchars(
                                $program['deskripsi']
                            );
                            ?>
                        </p>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>


<?php include __DIR__ . '/includes/footer.php'; ?>