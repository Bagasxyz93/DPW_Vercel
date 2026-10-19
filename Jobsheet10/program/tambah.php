
<?php
require_once __DIR__ . '/../includes/auth.php';

$page_title = "Tambah Program";

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/../includes/header.php';
?>

<section>
    <div class="page-header">
        <div>
            <h2>Tambah Program</h2>
            <p>Tambahkan program baru ke dalam sistem.</p>
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
                <label for="nama_program">Nama Program</label>

                <input
                    type="text"
                    id="nama_program"
                    name="nama_program"
                    placeholder="Contoh: Web Development"
                    required
                >
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>

                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="5"
                    placeholder="Masukkan deskripsi program..."
                ></textarea>
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Simpan Program
                </button>
            </div>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
