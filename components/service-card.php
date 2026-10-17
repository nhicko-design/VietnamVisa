<?php

$serviceCardIndex = ($serviceCardIndex ?? 0) + 1;
$serviceCardCategory = match (strtolower((string) ($service['category'] ?? ''))) {
    'visa' => 'Vietnam Visa',
    'immigration' => ($current_lang ?? 'en') === 'vn' ? 'Thẻ tạm trú' : 'Residence services',
    'support' => ($current_lang ?? 'en') === 'vn' ? 'Dịch vụ hỗ trợ' : 'Support services',
    default => 'SunViet',
};
?>

<article class="service-card">
    <div class="service-card-top">
        <span class="service-number">
            <?= e(str_pad((string) $serviceCardIndex, 2, '0', STR_PAD_LEFT)) ?>
        </span>
        <?php if (!empty($service['featured'])): ?>
            <span class="pill"><?= e(__('popular')) ?></span>
        <?php endif; ?>
    </div>

    <span class="service-category-label"><?= e($serviceCardCategory) ?></span>
    <h3><?= e(field($service, 'title')) ?></h3>
    <p><?= e(field($service, 'short')) ?></p>

    <a class="text-link" href="<?= e(url('service/' . $service['slug'])) ?>">
        <?= e(__('learn_more')) ?>
        <span aria-hidden="true">→</span>
    </a>
</article>
