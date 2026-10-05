<section class="hero section-dark">

    <div class="hero-glow hero-glow-one"></div>

    <div class="hero-glow hero-glow-two"></div>


    <div class="container hero-grid">

        <div class="hero-copy reveal">

            <span class="eyebrow eyebrow-light">
                <?= e(page_content_value('home', 'eyebrow', __('hero_eyebrow'))) ?>
            </span>

            <h1>
                <?= e(page_content_value('home', 'title', __('hero_title'))) ?>
            </h1>

            <p>
                <?= e(page_content_value('home', 'description', __('hero_desc'))) ?>
            </p>


            <div class="hero-actions">

                <a
                    class="btn btn-primary"
                    href="<?= e(page_content_value('home', 'primary_url', url('services'))) ?>"
                >
                    <?= e(page_content_value('home', 'primary_label', __('hero_primary'))) ?> &rarr;
                </a>

                <a
                    class="btn btn-ghost-white"
                    href="<?= e(page_content_value('home', 'secondary_url', url('contact'))) ?>"
                >
                    <?= e(page_content_value('home', 'secondary_label', __('hero_secondary'))) ?>
                </a>

            </div>


            <div class="hero-note">
                ✓ <?= __('hero_note') ?>
            </div>

        </div>


        <div class="hero-visual reveal">

            <div class="hero-image-wrap">

                <?php $heroMedia = $HOME_MEDIA['hero'][0] ?? null; ?>

                <?php if ($heroMedia && $heroMedia['media_type'] === 'video'): ?>
                    <video autoplay muted loop playsinline>
                        <source src="<?= e(asset_url($heroMedia['file_path'])) ?>">
                    </video>
                <?php else: ?>
                <img
                    src="<?= e(asset_url($heroMedia['file_path'] ?? 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=85')) ?>"
                    alt="<?= e($heroMedia['title'] ?? 'Vietnam landscape') ?>"
                >
                <?php endif; ?>


                <div class="hero-floating-card">

                    <span>
                        01
                    </span>

                    <strong>
                        <?= e(page_content_value('home', 'highlight_title', 'Visa & Immigration')) ?>
                    </strong>

                    <small>
                        <?= e(page_content_value('home', 'highlight_description', 'Clear information. Human support.')) ?>
                    </small>

                </div>

            </div>

        </div>

    </div>


    <div class="container hero-bottom">

        <span>
            Visa
        </span>

        <span>
            Work Permit
        </span>

        <span>
            Residence
        </span>

        <span>
            Airport Support
        </span>

    </div>

</section>


<section class="section section-white stats-section">

    <div class="container">

        <div class="section-intro center">

            <span class="eyebrow">
                <?= __('services_label') ?>
            </span>

            <h2 class="section-title">
                <?= __('stats_title') ?>
            </h2>

        </div>


        <div class="stats-grid">

            <div class="stat-item reveal">

                <strong data-counter="10">
                    0+
                </strong>

                <span>
                    <?= __('stat_1') ?>
                </span>

            </div>


            <div class="stat-item reveal">

                <strong>
                    24/7
                </strong>

                <span>
                    <?= __('stat_2') ?>
                </span>

            </div>


            <div class="stat-item reveal">

                <strong data-counter="6">
                    0
                </strong>

                <span>
                    <?= __('stat_3') ?>
                </span>

            </div>


            <div class="stat-item reveal">

                <strong>
                    01
                </strong>

                <span>
                    <?= __('stat_4') ?>
                </span>

            </div>

        </div>

    </div>

</section>


<section class="section section-soft">

    <div class="container">

        <div class="section-intro">

            <span class="eyebrow">
                <?= __('services_label') ?>
            </span>

            <h2 class="section-title">
                <?= __('services_title') ?>
            </h2>

            <p>
                <?= __('services_desc') ?>
            </p>

        </div>


        <div class="service-grid">

            <?php
            foreach (
                array_slice($SERVICES, 0, 4)
                as $service
            ):
                include __DIR__ . '/../components/service-card.php';
            endforeach;
            ?>

        </div>


        <div class="section-more">

            <a
                class="text-link"
                href="<?= e(url('services')) ?>"
            >
                <?= __('view_all') ?> ↗
            </a>

        </div>

    </div>

</section>


<section class="section why-section">

    <div class="container why-grid">

        <div class="why-visual reveal">

            <img
                src="https://images.unsplash.com/photo-1511988617509-a57c8a288659?auto=format&fit=crop&w=1200&q=85"
                alt="Vietnam travel"
            >


            <div class="why-badge">

                <strong>
                    01
                </strong>

                <span>
                    One clear place to start
                </span>

            </div>

        </div>


        <div class="why-copy">

            <span class="eyebrow eyebrow-light">
                <?= __('why_label') ?>
            </span>

            <h2 class="section-title light">
                <?= __('why_title') ?>
            </h2>

            <p>
                <?= __('why_desc') ?>
            </p>


            <div class="feature-list">

                <div class="feature-row reveal">

                    <b>
                        01
                    </b>

                    <div>

                        <h3>
                            <?= __('why_1') ?>
                        </h3>

                        <p>
                            <?= __('why_1_desc') ?>
                        </p>

                    </div>

                </div>


                <div class="feature-row reveal">

                    <b>
                        02
                    </b>

                    <div>

                        <h3>
                            <?= __('why_2') ?>
                        </h3>

                        <p>
                            <?= __('why_2_desc') ?>
                        </p>

                    </div>

                </div>


                <div class="feature-row reveal">

                    <b>
                        03
                    </b>

                    <div>

                        <h3>
                            <?= __('why_3') ?>
                        </h3>

                        <p>
                            <?= __('why_3_desc') ?>
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<section class="section section-white">

    <div class="container">

        <div class="section-intro center">

            <span class="eyebrow">
                <?= __('process_label') ?>
            </span>

            <h2 class="section-title">
                <?= __('process_title') ?>
            </h2>

        </div>


        <div class="process-grid">

            <?php for ($i = 1; $i <= 4; $i++): ?>

                <div class="process-card reveal">

                    <span>
                        0<?= $i ?>
                    </span>

                    <h3>
                        <?= __('step_' . $i) ?>
                    </h3>

                    <p>
                        <?= __('step_' . $i . '_desc') ?>
                    </p>

                </div>

            <?php endfor; ?>

        </div>

    </div>

</section>


<section class="section section-soft">

    <div class="container">

        <div class="section-intro">

            <span class="eyebrow">
                <?= __('news_label') ?>
            </span>

            <h2 class="section-title">
                <?= __('news_title') ?>
            </h2>

            <p>
                <?= __('news_desc') ?>
            </p>

        </div>


        <div class="news-grid">

            <?php
            foreach (
                array_slice($NEWS, 0, 3)
                as $article
            ):
            ?>

                <article class="news-card reveal">

                    <a
                        href="<?= e(url('article/' . $article['slug'])) ?>"
                    >

                        <img
                            src="<?= e(asset_url($article['image'])) ?>"
                            alt=""
                        >


                        <div class="news-card-body">

                            <div class="news-meta">

                                <span>
                                    <?= e(field($article, 'category')) ?>
                                </span>

                                <span>
                                    <?= e(format_date($article['date'])) ?>
                                </span>

                            </div>


                            <h3>
                                <?= e(field($article, 'title')) ?>
                            </h3>


                            <p>
                                <?= e(field($article, 'excerpt')) ?>
                            </p>


                            <span class="text-link">
                                <?= __('read_article') ?> ↗
                            </span>

                        </div>

                    </a>

                </article>

            <?php endforeach; ?>

        </div>


        <div class="section-more">

            <a
                class="text-link"
                href="<?= e(url('news')) ?>"
            >
                <?= __('view_news') ?> ↗
            </a>

        </div>

    </div>

</section>


<?php
include __DIR__ . '/../components/faq.php';
?>
