<?php
$page_title = "Data Guru";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarGuru = $pdo->query("
    SELECT *
    FROM guru
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
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
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <label for="search-input">Cari Guru</label>
        <input
            type="text"
            id="search-input"
            placeholder="Cari berdasarkan NIP atau nama..."
        >
    </div>

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
                            Belum ada data guru.
                            Silakan tambahkan guru terlebih dahulu.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($daftarGuru as $index => $guru): ?>

                        <tr>
                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($guru['nip'] ?? '-'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($guru['nama']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($guru['email'] ?? '-'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($guru['no_hp'] ?? '-'); ?>
                            </td>

                            <td>
                                <a
                                    href="edit.php?id=<?php echo $guru['id']; ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>

                                <a
                                    href="hapus.php?id=<?php echo $guru['id']; ?>"
                                    class="btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus data guru ini?');"
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