<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page_title = "Data Program";

require __DIR__ . '/../includes/koneksi.php';

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
    FROM program
    WHERE nama_program ILIKE :keyword
";

$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute([
    'keyword' => '%' . $keyword . '%',
]);

$totalData = (int) $stmtCount->fetchColumn();

$totalHalaman = max(1, (int) ceil($totalData / $limit));
$page = min($page, $totalHalaman);
$offset = ($page - 1) * $limit;

// AMBIL DATA PROGRAM
$sqlProgram = "
    SELECT *
    FROM program
    WHERE nama_program ILIKE :keyword
    ORDER BY id DESC
    LIMIT :limit OFFSET :offset
";

$stmtProgram = $pdo->prepare($sqlProgram);

$stmtProgram->bindValue(
    ':keyword',
    '%' . $keyword . '%',
    PDO::PARAM_STR
);
$stmtProgram->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmtProgram->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmtProgram->execute();

$daftarProgram = $stmtProgram->fetchAll(PDO::FETCH_ASSOC);

// URL PAGINATION
function urlHalamanProgram($halaman, $keyword)
{
    return '?' . http_build_query([
        'q' => $keyword,
        'page' => $halaman,
    ]);
}

include __DIR__ . '/../includes/header.php';
?>

<section>

    <div class="page-header">
        <div>
            <h2>Data Program</h2>
            <p>Kelola program yang tersedia dalam sistem.</p>
        </div>

        <a href="tambah.php" class="btn-primary">
            + Tambah Program
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
        <label for="search-input">Cari Program</label>

        <div class="search-controls">
            <input
                type="search"
                id="search-input"
                name="q"
                value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Cari berdasarkan nama program..."
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
        Total <?php echo $totalData; ?> data program.
        Halaman <?php echo $page; ?> dari <?php echo $totalHalaman; ?>.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Program</th>
                    <th>Deskripsi</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php if (empty($daftarProgram)): ?>
                    <tr>
                        <td colspan="5">
                            <?php
                            echo $keyword !== ''
                                ? 'Program tidak ditemukan.'
                                : 'Belum ada data program. Silakan tambahkan program terlebih dahulu.';
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarProgram as $index => $program): ?>
                        <tr>
                            <td><?php echo $offset + $index + 1; ?></td>

                            <td>
                                <?php echo htmlspecialchars($program['nama_program'], ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($program['deskripsi'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
                            </td>

                            <td>
                                <?php
                                if (!empty($program['created_at'])) {
                                    echo htmlspecialchars(
                                        date('d-m-Y', strtotime($program['created_at'])),
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>

                            <td>
                                <a
                                    href="edit.php?id=<?php echo (int) $program['id']; ?>"
                                    class="btn-edit"
                                >Edit</a>

                                <form
                                    action="hapus.php"
                                    method="post"
                                    class="form-hapus"
                                >
                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?php echo (int) $program['id']; ?>"
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
                <a href="<?php echo htmlspecialchars(urlHalamanProgram($page - 1, $keyword), ENT_QUOTES, 'UTF-8'); ?>">
                    &laquo; Sebelumnya
                </a>
            <?php else: ?>
                <span class="disabled">&laquo; Sebelumnya</span>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalHalaman; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="active" aria-current="page"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="<?php echo htmlspecialchars(urlHalamanProgram($i, $keyword), ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $totalHalaman): ?>
                <a href="<?php echo htmlspecialchars(urlHalamanProgram($page + 1, $keyword), ENT_QUOTES, 'UTF-8'); ?>">
                    Berikutnya &raquo;
                </a>
            <?php else: ?>
                <span class="disabled">Berikutnya &raquo;</span>
            <?php endif; ?>

        </nav>
    <?php endif; ?>

</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>