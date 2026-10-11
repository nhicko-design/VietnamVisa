<?php

/** @var array<string, mixed> $card */
/** @var list<string> $errors */
/** @var string $formTitle */
/** @var string $submitLabel */
/** @var bool $isEdit */

?>

<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>

        <main class="admin-content resident-card-editor-page">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">TEMPORARY RESIDENT CARD</span>
                    <h1><?= htmlspecialchars($formTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                    <p>Set the card content and Hero media for this Resident Card service.</p>
                </div>

                <a class="admin-btn admin-btn-secondary" href="temporary-resident-card.php">
                    ← Back to Resident Cards
                </a>
            </section>

            <?php foreach ($errors as $error): ?>
                <div class="error-box" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>

            <section class="admin-card form-card">
                <form method="post" class="admin-form" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

                    <div class="form-grid">
                        <label>
                            English title *
                            <input name="title" required value="<?= htmlspecialchars((string) $card['title'], ENT_QUOTES, 'UTF-8') ?>">
                        </label>

                        <label>
                            Vietnamese title *
                            <input name="title_vn" required value="<?= htmlspecialchars((string) $card['title_vn'], ENT_QUOTES, 'UTF-8') ?>">
                        </label>

                        <label class="full">
                            Slug *
                            <input name="slug" required value="<?= htmlspecialchars((string) $card['slug'], ENT_QUOTES, 'UTF-8') ?>">
                        </label>

                        <label class="full">
                            English card summary
                            <textarea name="short_description" rows="3"><?= htmlspecialchars((string) $card['short_description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </label>

                        <label class="full">
                            Vietnamese card summary
                            <textarea name="short_description_vn" rows="3"><?= htmlspecialchars((string) $card['short_description_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </label>

                        <label class="full">
                            English detail description
                            <textarea name="description" rows="6" data-rich-editor="English resident card description"><?= htmlspecialchars((string) $card['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </label>

                        <label class="full">
                            Vietnamese detail description
                            <textarea name="description_vn" rows="6" data-rich-editor="Vietnamese resident card description"><?= htmlspecialchars((string) $card['description_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </label>

                        <div class="full admin-media-picker">
                            <label for="media_files">Images and videos for Hero</label>
                            <input id="media_files" type="file" name="media_files[]" multiple accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime">
                            <small>Choose one or multiple files. These files appear only in the Hero slideshow, in saved order.</small>
                        </div>

                        <label>
                            Order
                            <input type="number" name="sort_order" min="0" value="<?= (int) $card['sort_order'] ?>">
                        </label>

                        <label class="checkbox-row">
                            <input type="checkbox" name="is_active" value="1" <?= (int) $card['is_active'] ? 'checked' : '' ?>>
                            Show on website
                        </label>
                    </div>

                    <div class="form-actions">
                        <a class="admin-btn admin-btn-secondary" href="temporary-resident-card.php">Cancel</a>
                        <button class="admin-btn admin-btn-primary" type="submit"><?= htmlspecialchars($submitLabel, ENT_QUOTES, 'UTF-8') ?></button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</div>

<?php require __DIR__ . '/components/footer.php'; ?>
