<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$page_title = 'Homepage Media';
$admin_section = 'homepage';
$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $statement = $pdo->prepare('SELECT file_path FROM home_media WHERE id = ?');
        $statement->execute([$mediaId]);
        $media = $statement->fetch(PDO::FETCH_ASSOC);

        $statement = $pdo->prepare('DELETE FROM home_media WHERE id = ?');
        $statement->execute([$mediaId]);

        if ($media) {
            $localPath = admin_local_home_upload_path((string) $media['file_path']);

            if ($localPath !== null && is_file($localPath)) {
                @unlink($localPath);
            }
        }
        header('Location: home-media.php?deleted=1');
        exit;
    } elseif (($_POST['action'] ?? '') === 'toggle') {
        $statement = $pdo->prepare('UPDATE home_media SET is_active = 1 - is_active WHERE id = ?');
        $statement->execute([(int) ($_POST['media_id'] ?? 0)]);
        header('Location: home-media.php?updated=1');
        exit;
    } else {
        $section = $_POST['section'] ?? 'hero';
        $mediaType = $_POST['media_type'] ?? 'image';
        $filePath = trim((string) ($_POST['file_path'] ?? ''));
        $title = trim((string) ($_POST['title'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $hasUpload = isset($_FILES['media_file'])
            && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE;

        if (!in_array($section, ['hero'], true)) {
            $errors[] = 'Select a valid homepage section.';
        }

        if (!in_array($mediaType, ['image', 'video'], true)) {
            $errors[] = 'Select a valid media type.';
        }

        if ($filePath === '' && !$hasUpload) {
            $errors[] = 'Image or video URL/path is required.';
        }

        if (!$errors && $hasUpload) {
            $file = $_FILES['media_file'];
            $allowed = $mediaType === 'image'
                ? ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif']
                : ['video/mp4' => 'mp4', 'video/webm' => 'webm', 'video/quicktime' => 'mov'];

            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 50 * 1024 * 1024) {
                $errors[] = 'Upload failed or exceeds the 50 MB limit.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!isset($allowed[$mimeType])) {
                    $errors[] = 'File type does not match the selected media type.';
                } else {
                    $relativeDirectory = 'uploads/home/' . ($mediaType === 'image' ? 'images/' : 'videos/');
                    $directory = dirname(__DIR__) . '/' . $relativeDirectory;

                    if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
                        $errors[] = 'Unable to create upload directory.';
                    } else {
                        $fileName = date('YmdHis') . '_' . bin2hex(random_bytes(6)) . '.' . $allowed[$mimeType];

                        if (!move_uploaded_file($file['tmp_name'], $directory . $fileName)) {
                            $errors[] = 'Unable to save uploaded file.';
                        } else {
                            $filePath = $relativeDirectory . $fileName;
                        }
                    }
                }
            }
        }

        if (!$errors) {
            $statement = $pdo->prepare(
                'INSERT INTO home_media (
                    section, media_type, file_path, title, description, sort_order, is_active
                ) VALUES (
                    :section, :media_type, :file_path, :title, :description, :sort_order, :is_active
                )'
            );
            $statement->execute([
                'section' => $section,
                'media_type' => $mediaType,
                'file_path' => $filePath,
                'title' => $title ?: null,
                'description' => $description ?: null,
                'sort_order' => $sortOrder,
                'is_active' => $isActive,
            ]);
            header('Location: home-media.php?created=1');
            exit;
        }
    }
}

if (isset($_GET['created'])) {
    $message = 'Homepage media added successfully.';
} elseif (isset($_GET['deleted'])) {
    $message = 'Homepage media removed successfully.';
} elseif (isset($_GET['updated'])) {
    $message = 'Homepage media status updated.';
}

$mediaItems = $pdo->query(
    'SELECT id, section, media_type, file_path, title, description, sort_order, is_active
     FROM home_media
     ORDER BY section ASC, sort_order ASC, id ASC'
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>

        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">HOMEPAGE</span>
                    <h1>Homepage Media</h1>
                    <p>Set the image or video used in the website hero section.</p>
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

            <section class="admin-card news-form-card">
                <h2 class="media-section-title">Add hero media</h2>
                <form method="post" class="news-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                    <div class="news-form-grid">
                        <div>
                            <label for="section">Homepage section</label>
                            <select id="section" name="section"><option value="hero">Hero</option></select>
                        </div>
                        <div>
                            <label for="media_type">Media type</label>
                            <select id="media_type" name="media_type"><option value="image">Image</option><option value="video">Video</option></select>
                        </div>
                        <div class="full">
                            <label for="media_file">Upload image or video</label>
                            <input id="media_file" type="file" name="media_file" accept="image/*,video/*">
                            <small>JPG, PNG, WEBP, GIF, MP4, WEBM or MOV. Maximum 50 MB.</small>
                        </div>
                        <div class="full">
                            <label for="file_path">Or enter an image or direct video URL/path</label>
                            <input id="file_path" name="file_path" placeholder="https://example.com/hero.jpg">
                        </div>
                        <div>
                            <label for="title">Alt text / title</label>
                            <input id="title" name="title" placeholder="Vietnam landscape">
                        </div>
                        <div>
                            <label for="sort_order">Display order</label>
                            <input id="sort_order" type="number" name="sort_order" min="0" value="0">
                        </div>
                        <div class="full">
                            <label for="description">Description</label>
                            <textarea id="description" name="description"></textarea>
                        </div>
                        <label class="news-checkbox"><input type="checkbox" name="is_active" value="1" checked> Show on homepage</label>
                    </div>
                    <div class="news-form-actions"><button class="admin-btn admin-btn-primary" type="submit">Add Homepage Media</button></div>
                </form>
            </section>

            <section class="admin-card management-card media-list-card">
                <div class="media-list-heading"><h2>Hero media</h2><span><?= count($mediaItems) ?> item(s)</span></div>
                <?php if (!$mediaItems): ?>
                    <div class="empty-state"><h3>No homepage media yet</h3><p>The built-in hero image will remain visible until you add one here.</p></div>
                <?php else: ?>
                    <div class="news-media-grid">
                        <?php foreach ($mediaItems as $media): ?>
                            <article class="news-media-card">
                                <div class="news-media-preview">
                                    <?php if ($media['media_type'] === 'video'): ?>
                                        <video controls preload="metadata"><source src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>"></video>
                                    <?php else: ?>
                                        <img src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($media['title'] ?: '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="news-media-card-body">
                                    <span class="status-badge <?= (int) $media['is_active'] ? 'status-success' : 'status-muted' ?>"><?= (int) $media['is_active'] ? 'Visible' : 'Hidden' ?></span>
                                    <strong><?= htmlspecialchars($media['title'] ?: 'Untitled media', ENT_QUOTES, 'UTF-8') ?></strong>
                                    <small>Hero · Order <?= (int) $media['sort_order'] ?></small>
                                    <form method="post">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                        <input type="hidden" name="action" value="toggle">
                                        <button class="admin-btn admin-btn-secondary admin-btn-small" type="submit">
                                            <?= (int) $media['is_active'] ? 'Hide' : 'Show' ?>
                                        </button>
                                    </form>
                                    <form method="post" onsubmit="return confirm('Remove this homepage media?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Remove</button>
                                    </form>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>
<?php require __DIR__ . '/components/footer.php'; ?>
