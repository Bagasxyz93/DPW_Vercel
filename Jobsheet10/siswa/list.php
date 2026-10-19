
<?php
// =========================================================
// AUTENTIKASI
// Harus dijalankan sebelum output HTML apa pun.
// =========================================================

require_once __DIR__ . '/../includes/auth.php';

// =========================================================
// KONEKSI DATABASE
// =========================================================

require_once __DIR__ . '/../includes/koneksi.php';

$page_title = 'Data Siswa';

// =========================================================
// FLASH MESSAGE
// =========================================================

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// =========================================================
// PENCARIAN
// =========================================================

$keyword = trim($_GET['q'] ?? '');

// =========================================================
// PAGINATION
// =========================================================

$limit = 5;

$pageInput = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);

$page = (
    $pageInput !== false &&
    $pageInput !== null &&
    $pageInput > 0
) ? $pageInput : 1;

// =========================================================
// HITUNG TOTAL DATA
// =========================================================

$sqlCount = "
    SELECT COUNT(*)
    FROM siswa
    WHERE
        CAST(nis AS TEXT) ILIKE :keyword_nis
        OR nama ILIKE :keyword_nama
";

$stmtCount = $pdo->prepare($sqlCount);

$stmtCount->execute([
    'keyword_nis' => '%' . $keyword . '%',
    'keyword_nama' => '%' . $keyword . '%',
]);

$totalData = (int) $stmtCount->fetchColumn();

$totalHalaman = max(
    1,
    (int) ceil($totalData / $limit)
);

$page = min($page, $totalHalaman);
$offset = ($page - 1) * $limit;

// =========================================================
// AMBIL DATA SISWA
// =========================================================

$sqlSiswa = "
    SELECT
        siswa.id,
        siswa.nis,
        siswa.nama,
        siswa.jenis_kelamin,
        program.nama_program
    FROM siswa
    LEFT JOIN program
        ON siswa.program_id = program.id
    WHERE
        CAST(siswa.nis AS TEXT) ILIKE :keyword_nis
        OR siswa.nama ILIKE :keyword_nama
    ORDER BY siswa.id DESC
    LIMIT :limit OFFSET :offset
";

$stmtSiswa = $pdo->prepare($sqlSiswa);

$stmtSiswa->bindValue(
    ':keyword_nis',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);

$stmtSiswa->bindValue(
    ':keyword_nama',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);

$stmtSiswa->bindValue(
    ':limit',
    $limit,
    PDO::PARAM_INT
);

$stmtSiswa->bindValue(
    ':offset',
    $offset,
    PDO::PARAM_INT
);

$stmtSiswa->execute();

$daftarSiswa = $stmtSiswa->fetchAll(PDO::FETCH_ASSOC);

// =========================================================
// FUNGSI URL PAGINATION
// =========================================================

function urlHalamanSiswa($halaman, $keyword)
{
    return '?' . http_build_query([
        'q' => $keyword,
        'page' => $halaman,
    ]);
}

// =========================================================
// HEADER
// =========================================================

require __DIR__ . '/../includes/header.php';
?>

<section>

    <div class="page-header">
        <div>
            <h2>Data Siswa</h2>
            <p>Kelola data siswa yang terdaftar dalam sistem.</p>
        </div>

        <a href="tambah.php" class="btn-primary">
            + Tambah Siswa
        </a>
    </div>

    <!-- FLASH MESSAGE -->

    <?php if ($flash): ?>
        <p class="flash flash-<?php
            echo htmlspecialchars(
                $flash['type'] ?? 'success',
                ENT_QUOTES,
                'UTF-8'
            );
        ?>">
            <?php
            echo htmlspecialchars(
                $flash['pesan'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            );
            ?>
        </p>
    <?php endif; ?>

    <!-- PENCARIAN -->

    <form method="get" action="list.php" class="search-box">

        <label for="search-input">Cari Siswa</label>

        <div class="search-controls">

            <input
                type="search"
                id="search-input"
                name="q"
                value="<?php
                    echo htmlspecialchars(
                        $keyword,
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>"
                placeholder="Cari berdasarkan NIS atau nama..."
            >

            <button type="submit" class="btn-primary">
                Cari
            </button>

            <?php if ($keyword !== ''): ?>
                <a href="list.php" class="btn-edit">Reset</a>
            <?php endif; ?>

        </div>
    </form>

    <!-- INFORMASI PAGINATION -->

    <p class="pagination-info">
        Total <?php echo $totalData; ?> data siswa.
        Halaman <?php echo $page; ?> dari <?php echo $totalHalaman; ?>.
    </p>

    <!-- TABEL DATA SISWA -->

    <div class="table-responsive">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Program</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($daftarSiswa)): ?>

                    <tr>
                        <td colspan="6" class="empty-state">
                            <?php
                            echo $keyword !== ''
                                ? 'Data siswa tidak ditemukan.'
                                : 'Belum ada data siswa. Silakan tambahkan siswa terlebih dahulu.';
                            ?>
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($daftarSiswa as $index => $siswa): ?>

                        <tr>

                            <td>
                                <?php echo $offset + $index + 1; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) $siswa['nis'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $siswa['nama'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $siswa['jenis_kelamin'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $siswa['nama_program'] ?? '-',
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>

                                <a
                                    href="edit.php?id=<?php
                                        echo (int) $siswa['id'];
                                    ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>

                                <form
                                    action="hapus.php"
                                    method="post"
                                    class="form-hapus"
                                    onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php
                                            echo (int) $siswa['id'];
                                        ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn-delete"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <!-- NAVIGASI PAGINATION -->

    <?php if ($totalData > 0 && $totalHalaman > 1): ?>

        <nav
            class="pagination"
            aria-label="Navigasi halaman"
        >

            <?php if ($page > 1): ?>

                <a href="<?php
                    echo htmlspecialchars(
                        urlHalamanSiswa($page - 1, $keyword),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>">
                    &laquo; Sebelumnya
                </a>

            <?php else: ?>

                <span class="disabled">
                    &laquo; Sebelumnya
                </span>

            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>

                <?php if ($i === $page): ?>

                    <span
                        class="active"
                        aria-current="page"
                    >
                        <?php echo $i; ?>
                    </span>

                <?php else: ?>

                    <a href="<?php
                        echo htmlspecialchars(
                            urlHalamanSiswa($i, $keyword),
                            ENT_QUOTES,
                            'UTF-8'
                        );
                    ?>">
                        <?php echo $i; ?>
                    </a>

                <?php endif; ?>

            <?php endfor; ?>

            <?php if ($page < $totalHalaman): ?>

                <a href="<?php
                    echo htmlspecialchars(
                        urlHalamanSiswa($page + 1, $keyword),
                        ENT_QUOTES,
                        'UTF-8'
                    );
                ?>">
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

<?php
require __DIR__ . '/../includes/footer.php';
?>
