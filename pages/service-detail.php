<section class="detail-hero">

    <div class="container detail-hero-grid">

        <div>

            <a
                class="back-link"
                href="<?= e(url('services')) ?>"
            >
                ← <?= __('back_services') ?>
            </a>


            <span class="eyebrow">
                <?= e($service['category']) ?>
            </span>


            <h1>
                <?= e(field($service, 'title')) ?>
            </h1>


            <p>
                <?= e(field($service, 'description')) ?>
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

            <?php
            $mainImage = $service['image'] ?? '';

            /*
             * If the service does not have a main image,
             * use the first active image from service_media.
             */
            if (
                empty($mainImage) &&
                !empty($service['media'])
            ) {
                foreach ($service['media'] as $media) {

                    if (
                        ($media['media_type'] ?? '')
                        === 'image'
                    ) {
                        $mainImage =
                            $media['file_path'];

                        break;
                    }
                }
            }
            ?>


            <?php if (!empty($mainImage)): ?>

                <img
                    src="<?= e(asset_url($mainImage)) ?>"
                    alt="<?= e(field($service, 'title')) ?>"
                >

            <?php endif; ?>

        </div>

    </div>

</section>


<?php
/*
 * ============================================================
 * SERVICE MEDIA
 * ============================================================
 *
 * Display additional images/videos uploaded from Admin.
 */

$serviceMedia = $service['media'] ?? [];

$galleryImages = [];
$galleryVideos = [];

foreach ($serviceMedia as $media) {

    $type = $media['media_type'] ?? '';

    if ($type === 'image') {

        $galleryImages[] = $media;

    } elseif ($type === 'video') {

        $galleryVideos[] = $media;

    }
}
?>


<?php if (!empty($galleryImages) || !empty($galleryVideos)): ?>

<section class="section section-white">

    <div class="container">

        <div class="section-intro">

            <span class="eyebrow">
                <?= __('services_label') ?>
            </span>

            <h2 class="section-title">
                <?= e(field($service, 'title')) ?>
            </h2>

        </div>


        <?php if (!empty($galleryImages)): ?>

            <div class="service-media-grid">

                <?php foreach ($galleryImages as $media): ?>

                    <div class="service-media-item">

                        <img
                            src="<?= e(asset_url($media['file_path'])) ?>"
                            alt="<?= e(
                                $media['alt_text']
                                ?: ($media['title'] ?? '')
                            ) ?>"
                        >

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <?php if (!empty($galleryVideos)): ?>

            <div class="service-video-list">

                <?php foreach ($galleryVideos as $media): ?>

                    <div class="service-video-item">

                        <video
                            controls
                            preload="metadata"
                        >

                        <source
                            src="<?= e(asset_url($media['file_path'])) ?>"
                        >

                            Your browser does not support
                            HTML5 video.

                        </video>


                        <?php if (!empty($media['title'])): ?>

                            <h3>
                                <?= e($media['title']) ?>
                            </h3>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php endif; ?>


<section class="section section-white">

    <div class="container detail-grid">

        <main>

            <span class="eyebrow">
                <?= __('detail_overview') ?>
            </span>


            <h2 class="section-title">
                <?= e(field($service, 'title')) ?>
            </h2>


            <p class="detail-lead">
                <?= e(field($service, 'description')) ?>
            </p>


            <div class="feature-cards">

                <?php
                foreach (
                    field_array($service, 'features')
                    as $index => $feature
                ):
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


            <div class="detail-process">

                <span class="eyebrow">
                    <?= __('detail_process') ?>
                </span>


                <?php
                foreach (
                    field_array($service, 'steps')
                    as $index => $step
                ):
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

            <?php
            foreach (
                field_array($service, 'faq')
                as $faq
            ):
            ?>

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
