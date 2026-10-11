<?php

require_once __DIR__ . '/../components/media-hero.php';

$isVietnamVisaService = strtolower((string) ($service['category'] ?? '')) === 'visa';
$serviceBackUrl = $isVietnamVisaService
    ? url('vietnam-visa')
    : url('services');
$serviceBackLabel = $isVietnamVisaService
    ? (($current_lang ?? 'en') === 'vn' ? 'Quay lại Visa Việt Nam' : 'Back to Vietnam Visa')
    : __('back_services');

?>

<section class="detail-hero">

    <div class="container detail-hero-grid">

        <div>

            <a
                class="back-link"
                href="<?= e($serviceBackUrl) ?>"
            >
                ← <?= e($serviceBackLabel) ?>
            </a>


            <span class="eyebrow">
                <?= e($service['category']) ?>
            </span>


            <h1>
                <?= e(field($service, 'title')) ?>
            </h1>


            <p>
                <?= e(trim(strip_tags(field($service, 'description')))) ?>
            </p>


            <div class="detail-actions">

                <a
                    class="btn btn-primary"
                    href="<?= e(url('apply')) ?>"
                >
                    <?= __('apply_service') ?> ↗
                </a>

                <a
                    class="btn btn-secondary"
                    href="<?= e(url('contact')) ?>"
                >
                    <?= __('contact_team') ?>
                </a>

            </div>

        </div>


        <div class="detail-image">
            <?php render_media_hero(
                $service['media'] ?? [],
                (string) ($service['image'] ?? ''),
                field($service, 'title')
            ); ?>
        </div>

    </div>

</section>


<section class="section section-white">

    <div class="container detail-grid">

        <main>

            <span class="eyebrow">
                <?= __('detail_overview') ?>
            </span>


            <h2 class="section-title">
                <?= e(field($service, 'title')) ?>
            </h2>


            <div class="detail-lead rich-content">
                <?= rich_content_html(field($service, 'description')) ?>
            </div>


            <?php $serviceFeatures = field_array($service, 'features'); ?>

            <?php if (!empty($serviceFeatures)): ?>
            <div class="feature-cards">

                <?php
                foreach ($serviceFeatures as $index => $feature):
                ?>

                    <div class="mini-card">

                        <span>
                            <?= str_pad(
                                (string) ($index + 1),
                                2,
                                '0',
                                STR_PAD_LEFT
                            ) ?>
                        </span>


                        <h3>
                            <?= e($feature['title']) ?>
                        </h3>


                        <?php if (
                            !empty(
                                $feature['description']
                            )
                        ): ?>

                            <p>
                                <?= e(
                                    $feature['description']
                                ) ?>
                            </p>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>

            <?php endif; ?>


            <?php $serviceSteps = field_array($service, 'steps'); ?>

            <?php if (!empty($serviceSteps)): ?>
            <div class="detail-process">

                <span class="eyebrow">
                    <?= __('detail_process') ?>
                </span>


                <?php
                foreach ($serviceSteps as $index => $step):
                ?>

                    <div class="detail-step">

                        <strong>
                            <?= str_pad(
                                (string) (
                                    $step['step_number']
                                    ?? ($index + 1)
                                ),
                                2,
                                '0',
                                STR_PAD_LEFT
                            ) ?>
                        </strong>


                        <div>

                            <span>
                                <?= e(
                                    $step['title']
                                ) ?>
                            </span>


                            <?php if (
                                !empty(
                                    $step['description']
                                )
                            ): ?>

                                <p>
                                    <?= e(
                                        $step['description']
                                    ) ?>
                                </p>

                            <?php endif; ?>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <?php endif; ?>

        </main>


        <aside class="detail-sidebar">

            <div class="sticky-card">

                <span class="eyebrow">
                    <?= __('detail_cta') ?>
                </span>


                <h3>
                    <?= __('detail_cta_desc') ?>
                </h3>


                <a
                    class="btn btn-primary btn-full"
                    href="<?= e(url('contact')) ?>"
                >
                    <?= __('contact_team') ?> ↗
                </a>


                <a
                    class="btn btn-secondary btn-full"
                    href="<?= e(url('apply')) ?>"
                >
                    <?= __('apply_service') ?>
                </a>

            </div>

        </aside>

    </div>

</section>


<?php $serviceFaqs = field_array($service, 'faq'); ?>

<?php if (!empty($serviceFaqs)): ?>
<section class="section section-soft">

    <div class="container narrow-grid">

        <div>

            <span class="eyebrow">
                <?= __('detail_faq') ?>
            </span>


            <h2 class="section-title">
                <?= __('faq_title') ?>
            </h2>

        </div>


        <div class="faq-list">

            <?php foreach ($serviceFaqs as $faq): ?>

                <details class="faq-item">

                    <summary>

                        <?= e($faq['q']) ?>

                        <span>
                            +
                        </span>

                    </summary>


                    <p>
                        <?= e($faq['a']) ?>
                    </p>

                </details>

            <?php endforeach; ?>

        </div>

    </div>

</section>
<?php endif; ?>
