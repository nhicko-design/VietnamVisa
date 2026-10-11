<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/media-upload.php';
require_admin_login();

$pdo = db();
$page_title = 'Other Countries Visa';
$admin_section = 'other-countries-visa';
$errors = [];
$formPage = isset($_GET['form_page']) || isset($_POST['form_page']);
$regionId = (int) ($_GET['region'] ?? $_POST['region_id'] ?? 0);
$editId = (int) ($_GET['edit'] ?? $_POST['id'] ?? 0);
$type = $regionId ? 'country' : 'region';
$table = $type === 'country' ? 'visa_countries' : 'visa_regions';
$item = [
    'slug' => '', 'title' => '', 'title_vn' => '',
    'description' => '', 'description_vn' => '',
    'summary' => '', 'summary_vn' => '',
    'detail' => '', 'detail_vn' => '',
    'image' => '', 'sort_order' => 0, 'is_active' => 1,
];

if ($regionId) {
    $statement = $pdo->prepare('SELECT id FROM visa_regions WHERE id = ?');
    $statement->execute([$regionId]);
    if (!$statement->fetchColumn()) {
        http_response_code(404);
        exit('Region not found.');
    }
}

if ($editId) {
    $statement = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
    $statement->execute([$editId]);
    $record = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$record || ($type === 'country' && (int) $record['region_id'] !== $regionId)) {
        http_response_code(404);
        exit('Visa card not found.');
    }
    $item = array_merge($item, $record);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_post_exceeds_server_limit()) {
        $errors[] = 'The selected files exceed the server POST limit.';
    } elseif (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token.';
    } elseif (in_array($_POST['media_action'] ?? '', ['up', 'down'], true) && $editId) {
        $column = $type === 'country' ? 'country_id' : 'region_id';
        $direction = $_POST['media_action'] === 'up' ? -1 : 1;
        $statement = $pdo->prepare("SELECT id FROM visa_media WHERE $column = ? ORDER BY sort_order, id");
        $statement->execute([$editId]);
        $mediaIds = array_map('intval', $statement->fetchAll(PDO::FETCH_COLUMN));
        $currentIndex = array_search((int) $_POST['media_id'], $mediaIds, true);
        $targetIndex = $currentIndex === false ? false : $currentIndex + $direction;

        if ($currentIndex !== false && isset($mediaIds[$targetIndex])) {
            [$mediaIds[$currentIndex], $mediaIds[$targetIndex]] = [$mediaIds[$targetIndex], $mediaIds[$currentIndex]];
            $statement = $pdo->prepare('UPDATE visa_media SET sort_order = ? WHERE id = ?');
            foreach ($mediaIds as $position => $mediaId) {
                $statement->execute([$position + 1, $mediaId]);
            }
        }
                header('Location: other-countries-visa-edit.php?id=' . $editId . ($regionId ? '&region=' . $regionId : ''));
        exit;
    } elseif (($_POST['media_action'] ?? '') === 'remove' && $editId) {
        $column = $type === 'country' ? 'country_id' : 'region_id';
        $statement = $pdo->prepare("DELETE FROM visa_media WHERE id = ? AND $column = ?");
        $statement->execute([(int) $_POST['media_id'], $editId]);
        header('Location: other-countries-visa-edit.php?id=' . $editId . ($regionId ? '&region=' . $regionId : ''));
        exit;
    } elseif (isset($_POST['delete_id'])) {
        $deleteId = (int) $_POST['delete_id'];
        $statement = $pdo->prepare(
            $type === 'country'
                ? 'DELETE FROM visa_countries WHERE id = ? AND region_id = ?'
                : 'DELETE FROM visa_regions WHERE id = ?'
        );
        $statement->execute($type === 'country' ? [$deleteId, $regionId] : [$deleteId]);
        header('Location: other-countries-visa.php' . ($regionId ? '?region=' . $regionId : ''));
        exit;
    } else {
        $errors = array_merge($errors, admin_media_upload_errors($_FILES['media_files'] ?? []));
        $fields = $type === 'country'
            ? ['slug', 'title', 'title_vn', 'summary', 'summary_vn', 'detail', 'detail_vn']
            : ['slug', 'title', 'title_vn', 'description', 'description_vn'];

        foreach ($fields as $field) {
            $item[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $item['slug'] = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($item['slug'])), '-');
        $item['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));
        $item['is_active'] = isset($_POST['is_active']) ? 1 : 0;
        if ($item['slug'] === '' || $item['title'] === '') {
            $errors[] = 'Slug and English title are required.';
        }

        if (!$errors) {
            try {
                if ($type === 'country') {
                    $values = [
                        $item['slug'], $item['title'], $item['title_vn'],
                        $item['summary'], $item['summary_vn'],
                        $item['detail'], $item['detail_vn'],
                        $item['sort_order'], $item['is_active'],
                    ];
                    if ($editId) {
                        $statement = $pdo->prepare(
                            'UPDATE visa_countries SET slug=?, title=?, title_vn=?, summary=?, summary_vn=?,
                             detail=?, detail_vn=?, sort_order=?, is_active=? WHERE id=? AND region_id=?'
                        );
                        $statement->execute([...$values, $editId, $regionId]);
                        $ownerId = $editId;
                    } else {
                        $statement = $pdo->prepare(
                            'INSERT INTO visa_countries
                             (region_id, slug, title, title_vn, summary, summary_vn, detail, detail_vn, sort_order, is_active)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
                        );
                        $statement->execute([$regionId, ...$values]);
                        $ownerId = (int) $pdo->lastInsertId();
                    }
                } else {
                    $values = [
                        $item['slug'], $item['title'], $item['title_vn'],
                        $item['description'], $item['description_vn'],
                        $item['sort_order'], $item['is_active'],
                    ];
                    if ($editId) {
                        $statement = $pdo->prepare(
                            'UPDATE visa_regions SET slug=?, title=?, title_vn=?, description=?, description_vn=?,
                             sort_order=?, is_active=? WHERE id=?'
                        );
                        $statement->execute([...$values, $editId]);
                        $ownerId = $editId;
                    } else {
                        $statement = $pdo->prepare(
                            'INSERT INTO visa_regions
                             (slug, title, title_vn, description, description_vn, sort_order, is_active)
                             VALUES (?, ?, ?, ?, ?, ?, ?)'
                        );
                        $statement->execute($values);
                        $ownerId = (int) $pdo->lastInsertId();
                    }
                }

                $firstImage = admin_save_media_uploads($pdo, $_FILES['media_files'] ?? [], $type, $ownerId);
                if ($type === 'region' && $firstImage) {
                    $pdo->prepare('UPDATE visa_regions SET image = ? WHERE id = ?')->execute([$firstImage, $ownerId]);
                }
                header('Location: other-countries-visa.php' . ($regionId ? '?region=' . $regionId : ''));
                exit;
            } catch (Throwable $exception) {
                $errors[] = 'Unable to save the card. Check that its slug is unique.';
            }
        }
    }
}

$regions = $pdo->query('SELECT * FROM visa_regions ORDER BY sort_order, id')->fetchAll(PDO::FETCH_ASSOC);
if ($regionId) {
    $statement = $pdo->prepare('SELECT * FROM visa_countries WHERE region_id = ? ORDER BY sort_order, id');
    $statement->execute([$regionId]);
    $list = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $list = $regions;
}

$existingMedia = [];
if ($editId) {
    $column = $type === 'country' ? 'country_id' : 'region_id';
    $statement = $pdo->prepare("SELECT * FROM visa_media WHERE $column = ? ORDER BY sort_order, id");
    $statement->execute([$editId]);
    $existingMedia = $statement->fetchAll(PDO::FETCH_ASSOC);
}

$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
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
                    <h1>Other Countries Visa</h1>
                    <p><?= $regionId ? 'Manage country cards for this region.' : 'Manage region cards and their countries.' ?></p>
                </div>
                <?php if ($formPage): ?>
                    <a class="admin-btn admin-btn-secondary" href="other-countries-visa.php<?= $regionId ? '?region=' . $regionId : '' ?>">← Back to <?= $regionId ? 'Countries' : 'Regions' ?></a>
                <?php else: ?>
                    <div class="management-actions">
                        <?php if ($regionId): ?>
                            <a class="admin-btn admin-btn-secondary" href="other-countries-visa.php">Regions</a>
                        <?php endif; ?>
                        <a class="admin-btn admin-btn-primary" href="other-countries-visa-create.php<?= $regionId ? '?region=' . $regionId : '' ?>">+ Add <?= $regionId ? 'country' : 'region' ?></a>
                    </div>
                <?php endif; ?>
            </section>

            <?php foreach ($errors as $error): ?>
                <div class="error-box" role="alert"><?= $escape($error) ?></div>
            <?php endforeach; ?>

            <?php if ($formPage): ?>
            <section class="admin-card form-card">
                <h2><?= $editId ? 'Edit' : 'Add' ?> <?= $type === 'country' ? 'country' : 'region' ?> card</h2>
                <form method="post" class="admin-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $escape(csrf_token()) ?>">
                    <input type="hidden" name="region_id" value="<?= $regionId ?>">
                    <input type="hidden" name="id" value="<?= $editId ?>">
                    <input type="hidden" name="form_page" value="1">
                    <div class="form-grid">
                        <label>English title *<input name="title" required value="<?= $escape($item['title']) ?>"></label>
                        <label>Vietnamese title<input name="title_vn" value="<?= $escape($item['title_vn']) ?>"></label>
                        <label class="full">Slug *<input name="slug" required value="<?= $escape($item['slug']) ?>"></label>
                        <?php if ($type === 'country'): ?>
                            <label class="full">English card summary<textarea name="summary" rows="3"><?= $escape($item['summary']) ?></textarea></label>
                            <label class="full">Vietnamese card summary<textarea name="summary_vn" rows="3"><?= $escape($item['summary_vn']) ?></textarea></label>
                            <label class="full">English detail<textarea name="detail" rows="8" data-rich-editor="English country detail"><?= $escape($item['detail']) ?></textarea></label>
                            <label class="full">Vietnamese detail<textarea name="detail_vn" rows="8" data-rich-editor="Vietnamese country detail"><?= $escape($item['detail_vn']) ?></textarea></label>
                        <?php else: ?>
                            <label class="full">English description<textarea name="description" rows="3" data-rich-editor="English region description"><?= $escape($item['description']) ?></textarea></label>
                            <label class="full">Vietnamese description<textarea name="description_vn" rows="3" data-rich-editor="Vietnamese region description"><?= $escape($item['description_vn']) ?></textarea></label>
                        <?php endif; ?>
                        <div class="full admin-media-picker">
                            <label for="visa_media_files">Images and videos</label>
                            <input id="visa_media_files" type="file" name="media_files[]" multiple accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime">
                            <small>Choose one or multiple files. Images and videos rotate in the Hero in saved order. Use Up/Down below to change that order. Up to 100 MB per file and 128 MB total per save.</small>
                        </div>
                        <label>Order<input type="number" name="sort_order" min="0" value="<?= (int) $item['sort_order'] ?>"></label>
                        <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" <?= (int) $item['is_active'] ? 'checked' : '' ?>> Show on website</label>
                    </div>
                    <button class="admin-btn admin-btn-primary" type="submit">Save card</button>
                </form>
                <?php if ($existingMedia): ?>
                    <div class="admin-media-existing">
                        <?php foreach ($existingMedia as $media): ?>
                            <form method="post">
                                <input type="hidden" name="csrf_token" value="<?= $escape(csrf_token()) ?>">
                                <input type="hidden" name="region_id" value="<?= $regionId ?>">
                                <input type="hidden" name="id" value="<?= $editId ?>">
                                <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                <span><?= $escape($media['title'] ?: basename($media['file_path'])) ?></span>
                                <button class="admin-btn admin-btn-secondary admin-btn-small" name="media_action" value="up" type="submit">Up</button>
                                <button class="admin-btn admin-btn-secondary admin-btn-small" name="media_action" value="down" type="submit">Down</button>
                                <button class="admin-btn admin-btn-danger admin-btn-small" name="media_action" value="remove" type="submit" onclick="return confirm('Remove this media item?');">Remove</button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <?php endif; ?>

            <?php if (!$formPage): ?>
            <section class="admin-card management-card">
                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead><tr><th>Card</th><th>Slug</th><th>Status</th><th>Order</th><th>Actions</th></tr></thead>
                        <tbody>
                            <?php foreach ($list as $row): ?>
                                <tr>
                                    <td><strong><?= $escape($row['title']) ?></strong><small><?= $escape($row['title_vn']) ?></small></td>
                                    <td>/<?= $escape($row['slug']) ?></td>
                                    <td><?= (int) $row['is_active'] ? 'Active' : 'Hidden' ?></td>
                                    <td><?= (int) $row['sort_order'] ?></td>
                                    <td>
                                        <div class="management-actions">
                                            <a class="admin-btn admin-btn-secondary admin-btn-small" href="other-countries-visa-edit.php?id=<?= (int) $row['id'] ?><?= $regionId ? '&region=' . $regionId : '' ?>">Edit</a>
                                            <a class="admin-btn admin-btn-secondary admin-btn-small" href="other-countries-visa-content.php?section=feature&type=<?= $regionId ? 'country' : 'region' ?>&id=<?= (int) $row['id'] ?><?= $regionId ? '&region=' . $regionId : '' ?>">Features</a>
                                            <a class="admin-btn admin-btn-secondary admin-btn-small" href="other-countries-visa-content.php?section=step&type=<?= $regionId ? 'country' : 'region' ?>&id=<?= (int) $row['id'] ?><?= $regionId ? '&region=' . $regionId : '' ?>">Steps</a>
                                            <a class="admin-btn admin-btn-secondary admin-btn-small" href="other-countries-visa-content.php?section=faq&type=<?= $regionId ? 'country' : 'region' ?>&id=<?= (int) $row['id'] ?><?= $regionId ? '&region=' . $regionId : '' ?>">FAQ</a>
                                            <?php if (!$regionId): ?>
                                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="?region=<?= (int) $row['id'] ?>">Countries</a>
                                            <?php endif; ?>
                                            <form method="post" onsubmit="return confirm('Delete this card?');">
                                                <input type="hidden" name="csrf_token" value="<?= $escape(csrf_token()) ?>">
                                                <input type="hidden" name="region_id" value="<?= $regionId ?>">
                                                <input type="hidden" name="delete_id" value="<?= (int) $row['id'] ?>">
                                                <button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php require __DIR__ . '/components/footer.php'; ?>
