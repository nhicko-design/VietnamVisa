<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            <?= __('nav_support') ?>
        </span>

        <h1>
            <?= __('support_page_title') ?>
        </h1>

        <p>
            <?= __('services_desc') ?>
        </p>

    </div>

</section>


<section class="section section-soft">

    <div class="container">

        <div class="service-grid">

            <?php
            foreach ($SERVICES as $service) {

                if ($service['category'] !== 'support') {
                    continue;
                }

                include __DIR__ . '/../components/service-card.php';
            }
            ?>

        </div>

    </div>

</section>