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

<footer class="site-footer sv-site-footer">
    <div class="container">
        <div class="sv-footer-main">
            <!-- Col 1: Brand & Socials -->
            <div class="sv-footer-col sv-footer-brand">
                <a class="brand brand-light" href="<?= e(url('')) ?>">
                    <img
                        class="brand-logo"
                        src="<?= e(app_base()) ?>/assets/images/sunviet-logo.png"
                        alt="<?= e(SITE_NAME) ?>"
                    >
                </a>
                <p class="sv-footer-desc"><?= __('footer_desc') ?></p>

                <div class="sv-footer-socials" aria-label="SunViet social media">
                    <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" aria-label="YouTube" title="YouTube" class="sv-social-btn sv-social-yt">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" title="LinkedIn" class="sv-social-btn sv-social-li">
                        <svg viewBox="0 0 448 512" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.3 61.9 111.3 142.3V448z"/></svg>
                    </a>
                    <a href="https://x.com/" target="_blank" rel="noopener noreferrer" aria-label="X" title="X" class="sv-social-btn sv-social-x">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    <a href="<?= e(FACEBOOK_URL ?: 'https://www.facebook.com/') ?>" target="_blank" rel="noopener noreferrer" aria-label="Facebook" title="Facebook" class="sv-social-btn sv-social-fb">
                        <svg viewBox="0 0 320 512" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" title="Instagram" class="sv-social-btn sv-social-ig">
                        <svg viewBox="0 0 448 512" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Office Locations (Side-by-side) -->
            <div class="sv-footer-col sv-footer-offices">
                <h4 class="sv-footer-heading">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    <span><?= ($current_lang ?? 'en') === 'vn' ? 'Hệ Thống Văn Phòng' : 'Office Locations' ?></span>
                </h4>
                <div class="sv-offices-grid">
                    <div class="sv-office-card">
                        <div class="sv-office-header">
                            <span class="sv-office-badge">TP. Hồ Chí Minh</span>
                        </div>
                        <p class="sv-office-address">
                            <?= ($current_lang ?? 'en') === 'vn'
                                ? 'Officetel TS1 - 03.17, LOBBY OT1, The Tresor Apartment, Số 39 Bến Vân Đồn, P. Xóm Chiếu (Q.4), TP. Hồ Chí Minh'
                                : 'Officetel TS1 - 03.17, LOBBY OT1, The Tresor Apartment, No. 39 Ben Van Don Street, Xom Chieu Ward (District. 4) Ho Chi Minh City.'
                            ?>
                        </p>
                    </div>

                    <div class="sv-office-card">
                        <div class="sv-office-header">
                            <span class="sv-office-badge">Hà Nội</span>
                        </div>
                        <p class="sv-office-address">
                            <?= ($current_lang ?? 'en') === 'vn'
                                ? 'Phòng 1214, Tòa nhà B6A, Khu đô thị Nam Trung Yên, Phường Yên Hòa, Hà Nội'
                                : 'Room 1214, B6A Building, Nam Trung Yen Urban, Yen Hoa Ward, Hanoi'
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Col 3: Direct Contact Channels -->
            <div class="sv-footer-col sv-footer-contact">
                <h4 class="sv-footer-heading">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" aria-hidden="true"><path d="M12 1a9 9 0 0 0-9 9v7c0 1.66 1.34 3 3 3h3v-8H5v-2a7 7 0 0 1 14 0v2h-4v8h4c1.1 0 2-.9 2-2v-7a9 9 0 0 0-9-9z"/></svg>
                    <span><?= ($current_lang ?? 'en') === 'vn' ? 'Tư Vấn Trực Tiếp' : 'Direct Contact' ?></span>
                </h4>
                <div class="sv-contact-list">
                    <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>" class="sv-contact-pill">
                        <div class="sv-contact-pill-icon">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        </div>
                        <div class="sv-contact-pill-copy">
                            <small><?= ($current_lang ?? 'en') === 'vn' ? 'Hotline TP. Hồ Chí Minh' : 'Hotline HCMC' ?></small>
                            <strong><?= e(SITE_PHONE) ?></strong>
                        </div>
                    </a>

                    <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>" class="sv-contact-pill">
                        <div class="sv-contact-pill-icon">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M6.62 10.79a15.053 15.053 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/><path d="M19 8c.55 0 1 .45 1 1 0 2.21-1.79 4-4 4-.55 0-1-.45-1-1s.45-1 1-1c1.1 0 2-.9 2-2 0-.55.45-1 1-1z" opacity=".6"/><path d="M22 6c.55 0 1 .45 1 1 0 4.41-3.59 8-8 8-.55 0-1-.45-1-1s.45-1 1-1c3.31 0 6-2.69 6-6 0-.55.45-1 1-1z"/></svg>
                        </div>
                        <div class="sv-contact-pill-copy">
                            <small><?= ($current_lang ?? 'en') === 'vn' ? 'Hotline Hà Nội' : 'Hotline Ha Noi' ?></small>
                            <strong><?= e(SITE_PHONE) ?></strong>
                        </div>
                    </a>

                    <a href="mailto:<?= e(SITE_EMAIL) ?>" class="sv-contact-pill">
                        <div class="sv-contact-pill-icon">
                            <svg viewBox="0 0 24 24" width="15" height="15" fill="currentColor" aria-hidden="true"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        </div>
                        <div class="sv-contact-pill-copy">
                            <small><?= ($current_lang ?? 'en') === 'vn' ? 'Email hỗ trợ' : 'Email Support' ?></small>
                            <strong><?= e(SITE_EMAIL) ?></strong>
                        </div>
                    </a>
                </div>

                <div class="sv-footer-status">
                    <span class="sv-status-dot"></span>
                    <span><?= ($current_lang ?? 'en') === 'vn' ? 'Hỗ trợ tư vấn trực tuyến 24/7' : '24/7 Online Support Available' ?></span>
                </div>
            </div>
        </div>

        <!-- Footer Bottom Bar -->
        <div class="sv-footer-bottom">
            <div class="sv-footer-copy">
                <span>© <?= date('Y') ?> <?= e(__('site_name')) ?>. <?= __('footer_copy') ?></span>
            </div>
            <div class="sv-footer-links">
                <a href="<?= e(url('contact')) ?>"><?= ($current_lang ?? 'en') === 'vn' ? 'Liên hệ' : 'Contact' ?></a>
                <span class="sv-sep">•</span>
                <a href="<?= e(url('about')) ?>"><?= ($current_lang ?? 'en') === 'vn' ? 'Về chúng tôi' : 'About' ?></a>
                <span class="sv-sep">•</span>
                <span><?= e(SITE_EMAIL) ?></span>
            </div>
        </div>
    </div>
</footer>

<!-- =========================================================
     SUNVIET QUICK CONTACT
     WhatsApp | Messenger | Zalo Circular Floating Buttons
     ========================================================= -->
<div class="svqc" id="svqc" aria-label="Quick contact menu">
    <!-- Contact Menu Items (Circular Icons) -->
    <div class="svqc__menu" id="svqcMenu" aria-hidden="true">
        
        <!-- WhatsApp Contact -->
        <a class="svqc__circle svqc__circle--wa svqc__item" 
           href="https://wa.me/84901234567" 
           target="_blank" 
           rel="noopener noreferrer" 
           aria-label="WhatsApp">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="#ffffff" aria-hidden="true"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.816 9.816 0 0 0 12.04 2zm5.79 14.18c-.24.68-1.39 1.3-1.92 1.38-.49.08-1.12.11-3.62-.92-3.2-1.32-5.23-4.57-5.39-4.78-.16-.21-1.3-1.73-1.3-3.3 0-1.57.82-2.34 1.11-2.66.29-.32.64-.4.86-.4.21 0 .43 0 .61.01.2.01.47-.08.73.55.27.64.91 2.22.99 2.38.08.16.13.35.03.56-.1.21-.16.35-.32.53-.16.19-.34.42-.48.56-.16.16-.33.33-.14.66.19.32.84 1.38 1.8 2.24 1.24 1.1 2.28 1.44 2.6 1.6.32.16.51.13.7-.08.19-.21.82-.96 1.04-1.28.21-.32.43-.27.73-.16.29.11 1.87.88 2.19 1.04.32.16.53.24.61.37.08.13.08.77-.16 1.45z"/></svg>
            <span class="svqc__tooltip"><?= ($current_lang ?? 'en') === 'vn' ? 'WhatsApp' : 'WhatsApp' ?></span>
        </a>

        <!-- Messenger Contact -->
        <a class="svqc__circle svqc__circle--messenger svqc__item"
           href="<?= e(MESSENGER_URL ?: 'https://m.me/sunviettravel') ?>"
           target="_blank" 
           rel="noopener noreferrer" 
           aria-label="Messenger">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="#ffffff" aria-hidden="true"><path d="M12 2C6.36 2 2 6.13 2 11.7c0 2.91 1.2 5.42 3.16 7.15V22l3.03-1.68c1.18.33 2.45.52 3.81.52 5.64 0 10-4.13 10-9.14S17.64 2 12 2zm1.08 12.33l-2.61-2.79-5.1 2.79 5.61-5.95 2.67 2.79 5.04-2.79-5.61 5.95z"/></svg>
            <span class="svqc__tooltip"><?= ($current_lang ?? 'en') === 'vn' ? 'Messenger' : 'Messenger' ?></span>
        </a>

        <!-- Zalo Contact -->
        <a class="svqc__circle svqc__circle--zalo svqc__item"
           href="<?= e(ZALO_URL ?: 'https://zalo.me/0901234567') ?>"
           target="_blank" 
           rel="noopener noreferrer" 
           aria-label="Zalo">
            <svg viewBox="0 0 40 40" width="28" height="28" fill="#ffffff" aria-hidden="true"><path d="M8 9h24v4.5L18.5 27.5H32V32H8v-4.5L21.5 13.5H8V9z"/></svg>
            <span class="svqc__tooltip"><?= ($current_lang ?? 'en') === 'vn' ? 'Zalo' : 'Zalo' ?></span>
        </a>
    </div>

    <!-- Toggle Button (Visible on mobile only) -->
    <button class="svqc__toggle" 
            id="svqcToggle" 
            type="button" 
            aria-expanded="false" 
            aria-controls="svqcMenu" 
            aria-label="Open quick contact">
        <span class="svqc__phone" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="currentColor"><path d="M20.01 15.38c-1.23 0-2.42-.2-3.53-.56a.977.977 0 0 0-1.01.24l-1.57 1.97c-2.83-1.44-5.15-3.75-6.59-6.59l1.97-1.57c.28-.28.37-.68.25-1.02A11.36 11.36 0 0 1 8.96 4.3a1 1 0 0 0-1-1H4.46a1 1 0 0 0-1 1C3.46 13.1 10.9 20.54 20.01 20.54a1 1 0 0 0 1-1v-3.16a1 1 0 0 0-1-1z"/></svg>
        </span>
        <span class="svqc__close" aria-hidden="true">
            <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </span>
    </button>
</div>
<!-- End SVQC Contact -->
