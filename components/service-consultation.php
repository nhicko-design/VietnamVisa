<?php

function render_service_consultation(array $options = []): void
{
    $isVietnamese = ($GLOBALS['current_lang'] ?? 'en') === 'vn';
    ?>
    <div class="sticky-card service-consultation">
        <span class="eyebrow"><?= $isVietnamese ? 'TƯ VẤN CÙNG SUNVIET' : 'PERSONAL GUIDANCE' ?></span>
        <h3><?= e($options['title'] ?? ($isVietnamese ? 'Cùng tìm phương án phù hợp.' : 'Let’s find your next step.')) ?></h3>
        <p><?= e($options['description'] ?? ($isVietnamese
            ? 'Chia sẻ nhu cầu và hồ sơ của bạn để nhận hướng dẫn từ đội ngũ SunViet.'
            : 'Share your plans and circumstances for guidance from the SunViet team.')) ?></p>

        <ul class="service-consultation-list">
            <li><?= $isVietnamese ? 'Tư vấn theo trường hợp của bạn' : 'Advice for your circumstances' ?></li>
            <li><?= $isVietnamese ? 'Hướng dẫn chuẩn bị hồ sơ' : 'Document preparation guidance' ?></li>
            <li><?= $isVietnamese ? 'Làm rõ quy trình và bước tiếp theo' : 'A clear process and next steps' ?></li>
        </ul>

        <a class="btn btn-primary btn-full" href="<?= e(url('apply')) ?>">
            <?= $isVietnamese ? 'Gửi yêu cầu tư vấn' : 'Start your request' ?>
            <span aria-hidden="true">→</span>
        </a>
        <a class="btn btn-secondary btn-full" href="<?= e(url('contact')) ?>">
            <?= $isVietnamese ? 'Trao đổi với đội ngũ' : 'Contact the team' ?>
        </a>
        <a class="service-consultation-phone" href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>">
            <?= e(SITE_PHONE) ?>
        </a>
    </div>
    <?php
}
