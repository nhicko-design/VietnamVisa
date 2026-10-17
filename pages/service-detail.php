<?php

require_once __DIR__ . '/../components/service-page-hero.php';
require_once __DIR__ . '/../components/service-consultation.php';
require_once __DIR__ . '/../components/service-content-sections.php';

$isVietnamese = ($current_lang ?? 'en') === 'vn';
$serviceCategory = strtolower((string) ($service['category'] ?? ''));
$serviceParents = [
    'visa' => ['vietnam-visa', $isVietnamese ? 'Visa Việt Nam' : 'Vietnam Visa'],
    'immigration' => ['temporary-resident-card', $isVietnamese ? 'Thẻ tạm trú' : 'Temporary Resident Card'],
    'support' => ['other-services', $isVietnamese ? 'Dịch vụ khác' : 'Other Services'],
];
$serviceParent = $serviceParents[$serviceCategory] ?? [
    'services',
    $isVietnamese ? 'Dịch vụ' : 'Services',
];

if (($service['slug'] ?? '') === 'work-permit') {
    $serviceParent = ['services', $isVietnamese ? 'Dịch vụ' : 'Services'];
}

$serviceSummary = trim(strip_tags(field($service, 'short')));

if ($serviceSummary === '') {
    $serviceSummary = mb_strimwidth(
        trim(strip_tags(field($service, 'description'))),
        0,
        240,
        '…',
        'UTF-8'
    );
}

render_service_page_hero([
    'eyebrow' => $serviceParent[1],
    'title' => field($service, 'title'),
    'description' => $serviceSummary,
    'image' => (string) ($service['image'] ?? ''),
    'media' => $service['media'] ?? [],
    'breadcrumbs' => [[
        'label' => $serviceParent[1],
        'url' => url($serviceParent[0]),
    ]],
]);
?>

<section class="section section-white service-detail-body">
    <div class="container detail-grid">
        <main>
            <a class="detail-back-link" href="<?= e(url($serviceParent[0])) ?>">
                <span aria-hidden="true">←</span>
                <?= e($isVietnamese ? 'Quay lại ' . $serviceParent[1] : 'Back to ' . $serviceParent[1]) ?>
            </a>

            <div class="section-intro">
                <span class="eyebrow"><?= e(__('detail_overview')) ?></span>
                <h2 class="section-title"><?= e(field($service, 'title')) ?></h2>
            </div>

            <?php if (trim(strip_tags(field($service, 'description'))) !== '' || preg_match('/<(img|video)\b/i', field($service, 'description'))): ?>
                <div class="detail-lead rich-content">
                    <?= rich_content_html(field($service, 'description')) ?>
                </div>
            <?php endif; ?>

            <?php render_service_content_sections(
                field_array($service, 'features'),
                field_array($service, 'steps'),
                field_array($service, 'faq')
            ); ?>
        </main>

        <aside class="detail-sidebar">
            <?php render_service_consultation(); ?>
        </aside>
    </div>
</section>
