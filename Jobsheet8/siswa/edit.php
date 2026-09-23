<?php
$page_title = "Edit Siswa";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$id = $_GET['id'] ?? '';

if ($id === '') {
    header('Location: list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM siswa
    WHERE id = :id
");
$stmt->execute(['id' => $id]);

$siswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$siswa) {
    $_SESSION['flash'] = [
        'type' => 'error',
        'pesan' => 'Data siswa tidak ditemukan.'
    ];

    header('Location: list.php');
    exit;
}

// Ambil daftar program
$daftarProgram = $pdo->query("
    SELECT id, nama_program
    FROM program
    ORDER BY nama_program ASC
")->fetchAll(PDO::FETCH_ASSOC);

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>

<section>
    <div class="page-header">
        <div>
            <h2>Edit Siswa</h2>
            <p>Perbarui data siswa.</p>
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
                value="<?php echo $siswa['id']; ?>"
            >

            <div class="form-group">
                <label for="nis">NIS</label>
                <input
                    type="text"
                    id="nis"
                    name="nis"
                    value="<?php echo htmlspecialchars($siswa['nis']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="nama">Nama Siswa</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    value="<?php echo htmlspecialchars($siswa['nama']); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin</label>

                <select
                    id="jenis_kelamin"
                    name="jenis_kelamin"
                    required
                >
                    <option value="">-- Pilih Jenis Kelamin --</option>

                    <option
                        value="L"
                        <?php echo $siswa['jenis_kelamin'] === 'L' ? 'selected' : ''; ?>
                    >
                        Laki-laki
                    </option>

                    <option
                        value="P"
                        <?php echo $siswa['jenis_kelamin'] === 'P' ? 'selected' : ''; ?>
                    >
                        Perempuan
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label for="program_id">Program</label>

                <select
                    id="program_id"
                    name="program_id"
                    required
                >
                    <option value="">-- Pilih Program --</option>

                    <?php foreach ($daftarProgram as $program): ?>

                        <option
                            value="<?php echo $program['id']; ?>"
                            <?php echo $siswa['program_id'] == $program['id'] ? 'selected' : ''; ?>
                        >
                            <?php echo htmlspecialchars($program['nama_program']); ?>
                        </option>

                    <?php endforeach; ?>

                </select>
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