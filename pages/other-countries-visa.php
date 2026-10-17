<?php

require_once __DIR__ . '/../data/other-country-visas.php';
require_once __DIR__ . '/../components/media-hero.php';

$isVietnamese = ($current_lang ?? 'en') === 'vn';
$catalog = other_country_visa_catalog();
$regionKey = $slug ?? '';
$countryKey = $sub_slug ?? '';
$region = $catalog[$regionKey] ?? null;

// China is a standalone service, so it opens its content directly instead of
// presenting a one-country selection screen.
if ($regionKey === 'china' && $countryKey === '') {
    $countryKey = 'china';
}

$country = $region !== null ? ($region['countries'][$countryKey] ?? null) : null;

$value = static function (array $item, string $key) use ($isVietnamese): string {
    $localizedKey = $key . '_vn';

    if ($isVietnamese && !empty($item[$localizedKey])) {
        return (string) $item[$localizedKey];
    }

    return (string) ($item[$key] ?? '');
};

$applyLabel = $isVietnamese ? 'Bắt đầu yêu cầu' : 'Start your request';
$contactLabel = $isVietnamese ? 'Trao đổi với tư vấn viên' : 'Talk to an advisor';

if ($region === null):
    $pageTitle = $isVietnamese ? 'Visa các nước khác' : 'Other Countries Visa';
    $pageDescription = $isVietnamese
        ? 'Chọn khu vực để khám phá các dịch vụ visa quốc tế của SunViet.'
        : 'Choose a region to explore SunViet international visa services.';
    $heroImage = 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1400&q=85';
elseif ($country === null):
    $pageTitle = $value($region, 'title');
    $pageDescription = trim(strip_tags($value($region, 'short')));
    $heroImage = $region['image'];
else:
    $pageTitle = $value($country, 'title');
    $pageDescription = $value($country, 'summary');
    $heroImage = ($country['image'] ?? '') ?: $region['image'];
endif;

$heroImage = $heroImage ?: 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1400&q=85';
$heroMedia = $country['media'] ?? $region['media'] ?? [];
?>

<?php
require_once __DIR__ . '/../components/service-page-hero.php';
require_once __DIR__ . '/../components/service-consultation.php';
require_once __DIR__ . '/../components/service-content-sections.php';

$visaBreadcrumbs = [];

if ($region !== null) {
    $visaBreadcrumbs[] = [
        'label' => $isVietnamese ? 'Visa các nước khác' : 'Other Countries Visa',
        'url' => url('other-countries-visa'),
    ];
}

if ($country !== null && $regionKey !== 'china') {
    $visaBreadcrumbs[] = [
        'label' => $value($region, 'title'),
        'url' => url('other-countries-visa/' . $regionKey),
    ];
}

render_service_page_hero([
    'eyebrow' => $isVietnamese ? 'Dịch vụ visa quốc tế' : 'International visa services',
    'title' => $pageTitle,
    'description' => $pageDescription,
    'image' => $heroImage,
    'media' => $heroMedia,
    'breadcrumbs' => $visaBreadcrumbs,
]);
?>

<?php if ($region === null): ?>
    <section class="section section-white international-visa-section">
        <div class="container detail-grid">
            <main>
                <span class="eyebrow">
                    <?= $isVietnamese ? 'Chọn điểm đến' : 'Choose a destination' ?>
                </span>

                <h2 class="section-title">
                    <?= $isVietnamese ? 'Dịch vụ visa theo khu vực' : 'Visa services by region' ?>
                </h2>

                <p class="detail-lead">
                    <?= $isVietnamese ? 'Mỗi khu vực có quy trình và nhóm quốc gia riêng. Chọn một nhóm để xem các điểm đến cụ thể.' : 'Each region has its own process and destination list. Choose a group to explore its countries.' ?>
                </p>

                <div class="international-visa-panel">
                    <div class="service-grid international-region-grid">
                        <?php $index = 1; ?>
                        <?php foreach ($catalog as $key => $catalogRegion): ?>
                            <?php $regionUrl = $key === 'china'
                                ? url('other-countries-visa/china')
                                : url('other-countries-visa/' . $key); ?>

                            <article class="service-card international-region-card">
                                <div class="service-card-top">
                                    <span class="service-number">
                                        <?= e(str_pad((string) $index, 2, '0', STR_PAD_LEFT)) ?>
                                    </span>
                                </div>

                                <div class="service-icon">&rarr;</div>

                                <h3><?= e($value($catalogRegion, 'title')) ?></h3>

                                <p><?= e(strip_tags($value($catalogRegion, 'short'))) ?></p>

                                <a class="text-link" href="<?= e($regionUrl) ?>">
                                    <?= e($key === 'china'
                                        ? ($isVietnamese ? 'Xem thông tin' : 'View information')
                                        : ($isVietnamese ? 'Xem các quốc gia' : 'View countries')) ?>
                                    <span>&rarr;</span>
                                </a>
                            </article>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>

            <aside class="detail-sidebar">
            <?php render_service_consultation(); ?>
        </aside>
        </div>
    </section>
<?php elseif ($country === null): ?>
    <section class="section section-white international-visa-section">
        <div class="container detail-grid">
            <main>
                <a class="detail-back-link" href="<?= e(url('other-countries-visa')) ?>">
                    &larr; <?= e($isVietnamese ? 'Tất cả khu vực' : 'All regions') ?>
                </a>

                <span class="eyebrow">
                    <?= $isVietnamese ? 'Chọn quốc gia' : 'Choose a country' ?>
                </span>

                <h2 class="section-title">
                    <?= e($value($region, 'title')) ?>
                </h2>

                <p class="detail-lead">
                    <?= $isVietnamese ? 'Chọn quốc gia để xem phần thông tin tư vấn tương ứng.' : 'Select a country to view its corresponding consultation information.' ?>
                </p>

                <div class="rich-content">
                    <?= rich_content_html($value($region, 'short')) ?>
                </div>

                <?php
                $contentOwner = $region;
                $normalizeContent = static function (array $item) use ($value): array {
                    return [
                        'title' => $value($item, 'title'),
                        'description' => $value($item, 'content'),
                    ];
                };
                $normalizeFaq = static function (array $item) use ($value): array {
                    return [
                        'q' => $value($item, 'title'),
                        'a' => $value($item, 'content'),
                    ];
                };
                render_service_content_sections(
                    array_map($normalizeContent, $contentOwner['features'] ?? []),
                    array_map($normalizeContent, $contentOwner['steps'] ?? []),
                    array_map($normalizeFaq, $contentOwner['faqs'] ?? [])
                );
                ?>

                <div class="international-visa-panel">
                    <div class="service-grid international-country-grid">
                        <?php $index = 1; ?>
                        <?php foreach ($region['countries'] as $key => $catalogCountry): ?>
                            <article class="service-card international-country-card">
                                <div class="service-card-top">
                                    <span class="service-number">
                                        <?= e(str_pad((string) $index, 2, '0', STR_PAD_LEFT)) ?>
                                    </span>
                                </div>

                                <div class="service-icon">&rarr;</div>

                                <h3><?= e($value($catalogCountry, 'title')) ?></h3>

                                <p><?= e($value($catalogCountry, 'summary')) ?></p>

                                <a class="text-link" href="<?= e(url('other-countries-visa/' . $regionKey . '/' . $key)) ?>">
                                    <?= e($isVietnamese ? 'Xem thông tin' : 'View information') ?>
                                    <span>&rarr;</span>
                                </a>
                            </article>
                            <?php $index++; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </main>

            <aside class="detail-sidebar">
            <?php render_service_consultation(); ?>
        </aside>
        </div>
    </section>
<?php else: ?>
    <section class="section section-white international-visa-section">
        <div class="container detail-grid">
            <main>
                <a class="detail-back-link" href="<?= e(url($regionKey === 'china' ? 'other-countries-visa' : 'other-countries-visa/' . $regionKey)) ?>">
                    &larr; <?= e($isVietnamese ? 'Quay lại danh sách quốc gia' : 'Back to country list') ?>
                </a>

                <span class="eyebrow">
                    <?= e($value($region, 'title')) ?>
                </span>

                <h2 class="section-title">
                    <?= e($value($country, 'title')) ?>
                </h2>

                <p class="detail-lead">
                    <?= e($value($country, 'summary')) ?>
                </p>

                <div class="country-visa-information">
                    <div class="country-info-card">
                        <span>01</span>
                        <h3><?= $isVietnamese ? 'Trọng tâm tư vấn' : 'Consultation focus' ?></h3>
                        <div class="rich-content"><?= rich_content_html($value($country, 'focus')) ?></div>
                    </div>

                    <div class="country-info-card">
                        <span>02</span>
                        <h3><?= $isVietnamese ? 'Kiểm tra theo hồ sơ' : 'Profile-based review' ?></h3>
                        <p><?= $isVietnamese ? 'Đội ngũ sẽ xem mục đích chuyến đi và hồ sơ của bạn để tư vấn phương án phù hợp.' : 'Our team reviews your travel purpose and profile to recommend a suitable application route.' ?></p>
                    </div>

                    <div class="country-info-card">
                        <span>03</span>
                        <h3><?= $isVietnamese ? 'Yêu cầu có thể thay đổi' : 'Requirements can change' ?></h3>
                        <p><?= $isVietnamese ? 'Điều kiện và giấy tờ được xác nhận theo quy định hiện hành khi bạn gửi yêu cầu tư vấn.' : 'Eligibility and required documents are confirmed against the current rules when you request a consultation.' ?></p>
                    </div>
                </div>

                <?php
                $contentOwner = $country;
                $normalizeContent = static function (array $item) use ($value): array {
                    return [
                        'title' => $value($item, 'title'),
                        'description' => $value($item, 'content'),
                    ];
                };
                $normalizeFaq = static function (array $item) use ($value): array {
                    return [
                        'q' => $value($item, 'title'),
                        'a' => $value($item, 'content'),
                    ];
                };
                render_service_content_sections(
                    array_map($normalizeContent, $contentOwner['features'] ?? []),
                    array_map($normalizeContent, $contentOwner['steps'] ?? []),
                    array_map($normalizeFaq, $contentOwner['faqs'] ?? [])
                );
                ?>

                <div class="country-visa-cta">
                    <div>
                        <span class="eyebrow"><?= $isVietnamese ? 'Bước tiếp theo' : 'Next step' ?></span>
                        <h3><?= $isVietnamese ? 'Nhận tư vấn cho hồ sơ của bạn' : 'Get advice for your application' ?></h3>
                    </div>

                    <a class="btn btn-primary" href="<?= e(url('apply')) ?>">
                        <?= e($applyLabel) ?> &rarr;
                    </a>
                </div>
            </main>

            <aside class="detail-sidebar">
            <?php render_service_consultation(); ?>
        </aside>
        </div>
    </section>
<?php endif; ?>
