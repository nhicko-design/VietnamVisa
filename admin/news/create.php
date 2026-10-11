<?php

require_once __DIR__ . '/../config.php';

require_admin_login();
require_once __DIR__ . '/../media-upload.php';

$pdo = db();
$errors = [];
$article = [
    'slug' => '',
    'category' => '',
    'category_vn' => '',
    'title' => '',
    'title_vn' => '',
    'excerpt' => '',
    'excerpt_vn' => '',
    'content' => '',
    'content_vn' => '',
    'featured_image' => '',
    'author' => '',
    'status' => 'draft',
    'published_at' => '',
    'is_featured' => 0,
    'sort_order' => 0,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array_merge($errors, admin_media_upload_errors($_FILES['media_files'] ?? []));
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    }

    foreach (array_keys($article) as $field) {
        if ($field !== 'is_featured') {
            $article[$field] = trim((string) ($_POST[$field] ?? $article[$field]));
        }
    }

    $article['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;
    $article['slug'] = trim((string) preg_replace('/[^a-z0-9]+/i', '-', strtolower($article['slug'])), '-');

    if ($article['slug'] === '') {
        $errors[] = 'Slug must contain letters or numbers.';
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
                'INSERT INTO news (
                    slug, category, category_vn, title, title_vn, excerpt, excerpt_vn,
                    content, content_vn, featured_image, author, status, published_at,
                    is_featured, sort_order, created_at, updated_at
                ) VALUES (
                    :slug, :category, :category_vn, :title, :title_vn, :excerpt, :excerpt_vn,
                    :content, :content_vn, :featured_image, :author, :status, :published_at,
                    :is_featured, :sort_order, NOW(), NOW()
                )'
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
                'sort_order' => (int) $article['sort_order'],
            ]);

            $newArticleId = (int) $pdo->lastInsertId();
            $firstImage = admin_save_media_uploads($pdo, $_FILES['media_files'] ?? [], 'news', $newArticleId);

            if ($firstImage && $article['featured_image'] === '') {
                $pdo->prepare('UPDATE news SET featured_image = ? WHERE id = ?')->execute([$firstImage, $newArticleId]);
            }

            header('Location: manage.php?created=1');
            exit;
        } catch (Throwable $exception) {
            $errors[] = 'Unable to create article. The slug may already exist.';
        }
    }
}

require __DIR__ . '/form-layout.php';
