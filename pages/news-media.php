<?php

$newsMedia = $article['media'] ?? [];
$newsImages = [];
$newsVideos = [];

foreach ($newsMedia as $media) {
    if (($media['media_type'] ?? '') === 'image') {
        $newsImages[] = $media;
    } elseif (($media['media_type'] ?? '') === 'video') {
        $newsVideos[] = $media;
    }
}

if (!$newsImages && !$newsVideos) {
    return;
}

$mediaHeading = $current_lang === 'vn' ? 'Hình ảnh và video' : 'Images & Videos';
$mediaDescription = $current_lang === 'vn'
    ? 'Khám phá thêm hình ảnh và video liên quan đến bài viết.'
    : 'Explore additional images and videos related to this article.';

?>
<section class="section section-white news-media-section">
    <div class="container">
        <div class="section-intro">
            <span class="eyebrow">MEDIA</span>
            <h2 class="section-title"><?= e($mediaHeading) ?></h2>
            <p><?= e($mediaDescription) ?></p>
        </div>

        <?php if ($newsImages): ?>
            <div class="service-media-grid">
                <?php foreach ($newsImages as $media): ?>
                    <figure class="service-media-item">
                        <img
                            src="<?= e(asset_url((string) $media['file_path'])) ?>"
                            alt="<?= e((string) ($media['alt_text'] ?: $media['title'] ?: field($article, 'title'))) ?>"
                        >

                        <?php if (!empty($media['title'])): ?>
                            <figcaption><?= e($media['title']) ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($newsVideos): ?>
            <div class="service-video-list">
                <?php foreach ($newsVideos as $media): ?>
                    <div class="service-video-item">
                        <video controls preload="metadata">
                            <source src="<?= e(asset_url((string) $media['file_path'])) ?>">
                            Your browser does not support HTML5 video.
                        </video>

                        <?php if (!empty($media['title'])): ?>
                            <h3><?= e($media['title']) ?></h3>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
