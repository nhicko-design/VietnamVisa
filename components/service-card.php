<article class="service-card reveal">
    <div class="service-card-top"><span class="service-number"><?= e($service['icon']) ?></span><?php if (!empty($service['featured'])): ?><span class="pill"><?= __('popular') ?></span><?php endif; ?></div>
    <div class="service-icon">↗</div>
    <h3><?= e(field($service,'title')) ?></h3>
    <p><?= e(field($service,'short')) ?></p>
    <a class="text-link" href="<?= e(url('service/'.$service['slug'])) ?>"><?= __('learn_more') ?> <span>↗</span></a>
</article>
