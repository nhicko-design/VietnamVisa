<?php

$isVietnamese = ($current_lang ?? 'en') === 'vn';
$residentCardParent = find_service('temporary-residence-card');
$residentCardImage = trim((string) ($residentCardParent['image'] ?? ''));

$residentCardHeroVisual = $residentCardImage !== ''
    ? asset_url($residentCardImage)
    : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1400&q=85';

$residentCardServices = [
    [
        'number' => '01',
        'slug' => 'spouse-resident-card',
        'title' => $isVietnamese ? 'Thẻ tạm trú diện vợ/chồng' : 'Spouse Resident Card',
        'description' => $isVietnamese
            ? 'Dành cho người nước ngoài kết hôn với công dân Việt Nam và có giấy đăng ký kết hôn hợp lệ.'
            : 'For eligible foreign spouses of Vietnamese citizens who hold a valid marriage certificate.',
    ],
    [
        'number' => '02',
        'slug' => 'investor-resident-card',
        'title' => $isVietnamese ? 'Thẻ tạm trú diện đầu tư' : 'Investor Resident Card',
        'description' => $isVietnamese
            ? 'Dành cho nhà đầu tư nước ngoài có khoản đầu tư hợp lệ vào doanh nghiệp tại Việt Nam.'
            : 'For eligible foreign investors in a Vietnam-based company.',
    ],
    [
        'number' => '03',
        'slug' => 'labor-resident-card',
        'title' => $isVietnamese ? 'Thẻ tạm trú diện lao động' : 'Labor Resident Card',
        'description' => $isVietnamese
            ? 'Dành cho người nước ngoài làm việc tại Việt Nam và có giấy phép lao động phù hợp.'
            : 'For eligible foreign employees who hold a valid Vietnam work permit.',
    ],
];

try {
    $statement = db()->prepare(
        "SELECT s.slug, s.title, s.title_vn, s.short_description, s.short_description_vn, l.sort_order
         FROM service_page_links l
         JOIN services s ON s.id = l.service_id
         WHERE l.page_key = 'temporary-resident-card' AND s.is_active = 1
         ORDER BY l.sort_order ASC, l.id ASC"
    );
    $statement->execute();
    $managedResidentServices = $statement->fetchAll(PDO::FETCH_ASSOC);

    if ($managedResidentServices) {
        $residentCardServices = array_map(
            static function (array $service, int $index) use ($isVietnamese): array {
                return [
                    'number' => str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'slug' => $service['slug'],
                    'title' => $isVietnamese && $service['title_vn'] !== '' ? $service['title_vn'] : $service['title'],
                    'description' => $isVietnamese && $service['short_description_vn'] !== '' ? $service['short_description_vn'] : $service['short_description'],
                ];
            },
            $managedResidentServices,
            array_keys($managedResidentServices)
        );
    }
} catch (Throwable $exception) {
    // Keep the original three cards available until the migration is applied.
}

$heroEyebrow = $isVietnamese ? 'Lưu trú dài hạn tại Việt Nam' : 'Long-term residence in Vietnam';
$heroTitle = $isVietnamese ? 'Thẻ tạm trú' : 'Temporary Resident Card';
$heroDescription = $isVietnamese
    ? 'Hướng dẫn lưu trú dài hạn cho vợ/chồng, nhà đầu tư và người lao động nước ngoài đủ điều kiện tại Việt Nam.'
    : 'Long-term residence guidance for eligible spouses, investors and foreign employees in Vietnam.';
$applyLabel = $isVietnamese ? 'Bắt đầu yêu cầu' : 'Start your request';
$contactLabel = $isVietnamese ? 'Trao đổi với tư vấn viên' : 'Talk to an advisor';

?>

<?php
require_once __DIR__ . '/../components/service-page-hero.php';
require_once __DIR__ . '/../components/service-consultation.php';

render_service_page_hero([
    'eyebrow' => $heroEyebrow,
    'title' => $heroTitle,
    'description' => $heroDescription,
    'image' => $residentCardHeroVisual,
    'media' => $residentCardParent['media'] ?? [],
]);
?>

<section class="section section-white resident-card-services-section">
    <div class="container detail-grid">
        <main>
            <div class="resident-card-services-panel">
                <div class="section-intro">
                    <span class="eyebrow">
                        <?= $isVietnamese ? 'Các diện thẻ tạm trú' : 'Temporary resident card services' ?>
                    </span>

                    <h2 class="section-title">
                        <?= $isVietnamese ? 'Dịch vụ thẻ tạm trú tại SunViet' : 'Temporary resident card services at SunViet' ?>
                    </h2>

                    <p>
                        <?= $isVietnamese
                            ? 'Khám phá các nhóm dịch vụ chính trước khi gửi yêu cầu tư vấn.'
                            : 'Explore the main service groups before sending your consultation request.' ?>
                    </p>
                </div>

                <div class="service-grid resident-card-service-grid">
                    <?php foreach ($residentCardServices as $residentCardService): ?>
                        <article class="service-card resident-card-service-card">
                            <div class="service-card-top">
                                <span class="service-number">
                                    <?= e($residentCardService['number']) ?>
                                </span>
                            </div>

                            <div class="service-icon">
                                &rarr;
                            </div>

                            <h3>
                                <?= e($residentCardService['title']) ?>
                            </h3>

                            <p>
                                <?= e($residentCardService['description']) ?>
                            </p>

                            <a class="text-link" href="<?= e(url('service/' . $residentCardService['slug'])) ?>">
                                <?= e($isVietnamese ? 'Xem chi tiết' : 'View details') ?>
                                <span>&rarr;</span>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>

            <section class="service-content-section service-planning-section">
                <span class="eyebrow">
                    <?= $isVietnamese ? 'Tổng quan dịch vụ' : 'Service overview' ?>
                </span>

                <h2 class="section-title">
                    <?= $isVietnamese ? 'Chọn diện thẻ phù hợp với hồ sơ của bạn' : 'Choose the residence route that fits your profile' ?>
                </h2>

                <p class="detail-lead">
                    <?= $isVietnamese
                        ? 'Mỗi diện thẻ có điều kiện và hồ sơ riêng. Chọn nhóm phù hợp để bắt đầu trao đổi cùng đội ngũ SunViet.'
                        : 'Each route has its own eligibility and documents. Choose the group that matches your situation before speaking with SunViet.' ?>
                </p>

                <div class="feature-cards">
                    <div class="mini-card">
                        <span>01</span>
                        <h3><?= $isVietnamese ? 'Xác định diện phù hợp' : 'Identify your route' ?></h3>
                        <p><?= $isVietnamese ? 'Dựa trên quan hệ gia đình, khoản đầu tư hoặc công việc hiện tại của bạn.' : 'Start with your family relationship, investment, or current employment.' ?></p>
                    </div>

                    <div class="mini-card">
                        <span>02</span>
                        <h3><?= $isVietnamese ? 'Chuẩn bị hồ sơ' : 'Prepare your documents' ?></h3>
                        <p><?= $isVietnamese ? 'Kiểm tra giấy tờ nền tảng và thời hạn hiệu lực trước khi nộp yêu cầu.' : 'Check your supporting documents and their validity before submitting a request.' ?></p>
                    </div>

                    <div class="mini-card">
                        <span>03</span>
                        <h3><?= $isVietnamese ? 'Nhận tư vấn' : 'Receive guidance' ?></h3>
                        <p><?= $isVietnamese ? 'Trao đổi với tư vấn viên nếu hồ sơ của bạn có yêu cầu riêng.' : 'Speak with an advisor if your circumstances need tailored guidance.' ?></p>
                    </div>

                    <div class="mini-card">
                        <span>04</span>
                        <h3><?= $isVietnamese ? 'Gửi yêu cầu' : 'Send your request' ?></h3>
                        <p><?= $isVietnamese ? 'Bắt đầu yêu cầu với thông tin rõ ràng để đội ngũ hỗ trợ nhanh hơn.' : 'Submit clear information so our team can support you promptly.' ?></p>
                    </div>
                </div>
            </section>
        </main>

        <aside class="detail-sidebar">
            <?php render_service_consultation(); ?>
        </aside>
    </div>
</section>
