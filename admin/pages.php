<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$page_title = 'Page Content';
$admin_section = 'pages';
$message = '';
$errors = [];

$pageDefinitions = [
    'home' => [
        'label' => 'Homepage',
        'description' => 'Manage the hero copy and calls to action on the homepage.',
        'fields' => [
            'eyebrow' => 'Hero eyebrow',
            'title' => 'Hero title',
            'description' => 'Hero description',
            'primary_label' => 'Primary button label',
            'primary_url' => 'Primary button URL',
            'secondary_label' => 'Secondary button label',
            'secondary_url' => 'Secondary button URL',
            'highlight_title' => 'Hero highlight title',
            'highlight_description' => 'Hero highlight description',
        ],
    ],
    'about' => [
        'label' => 'About',
        'description' => 'Manage the public About page heading and introduction.',
        'fields' => [
            'eyebrow' => 'Page eyebrow',
            'title' => 'Page title',
            'description' => 'Page description',
            'section_title' => 'Main section title',
        ],
    ],
    'visa' => [
        'label' => 'Vietnam Visa',
        'description' => 'Manage the heading shown above Visa services.',
        'fields' => [
            'eyebrow' => 'Page eyebrow',
            'title' => 'Page title',
            'description' => 'Page description',
        ],
    ],
    'support' => [
        'label' => 'Support',
        'description' => 'Manage the heading shown above Support services.',
        'fields' => [
            'eyebrow' => 'Page eyebrow',
            'title' => 'Page title',
            'description' => 'Page description',
        ],
    ],
];

$page = (string) ($_GET['page'] ?? $_POST['page'] ?? 'home');

if (!isset($pageDefinitions[$page])) {
    $page = 'home';
}

$supportsHeroImage = $page !== 'home';
$heroImage = '';

$fields = [];

foreach (array_keys($pageDefinitions[$page]['fields']) as $field) {
    $fields[$field . '_en'] = '';
    $fields[$field . '_vn'] = '';
}

$settingKeys = array_map(
    static fn (string $field): string => 'page_' . $page . '_' . $field,
    array_keys($fields)
);

$placeholders = implode(', ', array_fill(0, count($settingKeys), '?'));
$statement = $pdo->prepare(
    'SELECT setting_key, setting_value
     FROM settings
     WHERE setting_key IN (' . $placeholders . ')'
);
$statement->execute($settingKeys);

foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $setting) {
    $fieldKey = substr($setting['setting_key'], strlen('page_' . $page . '_'));

    if (array_key_exists($fieldKey, $fields)) {
        $fields[$fieldKey] = (string) $setting['setting_value'];
    }
}

if ($supportsHeroImage) {
    $statement = $pdo->prepare(
        'SELECT setting_value
         FROM settings
         WHERE setting_key = :setting_key
         LIMIT 1'
    );
    $statement->execute([
        'setting_key' => 'page_' . $page . '_hero_image',
    ]);
    $heroImage = (string) $statement->fetchColumn();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_post_exceeds_server_limit()) {
        $errors[] = 'The selected file exceeds the server upload limit. Please choose a smaller file and try again.';
    } elseif (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        foreach (array_keys($fields) as $field) {
            $limit = str_ends_with($field, 'description_en') || str_ends_with($field, 'description_vn')
                ? 2000
                : 500;

            $fields[$field] = mb_substr(
                trim((string) ($_POST[$field] ?? '')),
                0,
                $limit,
                'UTF-8'
            );
        }

        foreach (['primary_url', 'secondary_url'] as $urlField) {
            foreach (['_en', '_vn'] as $suffix) {
                $field = $urlField . $suffix;

                if (!array_key_exists($field, $fields) || $fields[$field] === '') {
                    continue;
                }

                if ($fields[$field][0] !== '/' && !filter_var($fields[$field], FILTER_VALIDATE_URL)) {
                    $errors[] = 'Enter a full URL or a website path beginning with / for ' . str_replace('_', ' ', $field) . '.';
                }
            }
        }

        $removeHeroImage = isset($_POST['remove_hero_image']);
        $hasHeroUpload = $supportsHeroImage
            && isset($_FILES['hero_image_file'])
            && $_FILES['hero_image_file']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($hasHeroUpload) {
            $file = $_FILES['hero_image_file'];
            $allowedImages = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif',
            ];

            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 10 * 1024 * 1024) {
                $errors[] = 'Hero image upload failed or exceeds the 10 MB limit.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!isset($allowedImages[$mimeType])) {
                    $errors[] = 'Hero image must be a JPG, PNG, WEBP or GIF file.';
                } else {
                    $relativeDirectory = 'uploads/pages/images/';
                    $directory = dirname(__DIR__) . '/' . $relativeDirectory;

                    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
                        $errors[] = 'Unable to create the hero image upload directory.';
                    } else {
                        $fileName = $page . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $allowedImages[$mimeType];

                        if (!move_uploaded_file($file['tmp_name'], $directory . $fileName)) {
                            $errors[] = 'Unable to save the hero image.';
                        } else {
                            $newHeroImage = $relativeDirectory . $fileName;
                        }
                    }
                }
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

            foreach ($fields as $field => $value) {
                $statement->execute([
                    'setting_key' => 'page_' . $page . '_' . $field,
                    'setting_value' => $value,
                    'setting_group' => 'pages',
                ]);
            }

            if ($supportsHeroImage) {
                $nextHeroImage = $newHeroImage
                    ?? ($removeHeroImage ? '' : $heroImage);

                $statement->execute([
                    'setting_key' => 'page_' . $page . '_hero_image',
                    'setting_value' => $nextHeroImage,
                    'setting_group' => 'pages',
                ]);

                if (($removeHeroImage || isset($newHeroImage)) && $heroImage !== '') {
                    $localPath = admin_local_page_upload_path($heroImage);

                    if ($localPath !== null && is_file($localPath)) {
                        @unlink($localPath);
                    }
                }

                $heroImage = $nextHeroImage;
            }

            $message = $pageDefinitions[$page]['label'] . ' content saved successfully.';
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
                    <span class="page-eyebrow">WEBSITE CONTENT</span>
                    <h1>Page Content</h1>
                    <p><?= htmlspecialchars($pageDefinitions[$page]['description'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <?php if ($page === 'home'): ?>
                    <a class="admin-btn admin-btn-secondary" href="home-media.php">Manage hero media</a>
                <?php endif; ?>
            </section>

            <div class="management-actions page-tabs">
                <?php foreach ($pageDefinitions as $pageKey => $definition): ?>
                    <a
                        class="admin-btn admin-btn-secondary admin-btn-small <?= $page === $pageKey ? 'is-selected' : '' ?>"
                        href="pages.php?page=<?= urlencode($pageKey) ?>"
                    >
                        <?= htmlspecialchars($definition['label'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                <?php endforeach; ?>
            </div>

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

            <form method="post" class="news-form" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="page" value="<?= htmlspecialchars($page, ENT_QUOTES, 'UTF-8') ?>">

                <?php if ($supportsHeroImage): ?>
                    <section class="admin-card news-form-card page-hero-media-card">
                        <div class="page-language-heading">
                            <span>IMG</span>
                            <h2>Hero image</h2>
                        </div>

                        <div class="news-form-grid">
                            <div class="full">
                                <label for="hero_image_file">Choose an image file</label>
                                <input id="hero_image_file" type="file" name="hero_image_file" accept="image/jpeg,image/png,image/webp,image/gif">
                                <small>JPG, PNG, WEBP or GIF. Maximum 10 MB. A new upload replaces the current hero image.</small>
                            </div>

                            <?php if ($heroImage !== ''): ?>
                                <div class="full page-hero-image-preview">
                                    <img src="<?= htmlspecialchars(admin_media_url($heroImage), ENT_QUOTES, 'UTF-8') ?>" alt="Current hero image">
                                </div>

                                <label class="news-checkbox">
                                    <input type="checkbox" name="remove_hero_image" value="1">
                                    Remove current hero image
                                </label>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="admin-card news-form-card">
                    <div class="page-language-heading">
                        <span>EN</span>
                        <h2>English content</h2>
                    </div>

                    <div class="news-form-grid">
                        <?php foreach ($pageDefinitions[$page]['fields'] as $field => $label): ?>
                            <div class="<?= str_contains($field, 'description') ? 'full' : '' ?>">
                                <label for="<?= htmlspecialchars($field . '_en', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                                <?php if (str_contains($field, 'description')): ?>
                                    <textarea id="<?= htmlspecialchars($field . '_en', ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($field . '_en', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($fields[$field . '_en'], ENT_QUOTES, 'UTF-8') ?></textarea>
                                <?php else: ?>
                                    <input id="<?= htmlspecialchars($field . '_en', ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($field . '_en', ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($fields[$field . '_en'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <section class="admin-card news-form-card page-language-card">
                    <div class="page-language-heading">
                        <span>VI</span>
                        <h2>Vietnamese content</h2>
                    </div>

                    <div class="news-form-grid">
                        <?php foreach ($pageDefinitions[$page]['fields'] as $field => $label): ?>
                            <div class="<?= str_contains($field, 'description') ? 'full' : '' ?>">
                                <label for="<?= htmlspecialchars($field . '_vn', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></label>
                                <?php if (str_contains($field, 'description')): ?>
                                    <textarea id="<?= htmlspecialchars($field . '_vn', ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($field . '_vn', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($fields[$field . '_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                                <?php else: ?>
                                    <input id="<?= htmlspecialchars($field . '_vn', ENT_QUOTES, 'UTF-8') ?>" name="<?= htmlspecialchars($field . '_vn', ENT_QUOTES, 'UTF-8') ?>" value="<?= htmlspecialchars($fields[$field . '_vn'], ENT_QUOTES, 'UTF-8') ?>">
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="news-form-actions">
                        <button class="admin-btn admin-btn-primary" type="submit">Save <?= htmlspecialchars($pageDefinitions[$page]['label'], ENT_QUOTES, 'UTF-8') ?></button>
                    </div>
                </section>
            </form>
        </main>
    </div>
</div>

<?php require __DIR__ . '/components/footer.php'; ?>
