<?php require_once __DIR__ . '/../components/media-hero.php'; ?>
<section class="article-hero">
    <div class="container article-hero-inner">
        <a class="back-link" href="<?= e(url('news')) ?>">
            &larr; <?= __('news_back') ?>
        </a>
        <div class="article-meta">
            <span><?= e(field($article, 'category')) ?></span>
            <span><?= __('news_by') ?> <?= e(format_date($article['date'])) ?></span>
        </div>
        <h1><?= e(field($article, 'title')) ?></h1>
        <p><?= e(field($article, 'excerpt')) ?></p>
        <div class="article-hero-media">
            <?php render_media_hero(
                $article['media'] ?? [],
                (string) ($article['image'] ?? ''),
                field($article, 'title')
            ); ?>
        </div>
    </div>
</section>

<section class="article-content">
    <div class="container article-layout">
        <article>
            <div class="rich-content">
                <?= rich_content_html(field($article, 'body')) ?>
            </div>

            <div class="article-end">
                <a class="text-link" href="<?= e(url('news')) ?>">
                    &larr; <?= __('news_back') ?>
                </a>
            </div>
        </article>

        <aside>
            <div class="sticky-card">
                <span class="eyebrow"><?= __('latest_news') ?></span>
                <?php foreach (array_slice($NEWS, 0, 3) as $item): ?>
                    <?php if ($item['slug'] === $article['slug']) continue; ?>
                    <a class="side-news" href="<?= e(url('article/' . $item['slug'])) ?>">
                        <small><?= e(format_date($item['date'])) ?></small>
                        <strong><?= e(field($item, 'title')) ?></strong>
                    </a>
                <?php endforeach; ?>
            </div>
        </aside>
    </div>
</section>
