<?php

if (!function_exists('render_media_hero')) {
    function render_media_hero(array $media, string $fallbackImage, string $title): void
    {
        $slides = [];

        foreach ($media as $item) {
            if (!in_array($item['media_type'] ?? '', ['image', 'video'], true)) {
                continue;
            }
            if (empty($item['file_path'])) {
                continue;
            }
            $slides[] = $item;
        }

        if (!$slides && $fallbackImage !== '') {
            $slides[] = [
                'media_type' => 'image',
                'file_path' => $fallbackImage,
                'title' => $title,
                'alt_text' => $title,
            ];
        }

        if (!$slides) {
            return;
        }
        ?>
        <div class="media-hero" data-media-hero>
            <div class="media-hero-track">
                <?php foreach ($slides as $index => $slide): ?>
                    <figure class="media-hero-slide <?= $index === 0 ? 'is-active' : '' ?>" data-media-slide>
                        <?php if ($slide['media_type'] === 'video'): ?>
                            <video controls muted playsinline preload="metadata">
                                <source src="<?= e(asset_url((string) $slide['file_path'])) ?>">
                            </video>
                        <?php else: ?>
                            <img
                                src="<?= e(asset_url((string) $slide['file_path'])) ?>"
                                alt="<?= e((string) ($slide['alt_text'] ?: $slide['title'] ?: $title)) ?>"
                                <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                            >
                        <?php endif; ?>

                        <?php if (!empty($slide['title'])): ?>
                            <figcaption><?= e((string) $slide['title']) ?></figcaption>
                        <?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>

            <?php if (count($slides) > 1): ?>
                <button class="media-hero-arrow media-hero-prev" type="button" data-media-prev aria-label="Previous media">&larr;</button>
                <button class="media-hero-arrow media-hero-next" type="button" data-media-next aria-label="Next media">&rarr;</button>
                <div class="media-hero-dots" aria-label="Choose media">
                    <?php foreach ($slides as $index => $slide): ?>
                        <button
                            type="button"
                            class="<?= $index === 0 ? 'is-active' : '' ?>"
                            data-media-dot="<?= $index ?>"
                            aria-label="Media <?= $index + 1 ?>"
                        ></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}
