<footer class="admin-footer">

    <span>
        © <?= date('Y') ?> SunViet Admin
    </span>

    <span>
        Website Management System
    </span>

</footer>


<script>
window.SunVietEditor = <?= json_encode([
    'endpoint' => ADMIN_BASE_URL . '/editor-media.php',
    'csrfToken' => csrf_token(),
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/js/admin.js', ENT_QUOTES, 'UTF-8') ?>"></script>
<script src="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/js/rich-editor.js?v=' . filemtime(__DIR__ . '/../assets/js/rich-editor.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>
