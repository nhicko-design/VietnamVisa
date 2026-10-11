<?php

$isVietnamese = ($current_lang ?? 'en') === 'vn';

$otherServices = array_values(array_filter(
    $SERVICES,
    static fn (array $service): bool => strtolower((string) $service['category']) === 'support'
));

?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">
            <?= $isVietnamese ? 'Dịch vụ hỗ trợ' : 'Support services' ?>
        </span>

        <h1>
            <?= $isVietnamese ? 'Dịch vụ khác' : 'Other Services' ?>
        </h1>

        <p>
            <?= $isVietnamese
                ? 'Khám phá các dịch vụ hỗ trợ giúp hành trình của bạn thuận tiện hơn.'
                : 'Explore practical support services that make your journey more convenient.' ?>
        </p>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="service-grid service-grid-large">
            <?php foreach ($otherServices as $service): ?>
                <?php include __DIR__ . '/../components/service-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($otherServices === []): ?>
            <div class="empty-services-message">
                <p>
                    <?= $isVietnamese
                        ? 'Các dịch vụ hỗ trợ sẽ được cập nhật sớm.'
                        : 'More support services will be available soon.' ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>
