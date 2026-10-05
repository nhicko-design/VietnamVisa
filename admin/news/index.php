<?php
require_once __DIR__ . '/../config.php';
require_admin_login();

header('Location: manage.php');
exit;

$pdo = db();
$message = isset($_GET['created']) ? 'News article created.' : (isset($_GET['updated']) ? 'News article updated.' : (isset($_GET['deleted']) ? 'News article deleted.' : ''));
$error = '';

try {
    $stmt = $pdo->query("SELECT id, slug, title, title_vn, category, status, published_at, is_featured, sort_order FROM news ORDER BY is_featured DESC, sort_order ASC, published_at DESC, id DESC");
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $articles = [];
    $error = 'Unable to load news articles.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News - SunViet Admin</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#f6f7fb;color:#17171a;font-family:Arial,Helvetica,sans-serif}.page{max-width:1180px;margin:0 auto;padding:38px 22px}.top{display:flex;justify-content:space-between;align-items:flex-start;gap:20px;margin-bottom:24px}.top h1{margin:0 0 7px}.muted{color:#686871}.button{display:inline-flex;padding:11px 16px;border-radius:9px;border:1px solid #dddde5;background:#fff;color:#333;text-decoration:none}.primary{background:#673de6;color:#fff;border-color:#673de6}.card{background:#fff;border:1px solid #e7e7ec;border-radius:14px;overflow:auto}.notice{padding:13px 16px;margin-bottom:18px;border-radius:9px;background:#ecfdf3;color:#166534}.error{padding:13px 16px;margin-bottom:18px;border-radius:9px;background:#fff0f0;color:#b42318}table{width:100%;border-collapse:collapse;min-width:850px}th,td{padding:14px 16px;border-bottom:1px solid #eeeef2;text-align:left;vertical-align:top}th{font-size:12px;color:#686871;background:#fafafd}.title{font-weight:700;margin-bottom:4px}.vn{color:#5b46a8;font-size:13px}.badge{display:inline-block;padding:5px 8px;border-radius:999px;font-size:12px}.published{background:#eaf8ef;color:#217a42}.draft{background:#fff7e7;color:#9a6500}.actions{display:flex;gap:8px;flex-wrap:wrap}.danger{color:#b42318;background:#fff0f0;border-color:#ffd2d2}.empty{text-align:center;padding:50px;color:#686871}
    </style>
</head>
<body><main class="page">
    <div class="top"><div><h1>News</h1><div class="muted">Manage English and Vietnamese articles.</div></div><a class="button primary" href="create.php">+ Add Article</a></div>
    <?php if ($message): ?><div class="notice"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <div class="card">
    <?php if (!$articles): ?><div class="empty"><h3>No news articles yet</h3><p>Create the first article to publish it on the website.</p><a class="button primary" href="create.php">Add Article</a></div>
    <?php else: ?><table><thead><tr><th>Article</th><th>Category</th><th>Status</th><th>Published</th><th>Order</th><th>Actions</th></tr></thead><tbody>
    <?php foreach ($articles as $article): ?><tr><td><div class="title"><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></div><?php if ($article['title_vn']): ?><div class="vn"><?= htmlspecialchars($article['title_vn'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?><small class="muted"><?= htmlspecialchars($article['slug'], ENT_QUOTES, 'UTF-8') ?></small></td><td><?= htmlspecialchars((string) $article['category'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="badge <?= $article['status'] === 'published' ? 'published' : 'draft' ?>"><?= htmlspecialchars(ucfirst($article['status']), ENT_QUOTES, 'UTF-8') ?></span><?php if ((int) $article['is_featured']): ?><div class="vn">Featured</div><?php endif; ?></td><td><?= htmlspecialchars((string) ($article['published_at'] ?: '—'), ENT_QUOTES, 'UTF-8') ?></td><td><?= (int) $article['sort_order'] ?></td><td><div class="actions"><a class="button" href="edit.php?id=<?= (int) $article['id'] ?>">Edit</a><form method="post" action="delete.php" onsubmit="return confirm('Delete this article?');"><input type="hidden" name="id" value="<?= (int) $article['id'] ?>"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>"><button class="button danger" type="submit">Delete</button></form></div></td></tr><?php endforeach; ?>
    </tbody></table><?php endif; ?></div>
</main></body></html>
