
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = "Data Absensi";

// FLASH MESSAGE
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// PENCARIAN
$keyword = trim($_GET['q'] ?? '');

// PAGINATION
$limit = 5;

$page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
$page = ($page !== false && $page !== null && $page > 0)
    ? $page
    : 1;

// KONDISI PENCARIAN
$where = "
    WHERE (
        siswa.nis ILIKE :keyword_nis
        OR siswa.nama ILIKE :keyword_nama
    )
";

// HITUNG TOTAL DATA
$sqlCount = "
    SELECT COUNT(*)
    FROM absensi
    INNER JOIN siswa
        ON absensi.siswa_id = siswa.id
    LEFT JOIN program
        ON siswa.program_id = program.id
    $where
";

$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute([
    'keyword_nis' => '%' . $keyword . '%',
    'keyword_nama' => '%' . $keyword . '%'
]);

$totalData = (int) $stmtCount->fetchColumn();

// HITUNG TOTAL HALAMAN
$totalHalaman = max(1, (int) ceil($totalData / $limit));
$page = min($page, $totalHalaman);
$offset = ($page - 1) * $limit;

// AMBIL DATA ABSENSI
$sqlAbsensi = "
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
    $where
    ORDER BY
        absensi.tanggal DESC,
        absensi.waktu_absen DESC NULLS LAST,
        absensi.id DESC
    LIMIT :limit OFFSET :offset
";

$stmtAbsensi = $pdo->prepare($sqlAbsensi);

$stmtAbsensi->bindValue(
    ':keyword_nis',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);
$stmtAbsensi->bindValue(
    ':keyword_nama',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);
$stmtAbsensi->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtAbsensi->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmtAbsensi->execute();

$daftarAbsensi = $stmtAbsensi->fetchAll(PDO::FETCH_ASSOC);

// URL PAGINATION
function urlHalamanAbsensi($halaman, $keyword)
{
    return '?' . http_build_query([
        'q' => $keyword,
        'page' => $halaman
    ]);
}

require __DIR__ . '/../includes/header.php';
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
        <p class="flash flash-<?php
            echo htmlspecialchars(
                $flash['type'],
                ENT_QUOTES,
                'UTF-8'
            );
        ?>">
            <?php
            echo htmlspecialchars(
                $flash['pesan'],
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </p>
    <?php endif; ?>

    <!-- PENCARIAN -->
    <form method="get" action="list.php" class="search-box">
        <label for="search-input">Cari Absensi</label>

        <div class="search-controls">
            <input
                type="search"
                id="search-input"
                name="q"
                value="<?php echo htmlspecialchars(
                    $keyword,
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>"
                placeholder="Cari berdasarkan NIS atau nama siswa..."
            >

            <button type="submit" class="btn-primary">
                Cari
            </button>

            <?php if ($keyword !== ''): ?>
                <a href="list.php" class="btn-edit">
                    Reset
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- INFORMASI PAGINATION -->
    <p class="pagination-info">
        Total <?php echo $totalData; ?> data absensi.
        Halaman <?php echo $page; ?> dari <?php echo $totalHalaman; ?>.
    </p>

    <!-- TABEL ABSENSI -->
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
                            <?php
                            echo $keyword !== ''
                                ? 'Data absensi tidak ditemukan.'
                                : 'Belum ada data absensi.';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarAbsensi as $index => $absensi): ?>
                        <tr>
                            <td>
                                <?php echo $offset + $index + 1; ?>
                            </td>

                            <td>
                                <?php
                                echo !empty($absensi['tanggal'])
                                    ? htmlspecialchars(
                                        date(
                                            'd-m-Y',
                                            strtotime($absensi['tanggal'])
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                    : '-';
                                ?>
                            </td>

                            <td>
                                <?php
                                echo !empty($absensi['waktu_absen'])
                                    ? htmlspecialchars(
                                        substr(
                                            (string) $absensi['waktu_absen'],
                                            0,
                                            5
                                        ),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                    : '-';
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars(
                                    $absensi['nis'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars(
                                    $absensi['nama'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars(
                                    $absensi['nama_program'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars(
                                    $absensi['status'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars(
                                    $absensi['keterangan'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- NAVIGASI HALAMAN -->
    <?php if ($totalData > 0 && $totalHalaman > 1): ?>
        <nav class="pagination" aria-label="Navigasi halaman">

            <?php if ($page > 1): ?>
                <a href="<?php echo htmlspecialchars(
                    urlHalamanAbsensi($page - 1, $keyword),
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>">
                    &laquo; Sebelumnya
                </a>
            <?php else: ?>
                <span class="disabled">
                    &laquo; Sebelumnya
                </span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="active" aria-current="page">
                        <?php echo $i; ?>
                    </span>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars(
                        urlHalamanAbsensi($i, $keyword),
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalHalaman): ?>
                <a href="<?php echo htmlspecialchars(
                    urlHalamanAbsensi($page + 1, $keyword),
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>">
                    Berikutnya &raquo;
                </a>
            <?php else: ?>
                <span class="disabled">
                    Berikutnya &raquo;
                </span>
            <?php endif; ?>

        </nav>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
