<div class="sticky-card">
    <span class="eyebrow">
        <?= $isVietnamese ? 'Tư vấn visa quốc tế' : 'International visa consultation' ?>
    </span>

    <h3>
        <?= $isVietnamese ? 'Chưa chắc nên chọn diện nào?' : 'Not sure which route to choose?' ?>
    </h3>

    <p>
        <?= $isVietnamese ? 'Chia sẻ điểm đến và kế hoạch chuyến đi để đội ngũ SunViet hỗ trợ định hướng hồ sơ.' : 'Tell us your destination and travel plan so the SunViet team can help guide your application.' ?>
    </p>

    <a class="btn btn-primary btn-full" href="<?= e(url('contact')) ?>">
        <?= e($contactLabel) ?>
    </a>

    <a class="btn btn-secondary btn-full" href="<?= e(url('apply')) ?>">
        <?= e($applyLabel) ?>
    </a>
</div>
