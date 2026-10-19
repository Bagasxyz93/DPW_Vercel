
<?php
require_once __DIR__ . '/../includes/auth.php';

$page_title = "Tambah Guru";

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

require __DIR__ . '/../includes/header.php';
?>

<section>

    <div class="page-header">
        <div>
            <h2>Tambah Guru</h2>
            <p>Tambahkan data guru ke dalam sistem.</p>
        </div>

        <a href="list.php" class="btn-secondary">
            &larr; Kembali
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

        <form
            id="form-tambah"
            method="post"
            action="proses_tambah.php"
        >

            <div class="form-group">
                <label for="nip">NIP</label>
                <input
                    type="text"
                    id="nip"
                    name="nip"
                    placeholder="Masukkan NIP guru"
                    required
                >
            </div>

            <div class="form-group">
                <label for="nama">Nama Guru</label>
                <input
                    type="text"
                    id="nama"
                    name="nama"
                    placeholder="Masukkan nama guru"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Masukkan email guru"
                    required
                >
            </div>

            <div class="form-group">
                <label for="no_hp">No. HP</label>
                <input
                    type="text"
                    id="no_hp"
                    name="no_hp"
                    placeholder="Masukkan nomor HP"
                >
            </div>

            <div class="form-actions">
                <a href="list.php" class="btn-secondary">
                    Batal
                </a>

                <button type="submit" class="btn-primary">
                    Simpan Guru
                </button>
            </div>

        </form>

    </div>

</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>
