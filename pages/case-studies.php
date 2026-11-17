<?php
$isVn = ($current_lang ?? 'en') === 'vn';
require_once __DIR__ . '/../data/case-studies.php';
$caseStudyServices = get_case_study_services($isVn);

$requestedService = trim((string) ($_GET['service'] ?? 'all'));
if ($requestedService !== 'all' && !isset($caseStudyServices[$requestedService])) {
    $requestedService = 'all';
}

$totalAllCases = 0;
foreach ($caseStudyServices as $svc) {
    $totalAllCases += count($svc['cases']);
}
?>

<!-- Hero Header -->
<section class="sv-cs-page-hero">
    <div class="container">
        <div class="sv-cs-page-hero-inner">
            <div class="sv-cs-breadcrumb">
                <a href="<?= e(url('')) ?>"><?= $isVn ? 'Trang chủ' : 'Home' ?></a>
                <span>/</span>
                <span><?= $isVn ? 'Thư viện Case Study' : 'Case Studies' ?></span>
            </div>
            <h1><?= $isVn ? 'Thư Viện Case Study & Giải Pháp Thực Chiến' : 'Real-World Case Studies & Proven Solutions' ?></h1>
            <p>
                <?= $isVn
                    ? "Khám phá toàn bộ {$totalAllCases} tình huống xuất nhập cảnh, visa, giấy phép lao động và thẻ tạm trú thực tế mà SunViet đã xử lý thành công. Bấm vào từng bài viết để xem điểm đặc biệt và quy trình giải quyết."
                    : "Explore all {$totalAllCases} real-world Vietnam immigration, visa, work permit, and residency cases solved by SunViet. Click any article to view what made it special and our step-by-step execution."
                ?>
            </p>
            <div class="sv-cs-search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="search"
                    id="csSearchInput"
                    placeholder="<?= $isVn ? 'Tìm theo tình huống, quốc tịch, hoặc từ khóa (vd: Overstay, Fast-Track, DICA...)' : 'Search by scenario, nationality, or keyword (e.g., Overstay, Fast-Track, DICA...)' ?>"
                    aria-label="<?= $isVn ? 'Tìm kiếm Case Study' : 'Search Case Studies' ?>"
                >
            </div>
        </div>
    </div>
</section>

<!-- Archive Grid Section -->
<section class="sv-cs-archive-section">
    <div class="container">
        <!-- Service Filter Pills -->
        <div class="sv-cs-filter-bar" role="tablist" aria-label="Filter case studies by service">
            <button
                type="button"
                class="sv-cs-filter-pill <?= $requestedService === 'all' ? 'is-active' : '' ?>"
                data-filter="all"
            >
                <i class="fa-solid fa-layer-group"></i>
                <span><?= $isVn ? "Tất cả ({$totalAllCases})" : "All Services ({$totalAllCases})" ?></span>
            </button>

            <?php foreach ($caseStudyServices as $svcKey => $svc): ?>
                <button
                    type="button"
                    class="sv-cs-filter-pill <?= $requestedService === $svcKey ? 'is-active' : '' ?>"
                    data-filter="<?= e($svcKey) ?>"
                >
                    <i class="<?= e($svc['icon']) ?>" style="color: <?= e($svc['color']) ?>;"></i>
                    <span><?= e($svc['title']) ?> (<?= count($svc['cases']) ?>)</span>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Full Case Study Cards Grid -->
        <div class="sv-cs-grid" id="csArchiveGrid">
            <?php foreach ($caseStudyServices as $svcKey => $svc): ?>
                <?php foreach ($svc['cases'] as $cIdx => $case):
                    $isVisible = ($requestedService === 'all' || $requestedService === $svcKey);
                    $searchable = mb_strtolower(
                        $svc['title'] . ' ' . $case['tag'] . ' ' . $case['title'] . ' ' . $case['excerpt'] . ' ' . $case['special_point'] . ' ' . $case['client_origin'],
                        'UTF-8'
                    );
                ?>
                    <article
                        class="sv-cs-article-card"
                        data-service="<?= e($svcKey) ?>"
                        data-index="<?= $cIdx ?>"
                        data-search="<?= e($searchable) ?>"
                        style="<?= $isVisible ? '' : 'display: none;' ?>"
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
            <?php endforeach; ?>
        </div>

        <div class="sv-cs-empty-state" id="csEmptyState">
            <i class="fa-solid fa-magnifying-glass mb-2" style="font-size: 28px; color: #94a3b8; display: block;"></i>
            <p style="margin: 0; font-weight: 600;">
                <?= $isVn ? 'Không tìm thấy case study phù hợp với từ khóa bạn nhập.' : 'No case studies matched your search query.' ?>
            </p>
        </div>

        <!-- Bottom Consultation Banner -->
        <div class="sv-cs-bottom-banner" style="margin-top: 38px;">
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
    const filterPills = document.querySelectorAll('.sv-cs-filter-pill');
    const searchInput = document.getElementById('csSearchInput');
    const cards = document.querySelectorAll('#csArchiveGrid .sv-cs-article-card');
    const emptyState = document.getElementById('csEmptyState');

    let activeFilter = <?= json_encode($requestedService) ?>;
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const svcParam = urlParams.get('service');
        if (svcParam && document.querySelector(`.sv-cs-filter-pill[data-filter="${svcParam}"]`)) {
            activeFilter = svcParam;
            filterPills.forEach(p => {
                p.classList.toggle('is-active', p.getAttribute('data-filter') === activeFilter);
            });
        }
    } catch (e) {}

    function applyFilters() {
        const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
        let visibleCount = 0;

        cards.forEach(card => {
            const svc = card.getAttribute('data-service') || '';
            const searchStr = card.getAttribute('data-search') || '';
            const matchService = (activeFilter === 'all' || svc === activeFilter);
            const matchQuery = (!query || searchStr.includes(query));

            if (matchService && matchQuery) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (emptyState) {
            emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    filterPills.forEach(pill => {
        pill.addEventListener('click', () => {
            activeFilter = pill.getAttribute('data-filter') || 'all';
            filterPills.forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');
            applyFilters();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    applyFilters();
})();
</script>
