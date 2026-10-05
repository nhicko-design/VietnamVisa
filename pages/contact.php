<?php

require_once __DIR__ . '/../common/public-forms.php';

$contactErrors = [];
$contactSuccess = isset($_GET['sent']);
$contactValues = [
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'service' => '',
    'message' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = submit_public_inquiry('contact', $current_lang ?? 'en');

    if ($result['success']) {
        header('Location: ' . url('contact') . '?sent=1');
        exit;
    }

    $contactErrors = $result['errors'];

    foreach (array_keys($contactValues) as $key) {
        $contactValues[$key] = public_form_value(
            $key,
            $key === 'message' ? 4000 : 255
        );
    }
}

?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow"><?= __('nav_contact') ?></span>
        <h1><?= __('contact_page_title') ?></h1>
        <p><?= __('cta_desc') ?></p>
    </div>
</section>

<section class="section section-white">
    <div class="container contact-grid">
        <div>
            <span class="eyebrow">SunViet</span>
            <h2 class="section-title">Let's talk about your next step.</h2>
            <p class="detail-lead">Send us your question and our team will review your request.</p>

            <div class="contact-items">
                <a href="mailto:<?= e(SITE_EMAIL) ?>">
                    <small>Email</small>
                    <strong><?= e(SITE_EMAIL) ?></strong>
                </a>
                <a href="tel:<?= e(SITE_PHONE) ?>">
                    <small>Phone</small>
                    <strong><?= e(SITE_PHONE) ?></strong>
                </a>
            </div>
        </div>

        <form method="post" class="contact-form">
            <input type="hidden" name="csrf_token" value="<?= e(public_form_csrf_token()) ?>">

            <div class="form-honeypot" aria-hidden="true">
                <label for="contact_website">Website</label>
                <input id="contact_website" type="text" name="website" tabindex="-1" autocomplete="off">
            </div>

            <?php if ($contactSuccess): ?>
                <div class="public-form-notice success" role="status">
                    Thank you. Your request has been received.
                </div>
            <?php endif; ?>

            <?php if ($contactErrors): ?>
                <div class="public-form-notice error" role="alert">
                    <?php foreach ($contactErrors as $error): ?>
                        <div><?= e($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label>
                Full name
                <input name="full_name" required autocomplete="name" value="<?= e($contactValues['full_name']) ?>">
            </label>

            <label>
                Email
                <input type="email" name="email" required autocomplete="email" value="<?= e($contactValues['email']) ?>">
            </label>

            <label>
                Phone (optional)
                <input type="tel" name="phone" autocomplete="tel" value="<?= e($contactValues['phone']) ?>">
            </label>

            <label>
                Service
                <select name="service">
                    <option value="">Select a service</option>
                    <?php foreach (['Vietnam Visa', 'Work Permit', 'Temporary Residence Card', 'Airport Support'] as $service): ?>
                        <option value="<?= e($service) ?>" <?= $contactValues['service'] === $service ? 'selected' : '' ?>>
                            <?= e($service) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                Message
                <textarea name="message" rows="6"><?= e($contactValues['message']) ?></textarea>
            </label>

            <button class="btn btn-primary" type="submit">Send message &rarr;</button>
            <p class="form-note">We only use these details to respond to your request.</p>
        </form>
    </div>
</section>
