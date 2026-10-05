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

            <?php if ($isEdit): ?>
                <div class="article-media-shortcut">
                    <div>
                        <strong>Article media</strong>
                        <span>Add multiple images or direct video links for this article.</span>
                    </div>

                    <a class="admin-btn admin-btn-secondary" href="media.php?id=<?= (int) $id ?>">
                        Manage Media
                    </a>
                </div>
            <?php endif; ?>

            <form method="post" class="news-form">
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
                            <textarea id="content" name="content" placeholder="Use one paragraph per line."><?= htmlspecialchars((string) $article['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="full">
                            <label for="content_vn">Vietnamese content</label>
                            <textarea id="content_vn" name="content_vn" placeholder="Use one paragraph per line."><?= htmlspecialchars((string) $article['content_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                        <div class="full">
                            <label for="featured_image">Featured image URL or path</label>
                            <input id="featured_image" name="featured_image" value="<?= htmlspecialchars((string) $article['featured_image'], ENT_QUOTES, 'UTF-8') ?>">
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
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
