<?php require __DIR__ . '/page-faq.php'; ?>

<section class="cta-band">
    <div class="container cta-inner">
        <div>
            <span class="eyebrow eyebrow-light"><?= __('cta_title') ?></span>
            <h2><?= __('cta_desc') ?></h2>
        </div>
        <div class="cta-actions">
            <a class="btn btn-white" href="<?= e(url('apply')) ?>">
                <?= __('cta_primary') ?> ↗
            </a>
            <a class="btn btn-ghost-white" href="<?= e(url('contact')) ?>">
                <?= __('cta_secondary') ?>
            </a>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-brand">
            <a class="brand brand-light" href="<?= e(url('')) ?>">
                <img
                    class="brand-logo"
                    src="<?= e(app_base()) ?>/assets/images/sunviet-logo.png"
                    alt="<?= e(SITE_NAME) ?>"
                >
            </a>
            <p><?= __('footer_desc') ?></p>

            <div class="footer-contact-details">
                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>">
                    <strong>Hotline Hồ Chí Minh</strong>
                    <span><?= e(SITE_PHONE) ?></span>
                </a>
                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>">
                    <strong>Hotline Hà Nội</strong>
                    <span><?= e(SITE_PHONE) ?></span>
                </a>
                <a href="mailto:<?= e(SITE_EMAIL) ?>">
                    <strong>Email</strong>
                    <span><?= e(SITE_EMAIL) ?></span>
                </a>
            </div>

            <div class="footer-socials" aria-label="SunViet social media">
                <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12c0-2.5-.3-4.2-.7-5.1a2.8 2.8 0 0 0-1.6-1.6C18.8 5 16.7 5 12 5s-6.8 0-7.7.3A2.8 2.8 0 0 0 2.7 7C2.3 7.8 2 9.5 2 12s.3 4.2.7 5.1a2.8 2.8 0 0 0 1.6 1.6C5.2 19 7.3 19 12 19s6.8 0 7.7-.3a2.8 2.8 0 0 0 1.6-1.6c.4-.9.7-2.6.7-5.1ZM10 15.5v-7l6 3.5-6 3.5Z"/></svg></a>
                <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 8.3H3.2V21h3.3V8.3ZM4.8 3A2 2 0 1 0 4.8 7a2 2 0 0 0 0-4ZM21 13.7c0-3.8-2-5.6-4.7-5.6-2.2 0-3.1 1.2-3.7 2v-1.8H9.3V21h3.3v-6.3c0-1.7.3-3.3 2.4-3.3 2 0 2 1.9 2 3.4V21H21v-7.3Z"/></svg></a>
                <a href="https://x.com/" target="_blank" rel="noopener noreferrer" aria-label="X"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2H22l-6.8 7.8L23.2 22h-6.3L12 15.6 6.4 22H3.2l7.3-8.4L2.8 2h6.4l4.4 5.8L18.9 2Zm-1.1 18h1.7L8.3 3.9H6.5L17.8 20Z"/></svg></a>
                <a href="<?= e(FACEBOOK_URL ?: 'https://www.facebook.com/') ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.7 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5H17V3.9a22 22 0 0 0-2.4-.1c-2.4 0-4 1.4-4 4.1V10H8v3h2.6v8h3.1Z"/></svg></a>
                <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.3 2h9.4A5.3 5.3 0 0 1 22 7.3v9.4a5.3 5.3 0 0 1-5.3 5.3H7.3A5.3 5.3 0 0 1 2 16.7V7.3A5.3 5.3 0 0 1 7.3 2Zm-.2 2A3.1 3.1 0 0 0 4 7.1v9.8A3.1 3.1 0 0 0 7.1 20h9.8a3.1 3.1 0 0 0 3.1-3.1V7.1A3.1 3.1 0 0 0 16.9 4H7.1ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.2-3.5a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4Z"/></svg></a>
            </div>
        </div>
        <div>
            <h4><?= __('footer_services') ?></h4>
            <a href="<?= e(url('services')) ?>"><?= __('nav_services') ?></a>
            <a href="<?= e(url('visa')) ?>"><?= __('nav_visa') ?></a>
            <a href="<?= e(url('support')) ?>"><?= __('nav_support') ?></a>
        </div>
        <div>
            <h4><?= __('footer_company') ?></h4>
            <a href="<?= e(url('about')) ?>"><?= __('footer_about') ?></a>
            <a href="<?= e(url('contact')) ?>"><?= __('footer_contact') ?></a>
            <a href="<?= e(url('news')) ?>"><?= __('footer_news') ?></a>
        </div>
        <div>
            <h4><?= __('footer_resources') ?></h4>
            <a href="<?= e(url('quotation')) ?>"><?= __('quotation_page_title') ?></a>
            <a href="<?= e(url('apply')) ?>"><?= __('nav_apply') ?></a>
            <a href="<?= e(url('news')) ?>#faq">FAQ</a>
        </div>
    </div>
    <div class="container footer-bottom">
        <span>© <?= date('Y') ?> <?= e(__('site_name')) ?>. <?= __('footer_copy') ?></span>
        <span><?= e(SITE_EMAIL) ?> · <?= e(SITE_PHONE) ?></span>
    </div>
</footer>

<!-- =========================================================
     SUNVIET QUICK CONTACT
     WhatsApp | Messenger | Zalo Contact Menu
     ========================================================= -->
<div class="svqc" id="svqc" aria-label="Quick contact menu">
    <!-- Contact Menu Items -->
    <div class="svqc__menu" id="svqcMenu" aria-hidden="true">
        
        <!-- WhatsApp Contact -->
        <a class="svqc__item" 
           href="https://wa.me/84901234567" 
           target="_blank" 
           rel="noopener noreferrer" 
           aria-label="Contact SunViet on WhatsApp">
            <span class="svqc__icon svqc__icon--wa" aria-hidden="true">
                <span>WA</span>
            </span>
            <span class="svqc__copy">
                <strong>WhatsApp</strong>
                <small>Chat with us</small>
            </span>
            <span class="svqc__arrow" aria-hidden="true">↗</span>
        </a>

        <!-- Messenger Contact -->
        <a class="svqc__item"
           href="<?= e(MESSENGER_URL ?: '#') ?>"
           target="_blank" 
           rel="noopener noreferrer" 
           aria-label="Contact SunViet on Messenger">
            <span class="svqc__icon svqc__icon--messenger" aria-hidden="true">
                <span>M</span>
            </span>
            <span class="svqc__copy">
                <strong>Messenger</strong>
                <small>Chat with us</small>
            </span>
            <span class="svqc__arrow" aria-hidden="true">↗</span>
        </a>

        <!-- Zalo Contact -->
        <a class="svqc__item"
           href="<?= e(ZALO_URL ?: '#') ?>"
           target="_blank" 
           rel="noopener noreferrer" 
           aria-label="Contact SunViet on Zalo">
            <span class="svqc__icon svqc__icon--zalo" aria-hidden="true">
                <span>Z</span>
            </span>
            <span class="svqc__copy">
                <strong>Zalo</strong>
                <small>Chat with us</small>
            </span>
            <span class="svqc__arrow" aria-hidden="true">↗</span>
        </a>
    </div>

    <!-- Toggle Button -->
    <button class="svqc__toggle" 
            id="svqcToggle" 
            type="button" 
            aria-expanded="false" 
            aria-controls="svqcMenu" 
            aria-label="Open quick contact">
        <span class="svqc__phone" aria-hidden="true">☎</span>
        <span class="svqc__close" aria-hidden="true">×</span>
    </button>
</div>
<!-- End SVQC Contact -->
