<section class="section faq-section" id="faq">
    <div class="container narrow-grid"><div><span class="eyebrow"><?= __('faq_label') ?></span><h2 class="section-title"><?= __('faq_title') ?></h2></div><div class="faq-list">
        <?php for ($i=1; $i<=4; $i++): ?><details class="faq-item"><summary><?= e(__('faq_'.$i)) ?><span>+</span></summary><p><?= e(__('faq_'.$i.'_a')) ?></p></details><?php endfor; ?>
    </div></div>
</section>
