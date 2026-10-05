<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../common/auth.php';
require_once __DIR__ . '/../common/database.php';

require_admin_login();

$admin = current_admin();

$page_title = 'Dashboard';
$admin_section = 'dashboard';


/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

$pdo = db();


/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/

$stats = [
    'services' => 0,
    'inactive_services' => 0,
    'news' => 0,
    'published_news' => 0,
    'draft_news' => 0,
    'home_media' => 0,
    'news_media' => 0,
    'new_inquiries' => 0,
];


/*
|--------------------------------------------------------------------------
| SERVICES COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM services
        WHERE is_active = 1
    ");

    $stats['services'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['services'] = 0;

}


/*
|--------------------------------------------------------------------------
| INACTIVE SERVICES COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM services
        WHERE is_active = 0
    ");

    $stats['inactive_services'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['inactive_services'] = 0;

}


/*
|--------------------------------------------------------------------------
| NEWS COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM news
    ");

    $stats['news'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['news'] = 0;

}


/*
|--------------------------------------------------------------------------
| DRAFT NEWS COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM news
        WHERE status = 'draft'
    ");

    $stats['draft_news'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['draft_news'] = 0;

}


/*
|--------------------------------------------------------------------------
| PUBLISHED NEWS COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM news
        WHERE status = 'published'
    ");

    $stats['published_news'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['published_news'] = 0;

}


/*
|--------------------------------------------------------------------------
| HOME MEDIA COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM home_media
        WHERE is_active = 1
    ");

    $stats['home_media'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['home_media'] = 0;

}


/*
|--------------------------------------------------------------------------
| NEWS MEDIA COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM news_media
        WHERE is_active = 1
    ");

    $stats['news_media'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['news_media'] = 0;

}


/*
|--------------------------------------------------------------------------
| NEW CUSTOMER REQUESTS COUNT
|--------------------------------------------------------------------------
*/

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM inquiries
        WHERE status = 'new'
    ");

    $stats['new_inquiries'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['new_inquiries'] = 0;

}


/*
|--------------------------------------------------------------------------
| RECENT NEWS
|--------------------------------------------------------------------------
*/

$recent_news = [];

try {

    $stmt = $pdo->query("
        SELECT
            id,
            title,
            excerpt,
            featured_image AS image,
            author,
            status,
            published_at,
            is_featured AS featured
        FROM news
        ORDER BY created_at DESC
        LIMIT 5
    ");

    $recent_news = $stmt->fetchAll();

} catch (Throwable $e) {

    $recent_news = [];

}


/*
|--------------------------------------------------------------------------
| FEATURED / ACTIVE SERVICES
|--------------------------------------------------------------------------
*/

$featured_services = [];

try {

    $stmt = $pdo->query("
        SELECT
            id,
            title,
            category,
            image,
            is_featured AS featured,
            is_active AS active
        FROM services
        WHERE is_active = 1
        ORDER BY is_featured DESC, sort_order ASC, id DESC
        LIMIT 5
    ");

    $featured_services = $stmt->fetchAll();

} catch (Throwable $e) {

    $featured_services = [];

}


/*
|--------------------------------------------------------------------------
| CONTENT ALERTS
|--------------------------------------------------------------------------
*/

$content_alerts = [];

try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM services
        WHERE is_active = 1
          AND (image IS NULL OR TRIM(image) = '')
    ");

    $services_without_image = (int) $stmt->fetchColumn();

    if ($services_without_image > 0) {
        $content_alerts[] = [
            'icon' => 'IMG',
            'title' => $services_without_image . ' active service' . ($services_without_image === 1 ? '' : 's') . ' without a cover image',
            'description' => 'Add an image to improve the service listing.',
            'url' => ADMIN_BASE_URL . '/services/index.php',
            'action' => 'Review',
        ];
    }

} catch (Throwable $e) {

    // The dashboard remains usable when an optional content query fails.

}

if ($stats['draft_news'] > 0) {
    $content_alerts[] = [
        'icon' => 'DR',
        'title' => $stats['draft_news'] . ' article' . ($stats['draft_news'] === 1 ? '' : 's') . ' still in draft',
        'description' => 'Review and publish when the content is ready.',
        'url' => ADMIN_BASE_URL . '/news/manage.php',
        'action' => 'Open',
    ];
}

if ($stats['new_inquiries'] > 0) {
    $content_alerts[] = [
        'icon' => 'RQ',
        'title' => $stats['new_inquiries'] . ' new customer request' . ($stats['new_inquiries'] === 1 ? '' : 's'),
        'description' => 'Review new Contact and Apply Online submissions.',
        'url' => ADMIN_BASE_URL . '/inquiries.php?status=new',
        'action' => 'Open',
    ];
}


try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM services
        WHERE is_active = 1
          AND (title_vn IS NULL OR TRIM(title_vn) = '')
    ");

    $services_without_vietnamese = (int) $stmt->fetchColumn();

    if ($services_without_vietnamese > 0) {
        $content_alerts[] = [
            'icon' => 'VN',
            'title' => $services_without_vietnamese . ' active service' . ($services_without_vietnamese === 1 ? '' : 's') . ' missing Vietnamese content',
            'description' => 'Add a Vietnamese title before promoting the service.',
            'url' => ADMIN_BASE_URL . '/services/index.php',
            'action' => 'Review',
        ];
    }

} catch (Throwable $e) {

    // The dashboard remains usable when an optional content query fails.

}


try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM news
        WHERE status = 'published'
          AND (
              title_vn IS NULL
              OR TRIM(title_vn) = ''
              OR content_vn IS NULL
              OR TRIM(content_vn) = ''
          )
    ");

    $articles_without_vietnamese = (int) $stmt->fetchColumn();

    if ($articles_without_vietnamese > 0) {
        $content_alerts[] = [
            'icon' => 'VN',
            'title' => $articles_without_vietnamese . ' published article' . ($articles_without_vietnamese === 1 ? '' : 's') . ' missing Vietnamese content',
            'description' => 'Complete the Vietnamese title and article body.',
            'url' => ADMIN_BASE_URL . '/news/manage.php',
            'action' => 'Review',
        ];
    }

} catch (Throwable $e) {

    // The dashboard remains usable when an optional content query fails.

}


try {

    $stmt = $pdo->query("
        SELECT COUNT(*)
        FROM news n
        WHERE n.status = 'published'
          AND (n.featured_image IS NULL OR TRIM(n.featured_image) = '')
          AND NOT EXISTS (
              SELECT 1
              FROM news_media nm
              WHERE nm.news_id = n.id
                AND nm.is_active = 1
          )
    ");

    $articles_without_media = (int) $stmt->fetchColumn();

    if ($articles_without_media > 0) {
        $content_alerts[] = [
            'icon' => 'MD',
            'title' => $articles_without_media . ' published article' . ($articles_without_media === 1 ? '' : 's') . ' without media',
            'description' => 'Add a featured image or article media.',
            'url' => ADMIN_BASE_URL . '/news/manage.php',
            'action' => 'Review',
        ];
    }

} catch (Throwable $e) {

    // news_media can be unavailable before its migration is applied.

}

if ($stats['home_media'] === 0) {
    $content_alerts[] = [
        'icon' => 'HM',
        'title' => 'No homepage media is visible',
        'description' => 'Add a hero image or video for the homepage.',
        'url' => ADMIN_BASE_URL . '/home-media.php',
        'action' => 'Add media',
    ];
}


/*
|--------------------------------------------------------------------------
| RECENT MEDIA
|--------------------------------------------------------------------------
*/

$recent_media = [];

try {

    $stmt = $pdo->query("
        SELECT
            media_type,
            file_path,
            title,
            source,
            created_at,
            action_url
        FROM (
            SELECT
                media_type,
                file_path,
                COALESCE(NULLIF(title, ''), section) AS title,
                'Homepage' AS source,
                created_at,
                '" . ADMIN_BASE_URL . "/home-media.php' AS action_url
            FROM home_media

            UNION ALL

            SELECT
                nm.media_type,
                nm.file_path,
                COALESCE(NULLIF(nm.title, ''), n.title) AS title,
                'News' AS source,
                nm.created_at,
                CONCAT('" . ADMIN_BASE_URL . "/news/media.php?id=', nm.news_id) AS action_url
            FROM news_media nm
            INNER JOIN news n ON n.id = nm.news_id
        ) AS media_items
        ORDER BY created_at DESC
        LIMIT 4
    ");

    $recent_media = $stmt->fetchAll();

} catch (Throwable $e) {

    $recent_media = [];

}


/*
|--------------------------------------------------------------------------
| DATABASE STATUS
|--------------------------------------------------------------------------
*/

$database_connected = false;

try {

    $pdo->query("SELECT 1");

    $database_connected = true;

} catch (Throwable $e) {

    $database_connected = false;

}


/*
|--------------------------------------------------------------------------
| STORAGE STATUS
|--------------------------------------------------------------------------
|
| The upload directory will be used later by the CMS.
| We only check it here. We do not create files automatically.
|
*/

$storage_path = dirname(__DIR__) . '/uploads';

$storage_operational =
    is_dir($storage_path)
    && is_writable($storage_path);


/*
|--------------------------------------------------------------------------
| PAGE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/components/header.php';

?>


<div class="admin-layout">


    <?php require __DIR__ . '/components/sidebar.php'; ?>


    <div class="admin-main">


        <?php require __DIR__ . '/components/topbar.php'; ?>


        <main class="admin-content">


            <!-- =========================================================
                 PAGE HEADER
            ========================================================== -->

            <section class="dashboard-header">

                <div>

                    <span class="page-eyebrow">
                        OVERVIEW
                    </span>


                    <h1>
                        Good morning 👋
                    </h1>


                    <p>
                        Manage your website content from one place.
                    </p>

                </div>


                <a
                    href="/VietnamVisa/admin/services/create.php"
                    class="admin-btn admin-btn-primary"
                >
                    + Create Content
                </a>

            </section>



            <!-- =========================================================
                 STAT CARDS
            ========================================================== -->

            <section class="dashboard-stats">


                <!-- SERVICES -->

                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon purple">
                            ◈
                        </div>


                        <span class="stat-status">
                            Active
                        </span>

                    </div>


                    <strong>
                        <?= $stats['services'] ?>
                    </strong>


                    <h3>
                        Services
                    </h3>


                    <p>
                        <?= $stats['inactive_services'] ?> inactive
                    </p>

                </article>



                <!-- NEWS -->

                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon blue">
                            ◫
                        </div>


                        <span class="stat-status">
                            Content
                        </span>

                    </div>


                    <strong>
                        <?= $stats['news'] ?>
                    </strong>


                    <h3>
                        Articles
                    </h3>


                    <p>
                        Total news articles
                    </p>

                </article>



                <!-- HOME MEDIA -->

                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon green">
                            ◉
                        </div>


                        <span class="stat-status">
                            Live
                        </span>

                    </div>


                    <strong>
                        <?= $stats['home_media'] ?>
                    </strong>


                    <h3>
                        Home Media
                    </h3>


                    <p>
                        <?= $stats['news_media'] ?> active article media
                    </p>

                </article>



                <!-- PUBLISHED NEWS -->

                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon orange">
                            ✓
                        </div>


                        <span class="stat-status">
                            New
                        </span>

                    </div>


                    <strong>
                        <?= $stats['new_inquiries'] ?>
                    </strong>


                    <h3>
                        New requests
                    </h3>


                    <p>
                        Contact and application requests
                    </p>

                </article>


            </section>



            <!-- =========================================================
                 MAIN DASHBOARD GRID
            ========================================================== -->

            <section class="dashboard-grid">


                <!-- =====================================================
                     RECENT ARTICLES
                ====================================================== -->

                <div class="dashboard-panel panel-large">


                    <div class="panel-header">

                        <div>

                            <span class="panel-eyebrow">
                                CONTENT
                            </span>


                            <h2>
                                Recent articles
                            </h2>

                        </div>


                        <a
                            href="/VietnamVisa/admin/news/index.php"
                            class="panel-link"
                        >
                            View all →
                        </a>

                    </div>



                    <div class="article-list">


                        <?php if (!empty($recent_news)): ?>


                            <?php foreach ($recent_news as $article): ?>


                                <article class="article-row">


                                    <!-- THUMBNAIL -->

                                    <div class="article-thumbnail">


                                        <?php if (!empty($article['image'])): ?>

                                            <img
                                                src="<?= htmlspecialchars(admin_media_url($article['image']), ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($article['title']) ?>"
                                            >

                                        <?php else: ?>

                                            <div class="article-placeholder">
                                                NEWS
                                            </div>

                                        <?php endif; ?>


                                    </div>



                                    <!-- ARTICLE INFO -->

                                    <div class="article-info">


                                        <h3>
                                            <?= htmlspecialchars($article['title']) ?>
                                        </h3>


                                        <div class="article-meta">


                                            <?php if (!empty($article['author'])): ?>

                                                <span>
                                                    <?= htmlspecialchars($article['author']) ?>
                                                </span>

                                            <?php else: ?>

                                                <span>
                                                    SunViet
                                                </span>

                                            <?php endif; ?>


                                            <span>
                                                •
                                            </span>


                                            <?php if (!empty($article['published_at'])): ?>

                                                <span>
                                                    <?= date(
                                                        'd M Y',
                                                        strtotime($article['published_at'])
                                                    ) ?>
                                                </span>

                                            <?php else: ?>

                                                <span>
                                                    Not published
                                                </span>

                                            <?php endif; ?>


                                        </div>


                                    </div>



                                    <!-- STATUS -->

                                    <?php if ($article['status'] === 'published'): ?>

                                        <span class="status-badge published">
                                            Published
                                        </span>

                                    <?php else: ?>

                                        <span class="status-badge draft">
                                            Draft
                                        </span>

                                    <?php endif; ?>



                                    <!-- ACTION -->

                                    <a
                                        href="/VietnamVisa/admin/news/edit.php?id=<?= (int) $article['id'] ?>"
                                        class="row-action"
                                    >
                                        Edit
                                    </a>


                                </article>


                            <?php endforeach; ?>


                        <?php else: ?>


                            <div class="empty-state">

                                <p>
                                    No articles found.
                                </p>


                                <a
                                    href="/VietnamVisa/admin/news/create.php"
                                    class="admin-btn admin-btn-secondary"
                                >
                                    Create first article
                                </a>

                            </div>


                        <?php endif; ?>


                    </div>


                </div>



                <!-- =====================================================
                     QUICK ACTIONS
                ====================================================== -->

                <div class="dashboard-panel">


                    <div class="panel-header">

                        <div>

                            <span class="panel-eyebrow">
                                SHORTCUTS
                            </span>


                            <h2>
                                Quick actions
                            </h2>

                        </div>

                    </div>



                    <div class="quick-actions">


                        <!-- ADD SERVICE -->

                        <a
                            href="/VietnamVisa/admin/services/create.php"
                            class="quick-action"
                        >

                            <span class="quick-action-icon purple">
                                +
                            </span>


                            <span>

                                <strong>
                                    Add Service
                                </strong>


                                <small>
                                    Create a new service
                                </small>

                            </span>


                            <span class="quick-arrow">
                                →
                            </span>

                        </a>



                        <!-- WRITE ARTICLE -->

                        <a
                            href="/VietnamVisa/admin/news/create.php"
                            class="quick-action"
                        >

                            <span class="quick-action-icon blue">
                                +
                            </span>


                            <span>

                                <strong>
                                    Write Article
                                </strong>


                                <small>
                                    Publish a new article
                                </small>

                            </span>


                            <span class="quick-arrow">
                                →
                            </span>

                        </a>



                        <!-- MANAGE HOME -->

                        <a
                            href="<?= ADMIN_BASE_URL ?>/home-media.php"
                            class="quick-action"
                        >

                            <span class="quick-action-icon green">
                                +
                            </span>


                            <span>

                                <strong>
                                    Manage Home
                                </strong>


                                <small>
                                    Update homepage media
                                </small>

                            </span>


                            <span class="quick-arrow">
                                →
                            </span>

                        </a>



                        <!-- VIEW REQUESTS -->

                        <a
                            href="<?= ADMIN_BASE_URL ?>/inquiries.php?status=new"
                            class="quick-action"
                        >

                            <span class="quick-action-icon orange">
                                R
                            </span>


                            <span>

                                <strong>
                                    View Requests
                                </strong>


                                <small>
                                    Review customer submissions
                                </small>

                            </span>


                            <span class="quick-arrow">
                                &rarr;
                            </span>

                        </a>


                    </div>


                </div>


            </section>



            <!-- =========================================================
                 BOTTOM GRID
            ========================================================== -->

            <section class="dashboard-bottom">


                <!-- =====================================================
                     SERVICES
                ====================================================== -->

                <div class="dashboard-panel">


                    <div class="panel-header">

                        <div>

                            <span class="panel-eyebrow">
                                SERVICES
                            </span>


                            <h2>
                                Popular services
                            </h2>

                        </div>


                        <a
                            href="/VietnamVisa/admin/services/index.php"
                            class="panel-link"
                        >
                            Manage →
                        </a>

                    </div>



                    <div class="simple-list">


                        <?php if (!empty($featured_services)): ?>


                            <?php foreach ($featured_services as $service): ?>


                                <div class="simple-list-row">


                                    <!-- SERVICE ICON / IMAGE -->

                                    <div class="simple-list-icon">


                                        <?php if (!empty($service['image'])): ?>

                                            <img
                                                src="<?= htmlspecialchars(admin_media_url($service['image']), ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($service['title']) ?>"
                                            >

                                        <?php else: ?>

                                            <?= htmlspecialchars(
                                                strtoupper(
                                                    substr(
                                                        $service['title'],
                                                        0,
                                                        1
                                                    )
                                                )
                                            ) ?>

                                        <?php endif; ?>


                                    </div>



                                    <!-- SERVICE INFO -->

                                    <div>


                                        <strong>
                                            <?= htmlspecialchars($service['title']) ?>
                                        </strong>


                                        <small>
                                            <?= htmlspecialchars($service['category']) ?>
                                        </small>


                                    </div>



                                    <!-- STATUS -->

                                    <?php if (!empty($service['featured'])): ?>

                                        <span class="status-dot active">
                                            Featured
                                        </span>

                                    <?php else: ?>

                                        <span class="status-dot active">
                                            Active
                                        </span>

                                    <?php endif; ?>


                                </div>


                            <?php endforeach; ?>


                        <?php else: ?>


                            <div class="empty-state">

                                <p>
                                    No active services found.
                                </p>


                                <a
                                    href="/VietnamVisa/admin/services/create.php"
                                    class="admin-btn admin-btn-secondary"
                                >
                                    Add Service
                                </a>

                            </div>


                        <?php endif; ?>


                    </div>


                </div>



                <!-- =====================================================
                     CONTENT ATTENTION
                ====================================================== -->

                <div class="dashboard-panel">

                    <div class="panel-header">

                        <div>

                            <span class="panel-eyebrow">
                                CONTENT HEALTH
                            </span>

                            <h2>
                                Needs attention
                            </h2>

                        </div>

                        <span class="panel-count">
                            <?= count($content_alerts) ?> item<?= count($content_alerts) === 1 ? '' : 's' ?>
                        </span>

                    </div>

                    <div class="attention-list">

                        <?php if (!empty($content_alerts)): ?>

                            <?php foreach ($content_alerts as $alert): ?>

                                <a
                                    href="<?= htmlspecialchars($alert['url'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="attention-row"
                                >

                                    <span class="attention-icon">
                                        <?= htmlspecialchars($alert['icon'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                    <span>

                                        <strong>
                                            <?= htmlspecialchars($alert['title'], ENT_QUOTES, 'UTF-8') ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars($alert['description'], ENT_QUOTES, 'UTF-8') ?>
                                        </small>

                                    </span>

                                    <span class="attention-action">
                                        <?= htmlspecialchars($alert['action'], ENT_QUOTES, 'UTF-8') ?>
                                    </span>

                                </a>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="dashboard-empty-state">

                                <span class="dashboard-empty-icon">
                                    &#10003;
                                </span>

                                <div>

                                    <strong>
                                        Content is in good shape
                                    </strong>

                                    <p>
                                        There are no draft or media alerts to review.
                                    </p>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- =====================================================
                     RECENT MEDIA
                ====================================================== -->

                <div class="dashboard-panel">

                    <div class="panel-header">

                        <div>

                            <span class="panel-eyebrow">
                                MEDIA LIBRARY
                            </span>

                            <h2>
                                Recent media
                            </h2>

                        </div>

                        <a
                            href="<?= ADMIN_BASE_URL ?>/home-media.php"
                            class="panel-link"
                        >
                            Manage &rarr;
                        </a>

                    </div>

                    <div class="media-list">

                        <?php if (!empty($recent_media)): ?>

                            <?php foreach ($recent_media as $media): ?>

                                <a
                                    href="<?= htmlspecialchars($media['action_url'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="media-list-row"
                                >

                                    <span class="media-thumb">

                                        <?php if ($media['media_type'] === 'image'): ?>

                                            <img
                                                src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>"
                                                alt="<?= htmlspecialchars($media['title'], ENT_QUOTES, 'UTF-8') ?>"
                                            >

                                        <?php else: ?>

                                            VID

                                        <?php endif; ?>

                                    </span>

                                    <span>

                                        <strong>
                                            <?= htmlspecialchars($media['title'], ENT_QUOTES, 'UTF-8') ?>
                                        </strong>

                                        <small>
                                            <?= htmlspecialchars($media['source'], ENT_QUOTES, 'UTF-8') ?>
                                            &bull;
                                            <?= htmlspecialchars(ucfirst($media['media_type']), ENT_QUOTES, 'UTF-8') ?>
                                        </small>

                                    </span>

                                    <span class="quick-arrow">
                                        &rarr;
                                    </span>

                                </a>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="dashboard-empty-state">

                                <span class="dashboard-empty-icon">
                                    +
                                </span>

                                <div>

                                    <strong>
                                        No media added yet
                                    </strong>

                                    <p>
                                        Add photos or videos to your homepage and articles.
                                    </p>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- =====================================================
                     WEBSITE STATUS
                ====================================================== -->

                <div class="dashboard-panel">


                    <div class="panel-header">

                        <div>

                            <span class="panel-eyebrow">
                                SYSTEM
                            </span>


                            <h2>
                                Website status
                            </h2>

                        </div>

                    </div>



                    <div class="system-status">


                        <!-- WEBSITE -->

                        <div class="system-status-item">


                            <div class="system-status-icon success">
                                ✓
                            </div>


                            <div>

                                <strong>
                                    Website
                                </strong>


                                <span>
                                    Online
                                </span>

                            </div>


                            <b>
                                Operational
                            </b>


                        </div>



                        <!-- DATABASE -->

                        <div class="system-status-item">


                            <?php if ($database_connected): ?>


                                <div class="system-status-icon success">
                                    ✓
                                </div>


                                <div>

                                    <strong>
                                        Database
                                    </strong>


                                    <span>
                                        Connected
                                    </span>

                                </div>


                                <b>
                                    Operational
                                </b>


                            <?php else: ?>


                                <div class="system-status-icon error">
                                    !
                                </div>


                                <div>

                                    <strong>
                                        Database
                                    </strong>


                                    <span>
                                        Connection failed
                                    </span>

                                </div>


                                <b>
                                    Error
                                </b>


                            <?php endif; ?>


                        </div>



                        <!-- STORAGE -->

                        <div class="system-status-item">


                            <?php if ($storage_operational): ?>


                                <div class="system-status-icon success">
                                    ✓
                                </div>


                                <div>

                                    <strong>
                                        Storage
                                    </strong>


                                    <span>
                                        Media storage
                                    </span>

                                </div>


                                <b>
                                    Operational
                                </b>


                            <?php else: ?>


                                <div class="system-status-icon error">
                                    !
                                </div>


                                <div>

                                    <strong>
                                        Storage
                                    </strong>


                                    <span>
                                        Upload directory unavailable
                                    </span>

                                </div>


                                <b>
                                    Check
                                </b>


                            <?php endif; ?>


                        </div>


                    </div>


                </div>


            </section>


        </main>


    </div>


</div>



<?php

require __DIR__ . '/components/footer.php';

?>
