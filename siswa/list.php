<?php
$page_title = "Data Siswa";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarSiswa = $pdo->query("
    SELECT
        siswa.id,
        siswa.nis,
        siswa.nama,
        siswa.jenis_kelamin,
        program.nama_program
    FROM siswa
    LEFT JOIN program
        ON siswa.program_id = program.id
    ORDER BY siswa.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
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

    <?php if ($flash): ?>
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo $flash['pesan']; ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <label for="search-input">Cari Siswa</label>
        <input
            type="text"
            id="search-input"
            placeholder="Cari berdasarkan NIS atau nama..."
        >
    </div>

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
                        <td colspan="6">
                            Belum ada data siswa.
                            Silakan tambahkan siswa terlebih dahulu.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($daftarSiswa as $index => $siswa): ?>

                        <tr>
                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($siswa['nis']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($siswa['nama']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($siswa['jenis_kelamin']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($siswa['nama_program'] ?? '-'); ?>
                            </td>

                            <td>
                                <a
                                    href="edit.php?id=<?php echo $siswa['id']; ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>

                                <a
                                    href="hapus.php?id=<?php echo $siswa['id']; ?>"
                                    class="btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus data siswa ini?');"
                                >
                                    Hapus
                                </a>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>