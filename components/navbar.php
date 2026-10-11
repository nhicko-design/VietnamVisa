<header class="site-header" id="siteHeader">
    <div class="container nav-wrap">
        <a class="brand" href="<?= e(url('')) ?>" aria-label="<?= e(SITE_NAME) ?> Home">
            <img
                class="brand-logo"
                src="<?= e(app_base()) ?>/assets/images/sunviet-logo.png"
                alt="<?= e(SITE_NAME) ?>"
            >
        </a>
        <nav class="desktop-nav" aria-label="Main navigation">
            <a class="nav-link <?= $route === 'home' ? 'is-active' : '' ?>" href="<?= e(url('')) ?>"><?= __('nav_home') ?></a>
            <a class="nav-link <?= $route === 'vietnam-visa' ? 'is-active' : '' ?>" href="<?= e(url('vietnam-visa')) ?>">Vietnam Visa</a>
            <a class="nav-link <?= $route === 'temporary-resident-card' ? 'is-active' : '' ?>" href="<?= e(url('temporary-resident-card')) ?>">Temporary Resident Card</a>
            <a class="nav-link <?= $route === 'work-permit' ? 'is-active' : '' ?>" href="<?= e(url('work-permit')) ?>">Work Permit</a>
            <a class="nav-link <?= $route === 'other-countries-visa' ? 'is-active' : '' ?>" href="<?= e(url('other-countries-visa')) ?>">Other Countries Visa</a>
            <a class="nav-link <?= $route === 'other-services' ? 'is-active' : '' ?>" href="<?= e(url('other-services')) ?>">Other Services</a>
            <a class="nav-link <?= in_array($route, ['news','news-detail']) ? 'is-active' : '' ?>" href="<?= e(url('news')) ?>"><?= __('nav_news') ?></a>
        </nav>
        <?php $switch_path = $route === 'service-detail' && isset($service) ? 'service/'.$service['slug'] : ($route === 'news-detail' && isset($article) ? 'article/'.$article['slug'] : ($page === 'home' ? '' : $page)); ?>
        <div class="nav-actions">
            <div class="lang-switch"><a class="<?= $current_lang==='en'?'active':'' ?>" href="<?= e(url($switch_path, 'en')) ?>">EN</a><span>/</span><a class="<?= $current_lang==='vn'?'active':'' ?>" href="<?= e(url($switch_path, 'vn')) ?>">VN</a></div>
            <a class="btn btn-primary btn-small" href="<?= e(url('apply')) ?>"><?= __('nav_apply') ?> <span>↗</span></a>
        </div>
        <button class="mobile-toggle" id="mobileToggle" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>
    </div>
    <div class="mobile-nav" id="mobileNav">
        <a href="<?= e(url('')) ?>"><?= __('nav_home') ?></a>
        <a href="<?= e(url('vietnam-visa')) ?>">Vietnam Visa</a>
        <a href="<?= e(url('temporary-resident-card')) ?>">Temporary Resident Card</a>
        <a href="<?= e(url('work-permit')) ?>">Work Permit</a>
        <a href="<?= e(url('other-countries-visa')) ?>">Other Countries Visa</a>
        <a href="<?= e(url('other-services')) ?>">Other Services</a>
        <a href="<?= e(url('news')) ?>"><?= __('nav_news') ?></a>
        <div class="mobile-nav-bottom"><a href="<?= e(url('apply')) ?>" class="btn btn-primary"><?= __('nav_apply') ?> ↗</a><div class="lang-switch"><a href="<?= e(url($page,'en')) ?>">EN</a><span>/</span><a href="<?= e(url($page,'vn')) ?>">VN</a></div></div>
    </div>
</header>
