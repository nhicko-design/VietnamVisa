<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$page_title = 'Settings';
$admin_section = 'settings';
$message = '';
$errors = [];
$fields = [
    'site_name' => 'SunViet',
    'site_email' => 'info@sunviettravel.com',
    'site_phone' => '+84 90 123 4567',
    'default_language' => 'en',
    'site_address' => '',
    'facebook_url' => '',
    'messenger_url' => '',
    'zalo_url' => '',
];

$statement = $pdo->query(
    "SELECT setting_key, setting_value
     FROM settings
     WHERE setting_key IN (
        'site_name', 'site_email', 'site_phone', 'default_language',
        'site_address', 'facebook_url', 'messenger_url', 'zalo_url'
     )"
);

foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $setting) {
    $fields[$setting['setting_key']] = (string) $setting['setting_value'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $fields['site_name'] = trim((string) ($_POST['site_name'] ?? ''));
        $fields['site_email'] = trim((string) ($_POST['site_email'] ?? ''));
        $fields['site_phone'] = trim((string) ($_POST['site_phone'] ?? ''));
        $fields['default_language'] = $_POST['default_language'] ?? 'en';
        $fields['site_address'] = trim((string) ($_POST['site_address'] ?? ''));
        $fields['facebook_url'] = trim((string) ($_POST['facebook_url'] ?? ''));
        $fields['messenger_url'] = trim((string) ($_POST['messenger_url'] ?? ''));
        $fields['zalo_url'] = trim((string) ($_POST['zalo_url'] ?? ''));

        if ($fields['site_name'] === '') {
            $errors[] = 'Site name is required.';
        }

        if ($fields['site_email'] === '' || !filter_var($fields['site_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid site email address.';
        }

        if (!in_array($fields['default_language'], ['en', 'vn'], true)) {
            $errors[] = 'Select a valid default language.';
        }

        foreach (['facebook_url', 'messenger_url', 'zalo_url'] as $field) {
            if ($fields[$field] !== '' && !filter_var($fields[$field], FILTER_VALIDATE_URL)) {
                $errors[] = 'Enter a valid URL for ' . str_replace('_', ' ', $field) . '.';
            }
        }

        if (!$errors) {
            $statement = $pdo->prepare(
                'INSERT INTO settings (setting_key, setting_value, setting_group)
                 VALUES (:setting_key, :setting_value, :setting_group)
                 ON DUPLICATE KEY UPDATE
                    setting_value = VALUES(setting_value),
                    setting_group = VALUES(setting_group),
                    updated_at = NOW()'
            );

            foreach ($fields as $key => $value) {
                $statement->execute([
                    'setting_key' => $key,
                    'setting_value' => $value,
                    'setting_group' => 'general',
                ]);
            }

            $message = 'Settings saved successfully.';
        }
    }
}

require __DIR__ . '/components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>

        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">SYSTEM</span>
                    <h1>General Settings</h1>
                    <p>Maintain the public contact and language settings for the website.</p>
                </div>
            </section>

            <?php if ($message): ?>
                <div class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="error-box" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="news-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

                <section class="admin-card news-form-card">
                    <div class="news-form-grid">
                        <div>
                            <label for="site_name">Site name</label>
                            <input id="site_name" name="site_name" required value="<?= htmlspecialchars($fields['site_name'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="default_language">Default language</label>
                            <select id="default_language" name="default_language">
                                <option value="en" <?= $fields['default_language'] === 'en' ? 'selected' : '' ?>>English</option>
                                <option value="vn" <?= $fields['default_language'] === 'vn' ? 'selected' : '' ?>>Vietnamese</option>
                            </select>
                        </div>

                        <div>
                            <label for="site_email">Public email</label>
                            <input id="site_email" type="email" name="site_email" required value="<?= htmlspecialchars($fields['site_email'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="site_phone">Public phone</label>
                            <input id="site_phone" name="site_phone" value="<?= htmlspecialchars($fields['site_phone'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="full">
                            <label for="site_address">Public address</label>
                            <input id="site_address" name="site_address" value="<?= htmlspecialchars($fields['site_address'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="facebook_url">Facebook URL</label>
                            <input id="facebook_url" type="url" name="facebook_url" value="<?= htmlspecialchars($fields['facebook_url'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="messenger_url">Messenger URL</label>
                            <input id="messenger_url" type="url" name="messenger_url" value="<?= htmlspecialchars($fields['messenger_url'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="zalo_url">Zalo URL</label>
                            <input id="zalo_url" type="url" name="zalo_url" value="<?= htmlspecialchars($fields['zalo_url'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                </section>

                <div class="news-form-actions">
                    <button class="admin-btn admin-btn-primary" type="submit">Save Settings</button>
                </div>
            </form>
        </main>
    </div>
</div>
<?php require __DIR__ . '/components/footer.php'; ?>
