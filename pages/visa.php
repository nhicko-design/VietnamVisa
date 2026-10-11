<?php

$visaHeroImage = page_content_asset_value('visa', 'hero_image');

$visaServices = array_values(array_filter(
    $SERVICES,
    static fn (array $service): bool => strtolower((string) $service['category']) === 'visa'
));

$isVietnamese = ($current_lang ?? 'en') === 'vn';

$visaOverviewTitle = $isVietnamese
    ? 'Chọn phương án visa phù hợp với hành trình của bạn'
    : 'Find the right visa option for your journey';

$visaOverviewDescription = $isVietnamese
    ? 'Từ e-visa đến hỗ trợ nhập cảnh, hãy xem các dịch vụ visa Việt Nam mà SunViet có thể hỗ trợ.'
    : 'From e-visas to arrival support, explore the Vietnam visa services SunViet can help you arrange.';

?>

<section
    class="page-hero <?= $visaHeroImage !== '' ? 'page-hero-image' : '' ?>"
    <?= $visaHeroImage !== '' ? 'style="--page-hero-image: url(\'' . e(asset_url($visaHeroImage)) . '\');"' : '' ?>
>
    <div class="container">
        <span class="eyebrow"><?= e(page_content_value('visa', 'eyebrow', __('nav_visa'))) ?></span>
        <h1><?= e(page_content_value('visa', 'title', __('visa_page_title'))) ?></h1>
        <p><?= e(page_content_value('visa', 'description', __('services_desc'))) ?></p>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="section-intro">
            <span class="eyebrow">
                <?= $isVietnamese ? 'Dịch vụ visa Việt Nam' : 'Vietnam visa services' ?>
            </span>

            <h2 class="section-title">
                <?= e($visaOverviewTitle) ?>
            </h2>

            <p>
                <?= e($visaOverviewDescription) ?>
            </p>
        </div>

        <div class="service-grid service-grid-large">
            <?php foreach ($visaServices as $service): ?>
                <?php include __DIR__ . '/../components/service-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
