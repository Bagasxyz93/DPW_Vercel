
        </main>

        <footer>
            <p>
                &copy; 2026 SIABSEN &mdash;
                Sistem Informasi Absensi
            </p>
        </footer>

    </div><!-- .main-content -->

</div><!-- .dashboard-layout -->

<script src="<?php
    echo htmlspecialchars($base ?? '', ENT_QUOTES, 'UTF-8');
?>assets/js/app.js"></script>

<?php if (!empty($extra_scripts) && is_array($extra_scripts)): ?>
    <?php foreach ($extra_scripts as $src): ?>
        <script src="<?php
            echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8');
        ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>
