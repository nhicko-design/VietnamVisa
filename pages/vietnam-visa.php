<?php

$visaHeroImage = page_content_asset_value('visa', 'hero_image');

$visaHeroVisual = $visaHeroImage !== ''
    ? asset_url($visaHeroImage)
    : 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=1400&q=85';

$visaServices = array_values(array_filter(
    $SERVICES,
    static fn (array $service): bool => strtolower((string) $service['category']) === 'visa'
));

$isVietnamese = ($current_lang ?? 'en') === 'vn';

$visaOverviewTitle = $isVietnamese
    ? 'Chọn phương án visa phù hợp với hành trình của bạn'
    : 'Find the right visa option for your journey';

$visaOverviewDescription = $isVietnamese
    ? 'Từ e-visa đến hỗ trợ nhập cảnh, hãy xem các dịch vụ visa Việt Nam mà SunViet có thể hỗ trợ.'
    : 'From e-visas to arrival support, explore the Vietnam visa services SunViet can help you arrange.';

$visaConsultationTitle = $isVietnamese
    ? 'Chưa chắc dịch vụ nào phù hợp?'
    : 'Not sure which option fits?';

$visaConsultationDescription = $isVietnamese
    ? 'Chia sẻ kế hoạch của bạn để đội ngũ SunViet đề xuất phương án phù hợp.'
    : 'Tell us about your plan and the SunViet team will point you to the right option.';

$visaApplyLabel = $isVietnamese
    ? 'Bắt đầu yêu cầu visa'
    : 'Start a visa request';

$visaContactLabel = $isVietnamese
    ? 'Trao đổi với tư vấn viên'
    : 'Talk to a visa advisor';

$visaPlanningTitle = $isVietnamese
    ? 'Bắt đầu với thông tin rõ ràng'
    : 'Start with a clear plan';

$visaPlanningDescription = $isVietnamese
    ? 'Mỗi dịch vụ bên dưới có thông tin chi tiết về hồ sơ, quy trình và cách SunViet hỗ trợ bạn.'
    : 'Each service below includes practical information about the process, documents and the support SunViet provides.';

?>

<section class="detail-hero">
    <div class="container detail-hero-grid">
        <div>
            <span class="eyebrow">
                <?= e(page_content_value('visa', 'eyebrow', __('nav_visa'))) ?>
            </span>

            <h1>
                <?= e(page_content_value('visa', 'title', __('visa_page_title'))) ?>
            </h1>

            <p>
                <?= e(page_content_value('visa', 'description', __('services_desc'))) ?>
            </p>

            <div class="detail-actions">
                <a class="btn btn-primary" href="<?= e(url('apply')) ?>">
                    <?= e($visaApplyLabel) ?> &rarr;
                </a>

                <a class="btn btn-secondary" href="<?= e(url('contact')) ?>">
                    <?= e($visaContactLabel) ?>
                </a>
            </div>
        </div>

        <div class="detail-image">
            <img
                src="<?= e($visaHeroVisual) ?>"
                alt="<?= e(page_content_value('visa', 'title', __('visa_page_title'))) ?>"
            >
        </div>
    </div>
</section>

<section class="section section-white visa-services-section">
    <div class="container detail-grid">
        <main>
            <span class="eyebrow">
                <?= $isVietnamese ? 'Tổng quan dịch vụ' : 'Service overview' ?>
            </span>

            <h2 class="section-title">
                <?= e($visaPlanningTitle) ?>
            </h2>

            <p class="detail-lead">
                <?= e($visaPlanningDescription) ?>
            </p>

            <div class="feature-cards">
                <div class="mini-card">
                    <span>01</span>
                    <h3><?= $isVietnamese ? 'Chọn dịch vụ' : 'Choose a service' ?></h3>
                    <p><?= $isVietnamese ? 'Xem mục đích chuyến đi và chọn phương án visa phù hợp.' : 'Review your travel purpose and select the visa option that fits.' ?></p>
                </div>

                <div class="mini-card">
                    <span>02</span>
                    <h3><?= $isVietnamese ? 'Chuẩn bị hồ sơ' : 'Prepare documents' ?></h3>
                    <p><?= $isVietnamese ? 'Đọc yêu cầu trên trang chi tiết trước khi gửi yêu cầu.' : 'Read the detail page requirements before you submit a request.' ?></p>
                </div>

                <div class="mini-card">
                    <span>03</span>
                    <h3><?= $isVietnamese ? 'Nhận tư vấn' : 'Get guidance' ?></h3>
                    <p><?= $isVietnamese ? 'Liên hệ đội ngũ khi trường hợp của bạn cần tư vấn riêng.' : 'Contact our team when your case needs personal guidance.' ?></p>
                </div>

                <div class="mini-card">
                    <span>04</span>
                    <h3><?= $isVietnamese ? 'Gửi yêu cầu' : 'Send your request' ?></h3>
                    <p><?= $isVietnamese ? 'Bắt đầu yêu cầu với thông tin rõ ràng và đầy đủ hơn.' : 'Start your request with clearer and more complete information.' ?></p>
                </div>
            </div>

            <div class="visa-services-panel">
                <div class="section-intro">
                    <span class="eyebrow">
                        <?= $isVietnamese ? 'Dịch vụ visa Việt Nam' : 'Vietnam visa services' ?>
                    </span>

                    <h2 class="section-title">
                        <?= e($visaOverviewTitle) ?>
                    </h2>

                    <p>
                        <?= e($visaOverviewDescription) ?>
                    </p>
                </div>

                <div class="service-grid visa-service-grid">
                    <?php foreach ($visaServices as $service): ?>
                        <?php include __DIR__ . '/../components/service-card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>

        <aside class="detail-sidebar">
            <div class="sticky-card">
                <span class="eyebrow">
                    <?= $isVietnamese ? 'Tư vấn visa' : 'Visa consultation' ?>
                </span>

                <h3>
                    <?= e($visaConsultationTitle) ?>
                </h3>

                <p>
                    <?= e($visaConsultationDescription) ?>
                </p>

                <a class="btn btn-primary btn-full" href="<?= e(url('contact')) ?>">
                    <?= e($visaContactLabel) ?>
                </a>

                <a class="btn btn-secondary btn-full" href="<?= e(url('apply')) ?>">
                    <?= e($visaApplyLabel) ?>
                </a>
            </div>
        </aside>
    </div>
</section>
