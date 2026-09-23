<?php
$page_title = "Data Absensi";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAbsensi = $pdo->query("
    SELECT
        absensi.id,
        absensi.tanggal,
        absensi.waktu_absen,
        absensi.status,
        absensi.keterangan,
        siswa.nis,
        siswa.nama,
        program.nama_program
    FROM absensi
    INNER JOIN siswa
        ON absensi.siswa_id = siswa.id
    LEFT JOIN program
        ON siswa.program_id = program.id
    ORDER BY absensi.tanggal DESC, absensi.waktu_absen DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<section>

    <div class="page-header">
        <div>
            <h2>Data Absensi</h2>
            <p>Riwayat kehadiran siswa dalam sistem.</p>
        </div>

        <a href="tambah.php" class="btn-primary">
            + Tambah Absensi
        </a>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <label for="search-input">Cari Absensi</label>

        <input
            type="text"
            id="search-input"
            placeholder="Cari berdasarkan NIS atau nama siswa..."
        >
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Program</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($daftarAbsensi)): ?>

                    <tr>
                        <td colspan="8">
                            Belum ada data absensi.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($daftarAbsensi as $index => $absensi): ?>

                        <tr>

                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    'd-m-Y',
                                    strtotime($absensi['tanggal'])
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $absensi['waktu_absen']
                                    ? htmlspecialchars($absensi['waktu_absen'])
                                    : '-';
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($absensi['nis']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($absensi['nama']); ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $absensi['nama_program'] ?? '-'
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($absensi['status']); ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $absensi['keterangan'] ?? '-'
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>
        </table>
    </div>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>