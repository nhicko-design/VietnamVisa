<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$isEdit = isset($id) && $id > 0;
$page_title = $isEdit ? 'Edit News Article' : 'Create News Article';
$admin_section = 'news';
$publishedValue = !empty($article['published_at'])
    ? date('Y-m-d\TH:i', strtotime($article['published_at']))
    : '';

require __DIR__ . '/../components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/../components/sidebar.php'; ?>
    <div class="admin-main">
        <?php require __DIR__ . '/../components/topbar.php'; ?>
        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">WEBSITE CONTENT</span>
                    <h1><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h1>
                    <p>Maintain the English and Vietnamese versions of this article.</p>
                </div>

                <a class="admin-btn admin-btn-secondary" href="manage.php">
                    Back to News
                </a>
            </section>

            <?php if ($errors): ?>
                <div class="error-box" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="post" class="news-form" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

                <section class="admin-card news-form-card">
                    <div class="news-form-grid">
                        <div>
                            <label for="title">English title *</label>
                            <input id="title" name="title" required value="<?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="title_vn">Vietnamese title</label>
                            <input id="title_vn" name="title_vn" value="<?= htmlspecialchars((string) $article['title_vn'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="slug">Slug *</label>
                            <input id="slug" name="slug" required value="<?= htmlspecialchars((string) $article['slug'], ENT_QUOTES, 'UTF-8') ?>">
                            <small>Use lowercase letters, numbers and hyphens.</small>
                        </div>

                        <div>
                            <label for="author">Author</label>
                            <input id="author" name="author" value="<?= htmlspecialchars((string) $article['author'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="category">English category</label>
                            <input id="category" name="category" value="<?= htmlspecialchars((string) $article['category'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="category_vn">Vietnamese category</label>
                            <input id="category_vn" name="category_vn" value="<?= htmlspecialchars((string) $article['category_vn'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="full">
                            <label for="excerpt">English excerpt</label>
                            <textarea id="excerpt" name="excerpt"><?= htmlspecialchars((string) $article['excerpt'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="full">
                            <label for="excerpt_vn">Vietnamese excerpt</label>
                            <textarea id="excerpt_vn" name="excerpt_vn"><?= htmlspecialchars((string) $article['excerpt_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="full">
                            <label for="content">English content</label>
                            <textarea id="content" name="content" data-rich-editor="English article content"><?= htmlspecialchars((string) $article['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="full">
                            <label for="content_vn">Vietnamese content</label>
                            <textarea id="content_vn" name="content_vn" data-rich-editor="Vietnamese article content"><?= htmlspecialchars((string) $article['content_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="full">
                            <label for="featured_image">Featured image URL or path</label>
                            <input id="featured_image" name="featured_image" value="<?= htmlspecialchars((string) $article['featured_image'], ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div class="full admin-media-picker">
                            <label for="media_files">Images and videos</label>
                            <input id="media_files" name="media_files[]" type="file" accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime" multiple>
                            <small>Choose one or multiple files. They rotate in the article Hero in saved order; the first image also becomes the featured image when the field above is empty. Up to 100 MB per file and 128 MB total per save.</small>
                        </div>

                        <div>
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="draft" <?= $article['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= $article['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                            </select>
                        </div>

                        <div>
                            <label for="published_at">Published date</label>
                            <input id="published_at" type="datetime-local" name="published_at" value="<?= htmlspecialchars($publishedValue, ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <div>
                            <label for="sort_order">Sort order</label>
                            <input id="sort_order" type="number" name="sort_order" min="0" value="<?= (int) $article['sort_order'] ?>">
                        </div>

                        <label class="news-checkbox">
                            <input type="checkbox" name="is_featured" value="1" <?= (int) $article['is_featured'] ? 'checked' : '' ?>>
                            Featured article
                        </label>
                    </div>
                </section>

                <div class="news-form-actions">
                    <a class="admin-btn admin-btn-secondary" href="manage.php">Cancel</a>
                    <button class="admin-btn admin-btn-primary" type="submit">
                        <?= $isEdit ? 'Update Article' : 'Create Article' ?>
                    </button>
                </div>
            </form>

            <?php if ($isEdit): ?>
                <section class="admin-card service-media-library">
                    <div class="service-media-library-header">
                        <div><h2>Hero Media Library</h2><p>These items appear only in the article Hero slideshow.</p></div>
                        <span><?= count($articleMedia ?? []) ?> item(s)</span>
                    </div>
                    <?php if (empty($articleMedia)): ?>
                        <div class="empty-state"><p>No Hero media yet. Upload images or videos above, then save the article.</p></div>
                    <?php else: ?>
                        <div class="service-media-library-grid">
                            <?php foreach ($articleMedia as $media): ?>
                                <article class="service-media-library-item">
                                    <?php if ($media['media_type'] === 'video'): ?>
                                        <video controls preload="metadata" src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>"></video>
                                    <?php else: ?>
                                        <img src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($media['alt_text'] ?: $media['title'] ?: $article['title'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?php endif; ?>
                                    <div class="service-media-library-item-body">
                                        <strong><?= htmlspecialchars($media['title'] ?: ucfirst($media['media_type']), ENT_QUOTES, 'UTF-8') ?></strong>
                                        <small>Hero position <?= (int) $media['sort_order'] ?></small>
                                        <form class="service-media-library-actions" method="post">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                            <input type="hidden" name="media_id" value="<?= (int) $media['id'] ?>">
                                            <button class="admin-btn admin-btn-secondary admin-btn-small" name="media_action" value="move_up">Up</button>
                                            <button class="admin-btn admin-btn-secondary admin-btn-small" name="media_action" value="move_down">Down</button>
                                            <button class="admin-btn admin-btn-danger admin-btn-small" name="media_action" value="remove" onclick="return confirm('Remove this Hero media item?');">Remove</button>
                                        </form>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
