<?php

require_once __DIR__ . '/../common/database.php';

$pdo = db();

$stmt = $pdo->query("
    SELECT
        id, slug, category, category_vn,
        title, title_vn,
        excerpt, excerpt_vn,
        content, content_vn,
        featured_image, author, status, published_at,
        is_featured, sort_order, created_at
    FROM news
    WHERE status = 'published'
      AND (published_at IS NULL OR published_at <= NOW())
    ORDER BY is_featured DESC, sort_order ASC, published_at DESC, id DESC
");

$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
$mediaByNewsId = [];

$mediaStatement = $pdo->query(
    "SELECT news_id, media_type, file_path, title, alt_text
     FROM news_media
     WHERE is_active = 1
     ORDER BY sort_order ASC, id ASC"
);

foreach ($mediaStatement->fetchAll(PDO::FETCH_ASSOC) as $media) {
    $mediaByNewsId[(int) $media['news_id']][] = $media;
}

$NEWS = [];

foreach ($articles as $article) {
    $contentEn = trim((string) ($article['content'] ?? ''));
    $contentVn = trim((string) ($article['content_vn'] ?? ''));

    $NEWS[] = [
        'id' => (int) $article['id'],
        'slug' => (string) $article['slug'],
        'category_en' => (string) ($article['category'] ?? ''),
        'category_vn' => (string) ($article['category_vn'] ?? ($article['category'] ?? '')),
        'title_en' => (string) $article['title'],
        'title_vn' => (string) ($article['title_vn'] ?: $article['title']),
        'excerpt_en' => (string) ($article['excerpt'] ?? ''),
        'excerpt_vn' => (string) ($article['excerpt_vn'] ?: ($article['excerpt'] ?? '')),
        'content_en' => $contentEn === '' ? [] : preg_split('/\R+/', $contentEn),
        'content_vn' => $contentVn === ''
            ? ($contentEn === '' ? [] : preg_split('/\R+/', $contentEn))
            : preg_split('/\R+/', $contentVn),
        'image' => (string) ($article['featured_image'] ?? ''),
        'media' => $mediaByNewsId[(int) $article['id']] ?? [],
        'author' => (string) ($article['author'] ?? ''),
        'date' => (string) ($article['published_at'] ?: ($article['created_at'] ?? '')),
        'is_featured' => (int) $article['is_featured'],
        'status' => (string) $article['status'],
    ];
}
