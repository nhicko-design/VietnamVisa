<?php

require_once __DIR__ . '/../common/public-forms.php';

$routeFinderSuccess = false;
$routeFinderMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'route_finder_lead') {
    $email = trim((string) ($_POST['lead_email'] ?? ''));
    $service = trim((string) ($_POST['selected_service'] ?? ''));
    $option = trim((string) ($_POST['selected_option'] ?? ''));
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $routeFinderMessage = ($current_lang ?? 'en') === 'vn'
            ? 'Vui lòng nhập địa chỉ email hợp lệ.'
            : 'Please enter a valid email address.';
    } else {
        try {
            $ipAddress = (string) ($_SERVER['REMOTE_ADDR'] ?? '');
            $ipHash = $ipAddress === '' ? null : hash('sha256', $ipAddress);
            $fullName = explode('@', $email)[0];
            $messageContent = "Route Finder Inquiry: Service [{$service}] | Option [{$option}]";

            $stmt = db()->prepare(
                'INSERT INTO inquiries (
                    inquiry_type, full_name, email, service, message, language_code, source_url, ip_hash
                ) VALUES (
                    :inquiry_type, :full_name, :email, :service, :message, :language_code, :source_url, :ip_hash
                )'
            );
            $stmt->execute([
                'inquiry_type' => 'contact',
                'full_name' => $fullName,
                'email' => $email,
                'service' => $service,
                'message' => $messageContent,
                'language_code' => ($current_lang ?? 'en') === 'vn' ? 'vn' : 'en',
                'source_url' => mb_substr((string) ($_SERVER['REQUEST_URI'] ?? ''), 0, 1000, 'UTF-8'),
                'ip_hash' => $ipHash,
            ]);

            $routeFinderSuccess = true;
            $routeFinderMessage = ($current_lang ?? 'en') === 'vn'
                ? '✓ Cảm ơn bạn! Chúng tôi đã nhận được thông tin và sẽ gửi phản hồi qua email sớm nhất.'
                : '✓ Thank you! We have received your request and will follow up via email shortly.';
        } catch (Throwable $e) {
            $routeFinderMessage = ($current_lang ?? 'en') === 'vn'
                ? 'Hệ thống đang bận, vui lòng thử lại sau.'
                : 'System busy, please try again later.';
        }
    }

    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $routeFinderSuccess,
            'message' => $routeFinderMessage,
        ]);
        exit;
    }
}

$homeHelpCards = [
    ['number' => '01', 'title_en' => 'Vietnam Visa', 'title_vn' => 'Visa Việt Nam', 'short_en' => 'Explore visa options and entry support for Vietnam.', 'short_vn' => 'Khám phá các lựa chọn visa và hỗ trợ nhập cảnh Việt Nam.', 'path' => 'vietnam-visa'],
    ['number' => '02', 'title_en' => 'Temporary Resident Card', 'title_vn' => 'Thẻ tạm trú', 'short_en' => 'Find the suitable residence route for your situation.', 'short_vn' => 'Tìm diện thẻ tạm trú phù hợp với tình trạng của bạn.', 'path' => 'temporary-resident-card'],
    ['number' => '03', 'title_en' => 'Work Permit', 'title_vn' => 'Giấy phép lao động', 'short_en' => 'Get guidance for working legally in Vietnam.', 'short_vn' => 'Nhận hướng dẫn để làm việc hợp pháp tại Việt Nam.', 'path' => 'work-permit'],
    ['number' => '04', 'title_en' => 'Other Countries Visa', 'title_vn' => 'Visa các nước khác', 'short_en' => 'Choose an international destination and visa route.', 'short_vn' => 'Chọn điểm đến quốc tế và diện visa phù hợp.', 'path' => 'other-countries-visa'],
];
$heroMedia = $HOME_MEDIA['hero'][0] ?? null;

$isVn = ($current_lang ?? 'en') === 'vn';

$heroBadgeFallback = $isVn ? 'Hơn 16 năm kinh nghiệm xuất nhập cảnh' : '16+ Years Immigration Excellence';
$heroBadge = page_content_value('home', 'eyebrow', $heroBadgeFallback);

$heroTitleDefault = $isVn
    ? 'Visa Việt Nam, Giấy phép lao động & <span class="text-gold">Hỗ trợ định cư</span>'
    : 'Vietnam Visa, Work Permit & <span class="text-gold">Expat Support</span> Services';
$heroTitle = page_content_value('home', 'title', '');
$heroTitleHtml = !empty($heroTitle)
    ? (str_contains($heroTitle, '<span') ? $heroTitle : e($heroTitle))
    : $heroTitleDefault;

$heroDescFallback = $isVn
    ? 'Được tin tưởng bởi hàng ngàn du khách quốc tế, nhà đầu tư toàn cầu và các tập đoàn quốc tế về dịch vụ nhập cảnh, cư trú và định cư tại Việt Nam.'
    : 'Trusted by thousands of foreigners, global investors, and international corporations for reliable entry, residence compliance, and relocation support services in Vietnam.';
$heroDesc = page_content_value('home', 'description', $heroDescFallback);

$primaryLabelFallback = $isVn ? 'Nhận tư vấn miễn phí' : 'Get Free Consultation';
$primaryLabel = page_content_value('home', 'primary_label', $primaryLabelFallback);
$primaryUrl = page_content_value('home', 'primary_url', url('contact'));

$secondaryLabelFallback = $isVn ? 'Khám phá dịch vụ' : 'Explore Services';
$secondaryLabel = page_content_value('home', 'secondary_label', $secondaryLabelFallback);
$secondaryUrl = page_content_value('home', 'secondary_url', url('services'));

$heroBgImage = 'https://images.unsplash.com/photo-1583417319070-4a69db38a482?q=80&w=1920&auto=format&fit=crop';
$hasHeroVideo = $heroMedia && ($heroMedia['media_type'] ?? '') === 'video' && !empty($heroMedia['file_path']);
if ($heroMedia && ($heroMedia['media_type'] ?? '') === 'image' && !empty($heroMedia['file_path'])) {
    $heroBgImage = asset_url($heroMedia['file_path']);
}
?>

<!-- Section 1: Hero Section -->
<section id="hero" class="hero-section" style="background-image: linear-gradient(rgba(11, 26, 48, 0.85), rgba(11, 26, 48, 0.85)), url('<?= e($heroBgImage) ?>');">
    <?php if ($hasHeroVideo): ?>
        <video class="hero-bg-video" autoplay muted loop playsinline>
            <source src="<?= e(asset_url($heroMedia['file_path'])) ?>">
        </video>
        <div class="hero-video-overlay"></div>
    <?php endif; ?>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 text-start reveal animate-on-scroll">
                <span class="hero-badge"><i class="fa-solid fa-award text-gold me-2"></i><?= e($heroBadge) ?></span>
                <h1 class="display-3 fw-bold mb-4" style="line-height: 1.15;"><?= $heroTitleHtml ?></h1>
                <p class="lead text-light opacity-90 mb-5 fs-5"><?= e($heroDesc) ?></p>
                <div class="hero-btn-group d-sm-flex gap-3">
                    <a href="<?= e($primaryUrl) ?>" class="btn btn-gold btn-lg mb-3 mb-sm-0 w-sm-100"><i class="fa-solid fa-comments me-2"></i><?= e($primaryLabel) ?></a>
                    <a href="<?= e($secondaryUrl) ?>" class="btn btn-outline-gold btn-lg mb-3 mb-sm-0 w-sm-100"><i class="fa-solid fa-list-check me-2"></i><?= e($secondaryLabel) ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="sv-home-metrics">
    <div class="container">
        <div class="sv-section-heading sv-section-heading-center"><span class="sv-overline">WHAT WE HELP WITH</span><h2><?= e(__('stats_title')) ?></h2></div>
        <div class="sv-metric-grid">
            <article class="sv-metric-card sv-metric-featured"><strong>2009</strong><span><?= e(__('stat_1')) ?></span></article>
            <article class="sv-metric-card"><strong>24/7</strong><span><?= e(__('stat_2')) ?></span></article>
            <article class="sv-metric-card"><strong>6</strong><span><?= e(__('stat_3')) ?></span></article>
            <article class="sv-metric-card"><strong>01</strong><span><?= e(__('stat_4')) ?></span></article>
        </div>
    </div>
</section>

<?php
require_once __DIR__ . '/../data/case-studies.php';
$caseStudyServices = get_case_study_services($isVn);
?>

<section class="sv-home-case-studies" id="services-case-studies">
    <div class="container">
        <!-- Section Header -->
        <div class="sv-cs-header reveal">
            <div class="sv-cs-header-split">
                <div>
                    <span class="sv-cs-eyebrow">
                        <i class="fa-solid fa-award text-gold"></i>
                        <?= $isVn ? 'HỆ THỐNG DỊCH VỤ & TÌNH HUỐNG THỰC TẾ' : 'SERVICES & REAL-WORLD CASE STUDIES' ?>
                    </span>
                    <h2><?= $isVn ? 'Bạn đang gặp khó khăn gì? Xem cách chúng tôi giải quyết.' : 'Facing an immigration challenge? See how we solved it.' ?></h2>
                    <p><?= $isVn ? 'Khám phá tất cả các dịch vụ của SunViet. Bấm vào từng bài viết case study để xem chi tiết những điểm đặc biệt, bối cảnh nan giải và quy trình xử lý thực chiến.' : 'Explore SunViet services. Click any case study article to explore what makes it special, the initial dilemma, and our step-by-step resolution roadmap.' ?></p>
                </div>
                <a class="sv-inline-link" href="<?= e(url('services')) ?>">
                    <?= $isVn ? 'Xem tất cả dịch vụ' : 'View all services' ?> <span>↗</span>
                </a>
            </div>
        </div>

        <!-- Service Tabs (Responsive Grid Bar - Always 100% visible, No horizontal scroll) -->
        <div class="sv-cs-tabs-container reveal">
            <div class="sv-cs-tabs-list" role="tablist" aria-label="Services List">
                <?php $tabIndex = 0; foreach ($caseStudyServices as $svcKey => $svc): ?>
                    <button 
                        type="button" 
                        class="sv-cs-tab-btn <?= $tabIndex === 0 ? 'is-active' : '' ?>" 
                        data-target="cs-pane-<?= e($svcKey) ?>"
                        role="tab"
                        aria-selected="<?= $tabIndex === 0 ? 'true' : 'false' ?>"
                        aria-controls="cs-pane-<?= e($svcKey) ?>"
                    >
                        <div class="sv-cs-tab-main">
                            <div class="sv-cs-tab-icon-wrap" style="color: <?= e($svc['color']) ?>;">
                                <i class="<?= e($svc['icon']) ?>"></i>
                            </div>
                            <span class="sv-cs-tab-title"><?= e($svc['title']) ?></span>
                        </div>
                        <span class="sv-cs-tab-count"><?= count($svc['cases']) ?> <?= $isVn ? 'bài viết' : 'cases' ?></span>
                    </button>
                <?php $tabIndex++; endforeach; ?>
            </div>
        </div>

        <!-- Case Study Panes -->
        <div class="sv-cs-panes-wrapper">
            <?php $paneIndex = 0; foreach ($caseStudyServices as $svcKey => $svc): ?>
                <div 
                    class="sv-cs-pane <?= $paneIndex === 0 ? 'is-active' : '' ?>" 
                    id="cs-pane-<?= e($svcKey) ?>"
                    role="tabpanel"
                >
                    <!-- Service Overview Banner -->
                    <div class="sv-cs-pane-banner">
                        <div class="sv-cs-pane-banner-info">
                            <div class="sv-cs-pane-icon" style="background: <?= e($svc['color']) ?>;">
                                <i class="<?= e($svc['icon']) ?>"></i>
                            </div>
                            <div class="sv-cs-pane-texts">
                                <h4><?= e($svc['title']) ?></h4>
                                <p><?= e($svc['tagline']) ?></p>
                            </div>
                        </div>
                        <a href="<?= e($svc['page_url']) ?>" class="sv-cs-pane-link">
                            <span><?= $isVn ? 'Xem chi tiết dịch vụ' : 'Explore full service details' ?></span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Case Study Article Cards Grid (Max 6 cases on Home) -->
                    <?php
                        $totalSvcCases = count($svc['cases']);
                        $homeCases = array_slice($svc['cases'], 0, 6);
                    ?>
                    <div class="sv-cs-grid" id="cs-grid-<?= e($svcKey) ?>">
                        <?php foreach ($homeCases as $cIdx => $case): ?>
                            <article 
                                class="sv-cs-article-card"
                                data-service="<?= e($svcKey) ?>"
                                data-index="<?= $cIdx ?>"
                                tabindex="0"
                                role="button"
                                aria-label="<?= e($case['title']) ?>"
                            >
                                <div class="sv-cs-thumb-wrap">
                                    <img src="<?= e($case['image']) ?>" alt="<?= e($case['title']) ?>" class="sv-cs-thumb-img" loading="lazy">
                                    <div class="sv-cs-thumb-overlay"></div>
                                    <div class="sv-cs-thumb-top-badges">
                                        <span class="sv-cs-badge sv-cs-badge-<?= e($case['tag_type']) ?>">
                                            <?= e($case['tag']) ?>
                                        </span>
                                        <span class="sv-cs-time-pill"><?= e($case['time']) ?></span>
                                    </div>
                                    <div class="sv-cs-thumb-client">
                                        <i class="fa-solid fa-user-check"></i>
                                        <span><?= e($case['client_origin']) ?></span>
                                    </div>
                                </div>

                                <div class="sv-cs-body">
                                    <h3 class="sv-cs-article-title"><?= e($case['title']) ?></h3>
                                    <p class="sv-cs-article-excerpt"><?= e($case['excerpt']) ?></p>

                                    <!-- Special Feature Box -->
                                    <div class="sv-cs-special-tag">
                                        <i class="fa-solid fa-star"></i>
                                        <div>
                                            <strong><?= $isVn ? 'Điểm đặc biệt: ' : 'Highlight: ' ?></strong>
                                            <span><?= e($case['special_point']) ?></span>
                                        </div>
                                    </div>

                                    <div class="sv-cs-footer-action">
                                        <span class="sv-cs-read-link">
                                            <span><?= $isVn ? 'Xem chi tiết Case Study' : 'Read Case Breakdown' ?></span>
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </span>
                                        <span class="sv-cs-read-hint">
                                            <?= $isVn ? 'Bấm để xem' : 'Click to read' ?>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <div class="sv-cs-view-all-bar">
                        <?php if ($totalSvcCases > 6): ?>
                            <span class="sv-cs-showing-text">
                                <?= $isVn ? "Đang hiển thị 6 / {$totalSvcCases} tình huống tiêu biểu" : "Showing 6 of {$totalSvcCases} featured cases" ?>
                            </span>
                        <?php endif; ?>
                        <a href="<?= e(url('case-studies') . '?service=' . urlencode($svcKey)) ?>" class="sv-cs-view-all-btn">
                            <span><?= $isVn ? ($totalSvcCases > 6 ? "Xem thêm toàn bộ {$totalSvcCases} Case Study" : "Xem tất cả Case Study") : ($totalSvcCases > 6 ? "View all {$totalSvcCases} Case Studies" : "View all Case Studies") ?></span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            <?php $paneIndex++; endforeach; ?>
        </div>

        <!-- Bottom Consultation Banner -->
        <div class="sv-cs-bottom-banner">
            <div class="sv-cs-banner-copy">
                <h3><?= $isVn ? 'Trường hợp của bạn có tình tiết đặc biệt phức tạp?' : 'Have a unique or exceptionally complex case?' ?></h3>
                <p><?= $isVn ? 'Đội ngũ chuyên viên pháp lý xuất nhập cảnh SunViet với hơn 16 năm kinh nghiệm sẵn sàng phân tích hồ sơ và đưa ra phương án xử lý tối ưu trong 15 phút.' : 'SunViet legal and immigration counsel with 16+ years of expertise will review your profile and propose the optimal strategy within 15 minutes.' ?></p>
            </div>
            <div class="sv-cs-banner-actions">
                <a href="https://wa.me/84901234567" target="_blank" rel="noopener noreferrer" class="sv-cs-banner-btn-gold">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span><?= $isVn ? 'Chat WhatsApp ngay' : 'Instant WhatsApp' ?></span>
                </a>
                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>" class="sv-cs-banner-btn-outline">
                    <i class="fa-solid fa-phone"></i>
                    <span><?= e(SITE_PHONE) ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../components/case-study-modal.php'; ?>

<script>
(function() {
    const tabButtons = document.querySelectorAll('.sv-cs-tab-btn');
    const panes = document.querySelectorAll('.sv-cs-pane');

    tabButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            if (!targetId) return;

            tabButtons.forEach(b => {
                b.classList.remove('is-active');
                b.setAttribute('aria-selected', 'false');
            });
            panes.forEach(p => {
                p.classList.remove('is-active');
            });

            btn.classList.add('is-active');
            btn.setAttribute('aria-selected', 'true');

            const targetPane = document.getElementById(targetId);
            if (targetPane) {
                targetPane.classList.add('is-active');
            }
        });
    });
})();
</script>
<section class="sv-home-why">
    <div class="container sv-home-why-grid">
        <div class="sv-home-why-image reveal"><img src="https://images.unsplash.com/photo-1511988617509-a57c8a288659?auto=format&fit=crop&w=1200&q=85" alt="SunViet team support"><div><b>01</b><span>One clear place to start</span></div></div>
        <div class="sv-home-why-copy"><span class="sv-overline sv-overline-gold"><?= e(__('why_label')) ?></span><h2><?= e(__('why_title')) ?></h2><p><?= e(__('why_desc')) ?></p><div class="sv-feature-list">
            <?php for ($i = 1; $i <= 3; $i++): ?><article><b>0<?= $i ?></b><div><h3><?= e(__('why_' . $i)) ?></h3><p><?= e(__('why_' . $i . '_desc')) ?></p></div></article><?php endfor; ?>
        </div></div>
    </div>
</section>

<section class="sv-home-route-finder" id="route-finder">
    <div class="container">
        <div class="sv-route-finder-heading reveal">
            <span class="sv-route-finder-eyebrow"><?= $isVn ? 'TÌM LỘ TRÌNH CỦA BẠN TRONG 20 GIÂY' : 'FIND YOUR ROUTE IN 20 SECONDS' ?></span>
            <h2><?= $isVn ? 'Bạn đang cần hỗ trợ thủ tục gì?' : 'What do you need to sort out?' ?></h2>
            <p><?= $isVn ? 'Chọn 2 câu trả lời. Chúng tôi sẽ gợi ý dịch vụ phù hợp kèm bảng giá công khai.' : 'Pick two answers. We suggest the right service, with our published price where there is one.' ?></p>
        </div>

        <div class="sv-route-finder-grid">
            <!-- Step 1 Card -->
            <div class="sv-finder-card reveal">
                <div class="sv-finder-step-header">
                    <span class="sv-finder-step-num">1</span>
                    <h3 class="sv-finder-step-title"><?= $isVn ? 'Nhu cầu của bạn' : 'What you need' ?></h3>
                </div>
                <div class="sv-finder-options-grid" id="finderStep1">
                    <button type="button" class="sv-finder-btn" data-key="e-visa">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-passport" style="color:#0284c7;"></i></span>
                        <span><?= $isVn ? 'Visa điện tử (E-visa)' : 'A Vietnam e-visa' ?></span>
                    </button>
                    <button type="button" class="sv-finder-btn is-selected" data-key="airport-fast-track">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-plane-arrival" style="color:#0284c7;"></i></span>
                        <span><?= $isVn ? 'Airport Fast Track' : 'Airport fast track' ?></span>
                    </button>
                    <button type="button" class="sv-finder-btn" data-key="work-permit">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-briefcase" style="color:#854d0e;"></i></span>
                        <span><?= $isVn ? 'Làm việc tại Việt Nam' : 'Work in Vietnam' ?></span>
                    </button>
                    <button type="button" class="sv-finder-btn" data-key="trc">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-house-chimney" style="color:#ea580c;"></i></span>
                        <span><?= $isVn ? 'Thẻ tạm trú (TRC)' : 'Stay long-term (TRC)' ?></span>
                    </button>
                    <button type="button" class="sv-finder-btn" data-key="police-check">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-file-shield" style="color:#7c3aed;"></i></span>
                        <span><?= $isVn ? 'Lý lịch tư pháp' : 'Police check' ?></span>
                    </button>
                    <button type="button" class="sv-finder-btn" data-key="outbound-visa">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-plane-departure" style="color:#2563eb;"></i></span>
                        <span><?= $isVn ? 'Visa đi nước ngoài' : 'Visa to another country' ?></span>
                    </button>
                    <button type="button" class="sv-finder-btn" data-key="company-help">
                        <span class="sv-finder-btn-icon"><i class="fa-solid fa-building" style="color:#0891b2;"></i></span>
                        <span><?= $isVn ? 'Doanh nghiệp cần hỗ trợ' : 'My company needs help' ?></span>
                    </button>
                </div>
            </div>

            <!-- Step 2 Card -->
            <div class="sv-finder-card reveal">
                <div class="sv-finder-step-header">
                    <span class="sv-finder-step-num">2</span>
                    <h3 class="sv-finder-step-title" id="finderStep2Title"><?= $isVn ? 'Bạn đang hoặc sắp đến sân bay nào?' : 'Where are you now?' ?></h3>
                </div>

                <!-- Dynamic Sub-options -->
                <div class="sv-finder-suboptions-grid" id="finderStep2Options">
                    <!-- Populated by JS -->
                </div>

                <!-- Result Recommendation Box -->
                <div class="sv-finder-result-box" id="finderResultBox">
                    <h4 class="sv-finder-result-title" id="finderResultTitle">Airport fast track</h4>
                    <p class="sv-finder-result-desc" id="finderResultDesc">Fast track at Tan Son Nhat.</p>

                    <!-- Replaced price chips with email capture form -->
                    <form class="sv-finder-email-form" id="finderEmailForm" method="POST" action="<?= e(url('')) ?>#route-finder">
                        <input type="hidden" name="action" value="route_finder_lead">
                        <input type="hidden" name="csrf_token" value="<?= e(public_form_csrf_token()) ?>">
                        <input type="hidden" name="selected_service" id="finderHiddenService" value="Airport fast track">
                        <input type="hidden" name="selected_option" id="finderHiddenOption" value="Tan Son Nhat">

                        <div class="sv-finder-form-row">
                            <div class="sv-finder-input-wrap">
                                <i class="fa-regular fa-envelope sv-finder-input-icon"></i>
                                <input 
                                    type="email" 
                                    name="lead_email" 
                                    id="finderEmailInput" 
                                    class="sv-finder-email-input" 
                                    placeholder="<?= $isVn ? 'Nhập email của bạn để nhận tư vấn...' : 'Enter your email for consultation...' ?>" 
                                    required
                                >
                            </div>
                            <button type="submit" class="sv-finder-cta" id="finderResultCta">
                                <span id="finderCtaText"><?= $isVn ? 'Gửi yêu cầu' : 'Send my case' ?></span>
                                <i class="fa-solid fa-arrow-right" style="font-size:12px;"></i>
                            </button>
                        </div>
                        <div class="sv-finder-feedback <?= $routeFinderSuccess ? 'is-success' : ($routeFinderMessage !== '' ? 'is-error' : '') ?>" id="finderFeedback" <?= ($routeFinderSuccess || $routeFinderMessage !== '') ? 'style="display:block;"' : '' ?>>
                            <?= e($routeFinderMessage) ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const isVn = <?= json_encode($isVn) ?>;
    const finderData = {
        'airport-fast-track': {
            step2Title: isVn ? 'Bạn đang hoặc sắp đến sân bay nào?' : 'Where are you now?',
            subOptions: [
                { id: 'sgn', label: 'Tan Son Nhat', loc: 'Tan Son Nhat' },
                { id: 'han', label: 'Noi Bai', loc: 'Noi Bai' },
                { id: 'dad', label: 'Da Nang', loc: 'Da Nang' },
                { id: 'cxr', label: 'Cam Ranh', loc: 'Cam Ranh' },
                { id: 'pqc', label: 'Phu Quoc', loc: 'Phu Quoc' }
            ],
            resultTitle: isVn ? 'Airport Fast-Track' : 'Airport fast track',
            resultDescTemplate: isVn ? 'Dịch vụ đón tiễn ưu tiên tại sân bay {loc}.' : 'Fast track at {loc}.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        },
        'e-visa': {
            step2Title: isVn ? 'Kế hoạch nhập cảnh của bạn?' : 'What is your travel plan?',
            subOptions: [
                { id: 'ev-30', label: isVn ? 'Du lịch (30 ngày)' : 'Tourism (30 days)', loc: isVn ? 'Du lịch 30 ngày' : 'Tourism 30 days' },
                { id: 'ev-90', label: isVn ? 'Du lịch (90 ngày)' : 'Tourism (90 days)', loc: isVn ? 'Du lịch 90 ngày' : 'Tourism 90 days' },
                { id: 'ev-biz', label: isVn ? 'Công tác & Gặp đối tác' : 'Business & Meeting', loc: isVn ? 'Công tác' : 'Business & Meeting' },
                { id: 'ev-urg', label: isVn ? 'Khẩn cấp (1 - 4 giờ)' : 'Emergency (1-4 hrs)', loc: isVn ? 'Khẩn cấp' : 'Emergency' }
            ],
            resultTitle: isVn ? 'Visa điện tử Việt Nam (E-Visa)' : 'Vietnam E-Visa Service',
            resultDescTemplate: isVn ? 'Bảo đảm duyệt nhanh, hỗ trợ thủ tục hồ sơ {loc} chuẩn xác 100%.' : 'Guaranteed fast approval for {loc} travel.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        },
        'work-permit': {
            step2Title: isVn ? 'Tình trạng hiện tại của bạn?' : 'What is your current status?',
            subOptions: [
                { id: 'wp-new', label: isVn ? 'Cấp mới Work Permit' : 'New Work Permit', loc: isVn ? 'cấp mới' : 'New Work Permit' },
                { id: 'wp-renew', label: isVn ? 'Gia hạn Work Permit' : 'Work Permit Renewal', loc: isVn ? 'gia hạn' : 'Work Permit Renewal' },
                { id: 'wp-exempt', label: isVn ? 'Miễn Giấy phép LĐ' : 'Work Permit Exemption', loc: isVn ? 'miễn GPLĐ' : 'Exemption' },
                { id: 'wp-inv', label: isVn ? 'Nhà đầu tư / Chuyên gia' : 'Foreign Investor / Expert', loc: isVn ? 'nhà đầu tư / chuyên gia' : 'Investor / Expert' }
            ],
            resultTitle: isVn ? 'Giấy Phép Lao Động' : 'Work in Vietnam (Work Permit)',
            resultDescTemplate: isVn ? 'Giải pháp pháp lý trọn gói cho hồ sơ {loc}.' : 'Full legal assistance for {loc}.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        },
        'trc': {
            step2Title: isVn ? 'Bạn thuộc diện cư trú nào?' : 'Which category fits you?',
            subOptions: [
                { id: 'trc-fam', label: isVn ? 'Vợ / Chồng / Con người VN' : 'Spouse / Child of VN', loc: isVn ? 'Thăm thân (TT)' : 'Spouse / Family' },
                { id: 'trc-lab', label: isVn ? 'Lao động (Theo Work Permit)' : 'Labor TRC (Work Permit)', loc: isVn ? 'Lao động (LĐ)' : 'Labor TRC' },
                { id: 'trc-inv', label: isVn ? 'Nhà đầu tư (ĐT1 - ĐT4)' : 'Investor (DT1 - DT4)', loc: isVn ? 'Nhà đầu tư' : 'Investor TRC' },
                { id: 'trc-5y', label: isVn ? 'Miễn thị thực 5 năm' : '5-Year Visa Exemption', loc: isVn ? 'Miễn thị thực 5 năm' : '5-Year Exemption' }
            ],
            resultTitle: isVn ? 'Thẻ Tạm Trú (TRC)' : 'Stay long-term (TRC)',
            resultDescTemplate: isVn ? 'Lưu trú hợp pháp 2 - 5 năm ({loc}) không cần xuất cảnh.' : 'Long-term legal residence guidance for {loc}.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        },
        'police-check': {
            step2Title: isVn ? 'Mục đích sử dụng lý lịch tư pháp?' : 'Where will you use the certificate?',
            subOptions: [
                { id: 'pc-wp', label: isVn ? 'Làm Work Permit tại VN' : 'For Vietnam Work Permit', loc: isVn ? 'làm Work Permit' : 'Work Permit in VN' },
                { id: 'pc-abr', label: isVn ? 'Định cư / Du học' : 'Immigration Abroad', loc: isVn ? 'định cư / du học' : 'Immigration' },
                { id: 'pc-urg', label: isVn ? 'Làm nhanh (3 - 5 ngày)' : 'Expedited (3-5 days)', loc: isVn ? 'làm nhanh' : 'Expedited' },
                { id: 'pc-leg', label: isVn ? 'Kèm Hợp pháp hóa & Dịch' : 'With Legalization', loc: isVn ? 'kèm hợp pháp hóa' : 'Legalization' }
            ],
            resultTitle: isVn ? 'Phiếu Lý Lịch Tư Pháp' : 'Police check (Criminal Record)',
            resultDescTemplate: isVn ? 'Cấp phiếu lý lịch tư pháp số 1 & 2 phục vụ {loc}.' : 'Official criminal record issued for {loc}.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        },
        'outbound-visa': {
            step2Title: isVn ? 'Bạn muốn xin visa đi quốc gia nào?' : 'Where do you want to travel?',
            subOptions: [
                { id: 'v-sch', label: isVn ? 'Châu Âu (Schengen)' : 'Schengen / Europe', loc: isVn ? 'Châu Âu (Schengen)' : 'Schengen / Europe' },
                { id: 'v-usa', label: isVn ? 'Hoa Kỳ (Mỹ)' : 'United States', loc: isVn ? 'Hoa Kỳ' : 'United States' },
                { id: 'v-aus', label: isVn ? 'Úc / New Zealand' : 'Australia / NZ', loc: isVn ? 'Úc / New Zealand' : 'Australia' },
                { id: 'v-can', label: isVn ? 'Canada' : 'Canada', loc: 'Canada' },
                { id: 'v-asia', label: isVn ? 'Nhật Bản / Hàn Quốc' : 'Japan / Korea', loc: isVn ? 'Nhật Bản / Hàn Quốc' : 'Japan / Korea' }
            ],
            resultTitle: isVn ? 'Visa Đi Nước Ngoài' : 'Visa to another country',
            resultDescTemplate: isVn ? 'Tư vấn chuẩn bị hồ sơ và luyện phỏng vấn visa {loc}.' : 'Full document preparation and coaching for {loc}.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        },
        'company-help': {
            step2Title: isVn ? 'Doanh nghiệp của bạn cần hỗ trợ gì?' : 'What does your business require?',
            subOptions: [
                { id: 'c-staff', label: isVn ? 'Thủ tục nhân sự nước ngoài' : 'Foreign Employees', loc: isVn ? 'nhân sự nước ngoài' : 'Foreign Employees' },
                { id: 'c-fdi', label: isVn ? 'Thành lập công ty FDI' : 'Company Setup / FDI', loc: isVn ? 'công ty FDI' : 'FDI Company' },
                { id: 'c-legal', label: isVn ? 'Hợp pháp hóa lãnh sự' : 'Legalization & Consular', loc: isVn ? 'hợp pháp hóa' : 'Legalization' },
                { id: 'c-retain', label: isVn ? 'Dịch vụ định kỳ trọn gói' : 'Monthly Retainer Support', loc: isVn ? 'dịch vụ định kỳ' : 'Retainer' }
            ],
            resultTitle: isVn ? 'Hỗ Trợ Doanh Nghiệp (B2B)' : 'Corporate Immigration Support',
            resultDescTemplate: isVn ? 'Chuyên trách thủ tục pháp lý và tư vấn {loc}.' : 'Dedicated legal solutions for {loc}.',
            ctaText: isVn ? 'Gửi yêu cầu' : 'Send my case'
        }
    };

    let currentServiceKey = 'airport-fast-track';
    let currentSubOptionId = 'sgn';

    const step1Buttons = document.querySelectorAll('#finderStep1 .sv-finder-btn');
    const step2TitleEl = document.getElementById('finderStep2Title');
    const step2OptionsEl = document.getElementById('finderStep2Options');
    const resultTitleEl = document.getElementById('finderResultTitle');
    const resultDescEl = document.getElementById('finderResultDesc');
    const hiddenServiceEl = document.getElementById('finderHiddenService');
    const hiddenOptionEl = document.getElementById('finderHiddenOption');
    const emailFormEl = document.getElementById('finderEmailForm');
    const emailInputEl = document.getElementById('finderEmailInput');
    const submitBtnEl = document.getElementById('finderResultCta');
    const ctaTextEl = document.getElementById('finderCtaText');
    const feedbackEl = document.getElementById('finderFeedback');

    function renderStep2(serviceKey, selectedSubId) {
        const data = finderData[serviceKey];
        if (!data) return;

        step2TitleEl.textContent = data.step2Title;

        step2OptionsEl.innerHTML = '';
        const activeSub = (selectedSubId && data.subOptions.find(o => o.id === selectedSubId)) || data.subOptions[0];
        currentSubOptionId = activeSub ? activeSub.id : '';

        data.subOptions.forEach(opt => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'sv-finder-pill' + (opt.id === currentSubOptionId ? ' is-selected' : '');
            btn.textContent = opt.label;
            btn.addEventListener('click', () => {
                currentSubOptionId = opt.id;
                document.querySelectorAll('#finderStep2Options .sv-finder-pill').forEach(b => b.classList.remove('is-selected'));
                btn.classList.add('is-selected');
                updateResult(data, opt);
            });
            step2OptionsEl.appendChild(btn);
        });

        updateResult(data, activeSub);
    }

    function updateResult(data, activeSub) {
        resultTitleEl.textContent = data.resultTitle;
        const loc = activeSub ? activeSub.loc : '';
        resultDescEl.textContent = data.resultDescTemplate.replace('{loc}', loc);

        if (hiddenServiceEl) hiddenServiceEl.value = data.resultTitle;
        if (hiddenOptionEl) hiddenOptionEl.value = loc;
        if (ctaTextEl) ctaTextEl.textContent = data.ctaText;
    }

    step1Buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const key = btn.getAttribute('data-key');
            if (key === currentServiceKey) return;
            currentServiceKey = key;
            step1Buttons.forEach(b => b.classList.remove('is-selected'));
            btn.classList.add('is-selected');
            renderStep2(currentServiceKey);
        });
    });

    if (emailFormEl) {
        emailFormEl.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = emailInputEl ? emailInputEl.value.trim() : '';
            if (!email) return;

            submitBtnEl.disabled = true;
            if (ctaTextEl) ctaTextEl.textContent = isVn ? 'Đang gửi...' : 'Sending...';
            if (feedbackEl) {
                feedbackEl.className = 'sv-finder-feedback';
                feedbackEl.style.display = 'none';
            }

            const formData = new FormData(emailFormEl);

            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (feedbackEl) {
                    feedbackEl.textContent = data.message;
                    if (data.success) {
                        feedbackEl.className = 'sv-finder-feedback is-success';
                        emailInputEl.value = '';
                        if (ctaTextEl) ctaTextEl.textContent = isVn ? 'Đã gửi ✓' : 'Sent ✓';
                        setTimeout(() => {
                            submitBtnEl.disabled = false;
                            const currData = finderData[currentServiceKey];
                            if (ctaTextEl) ctaTextEl.textContent = currData ? currData.ctaText : (isVn ? 'Gửi yêu cầu' : 'Send my case');
                        }, 4000);
                    } else {
                        feedbackEl.className = 'sv-finder-feedback is-error';
                        submitBtnEl.disabled = false;
                        const currData = finderData[currentServiceKey];
                        if (ctaTextEl) ctaTextEl.textContent = currData ? currData.ctaText : (isVn ? 'Gửi yêu cầu' : 'Send my case');
                    }
                }
            })
            .catch(() => {
                if (feedbackEl) {
                    feedbackEl.textContent = isVn ? 'Có lỗi xảy ra, vui lòng thử lại.' : 'An error occurred. Please try again.';
                    feedbackEl.className = 'sv-finder-feedback is-error';
                }
                submitBtnEl.disabled = false;
                const currData = finderData[currentServiceKey];
                if (ctaTextEl) ctaTextEl.textContent = currData ? currData.ctaText : (isVn ? 'Gửi yêu cầu' : 'Send my case');
            });
        });
    }

    renderStep2('airport-fast-track', 'sgn');
})();
</script>

<section class="sv-home-insights">
    <div class="container">
        <div class="sv-section-heading sv-section-heading-split"><div><span class="sv-overline"><?= e(__('news_label')) ?></span><h2><?= e(__('news_title')) ?></h2><p><?= e(__('news_desc')) ?></p></div><a class="sv-inline-link" href="<?= e(url('news')) ?>"><?= e(__('view_news')) ?> <span>↗</span></a></div>
        <div class="sv-insights-grid">
            <?php foreach (array_slice($NEWS, 0, 3) as $article): ?>
                <article class="sv-insight-card reveal"><a href="<?= e(url('article/' . $article['slug'])) ?>"><img src="<?= e(asset_url($article['image'])) ?>" alt="<?= e(field($article, 'title')) ?>"><div><p><span><?= e(field($article, 'category')) ?></span><time><?= e(format_date($article['date'])) ?></time></p><h3><?= e(field($article, 'title')) ?></h3><small><?= e(field($article, 'excerpt')) ?></small><b><?= e(__('read_article')) ?> <em>↗</em></b></div></a></article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
