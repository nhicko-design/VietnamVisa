<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$page_title = 'News Management';
$admin_section = 'news';
$pdo = db();
$message = '';
$error = '';

if (isset($_GET['created'])) {
    $message = 'News article created.';
} elseif (isset($_GET['updated'])) {
    $message = 'News article updated.';
} elseif (isset($_GET['deleted'])) {
    $message = 'News article deleted.';
}

try {
    $statement = $pdo->query(
        'SELECT id, slug, title, title_vn, category, status, published_at, is_featured, sort_order
         FROM news
         ORDER BY is_featured DESC, sort_order ASC, published_at DESC, id DESC'
    );

    $articles = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    $articles = [];
    $error = 'Unable to load news articles.';
}

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
                    <h1>News</h1>
                    <p>Manage English and Vietnamese articles.</p>
                </div>

                <a class="admin-btn admin-btn-primary" href="create.php">
                    <span aria-hidden="true">+</span>
                    Add Article
                </a>
            </section>

            <?php if ($message): ?>
                <div class="notice" role="status">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-box" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <section class="admin-card management-card">
            <?php if (!$articles): ?>
                <div class="empty-state">
                    <h3>No news articles yet</h3>
                    <p>Create the first article to publish it on the website.</p>
                    <a class="admin-btn admin-btn-primary" href="create.php">Add Article</a>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Published</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></strong>

                        <?php if ($article['title_vn']): ?>
                            <div class="news-vn">
                                <?= htmlspecialchars($article['title_vn'], ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <small><?= htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8') ?></small>
                    </td>
                    <td><?= htmlspecialchars((string) $article['category'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <span class="status-badge <?= $article['status'] === 'published' ? 'status-success' : 'status-warning' ?>">
                            <?= htmlspecialchars(ucfirst($article['status']), ENT_QUOTES, 'UTF-8') ?>
                        </span>

                        <?php if ((int) $article['is_featured']): ?>
                            <span class="featured-label">Featured</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars((string) ($article['published_at'] ?: '—'), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= (int) $article['sort_order'] ?></td>
                    <td>
                        <div class="management-actions">
                            <a class="admin-btn admin-btn-secondary admin-btn-small" href="media.php?id=<?= (int) $article['id'] ?>">Media</a>
                            <a class="admin-btn admin-btn-secondary admin-btn-small" href="edit.php?id=<?= (int) $article['id'] ?>">Edit</a>

                            <form method="post" action="delete.php" onsubmit="return confirm('Delete this article?');">
                                <input type="hidden" name="id" value="<?= (int) $article['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                <button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            </section>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
