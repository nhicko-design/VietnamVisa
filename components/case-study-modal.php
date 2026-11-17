<?php
// Shared Case Study Interactive Detail Modal
// Requires $caseStudyServices and $isVn to be defined before including.
?>
<!-- ==========================================================================
     INTERACTIVE CASE STUDY DETAIL MODAL DIALOG
     ========================================================================== -->
<div class="sv-modal-backdrop" id="caseStudyModal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="sv-modal-dialog">
        <button type="button" class="sv-modal-close-btn" id="caseModalClose" aria-label="Close modal">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Modal Hero Header -->
        <div class="sv-modal-hero" id="modalHeroBg">
            <div class="sv-modal-hero-overlay"></div>
            <div class="sv-modal-hero-content">
                <div class="sv-modal-hero-meta">
                    <span class="sv-cs-badge" id="modalTag"></span>
                    <span class="sv-cs-time-pill" id="modalTime"></span>
                    <span class="sv-cs-time-pill" id="modalClient" style="background: rgba(15, 23, 42, 0.75);"></span>
                </div>
                <h3 class="sv-modal-hero-title" id="modalTitle"></h3>
            </div>
        </div>

        <!-- Scrollable Body -->
        <div class="sv-modal-body">
            <!-- 1. Special Feature Box -->
            <div class="sv-modal-special-box">
                <div class="sv-modal-sec-header">
                    <i class="fa-solid fa-star text-gold"></i>
                    <span><?= $isVn ? 'ĐIỂM ĐẶC BIỆT CỦA CASE NÀY' : 'WHAT MAKES THIS CASE SPECIAL' ?></span>
                </div>
                <p id="modalSpecial"></p>
            </div>

            <!-- 2. Dilemma vs Outcome Grid -->
            <div class="sv-modal-grid-2col">
                <div class="sv-modal-col-card sv-modal-col-dilemma">
                    <div class="sv-modal-sec-header">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span><?= $isVn ? 'Bối cảnh & Thách thức ban đầu' : 'Initial Dilemma & Risk' ?></span>
                    </div>
                    <p id="modalProblem"></p>
                </div>
                <div class="sv-modal-col-card sv-modal-col-outcome">
                    <div class="sv-modal-sec-header">
                        <i class="fa-solid fa-circle-check"></i>
                        <span><?= $isVn ? 'Kết quả đạt được' : 'Verified Outcome' ?></span>
                    </div>
                    <p id="modalResult"></p>
                </div>
            </div>

            <!-- 3. Steps -->
            <div class="sv-modal-steps-box">
                <div class="sv-modal-sec-header">
                    <i class="fa-solid fa-list-check"></i>
                    <span><?= $isVn ? 'QUY TRÌNH THỰC CHIẾN SUNVIET XỬ LÝ' : 'SUNVIET EXECUTION ROADMAP' ?></span>
                </div>
                <div id="modalStepsList"></div>
            </div>

            <!-- 4. Expert Tip -->
            <div class="sv-modal-tip-box">
                <div class="sv-modal-sec-header">
                    <i class="fa-solid fa-lightbulb"></i>
                    <span><?= $isVn ? 'LỜI KHUYÊN & LƯU Ý TỪ CHUYÊN GIA SUNVIET' : 'EXPERT ADVICE & TAKEAWAY' ?></span>
                </div>
                <p id="modalTip"></p>
            </div>
        </div>

        <!-- Sticky Bottom Footer Action Bar -->
        <div class="sv-modal-footer">
            <span class="sv-modal-footer-copy">
                <i class="fa-solid fa-shield-halved text-gold"></i>
                <?= $isVn ? 'SunViet cam kết bảo mật 100% thông tin & hồ sơ khách hàng.' : 'SunViet guarantees 100% confidentiality of client records.' ?>
            </span>
            <div class="sv-modal-footer-btns">
                <a href="#" id="modalWaBtn" target="_blank" rel="noopener noreferrer" class="sv-modal-btn-wa">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span><?= $isVn ? 'Tư vấn tình huống này' : 'Consult on this case' ?></span>
                </a>
                <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', SITE_PHONE)) ?>" class="sv-modal-btn-phone">
                    <i class="fa-solid fa-phone"></i>
                    <span><?= e(SITE_PHONE) ?></span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const svCasesData = <?= json_encode($caseStudyServices, JSON_UNESCAPED_UNICODE) ?>;
    const isVnMode = <?= $isVn ? 'true' : 'false' ?>;

    const modal = document.getElementById('caseStudyModal');
    const closeBtn = document.getElementById('caseModalClose');
    const heroBg = document.getElementById('modalHeroBg');
    const tag = document.getElementById('modalTag');
    const time = document.getElementById('modalTime');
    const client = document.getElementById('modalClient');
    const title = document.getElementById('modalTitle');
    const special = document.getElementById('modalSpecial');
    const problem = document.getElementById('modalProblem');
    const result = document.getElementById('modalResult');
    const stepsList = document.getElementById('modalStepsList');
    const tip = document.getElementById('modalTip');
    const waBtn = document.getElementById('modalWaBtn');

    function openCaseModal(item) {
        if (!modal || !item) return;

        heroBg.style.backgroundImage = 'linear-gradient(180deg, rgba(7, 27, 49, 0.45) 0%, rgba(7, 27, 49, 0.94) 100%), url("' + item.image + '")';
        tag.className = 'sv-cs-badge sv-cs-badge-' + item.tag_type;
        tag.textContent = item.tag;
        time.textContent = item.time;
        client.textContent = item.client_origin;
        title.textContent = item.title;
        special.textContent = item.special_point;
        problem.textContent = item.problem_detail;
        result.textContent = item.result;
        tip.textContent = item.expert_tip;

        let stepsHtml = '<ol class="sv-modal-steps-list">';
        if (Array.isArray(item.steps)) {
            item.steps.forEach((step, idx) => {
                const numStr = (idx + 1 < 10 ? '0' : '') + (idx + 1);
                stepsHtml += `
                    <li class="sv-modal-step-item">
                        <span class="sv-modal-step-num">${numStr}</span>
                        <p class="sv-modal-step-text">${step}</p>
                    </li>
                `;
            });
        }
        stepsHtml += '</ol>';
        stepsList.innerHTML = stepsHtml;

        const waPrefix = isVnMode ? 'Chào SunViet, tôi cần tư vấn về trường hợp: ' : 'Hi SunViet, I need consultation on case: ';
        waBtn.href = 'https://wa.me/84901234567?text=' + encodeURIComponent(waPrefix + item.title);

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeCaseModal() {
        if (!modal) return;
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.sv-cs-article-card').forEach(card => {
        const handler = () => {
            const svcKey = card.getAttribute('data-service');
            const idx = parseInt(card.getAttribute('data-index'), 10);
            if (svCasesData[svcKey] && svCasesData[svcKey].cases && svCasesData[svcKey].cases[idx]) {
                openCaseModal(svCasesData[svcKey].cases[idx]);
            }
        };

        card.addEventListener('click', handler);
        card.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                handler();
            }
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeCaseModal);
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeCaseModal();
            }
        });
    }

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) {
            closeCaseModal();
        }
    });
})();
</script>
