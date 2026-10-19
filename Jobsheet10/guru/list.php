
<?php
require_once __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/koneksi.php';

$page_title = "Data Guru";

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

// HITUNG TOTAL DATA
$sqlCount = "
    SELECT COUNT(*)
    FROM guru
    WHERE
        COALESCE(CAST(nip AS TEXT), '') ILIKE :keyword_nip
        OR nama ILIKE :keyword_nama
";

$stmtCount = $pdo->prepare($sqlCount);

$stmtCount->execute([
    'keyword_nip' => '%' . $keyword . '%',
    'keyword_nama' => '%' . $keyword . '%',
]);

$totalData = (int) $stmtCount->fetchColumn();

$totalHalaman = max(1, (int) ceil($totalData / $limit));
$page = min($page, $totalHalaman);
$offset = ($page - 1) * $limit;

// AMBIL DATA GURU
$sqlGuru = "
    SELECT *
    FROM guru
    WHERE
        COALESCE(CAST(nip AS TEXT), '') ILIKE :keyword_nip
        OR nama ILIKE :keyword_nama
    ORDER BY id DESC
    LIMIT :limit OFFSET :offset
";

$stmtGuru = $pdo->prepare($sqlGuru);

$stmtGuru->bindValue(
    ':keyword_nip',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);

$stmtGuru->bindValue(
    ':keyword_nama',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);

$stmtGuru->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtGuru->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmtGuru->execute();

$daftarGuru = $stmtGuru->fetchAll(PDO::FETCH_ASSOC);

// URL PAGINATION
function urlHalamanGuru($halaman, $keyword)
{
    return '?' . http_build_query([
        'q' => $keyword,
        'page' => $halaman,
    ]);
}

require __DIR__ . '/../includes/header.php';
?>

<section>

    <div class="page-header">
        <div>
            <h2>Data Guru</h2>
            <p>Kelola data guru yang terdaftar dalam sistem.</p>
        </div>

        <a href="tambah.php" class="btn-primary">
            + Tambah Guru
        </a>
    </div>

    <?php if ($flash): ?>
        <p class="flash flash-<?php
            echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8');
        ?>">
            <?php
            echo htmlspecialchars($flash['pesan'], ENT_QUOTES, 'UTF-8');
            ?>
        </p>
    <?php endif; ?>

    <form method="get" action="list.php" class="search-box">
        <label for="search-input">Cari Guru</label>

        <div class="search-controls">
            <input
                type="search"
                id="search-input"
                name="q"
                value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Cari berdasarkan NIP atau nama..."
            >

            <button type="submit" class="btn-primary">
                Cari
            </button>

            <?php if ($keyword !== ''): ?>
                <a href="list.php" class="btn-edit">Reset</a>
            <?php endif; ?>
        </div>
    </form>

    <p class="pagination-info">
        Total <?php echo $totalData; ?> data guru.
        Halaman <?php echo $page; ?> dari <?php echo $totalHalaman; ?>.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Guru</th>
                    <th>Email</th>
                    <th>No. HP</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($daftarGuru)): ?>
                    <tr>
                        <td colspan="6">
                            <?php
                            echo $keyword !== ''
                                ? 'Data guru tidak ditemukan.'
                                : 'Belum ada data guru. Silakan tambahkan guru terlebih dahulu.';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarGuru as $index => $guru): ?>
                        <tr>
                            <td><?php echo $offset + $index + 1; ?></td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($guru['nip'] ?? '-'),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $guru['nama'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($guru['email'] ?? '-'),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    (string) ($guru['no_hp'] ?? '-'),
                                    ENT_QUOTES,
                                    'UTF-8'
                                );
                                ?>
                            </td>

                            <td>
                                <a
                                    href="edit.php?id=<?php echo (int) $guru['id']; ?>"
                                    class="btn-edit"
                                >Edit</a>

                                <form
                                    action="hapus.php"
                                    method="post"
                                    class="form-hapus"
                                    onsubmit="return confirm('Yakin ingin menghapus data guru ini?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo (int) $guru['id']; ?>"
                                    >

                                    <button type="submit" class="btn-delete">
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

    <?php if ($totalData > 0 && $totalHalaman > 1): ?>
        <nav class="pagination" aria-label="Navigasi halaman">

            <?php if ($page > 1): ?>
                <a href="<?php echo htmlspecialchars(urlHalamanGuru($page - 1, $keyword), ENT_QUOTES, 'UTF-8'); ?>">
                    &laquo; Sebelumnya
                </a>
            <?php else: ?>
                <span class="disabled">&laquo; Sebelumnya</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="active" aria-current="page">
                        <?php echo $i; ?>
                    </span>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars(urlHalamanGuru($i, $keyword), ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalHalaman): ?>
                <a href="<?php echo htmlspecialchars(urlHalamanGuru($page + 1, $keyword), ENT_QUOTES, 'UTF-8'); ?>">
                    Berikutnya &raquo;
                </a>
            <?php else: ?>
                <span class="disabled">Berikutnya &raquo;</span>
            <?php endif; ?>

        </nav>
    <?php endif; ?>

</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
