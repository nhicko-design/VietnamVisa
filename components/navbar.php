<?php

require_once __DIR__ . '/../common/navigation.php';

$navigationItems = public_navigation_items(
    $SERVICES ?? [],
    (string) ($canonical_path ?? ''),
    $current_lang ?? 'en'
);
$switch_path = (string) ($canonical_path ?? '');
?>

<header class="site-header" id="siteHeader" data-phone="<?= e(SITE_PHONE) ?>">
    <div class="sv-utility-bar">
        <div class="container sv-utility-inner">
            <div>
                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>">Hotline HCM: <?= e(SITE_PHONE) ?></a>
                <span aria-hidden="true">|</span>
                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>">Hotline Hanoi: <?= e(SITE_PHONE) ?></a>
                <span aria-hidden="true">|</span>
                <a href="mailto:<?= e(SITE_EMAIL) ?>"><?= e(SITE_EMAIL) ?></a>
            </div>
            <small>Official Vietnam Immigration &amp; Visa Services</small>
        </div>
    </div>
    <div class="container nav-wrap">
        <a class="brand" href="<?= e(url('')) ?>" aria-label="<?= e(SITE_NAME) ?> Home">
            <img
                class="brand-logo"
                src="<?= e(app_base()) ?>/assets/images/sunviet-logo.png"
                alt="<?= e(SITE_NAME) ?>"
            >
        </a>
        <nav class="desktop-nav" aria-label="Main navigation">
            <?php foreach ($navigationItems as $navigationItem): ?>
                <?php
                $hasChildren = $navigationItem['children'] !== [];
                $submenuId = 'desktop-submenu-' . ($navigationItem['path'] ?: 'home');
                ?>
                <div class="nav-item <?= $hasChildren ? 'has-dropdown' : '' ?> <?= $navigationItem['active'] ? 'is-active' : '' ?>" data-nav-item>
                    <a
                        class="nav-link <?= $navigationItem['active'] ? 'is-active' : '' ?>"
                        href="<?= e(url($navigationItem['path'])) ?>"
                        <?= $navigationItem['current'] ? 'aria-current="page"' : '' ?>
                    >
                        <?= e($navigationItem['label']) ?>
                    </a>

                    <?php if ($hasChildren): ?>
                        <button
                            class="nav-dropdown-toggle"
                            type="button"
                            aria-expanded="false"
                            aria-controls="<?= e($submenuId) ?>"
                            aria-label="<?= e(($current_lang === 'vn' ? 'Mở danh sách: ' : 'Show services: ') . $navigationItem['label']) ?>"
                        >
                            <svg viewBox="0 0 16 16" width="12" height="12" aria-hidden="true">
                                <path d="m4 6 4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <div class="nav-dropdown" id="<?= e($submenuId) ?>">
                            <span class="nav-dropdown-heading"><?= e($navigationItem['label']) ?></span>
                            <ul>
                                <?php foreach ($navigationItem['children'] as $navigationChild): ?>
                                    <li>
                                        <a
                                            class="nav-dropdown-link <?= $navigationChild['active'] ? 'is-active' : '' ?>"
                                            href="<?= e(url($navigationChild['path'])) ?>"
                                            <?= $navigationChild['current'] ? 'aria-current="page"' : '' ?>
                                        >
                                            <span><?= e($navigationChild['label']) ?></span>
                                            <span aria-hidden="true">↗</span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </nav>
        <div class="nav-actions">
            <div class="lang-switch"><a class="<?= $current_lang==='en'?'active':'' ?>" href="<?= e(url($switch_path, 'en')) ?>">EN</a><span>/</span><a class="<?= $current_lang==='vn'?'active':'' ?>" href="<?= e(url($switch_path, 'vn')) ?>">VN</a></div>
        </div>
        <button class="mobile-toggle" id="mobileToggle" type="button" aria-label="Open menu" aria-controls="mobileNav" aria-expanded="false"><span></span><span></span><span></span></button>
    </div>
    <div class="mobile-nav" id="mobileNav">
        <?php foreach ($navigationItems as $navigationItem): ?>
            <a href="<?= e(url($navigationItem['path'])) ?>" <?= $navigationItem['current'] ? 'aria-current="page"' : '' ?>>
                <?= e($navigationItem['label']) ?>
            </a>
        <?php endforeach; ?>
        <div class="mobile-nav-bottom">
            <div class="lang-switch">
                <a href="<?= e(url($switch_path, 'en')) ?>">EN</a>
                <span>/</span>
                <a href="<?= e(url($switch_path, 'vn')) ?>">VN</a>
            </div>
        </div>
    </div>
</header>
