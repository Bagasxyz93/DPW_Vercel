<?php
$page_title = "Edit Program";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM program
    WHERE id = :id
");

$stmt->execute([
    'id' => $id
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
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <div class="form-card">

        <form method="post" action="proses_edit.php">

            <input
                type="hidden"
                name="id"
                value="<?php echo $program['id']; ?>"
            >

            <div class="form-group">
                <label for="nama_program">Nama Program</label>

                <input
                    type="text"
                    id="nama_program"
                    name="nama_program"
                    value="<?php echo htmlspecialchars($program['nama_program']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                ><?php echo htmlspecialchars($program['deskripsi'] ?? ''); ?></textarea>
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

<?php include __DIR__ . '/../includes/footer.php'; ?>