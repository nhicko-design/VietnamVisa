<?php

require_once __DIR__ . '/../components/service-page-hero.php';

render_service_page_hero([
    'eyebrow' => ($current_lang ?? 'en') === 'vn' ? 'DỊCH VỤ SUNVIET' : 'SUNVIET SERVICES',
    'title' => __('services_page_title'),
    'description' => __('services_desc'),
    'image' => page_content_asset_value('visa', 'hero_image')
        ?: 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=85',
]);
?>

<section class="section section-white">
    <div class="container">
        <div class="section-intro">
            <span class="eyebrow"><?= e(__('services_label')) ?></span>
            <h2 class="section-title"><?= e(__('services_title')) ?></h2>
        </div>
        <div class="service-grid service-grid-large">
            <?php foreach ($SERVICES as $service): ?>
                <?php include __DIR__ . '/../components/service-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
