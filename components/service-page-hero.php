<?php

require_once __DIR__ . '/media-hero.php';

/** Shared public service hero. All copy and media are supplied by the page. */
function render_service_page_hero(array $options): void
{
    $isVietnamese = ($GLOBALS['current_lang'] ?? 'en') === 'vn';
    $title = (string) ($options['title'] ?? '');
    $breadcrumbs = $options['breadcrumbs'] ?? [];
    $media = array_values(array_filter(
        $options['media'] ?? [],
        static fn (array $item): bool => !empty($item['file_path'])
            && in_array($item['media_type'] ?? '', ['image', 'video'], true)
    ));
    $image = trim((string) ($options['image'] ?? ''));
    ?>
    <section class="detail-hero service-page-hero">
        <div class="container">
            <nav class="service-breadcrumbs" aria-label="Breadcrumb">
                <a href="<?= e(url('')) ?>"><?= $isVietnamese ? 'Trang chủ' : 'Home' ?></a>
                <?php foreach ($breadcrumbs as $breadcrumb): ?>
                    <span aria-hidden="true">/</span>
                    <a href="<?= e($breadcrumb['url']) ?>"><?= e($breadcrumb['label']) ?></a>
                <?php endforeach; ?>
                <span aria-hidden="true">/</span>
                <span aria-current="page"><?= e($title) ?></span>
            </nav>

            <div class="detail-hero-grid">
                <div class="service-hero-copy">
                    <span class="eyebrow service-hero-label">
                        <span aria-hidden="true">◇</span>
                        <?= e($options['eyebrow'] ?? 'SunViet services') ?>
                    </span>

                    <h1><?= e($title) ?></h1>
                    <p class="service-hero-description"><?= e($options['description'] ?? '') ?></p>

                    <div class="detail-actions">
                        <a class="btn btn-primary" href="<?= e(url('apply')) ?>">
                            <?= $isVietnamese ? 'Gửi yêu cầu tư vấn' : 'Start your request' ?>
                            <span aria-hidden="true">→</span>
                        </a>
                        <a class="btn btn-secondary" href="<?= e(url('contact')) ?>">
                            <?= $isVietnamese ? 'Liên hệ chuyên viên' : 'Talk to an advisor' ?>
                        </a>
                    </div>

                    <div class="service-hero-benefits">
                        <?php
                        $benefits = $isVietnamese
                            ? ['Thông tin rõ ràng', 'Hỗ trợ theo hồ sơ', 'Quy trình minh bạch', 'Đồng hành cùng bạn']
                            : ['Clear requirements', 'Personal guidance', 'Transparent process', 'Dedicated support'];
                        ?>
                        <?php foreach ($benefits as $index => $benefit): ?>
                            <div>
                                <span aria-hidden="true"><?= ['◎', '↗', '✓', '◇'][$index] ?></span>
                                <strong><?= e($benefit) ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <p class="service-hero-note">
                        <span aria-hidden="true">✓</span>
                        <?= $isVietnamese
                            ? 'Tư vấn phương án phù hợp với mục đích và kế hoạch của bạn.'
                            : 'Guidance tailored to your purpose, profile and travel plans.' ?>
                    </p>
                </div>

                <div class="service-hero-panel">
                    <div class="service-panel-heading">
                        <div>
                            <span><?= $isVietnamese ? 'CÙNG SUNVIET' : 'YOUR NEXT CHAPTER' ?></span>
                            <strong><?= $isVietnamese ? 'Bắt đầu với một kế hoạch rõ ràng.' : 'A clearer path starts here.' ?></strong>
                        </div>
                        <span aria-hidden="true">↗</span>
                    </div>

                    <div class="detail-image">
                        <?php if ($media || $image !== ''): ?>
                            <?php render_media_hero($media, $image, $title); ?>
                        <?php else: ?>
                            <div class="service-media-placeholder">
                                <img src="<?= e(asset_url('assets/images/sunviet-logo.png')) ?>" alt="<?= e(SITE_NAME) ?>">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="service-panel-footer">
                        <span aria-hidden="true">✓</span>
                        <p><?= $isVietnamese
                            ? 'Chia sẻ nhu cầu của bạn. Đội ngũ sẽ hướng dẫn bước tiếp theo.'
                            : 'Tell us what you need. Our team will guide your next step.' ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}
