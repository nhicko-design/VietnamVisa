<section class="page-hero">

    <div class="container">

        <span class="eyebrow">
            <?= __('services_label') ?>
        </span>

        <h1>
            <?= __('services_page_title') ?>
        </h1>

        <p>
            <?= __('services_desc') ?>
        </p>

    </div>

</section>


<section class="section section-soft">

    <div class="container">

        <div class="service-grid service-grid-large">

            <?php
            foreach (
                $SERVICES as $service
            ):
                include __DIR__ . '/../components/service-card.php';
            endforeach;
            ?>

        </div>

    </div>

</section>