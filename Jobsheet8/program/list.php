<?php
$page_title = "Data Program";

include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarProgram = $pdo->query("
    SELECT *
    FROM program
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
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
        <p class="flash flash-<?php echo $flash['type']; ?>">
            <?php echo htmlspecialchars($flash['pesan']); ?>
        </p>
    <?php endif; ?>

    <div class="search-box">
        <label for="search-input">Cari Program</label>
        <input
            type="text"
            id="search-input"
            placeholder="Cari berdasarkan nama program..."
        >
    </div>

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
                            Belum ada data program.
                            Silakan tambahkan program terlebih dahulu.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($daftarProgram as $index => $program): ?>

                        <tr>
                            <td>
                                <?php echo $index + 1; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($program['nama_program']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($program['deskripsi'] ?? '-'); ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    'd-m-Y',
                                    strtotime($program['created_at'])
                                );
                                ?>
                            </td>

                            <td>
                                <a
                                    href="edit.php?id=<?php echo $program['id']; ?>"
                                    class="btn-edit"
                                >
                                    Edit
                                </a>

                                <a
                                    href="hapus.php?id=<?php echo $program['id']; ?>"
                                    class="btn-delete"
                                    onclick="return confirm('Yakin ingin menghapus program ini?');"
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