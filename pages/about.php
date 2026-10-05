<section class="page-hero">
    <div class="container">
        <span class="eyebrow"><?= e(page_content_value('about', 'eyebrow', __('why_label'))) ?></span>
        <h1><?= e(page_content_value('about', 'title', __('about_page_title'))) ?></h1>
        <p><?= e(page_content_value('about', 'description', __('why_desc'))) ?></p>
    </div>
</section>

<section class="section section-white">
    <div class="container about-grid">
        <div>
            <img
                class="rounded-image"
                src="https://images.unsplash.com/photo-1521737711867-e3b97375f902?auto=format&fit=crop&w=1200&q=85"
                alt="Team"
                loading="lazy"
            >
        </div>

        <div>
            <span class="eyebrow"><?= e(page_content_value('about', 'eyebrow', __('why_label'))) ?></span>
            <h2 class="section-title"><?= e(page_content_value('about', 'section_title', __('why_title'))) ?></h2>
            <p class="detail-lead"><?= e(page_content_value('about', 'description', __('why_desc'))) ?></p>

            <div class="feature-list light-text">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div class="feature-row">
                        <b>0<?= $i ?></b>
                        <div>
                            <h3><?= __('why_' . $i) ?></h3>
                            <p><?= __('why_' . $i . '_desc') ?></p>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>
