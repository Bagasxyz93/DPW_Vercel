<?php
$page_title = "Edit Guru";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM guru
    WHERE id = :id
");

$stmt->execute([
    'id' => $id
]);

$guru = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$guru) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data guru tidak ditemukan.'
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
            <h2>Edit Guru</h2>
            <p>Perbarui data guru.</p>
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
                value="<?php echo $guru['id']; ?>"
            >

            <div class="form-group">
                <label for="nip">NIP</label>
                <input
                    type="text"
                    id="nip"
                    name="nip"
                    value="<?php echo htmlspecialchars($guru['nip'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="nama">Nama Guru</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?php echo htmlspecialchars($guru['nama']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($guru['email'] ?? ''); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="no_hp">No. HP</label>
                <input
                    type="text"
                    id="no_hp"
                    name="no_hp"
                    value="<?php echo htmlspecialchars($guru['no_hp'] ?? ''); ?>"
                >
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