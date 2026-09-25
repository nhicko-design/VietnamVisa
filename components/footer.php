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
                <span class="brand-mark">S</span>
                <span class="brand-name">SunViet</span>
            </a>
            <p><?= __('footer_desc') ?></p>
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
           href="https://m.me/YOUR_MESSENGER_USERNAME" 
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
           href="https://zalo.me/84901234567" 
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