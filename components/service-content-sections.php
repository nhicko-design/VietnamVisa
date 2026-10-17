<?php

/** Empty optional sections deliberately produce no markup. */
function render_service_content_sections(array $features, array $steps, array $faqs): void
{
    $isVietnamese = ($GLOBALS['current_lang'] ?? 'en') === 'vn';
    ?>
    <?php if ($features): ?>
        <section class="service-content-section service-features-section">
            <div class="section-intro">
                <span class="eyebrow"><?= $isVietnamese ? 'ĐIỂM NỔI BẬT' : 'SERVICE HIGHLIGHTS' ?></span>
                <h2 class="section-title"><?= $isVietnamese ? 'Hỗ trợ đúng điều bạn cần.' : 'Support built around your needs.' ?></h2>
            </div>
            <div class="feature-cards">
                <?php foreach ($features as $index => $feature): ?>
                    <article class="mini-card">
                        <span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        <h3><?= e($feature['title']) ?></h3>
                        <?php if (!empty($feature['description'])): ?>
                            <p><?= e($feature['description']) ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($steps): ?>
        <section class="service-content-section service-workflow-section">
            <div class="section-intro">
                <span class="eyebrow"><?= $isVietnamese ? 'QUY TRÌNH DỊCH VỤ' : 'HOW IT WORKS' ?></span>
                <h2 class="section-title"><?= $isVietnamese ? 'Rõ ràng trong từng bước.' : 'Clarity at every step.' ?></h2>
            </div>
            <div class="detail-process">
                <?php foreach ($steps as $index => $step): ?>
                    <article class="detail-step">
                        <strong><?= e(str_pad((string) ($step['step_number'] ?? $index + 1), 2, '0', STR_PAD_LEFT)) ?></strong>
                        <div>
                            <h3><?= e($step['title']) ?></h3>
                            <?php if (!empty($step['description'])): ?>
                                <p><?= e($step['description']) ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($faqs): ?>
        <section class="service-content-section service-faq-section">
            <div class="section-intro">
                <span class="eyebrow"><?= $isVietnamese ? 'GIẢI ĐÁP THẮC MẮC' : 'YOUR QUESTIONS, ANSWERED' ?></span>
                <h2 class="section-title"><?= $isVietnamese ? 'Câu hỏi thường gặp.' : 'Frequently asked questions.' ?></h2>
            </div>
            <div class="faq-list">
                <?php foreach ($faqs as $faq): ?>
                    <details class="faq-item">
                        <summary>
                            <?= e($faq['q']) ?>
                            <span aria-hidden="true">+</span>
                        </summary>
                        <p><?= e($faq['a']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    <?php
}
