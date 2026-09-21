<?php
$page_title = "Tambah Siswa";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Ambil daftar program
$daftarProgram = $pdo->query("
    SELECT id, nama_program
    FROM program
    ORDER BY nama_program ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <div class="page-header">
        <div>
            <h2>Tambah Siswa</h2>
            <p>Tambahkan data siswa ke dalam sistem.</p>
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

        <form id="form-tambah" method="post" action="proses_tambah.php">

            <div class="form-group">
                <label for="nis">NIS</label>
                <input
                    type="text"
                    id="nis"
                    name="nis"
                    placeholder="Masukkan NIS siswa"
                    required
                >
            </div>

            <div class="form-group">
                <label for="nama">Nama Siswa</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama siswa"
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
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
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
                        <option value="<?php echo $program['id']; ?>">
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
                    Simpan Siswa
                </button>
            </div>

        </form>

    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>