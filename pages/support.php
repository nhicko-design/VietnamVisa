<section class="page-hero">
    <div class="container">
        <span class="eyebrow"><?= e(page_content_value('support', 'eyebrow', __('nav_support'))) ?></span>
        <h1><?= e(page_content_value('support', 'title', __('support_page_title'))) ?></h1>
        <p><?= e(page_content_value('support', 'description', __('services_desc'))) ?></p>
    </div>
</section>

<section class="section section-soft">
    <div class="container">
        <div class="service-grid">
            <?php foreach ($SERVICES as $service): ?>
                <?php if (strtolower((string) $service['category']) === 'support'): ?>
                    <?php include __DIR__ . '/../components/service-card.php'; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
