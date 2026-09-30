<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../common/auth.php';
require_once __DIR__ . '/../common/database.php';

require_admin_login();

$admin = current_admin();

$page_title = 'Dashboard';


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
    'news' => 0,
    'published_news' => 0,
    'home_media' => 0,
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
        WHERE active = 1
    ");

    $stats['services'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['services'] = 0;

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
        WHERE active = 1
    ");

    $stats['home_media'] = (int) $stmt->fetchColumn();

} catch (Throwable $e) {

    $stats['home_media'] = 0;

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
            image,
            author,
            status,
            published_at,
            featured
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
            featured,
            active
        FROM services
        WHERE active = 1
        ORDER BY featured DESC, id DESC
        LIMIT 5
    ");

    $featured_services = $stmt->fetchAll();

} catch (Throwable $e) {

    $featured_services = [];

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
                        Active services
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
                        Active homepage media
                    </p>

                </article>



                <!-- PUBLISHED NEWS -->

                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon orange">
                            ✓
                        </div>


                        <span class="stat-status">
                            Published
                        </span>

                    </div>


                    <strong>
                        <?= $stats['published_news'] ?>
                    </strong>


                    <h3>
                        Published
                    </h3>


                    <p>
                        Published articles
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
                                                src="<?= htmlspecialchars($article['image']) ?>"
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
                            href="/VietnamVisa/admin/home/index.php"
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
                                                src="<?= htmlspecialchars($service['image']) ?>"
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