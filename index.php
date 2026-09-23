<?php

$jobsheets = [
    [
        'nomor' => '01',
        'judul' => 'Jobsheet 1',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 1.',
        'link' => 'Jobsheet1/index.html'
    ],
    [
        'nomor' => '02',
        'judul' => 'Jobsheet 2',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 2.',
        'link' => 'Jobsheet2/index.html'
    ],
    [
        'nomor' => '03',
        'judul' => 'Jobsheet 3',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 3.',
        'link' => 'Jobsheet3/index.html'
    ],
    [
        'nomor' => '04',
        'judul' => 'Jobsheet 4',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 4.',
        'link' => 'Jobsheet4/index.html'
    ],
    [
        'nomor' => '05',
        'judul' => 'Jobsheet 5',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 5.',
        'link' => 'Jobsheet5/index.html'
    ],
    [
        'nomor' => '06',
        'judul' => 'Jobsheet 6',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 6.',
        'link' => 'Jobsheet6/index.html'
    ],
    [
        'nomor' => '07',
        'judul' => 'Jobsheet 7',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 7.',
        'link' => 'Jobsheet7/index.php'
    ],
    [
        'nomor' => '08',
        'judul' => 'Jobsheet 8',
        'deskripsi' => 'Hasil pengerjaan Jobsheet 8.',
        'link' => 'Jobsheet8/index.php'
    ]
];

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DPW | Kumpulan Jobsheet</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="page">

    <!-- HEADER -->
    <header class="header">

        <div class="brand">

            <div class="brand-icon">
                DPW
            </div>

            <div>
                <h1>DPW Project</h1>
                <span>Desain & Pemrograman Web</span>
            </div>

        </div>

        <div class="header-info">
            Semester 3
        </div>

    </header>


    <!-- CONTENT -->
    <main class="container">

        <!-- HERO -->
        <section class="hero">

            <span class="hero-label">
                DESAIN & PEMROGRAMAN WEB
            </span>

            <h2>
                Kumpulan Jobsheet
            </h2>

            <p>
                Dokumentasi dan hasil pengerjaan Jobsheet
                Desain & Pemrograman Web dari Jobsheet 1
                hingga Jobsheet 8.
            </p>

        </section>


        <!-- DAFTAR JOBSHEET -->
        <section>

            <div class="section-title">

                <div>
                    <h3>
                        Daftar Jobsheet
                    </h3>

                    <p>
                        Pilih jobsheet untuk melihat hasil pengerjaan.
                    </p>
                </div>

                <span class="total">
                    <?php echo count($jobsheets); ?> Jobsheet
                </span>

            </div>


            <div class="jobsheet-grid">

                <?php foreach ($jobsheets as $jobsheet): ?>

                    <article class="jobsheet-card">

                        <div class="card-number">
                            <?php echo $jobsheet['nomor']; ?>
                        </div>


                        <div class="card-content">

                            <span class="card-label">
                                JOBSHEET
                                <?php echo $jobsheet['nomor']; ?>
                            </span>


                            <h4>
                                <?php
                                echo htmlspecialchars($jobsheet['judul']);
                                ?>
                            </h4>


                            <p>
                                <?php
                                echo htmlspecialchars($jobsheet['deskripsi']);
                                ?>
                            </p>


                            <a
                                href="<?php echo htmlspecialchars($jobsheet['link']); ?>"
                                class="btn-open"
                            >
                                Buka Jobsheet
                                <span>→</span>
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        </section>

    </main>


    <!-- FOOTER -->
    <footer class="footer">

        <p>
            &copy; 2026 Desain & Pemrograman Web
        </p>

        <p>
            Politeknik Negeri Malang
        </p>

    </footer>

</div>

</body>

</html>