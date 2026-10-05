<header class="site-header" id="siteHeader">
    <div class="container nav-wrap">
        <a class="brand" href="<?= e(url('')) ?>" aria-label="<?= e(SITE_NAME) ?> Home">
            <span class="brand-mark">S</span><span class="brand-name"><?= e(SITE_NAME) ?></span>
        </a>
        <nav class="desktop-nav" aria-label="Main navigation">
            <a class="nav-link <?= $route === 'home' ? 'is-active' : '' ?>" href="<?= e(url('')) ?>"><?= __('nav_home') ?></a>
            <a class="nav-link <?= in_array($route, ['services','service-detail','visa']) ? 'is-active' : '' ?>" href="<?= e(url('services')) ?>"><?= __('nav_services') ?></a>
            <a class="nav-link" href="<?= e(url('visa')) ?>"><?= __('nav_visa') ?></a>
            <a class="nav-link" href="<?= e(url('support')) ?>"><?= __('nav_support') ?></a>
            <a class="nav-link <?= $route === 'about' ? 'is-active' : '' ?>" href="<?= e(url('about')) ?>"><?= __('nav_about') ?></a>
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
        <a href="<?= e(url('services')) ?>"><?= __('nav_services') ?></a>
        <a href="<?= e(url('visa')) ?>"><?= __('nav_visa') ?></a>
        <a href="<?= e(url('support')) ?>"><?= __('nav_support') ?></a>
        <a href="<?= e(url('about')) ?>"><?= __('nav_about') ?></a>
        <a href="<?= e(url('news')) ?>"><?= __('nav_news') ?></a>
        <a href="<?= e(url('contact')) ?>"><?= __('nav_contact') ?></a>
        <div class="mobile-nav-bottom"><a href="<?= e(url('apply')) ?>" class="btn btn-primary"><?= __('nav_apply') ?> ↗</a><div class="lang-switch"><a href="<?= e(url($page,'en')) ?>">EN</a><span>/</span><a href="<?= e(url($page,'vn')) ?>">VN</a></div></div>
    </div>
</header>
