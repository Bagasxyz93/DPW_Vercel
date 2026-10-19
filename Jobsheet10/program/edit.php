
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = "Edit Program";

$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id) || (int) $id < 1) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'ID program tidak valid.'
    ];

    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM program
    WHERE id = :id
");

$stmt->execute([
    'id' => (int) $id
]);

$program = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$program) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data program tidak ditemukan.'
    ];

    header('Location: list.php');
    exit;
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/../includes/header.php';
?>

<section>
    <div class="page-header">
        <div>
            <h2>Edit Program</h2>
            <p>Perbarui data program.</p>
        </div>

        <a href="list.php" class="btn-secondary">
            ← Kembali
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

    <div class="form-card">
        <form method="post" action="proses_edit.php">
            <input
                type="hidden"
                name="id"
                value="<?php echo (int) $program['id']; ?>"
            >

            <div class="form-group">
                <label for="nama_program">Nama Program</label>

                <input
                    type="text"
                    id="nama_program"
                    name="nama_program"
                    value="<?php echo htmlspecialchars(
                        $program['nama_program'],
                        ENT_QUOTES,
                        'UTF-8'
                    ); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                ><?php echo htmlspecialchars(
                    $program['deskripsi'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?></textarea>
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
