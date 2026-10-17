<?php

$homeHelpCards = [
    ['number' => '01', 'title_en' => 'Vietnam Visa', 'title_vn' => 'Visa Việt Nam', 'short_en' => 'Explore visa options and entry support for Vietnam.', 'short_vn' => 'Khám phá các lựa chọn visa và hỗ trợ nhập cảnh Việt Nam.', 'path' => 'vietnam-visa'],
    ['number' => '02', 'title_en' => 'Temporary Resident Card', 'title_vn' => 'Thẻ tạm trú', 'short_en' => 'Find the suitable residence route for your situation.', 'short_vn' => 'Tìm diện thẻ tạm trú phù hợp với tình trạng của bạn.', 'path' => 'temporary-resident-card'],
    ['number' => '03', 'title_en' => 'Work Permit', 'title_vn' => 'Giấy phép lao động', 'short_en' => 'Get guidance for working legally in Vietnam.', 'short_vn' => 'Nhận hướng dẫn để làm việc hợp pháp tại Việt Nam.', 'path' => 'work-permit'],
    ['number' => '04', 'title_en' => 'Other Countries Visa', 'title_vn' => 'Visa các nước khác', 'short_en' => 'Choose an international destination and visa route.', 'short_vn' => 'Chọn điểm đến quốc tế và diện visa phù hợp.', 'path' => 'other-countries-visa'],
];
$heroMedia = $HOME_MEDIA['hero'][0] ?? null;
?>

<section class="sv-home-hero">
    <div class="sv-home-orb sv-home-orb-one"></div>
    <div class="sv-home-orb sv-home-orb-two"></div>
    <div class="container sv-home-hero-grid">
        <div class="sv-home-hero-copy reveal">
            <span class="sv-overline sv-overline-gold"><?= e(page_content_value('home', 'eyebrow', __('hero_eyebrow'))) ?></span>
            <h1><?= e(page_content_value('home', 'title', __('hero_title'))) ?></h1>
            <p><?= e(page_content_value('home', 'description', __('hero_desc'))) ?></p>
            <div class="sv-home-hero-actions">
                <a class="sv-btn sv-btn-gold" href="<?= e(page_content_value('home', 'primary_url', url('vietnam-visa'))) ?>"><?= e(page_content_value('home', 'primary_label', __('hero_primary'))) ?> <span>→</span></a>
                <a class="sv-btn sv-btn-outline" href="<?= e(page_content_value('home', 'secondary_url', url('contact'))) ?>"><?= e(page_content_value('home', 'secondary_label', __('hero_secondary'))) ?></a>
            </div>
            <p class="sv-home-trust">✓ <?= e(__('hero_note')) ?></p>
            <div class="sv-home-tags"><span>Visa</span><span>Work Permit</span><span>Residence</span><span>Airport Support</span></div>
        </div>
        <div class="sv-home-hero-visual reveal">
            <div class="sv-home-hero-frame">
                <?php if ($heroMedia && ($heroMedia['media_type'] ?? '') === 'video'): ?>
                    <video autoplay muted loop playsinline><source src="<?= e(asset_url($heroMedia['file_path'])) ?>"></video>
                <?php else: ?>
                    <img src="<?= e(asset_url($heroMedia['file_path'] ?? 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=85')) ?>" alt="<?= e($heroMedia['title'] ?? 'Vietnam skyline') ?>">
                <?php endif; ?>
                <div class="sv-home-hero-badge"><span>01</span><div><strong><?= e(page_content_value('home', 'highlight_title', 'Visa & Immigration')) ?></strong><small><?= e(page_content_value('home', 'highlight_description', 'Clear information. Human support.')) ?></small></div><b>✓</b></div>
            </div>
        </div>
    </div>
</section>

<section class="sv-home-metrics">
    <div class="container">
        <div class="sv-section-heading sv-section-heading-center"><span class="sv-overline">WHAT WE HELP WITH</span><h2><?= e(__('stats_title')) ?></h2></div>
        <div class="sv-metric-grid">
            <article class="sv-metric-card sv-metric-featured"><strong>10+</strong><span><?= e(__('stat_1')) ?></span></article>
            <article class="sv-metric-card"><strong>24/7</strong><span><?= e(__('stat_2')) ?></span></article>
            <article class="sv-metric-card"><strong>6</strong><span><?= e(__('stat_3')) ?></span></article>
            <article class="sv-metric-card"><strong>01</strong><span><?= e(__('stat_4')) ?></span></article>
        </div>
    </div>
</section>

<section class="sv-home-services">
    <div class="container">
        <div class="sv-section-heading sv-section-heading-split"><div><span class="sv-overline">WHAT WE HELP WITH</span><h2><?= e(__('services_title')) ?></h2><p><?= e(__('services_desc')) ?></p></div><a class="sv-inline-link" href="<?= e(url('services')) ?>"><?= e(__('view_all')) ?> <span>↗</span></a></div>
        <div class="sv-service-grid">
            <?php foreach ($homeHelpCards as $homeHelpCard): ?>
                <article class="sv-service-card reveal"><div class="sv-service-card-top"><span><?= e($homeHelpCard['number']) ?></span><i>→</i></div><h3><?= e(field($homeHelpCard, 'title')) ?></h3><p><?= e(field($homeHelpCard, 'short')) ?></p><a href="<?= e(url($homeHelpCard['path'])) ?>"><?= e(__('learn_more')) ?> <span>→</span></a></article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="sv-home-why">
    <div class="container sv-home-why-grid">
        <div class="sv-home-why-image reveal"><img src="https://images.unsplash.com/photo-1511988617509-a57c8a288659?auto=format&fit=crop&w=1200&q=85" alt="SunViet team support"><div><b>01</b><span>One clear place to start</span></div></div>
        <div class="sv-home-why-copy"><span class="sv-overline sv-overline-gold"><?= e(__('why_label')) ?></span><h2><?= e(__('why_title')) ?></h2><p><?= e(__('why_desc')) ?></p><div class="sv-feature-list">
            <?php for ($i = 1; $i <= 3; $i++): ?><article><b>0<?= $i ?></b><div><h3><?= e(__('why_' . $i)) ?></h3><p><?= e(__('why_' . $i . '_desc')) ?></p></div></article><?php endfor; ?>
        </div></div>
    </div>
</section>

<section class="sv-home-process">
    <div class="container">
        <div class="sv-section-heading sv-section-heading-center"><span class="sv-overline"><?= e(__('process_label')) ?></span><h2><?= e(__('process_title')) ?></h2></div>
        <div class="sv-process-grid">
            <?php for ($i = 1; $i <= 4; $i++): ?><article class="sv-process-card reveal"><i></i><span>STEP 0<?= $i ?></span><h3><?= e(__('step_' . $i)) ?></h3><p><?= e(__('step_' . $i . '_desc')) ?></p><small>✓ <?= $i === 1 ? 'Transparent terms' : ($i === 2 ? 'Document checklist' : ($i === 3 ? 'Form submission' : 'Dedicated follow-up')) ?></small></article><?php endfor; ?>
        </div>
    </div>
</section>

<section class="sv-home-insights">
    <div class="container">
        <div class="sv-section-heading sv-section-heading-split"><div><span class="sv-overline"><?= e(__('news_label')) ?></span><h2><?= e(__('news_title')) ?></h2><p><?= e(__('news_desc')) ?></p></div><a class="sv-inline-link" href="<?= e(url('news')) ?>"><?= e(__('view_news')) ?> <span>↗</span></a></div>
        <div class="sv-insights-grid">
            <?php foreach (array_slice($NEWS, 0, 3) as $article): ?>
                <article class="sv-insight-card reveal"><a href="<?= e(url('article/' . $article['slug'])) ?>"><img src="<?= e(asset_url($article['image'])) ?>" alt="<?= e(field($article, 'title')) ?>"><div><p><span><?= e(field($article, 'category')) ?></span><time><?= e(format_date($article['date'])) ?></time></p><h3><?= e(field($article, 'title')) ?></h3><small><?= e(field($article, 'excerpt')) ?></small><b><?= e(__('read_article')) ?> <em>↗</em></b></div></a></article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
