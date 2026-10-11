<?php if (!empty($pageFaqs)): ?>
    <section class="section faq-section" id="faq">
        <div class="container narrow-grid">
            <div>
                <span class="eyebrow"><?= ($current_lang ?? 'en') === 'vn' ? 'Câu hỏi thường gặp' : 'Frequently asked questions' ?></span>
                <h2 class="section-title"><?= ($current_lang ?? 'en') === 'vn' ? 'Thông tin cần biết' : 'Helpful information' ?></h2>
            </div>

            <div class="faq-list">
                <?php foreach ($pageFaqs as $faq): ?>
                    <?php $isVietnamese = ($current_lang ?? 'en') === 'vn'; ?>
                    <details class="faq-item">
                        <summary>
                            <?= e($isVietnamese && !empty($faq['question_vn']) ? $faq['question_vn'] : $faq['question']) ?>
                            <span>+</span>
                        </summary>
                        <p><?= e($isVietnamese && !empty($faq['answer_vn']) ? $faq['answer_vn'] : $faq['answer']) ?></p>
                    </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
