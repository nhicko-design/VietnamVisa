<?php

require_once __DIR__ . '/../config.php';

require_admin_login();
require_once __DIR__ . '/../media-upload.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['media_action'])) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $action = (string) $_POST['media_action'];

        if ($action === 'remove') {
            $statement = $pdo->prepare('DELETE FROM news_media WHERE id = ? AND news_id = ?');
            $statement->execute([$mediaId, $id]);
        } elseif (in_array($action, ['move_up', 'move_down'], true)) {
            $statement = $pdo->prepare('SELECT id FROM news_media WHERE news_id = ? ORDER BY sort_order, id');
            $statement->execute([$id]);
            $ids = array_map('intval', $statement->fetchAll(PDO::FETCH_COLUMN));
            $current = array_search($mediaId, $ids, true);
            $target = $current === false ? false : $current + ($action === 'move_up' ? -1 : 1);

            if ($current !== false && isset($ids[$target])) {
                [$ids[$current], $ids[$target]] = [$ids[$target], $ids[$current]];
                $statement = $pdo->prepare('UPDATE news_media SET sort_order = ? WHERE id = ?');
                foreach ($ids as $position => $mediaItemId) {
                    $statement->execute([$position + 1, $mediaItemId]);
                }
            }
        }

        header('Location: edit.php?id=' . $id . '&media_updated=1');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['media_action'])) {
    $errors = array_merge($errors, admin_media_upload_errors($_FILES['media_files'] ?? []));
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

            $firstImage = admin_save_media_uploads($pdo, $_FILES['media_files'] ?? [], 'news', $id);

            if ($firstImage && $article['featured_image'] === '') {
                $pdo->prepare('UPDATE news SET featured_image = ? WHERE id = ?')->execute([$firstImage, $id]);
            }

            header('Location: manage.php?updated=1');
            exit;
        } catch (Throwable $exception) {
            $errors[] = 'Unable to update article. The slug may already exist.';
        }
    }
}

$statement = $pdo->prepare('SELECT id, media_type, file_path, title, alt_text, sort_order FROM news_media WHERE news_id = ? ORDER BY sort_order, id');
$statement->execute([$id]);
$articleMedia = $statement->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/form-layout.php';
