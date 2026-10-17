<?php

require_once __DIR__ . '/../components/service-page-hero.php';
require_once __DIR__ . '/../components/service-consultation.php';

$isVietnamese = ($current_lang ?? 'en') === 'vn';
$otherServices = array_values(array_filter(
    $SERVICES,
    static fn (array $service): bool => strtolower((string) $service['category']) === 'support'
));

render_service_page_hero([
    'eyebrow' => $isVietnamese ? 'HỖ TRỢ CHO HÀNH TRÌNH CỦA BẠN' : 'SUPPORT FOR YOUR JOURNEY',
    'title' => $isVietnamese ? 'Dịch vụ khác' : 'Other Services',
    'description' => $isVietnamese
        ? 'Khám phá các dịch vụ hỗ trợ giúp hành trình và cuộc sống của bạn tại Việt Nam thuận tiện hơn.'
        : 'Practical support to make your journey and everyday life in Vietnam more convenient.',
    'image' => page_content_asset_value('support', 'hero_image')
        ?: 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1200&q=85',
]);
?>

<section class="section section-white other-services-section">
    <div class="container">
        <div class="section-intro">
            <span class="eyebrow"><?= $isVietnamese ? 'GIẢI PHÁP THIẾT THỰC' : 'PRACTICAL SOLUTIONS' ?></span>
            <h2 class="section-title"><?= $isVietnamese ? 'Hỗ trợ cho từng nhu cầu.' : 'The support you need, all in one place.' ?></h2>
            <p><?= $isVietnamese
                ? 'Chọn dịch vụ để xem thông tin và cách đội ngũ SunViet có thể hỗ trợ bạn.'
                : 'Explore each service for details and find out how the SunViet team can help.' ?></p>
        </div>

        <div class="service-grid service-grid-large">
            <?php foreach ($otherServices as $service): ?>
                <?php include __DIR__ . '/../components/service-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($otherServices === []): ?>
            <div class="empty-services-message">
                <p><?= $isVietnamese ? 'Dịch vụ sẽ được cập nhật sớm.' : 'More support services will be available soon.' ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
