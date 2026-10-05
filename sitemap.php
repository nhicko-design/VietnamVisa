<?php

require_once __DIR__ . '/common/config.php';
require_once __DIR__ . '/common/database.php';
require_once __DIR__ . '/common/helpers.php';

header('Content-Type: application/xml; charset=UTF-8');

$paths = [
    '',
    'services',
    'visa',
    'support',
    'about',
    'news',
    'contact',
    'apply',
    'quotation',
];

try {
    $pdo = db();

    foreach ($pdo->query('SELECT slug FROM services WHERE is_active = 1') as $service) {
        $paths[] = 'service/' . $service['slug'];
    }

    foreach ($pdo->query("SELECT slug FROM news WHERE status = 'published'") as $article) {
        $paths[] = 'article/' . $article['slug'];
    }
} catch (Throwable $exception) {
    error_log('Unable to build sitemap: ' . $exception->getMessage());
}

?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
<?php foreach (array_unique($paths) as $path): ?>
    <url>
        <loc><?= htmlspecialchars(absolute_url($path, 'en'), ENT_XML1, 'UTF-8') ?></loc>
        <xhtml:link rel="alternate" hreflang="en" href="<?= htmlspecialchars(absolute_url($path, 'en'), ENT_XML1, 'UTF-8') ?>" />
        <xhtml:link rel="alternate" hreflang="vi" href="<?= htmlspecialchars(absolute_url($path, 'vn'), ENT_XML1, 'UTF-8') ?>" />
        <xhtml:link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars(absolute_url($path, 'en'), ENT_XML1, 'UTF-8') ?>" />
    </url>
<?php endforeach; ?>
</urlset>
