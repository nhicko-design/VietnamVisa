<!doctype html>
<html lang="<?= e($current_lang ?? 'en') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e($page_description ?? __('site_description')) ?>">
    <meta name="robots" content="<?= e($page_robots ?? 'index,follow') ?>">
    <link rel="canonical" href="<?= e(absolute_url($canonical_path ?? '', $current_lang ?? 'en')) ?>">
    <link rel="alternate" hreflang="en" href="<?= e(absolute_url($canonical_path ?? '', 'en')) ?>">
    <link rel="alternate" hreflang="vi" href="<?= e(absolute_url($canonical_path ?? '', 'vn')) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= e(absolute_url($canonical_path ?? '', 'en')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= e(__('site_name')) ?>">
    <meta property="og:title" content="<?= e($page_title ?? __('site_name')) ?>">
    <meta property="og:description" content="<?= e($page_description ?? __('site_description')) ?>">
    <meta property="og:url" content="<?= e(absolute_url($canonical_path ?? '', $current_lang ?? 'en')) ?>">
    <title><?= e($page_title ?? __('site_name')) ?> · <?= e(__('site_name')) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/responsive.css">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/svqc-contact.css">
    <script type="application/ld+json"><?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => SITE_NAME,
        'url' => absolute_url('', $current_lang ?? 'en'),
        'email' => SITE_EMAIL,
        'telephone' => SITE_PHONE,
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body class="route-<?= e($route) ?>">
<div class="site-progress" id="siteProgress"></div>
