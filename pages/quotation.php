<?php $isVietnamese = ($current_lang ?? 'en') === 'vn'; ?>

<section class="page-hero">
    <div class="container">
        <span class="eyebrow">SunViet</span>
        <h1><?= e(__('quotation_page_title')) ?></h1>
        <p><?= $isVietnamese
            ? 'Chia sẻ nhu cầu của bạn để đội ngũ SunViet tư vấn phương án phù hợp.'
            : 'Tell us what you need so the SunViet team can recommend a suitable service.' ?></p>
    </div>
</section>

<section class="section section-white">
    <div class="container">
        <div class="section-intro">
            <span class="eyebrow"><?= $isVietnamese ? 'Tư vấn cùng SunViet' : 'Personal guidance' ?></span>
            <h2 class="section-title"><?= $isVietnamese
                ? 'Bắt đầu với nhu cầu của bạn.'
                : 'Let’s start with your plans.' ?></h2>
            <p><?= $isVietnamese
                ? 'Gửi thông tin về dịch vụ bạn quan tâm. Đội ngũ sẽ liên hệ và hướng dẫn bước tiếp theo.'
                : 'Send us details about the service you are interested in. Our team will contact you with the next steps.' ?></p>
        </div>

        <div class="detail-actions">
            <a class="btn btn-primary" href="<?= e(url('apply')) ?>">
                <?= $isVietnamese ? 'Gửi yêu cầu tư vấn' : 'Start your request' ?>
                <span aria-hidden="true">→</span>
            </a>
            <a class="btn btn-secondary" href="<?= e(url('contact')) ?>">
                <?= $isVietnamese ? 'Liên hệ đội ngũ' : 'Contact the team' ?>
            </a>
        </div>
    </div>
</section>
