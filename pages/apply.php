<?php

require_once __DIR__ . '/../common/public-forms.php';

$applicationErrors = [];
$applicationSuccess = isset($_GET['sent']);
$applicationValues = [
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'nationality' => '',
    'service' => 'Vietnam E-Visa',
    'entry_date' => '',
    'entry_point' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = submit_public_inquiry('application', $current_lang ?? 'en');

    if ($result['success']) {
        header('Location: ' . url('apply') . '?sent=1');
        exit;
    }

    $applicationErrors = $result['errors'];

    foreach (array_keys($applicationValues) as $key) {
        $applicationValues[$key] = public_form_value(
            $key,
            $key === 'message' ? 4000 : 255
        );
    }
}

?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">01 / 04</span>
        <h1><?= __('apply_page_title') ?></h1>
        <p><?= __('cta_desc') ?></p>
    </div>
</section>

<section class="section section-soft">
    <div class="container form-shell">
        <form id="applyForm" method="post" class="application-form">
            <input type="hidden" name="csrf_token" value="<?= e(public_form_csrf_token()) ?>">

            <div class="form-honeypot" aria-hidden="true">
                <label for="application_website">Website</label>
                <input id="application_website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <?php if ($applicationSuccess): ?>
                <div class="public-form-notice success" role="status">
                    Thank you. Your application request has been received.
                </div>
            <?php endif; ?>

            <?php if ($applicationErrors): ?>
                <div class="public-form-notice error" role="alert">
                    <?php foreach ($applicationErrors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form-progress">
                <span class="active">01</span>
                <span>02</span>
                <span>03</span>
                <span>04</span>
            </div>

            <div class="form-step active">
                <h2>Tell us about you</h2>

                <div class="form-grid">
                    <label>
                        Full name
                        <input name="full_name" required autocomplete="name" value="<?= e($applicationValues['full_name']) ?>">
                    </label>
                    <label>
                        Email
                        <input name="email" type="email" required autocomplete="email" value="<?= e($applicationValues['email']) ?>">
                    </label>
                    <label>
                        Phone (optional)
                        <input name="phone" type="tel" autocomplete="tel" value="<?= e($applicationValues['phone']) ?>">
                    </label>
                    <label>
                        Nationality
                        <input name="nationality" required value="<?= e($applicationValues['nationality']) ?>">
                    </label>
                </div>

                <button type="button" class="btn btn-primary next-step">Continue &rarr;</button>
            </div>

            <div class="form-step">
                <h2>Choose a service</h2>

                <div class="choice-grid">
                    <?php foreach (['Vietnam E-Visa', 'Business Visa', 'Work Permit', 'Temporary Residence Card'] as $service): ?>
                        <label>
                            <input
                                type="radio"
                                name="service"
                                value="<?= e($service) ?>"
                                <?= $applicationValues['service'] === $service ? 'checked' : '' ?>
                            >
                            <span><?= e($service) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary prev-step">&larr; Back</button>
                    <button type="button" class="btn btn-primary next-step">Continue &rarr;</button>
                </div>
            </div>

            <div class="form-step">
                <h2>Travel information</h2>

                <div class="form-grid">
                    <label>
                        Expected entry date
                        <input type="date" name="entry_date" value="<?= e($applicationValues['entry_date']) ?>">
                    </label>
                    <label>
                        Entry point
                        <input name="entry_point" placeholder="e.g. Tan Son Nhat" value="<?= e($applicationValues['entry_point']) ?>">
                    </label>
                    <label class="full">
                        Additional information
                        <textarea name="message" rows="5" placeholder="Tell us anything we should know"><?= e($applicationValues['message']) ?></textarea>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary prev-step">&larr; Back</button>
                    <button type="button" class="btn btn-primary next-step">Review &rarr;</button>
                </div>
            </div>

            <div class="form-step">
                <h2>Review your request</h2>
                <div class="review-box" id="applicationReview"></div>
                <p class="form-note">Submit your request and our team will contact you using the email provided.</p>

                <div class="form-actions">
                    <button type="button" class="btn btn-secondary prev-step">&larr; Back</button>
                    <button type="submit" class="btn btn-primary">Submit request &rarr;</button>
                </div>
            </div>
        </form>
    </div>
</section>
