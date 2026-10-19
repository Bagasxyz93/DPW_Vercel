
<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/koneksi.php';

$page_title = "Tambah Absensi";

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarSiswa = $pdo->query("
    SELECT
        siswa.id,
        siswa.nis,
        siswa.nama,
        program.nama_program
    FROM siswa
    LEFT JOIN program
        ON siswa.program_id = program.id
    ORDER BY siswa.nama ASC
")->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../includes/header.php';
?>

<section>
    <div class="page-header">
        <div>
            <h2>Tambah Absensi</h2>
            <p>Tambahkan data kehadiran siswa.</p>
        </div>

        <a href="list.php" class="btn-secondary">
            ← Kembali
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

    <div class="form-card">
        <form method="post" action="proses_tambah.php">
            <div class="form-group">
                <label for="siswa_id">Siswa</label>

                <select
                    id="siswa_id"
                    name="siswa_id"
                    required
                >
                    <option value="">-- Pilih Siswa --</option>

                    <?php foreach ($daftarSiswa as $siswa): ?>
                        <option value="<?php echo (int) $siswa['id']; ?>">
                            <?php
                            echo htmlspecialchars(
                                $siswa['nis'] . ' - ' .
                                $siswa['nama'] . ' (' .
                                ($siswa['nama_program'] ?? 'Tanpa Program') .
                                ')',
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <?php if (empty($daftarSiswa)): ?>
                    <small>
                        Belum ada data siswa. Tambahkan data siswa terlebih dahulu.
                    </small>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>

                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="<?php echo date('Y-m-d'); ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="waktu_absen">Waktu Absen</label>

                <input
                    type="time"
                    id="waktu_absen"
                    name="waktu_absen"
                    value="<?php echo date('H:i'); ?>"
                >
            </div>

            <div class="form-group">
                <label for="status">Status</label>

                <select
                    id="status"
                    name="status"
                    required
                >
                    <option value="">-- Pilih Status --</option>
                    <option value="Hadir">Hadir</option>
                    <option value="Izin">Izin</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Alpha">Alpha</option>
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>

                <textarea
                    id="keterangan"
                    name="keterangan"
                    rows="4"
                    placeholder="Masukkan keterangan jika diperlukan..."
                ></textarea>
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn-secondary">
                    Batal
                </a>

                <button
                    type="submit"
                    class="btn-primary"
                    <?php echo empty($daftarSiswa) ? 'disabled' : ''; ?>
                >
                    Simpan Absensi
                </button>
            </div>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
