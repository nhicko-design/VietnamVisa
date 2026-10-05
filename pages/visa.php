<section class="page-hero">
    <div class="container">
        <span class="eyebrow"><?= e(page_content_value('visa', 'eyebrow', __('nav_visa'))) ?></span>
        <h1><?= e(page_content_value('visa', 'title', __('visa_page_title'))) ?></h1>
        <p><?= e(page_content_value('visa', 'description', __('services_desc'))) ?></p>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="service-grid">
            <?php foreach ($SERVICES as $service): ?>
                <?php if (strtolower((string) $service['category']) === 'visa'): ?>
                    <?php include __DIR__ . '/../components/service-card.php'; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
