<?php

$sunvietV2CssPath = __DIR__ . '/assets/css/sunviet-v2.css';
$sunvietV2CssVersion = is_file($sunvietV2CssPath)
    ? (string) filemtime($sunvietV2CssPath)
    : '1';
$mainCssVersion = (string) filemtime(__DIR__ . '/assets/css/style.css');
$compactCssPath = __DIR__ . '/assets/css/sunviet-compact.css';
$compactCssVersion = is_file($compactCssPath) ? (string) filemtime($compactCssPath) : '1';
$redesignCssPath = __DIR__ . '/assets/css/sunviet-redesign.css';
$redesignCssVersion = is_file($redesignCssPath) ? (string) filemtime($redesignCssPath) : '1';
$serviceDesignRoutes = [
    'services',
    'vietnam-visa',
    'airport-fast-track',
    'temporary-resident-card',
    'work-permit',
    'other-countries-visa',
    'other-services',
    'service-detail',
    'visa',
    'support',
];
$isServiceDesign = in_array($route, $serviceDesignRoutes, true);


?>

<!doctype html>
<html lang="<?= ($current_lang ?? 'en') === 'vn' ? 'vi' : 'en' ?>">
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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/style.css?v=<?= e($mainCssVersion) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/components.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/components.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/responsive.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/responsive.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/svqc-contact.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/svqc-contact.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-theme.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/sunviet-theme.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-v1.css?v=1">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-v2.css?v=<?= e($sunvietV2CssVersion) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-compact.css?v=<?= e($compactCssVersion) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-redesign.css?v=<?= e($redesignCssVersion) ?>">
    <?php if ($isServiceDesign): ?>
        <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-services.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/sunviet-services.css')) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-motion.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/sunviet-motion.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-navigation.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/sunviet-navigation.css')) ?>">
    <link rel="stylesheet" href="<?= e(app_base()) ?>/assets/css/sunviet-vietnamese.css?v=<?= e((string) filemtime(__DIR__ . '/assets/css/sunviet-vietnamese.css')) ?>">
    <script type="application/ld+json"><?= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => SITE_NAME,
        'url' => absolute_url('', $current_lang ?? 'en'),
        'email' => SITE_EMAIL,
        'telephone' => SITE_PHONE,
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES) ?></script>
</head>
<body class="route-<?= e($route) ?><?= $isServiceDesign ? ' service-design' : '' ?>">
<div class="site-progress" id="siteProgress"></div>
