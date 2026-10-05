<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();
$newsId = (int) ($_GET['id'] ?? $_POST['news_id'] ?? 0);
$errors = [];
$message = '';

if ($newsId <= 0) {
    header('Location: manage.php');
    exit;
}

$statement = $pdo->prepare('SELECT id, title, title_vn FROM news WHERE id = ? LIMIT 1');
$statement->execute([$newsId]);
$article = $statement->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    header('Location: manage.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'delete') {
        $mediaId = (int) ($_POST['media_id'] ?? 0);

        $statement = $pdo->prepare('SELECT file_path FROM news_media WHERE id = ? AND news_id = ?');
        $statement->execute([$mediaId, $newsId]);
        $media = $statement->fetch(PDO::FETCH_ASSOC);

        $statement = $pdo->prepare('DELETE FROM news_media WHERE id = ? AND news_id = ?');
        $statement->execute([$mediaId, $newsId]);

        if ($media) {
            $localPath = admin_local_news_upload_path((string) $media['file_path']);

            if ($localPath !== null && is_file($localPath)) {
                @unlink($localPath);
            }
        }

        header('Location: media.php?id=' . $newsId . '&deleted=1');
        exit;
    } elseif (($_POST['action'] ?? '') === 'move') {
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $direction = $_POST['direction'] ?? '';

        if (!in_array($direction, ['up', 'down'], true)) {
            $errors[] = 'Invalid move direction.';
        } else {
            $statement = $pdo->prepare(
                'SELECT id, sort_order
                 FROM news_media
                 WHERE id = ? AND news_id = ?'
            );
            $statement->execute([$mediaId, $newsId]);
            $currentMedia = $statement->fetch(PDO::FETCH_ASSOC);

            if (!$currentMedia) {
                $errors[] = 'Media item was not found.';
            } else {
                $operator = $direction === 'up' ? '<' : '>';
                $orderBy = $direction === 'up' ? 'DESC' : 'ASC';
                $statement = $pdo->prepare(
                    "SELECT id, sort_order
                     FROM news_media
                     WHERE news_id = :news_id
                       AND (sort_order {$operator} :sort_order_before
                            OR (sort_order = :sort_order_equal AND id {$operator} :id))
                     ORDER BY sort_order {$orderBy}, id {$orderBy}
                     LIMIT 1"
                );
                $statement->execute([
                    'news_id' => $newsId,
                    'sort_order_before' => $currentMedia['sort_order'],
                    'sort_order_equal' => $currentMedia['sort_order'],
                    'id' => $mediaId,
                ]);
                $targetMedia = $statement->fetch(PDO::FETCH_ASSOC);

                if ($targetMedia) {
                    $pdo->beginTransaction();

                    try {
                        $statement = $pdo->prepare(
                            'SELECT id
                             FROM news_media
                             WHERE news_id = ?
                             ORDER BY sort_order ASC, id ASC'
                        );
                        $statement->execute([$newsId]);
                        $mediaIds = array_map(
                            static fn (array $item): int => (int) $item['id'],
                            $statement->fetchAll(PDO::FETCH_ASSOC)
                        );

                        $currentIndex = array_search($mediaId, $mediaIds, true);
                        $targetIndex = array_search((int) $targetMedia['id'], $mediaIds, true);

                        if ($currentIndex !== false && $targetIndex !== false) {
                            [$mediaIds[$currentIndex], $mediaIds[$targetIndex]] = [
                                $mediaIds[$targetIndex],
                                $mediaIds[$currentIndex],
                            ];

                            $statement = $pdo->prepare('UPDATE news_media SET sort_order = ? WHERE id = ?');

                            foreach ($mediaIds as $index => $orderedMediaId) {
                                $statement->execute([$index + 1, $orderedMediaId]);
                            }
                        }

                        $pdo->commit();
                    } catch (Throwable $exception) {
                        $pdo->rollBack();
                        $errors[] = 'Unable to update media order.';
                    }
                }
            }
        }
    } else {
        $mediaType = trim((string) ($_POST['media_type'] ?? 'image'));
        $filePath = trim((string) ($_POST['file_path'] ?? ''));
        $title = trim((string) ($_POST['title'] ?? ''));
        $altText = trim((string) ($_POST['alt_text'] ?? ''));
        $sortOrder = (int) ($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $hasUpload = isset($_FILES['media_file'])
            && $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE;

        if (!in_array($mediaType, ['image', 'video'], true)) {
            $errors[] = 'Select a valid media type.';
        }

        if ($filePath === '') {
            if (!$hasUpload) {
                $errors[] = 'Upload a file or enter an image/video URL/path.';
            }
        }

        if (!$errors && $hasUpload) {
            $file = $_FILES['media_file'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'File upload failed.';
            } elseif ($file['size'] > 50 * 1024 * 1024) {
                $errors[] = 'File is too large. Maximum size is 50 MB.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mimeType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                $allowedImages = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    'image/gif' => 'gif',
                ];
                $allowedVideos = [
                    'video/mp4' => 'mp4',
                    'video/webm' => 'webm',
                    'video/quicktime' => 'mov',
                ];
                $allowedTypes = $mediaType === 'image'
                    ? $allowedImages
                    : $allowedVideos;

                if (!isset($allowedTypes[$mimeType])) {
                    $errors[] = $mediaType === 'image'
                        ? 'Invalid image format. Allowed: JPG, PNG, WEBP, GIF.'
                        : 'Invalid video format. Allowed: MP4, WEBM, MOV.';
                } else {
                    $relativeDirectory = $mediaType === 'image'
                        ? 'uploads/news/images/'
                        : 'uploads/news/videos/';
                    $uploadDirectory = dirname(__DIR__, 2) . '/' . $relativeDirectory;

                    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
                        $errors[] = 'Unable to create the upload directory.';
                    } else {
                        $fileName = date('YmdHis')
                            . '_'
                            . bin2hex(random_bytes(6))
                            . '.'
                            . $allowedTypes[$mimeType];

                        if (!move_uploaded_file($file['tmp_name'], $uploadDirectory . $fileName)) {
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
                'INSERT INTO news_media (
                    news_id, media_type, file_path, title, alt_text, sort_order, is_active
                ) VALUES (
                    :news_id, :media_type, :file_path, :title, :alt_text, :sort_order, :is_active
                )'
            );

            $statement->execute([
                'news_id' => $newsId,
                'media_type' => $mediaType,
                'file_path' => $filePath,
                'title' => $title ?: null,
                'alt_text' => $altText ?: null,
                'sort_order' => $sortOrder,
                'is_active' => $isActive,
            ]);

            header('Location: media.php?id=' . $newsId . '&created=1');
            exit;
        }
    }
}

if (isset($_GET['created'])) {
    $message = 'Media added successfully.';
} elseif (isset($_GET['deleted'])) {
    $message = 'Media removed successfully.';
}

$statement = $pdo->prepare(
    'SELECT id, media_type, file_path, title, alt_text, sort_order, is_active
     FROM news_media
     WHERE news_id = ?
     ORDER BY sort_order ASC, id ASC'
);
$statement->execute([$newsId]);
$mediaItems = $statement->fetchAll(PDO::FETCH_ASSOC);

$page_title = 'News Media';
$admin_section = 'news';

require __DIR__ . '/../components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/../components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/../components/topbar.php'; ?>

        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">ARTICLE MEDIA</span>
                    <h1>Images &amp; Videos</h1>
                    <p><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <a class="admin-btn admin-btn-secondary" href="edit.php?id=<?= (int) $newsId ?>">
                    Back to Article
                </a>
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
                <h2 class="media-section-title">Add media</h2>

                    <form method="post" class="news-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="news_id" value="<?= (int) $newsId ?>">

                    <div class="news-form-grid">
                        <div>
                            <label for="media_type">Media type</label>
                            <select id="media_type" name="media_type">
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </div>

                        <div>
                            <label for="sort_order">Display order</label>
                            <input id="sort_order" type="number" name="sort_order" min="0" value="0">
                        </div>

                        <div class="full">
                            <label for="media_file">Upload image or video</label>
                            <input id="media_file" type="file" name="media_file" accept="image/*,video/*">
                            <small>Images: JPG, PNG, WEBP, GIF. Videos: MP4, WEBM, MOV. Maximum 50 MB.</small>
                        </div>

                        <div class="full">
                            <label for="file_path">Or enter an image or direct video URL/path</label>
                            <input id="file_path" name="file_path" placeholder="https://example.com/image.jpg or uploads/news/video.mp4">
                            <small>Video must be a direct MP4, WEBM or MOV file URL to play on the website.</small>
                        </div>

                        <div class="full">
                            <div class="news-media-upload-preview" data-media-preview>
                                <span>Choose a file or enter a URL to preview it here.</span>
                            </div>
                        </div>

                        <div>
                            <label for="title">Title</label>
                            <input id="title" name="title" placeholder="Optional caption">
                        </div>

                        <div>
                            <label for="alt_text">Alt text</label>
                            <input id="alt_text" name="alt_text" placeholder="Describe the image">
                        </div>

                        <label class="news-checkbox">
                            <input type="checkbox" name="is_active" value="1" checked>
                            Show this media on the article
                        </label>
                    </div>

                    <div class="news-form-actions">
                        <button class="admin-btn admin-btn-primary" type="submit">Add Media</button>
                    </div>
                </form>
            </section>

            <section class="admin-card management-card media-list-card">
                <div class="media-list-heading">
                    <h2>Article gallery</h2>
                    <span><?= count($mediaItems) ?> item(s)</span>
                </div>

                <?php if (!$mediaItems): ?>
                    <div class="empty-state">
                        <h3>No extra media yet</h3>
                        <p>Add images or video links above to build this article’s gallery.</p>
                    </div>
                <?php else: ?>
                    <div class="news-media-grid">
                        <?php foreach ($mediaItems as $media): ?>
                            <article class="news-media-card">
                                <div class="news-media-preview">
                                    <?php if ($media['media_type'] === 'video'): ?>
                                        <video controls preload="metadata">
                                            <source src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>">
                                        </video>
                                    <?php else: ?>
                                        <img src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($media['alt_text'] ?: $media['title'] ?: '', ENT_QUOTES, 'UTF-8') ?>">
                                    <?php endif; ?>
                                </div>

                                <div class="news-media-card-body">
                                    <span class="status-badge <?= (int) $media['is_active'] ? 'status-success' : 'status-muted' ?>">
                                        <?= htmlspecialchars($media['media_type'], ENT_QUOTES, 'UTF-8') ?> · <?= (int) $media['is_active'] ? 'Visible' : 'Hidden' ?>
                                    </span>
                                    <strong><?= htmlspecialchars($media['title'] ?: 'Untitled media', ENT_QUOTES, 'UTF-8') ?></strong>
                                    <small>Order <?= (int) $media['sort_order'] ?></small>

                                    <form method="post" onsubmit="return confirm('Remove this media item?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                        <input type="hidden" name="news_id" value="<?= (int) $newsId ?>">
                                        <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Remove</button>
                                    </form>

                                    <div class="media-order-actions">
                                        <form method="post">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="news_id" value="<?= (int) $newsId ?>">
                                            <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                            <input type="hidden" name="action" value="move">
                                            <input type="hidden" name="direction" value="up">
                                            <button class="admin-btn admin-btn-secondary admin-btn-small" type="submit">Move up</button>
                                        </form>

                                        <form method="post">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="news_id" value="<?= (int) $newsId ?>">
                                            <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                            <input type="hidden" name="action" value="move">
                                            <input type="hidden" name="direction" value="down">
                                            <button class="admin-btn admin-btn-secondary admin-btn-small" type="submit">Move down</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>
<script>
    (() => {
        const typeInput = document.querySelector('#media_type');
        const fileInput = document.querySelector('#media_file');
        const pathInput = document.querySelector('#file_path');
        const preview = document.querySelector('[data-media-preview]');

        if (!typeInput || !fileInput || !pathInput || !preview) {
            return;
        }

        const renderPreview = (source) => {
            preview.replaceChildren();

            if (!source) {
                preview.textContent = 'Choose a file or enter a URL to preview it here.';
                return;
            }

            const element = document.createElement(typeInput.value === 'video' ? 'video' : 'img');
            element.src = source;
            element.alt = 'Media preview';

            if (element.tagName === 'VIDEO') {
                element.controls = true;
                element.preload = 'metadata';
            }

            preview.append(element);
        };

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            renderPreview(file ? URL.createObjectURL(file) : pathInput.value.trim());
        });

        pathInput.addEventListener('input', () => {
            if (!fileInput.files[0]) {
                renderPreview(pathInput.value.trim());
            }
        });

        typeInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            renderPreview(file ? URL.createObjectURL(file) : pathInput.value.trim());
        });
    })();
</script>
<?php require __DIR__ . '/../components/footer.php'; ?>
