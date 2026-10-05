<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();
$id = (int) ($_GET['id'] ?? 0);
$errors = [];

$statement = $pdo->prepare('SELECT * FROM news WHERE id = ? LIMIT 1');
$statement->execute([$id]);
$article = $statement->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    http_response_code(404);
    exit('Article not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    }

    $fields = ['slug', 'category', 'category_vn', 'title', 'title_vn', 'excerpt', 'excerpt_vn', 'content', 'content_vn', 'featured_image', 'author', 'status', 'published_at'];

    foreach ($fields as $field) {
        $article[$field] = trim((string) ($_POST[$field] ?? ''));
    }

    $article['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
    $article['sort_order'] = (int) ($_POST['sort_order'] ?? 0);
    $article['slug'] = trim((string) preg_replace('/[^a-z0-9]+/i', '-', strtolower($article['slug'])), '-');

    if ($article['slug'] === '') {
        $errors[] = 'Slug is required.';
    }

    if ($article['title'] === '') {
        $errors[] = 'English title is required.';
    }

    if (!in_array($article['status'], ['draft', 'published'], true)) {
        $errors[] = 'Invalid status.';
    }

    if ($article['status'] === 'published' && $article['published_at'] === '') {
        $article['published_at'] = date('Y-m-d H:i:s');
    }

    if ($article['published_at'] !== '') {
        $article['published_at'] = str_replace('T', ' ', $article['published_at']);
    }

    if (!$errors) {
        try {
            $statement = $pdo->prepare(
                'UPDATE news
                 SET slug = :slug, category = :category, category_vn = :category_vn,
                     title = :title, title_vn = :title_vn, excerpt = :excerpt,
                     excerpt_vn = :excerpt_vn, content = :content, content_vn = :content_vn,
                     featured_image = :featured_image, author = :author, status = :status,
                     published_at = :published_at, is_featured = :is_featured,
                     sort_order = :sort_order, updated_at = NOW()
                 WHERE id = :id'
            );

            $statement->execute([
                'slug' => $article['slug'],
                'category' => $article['category'] ?: null,
                'category_vn' => $article['category_vn'] ?: null,
                'title' => $article['title'],
                'title_vn' => $article['title_vn'] ?: null,
                'excerpt' => $article['excerpt'] ?: null,
                'excerpt_vn' => $article['excerpt_vn'] ?: null,
                'content' => $article['content'] ?: null,
                'content_vn' => $article['content_vn'] ?: null,
                'featured_image' => $article['featured_image'] ?: null,
                'author' => $article['author'] ?: null,
                'status' => $article['status'],
                'published_at' => $article['published_at'] ?: null,
                'is_featured' => $article['is_featured'],
                'sort_order' => $article['sort_order'],
                'id' => $id,
            ]);

            header('Location: manage.php?updated=1');
            exit;
        } catch (Throwable $exception) {
            $errors[] = 'Unable to update article. The slug may already exist.';
        }
    }
}

require __DIR__ . '/form-layout.php';
