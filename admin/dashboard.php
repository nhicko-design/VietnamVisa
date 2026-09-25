<?php

$page_title = 'Dashboard';

require __DIR__ . '/components/header.php';

?>

<div class="admin-layout">

    <?php require __DIR__ . '/components/sidebar.php'; ?>


    <div class="admin-main">

        <?php require __DIR__ . '/components/topbar.php'; ?>


        <main class="admin-content">


            <!-- PAGE HEADER -->

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
                    href="#"
                    class="admin-btn admin-btn-primary"
                >
                    + Create Content
                </a>

            </section>


            <!-- STAT CARDS -->

            <section class="dashboard-stats">


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
                        12
                    </strong>

                    <h3>
                        Services
                    </h3>

                    <p>
                        Published services
                    </p>

                </article>


                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon blue">
                            ◫
                        </div>

                        <span class="stat-status">
                            Published
                        </span>

                    </div>

                    <strong>
                        24
                    </strong>

                    <h3>
                        Articles
                    </h3>

                    <p>
                        Published articles
                    </p>

                </article>


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
                        8
                    </strong>

                    <h3>
                        Pages
                    </h3>

                    <p>
                        Website pages
                    </p>

                </article>


                <article class="stat-card">

                    <div class="stat-card-top">

                        <div class="stat-icon orange">
                            ?
                        </div>

                        <span class="stat-status">
                            Active
                        </span>

                    </div>

                    <strong>
                        18
                    </strong>

                    <h3>
                        FAQs
                    </h3>

                    <p>
                        Questions and answers
                    </p>

                </article>


            </section>


            <!-- MAIN DASHBOARD GRID -->

            <section class="dashboard-grid">


                <!-- RECENT ARTICLES -->

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
                            href="#"
                            class="panel-link"
                        >
                            View all →
                        </a>

                    </div>


                    <div class="article-list">


                        <article class="article-row">

                            <div class="article-thumbnail">
                                <img
                                    src="https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=300&q=80"
                                    alt="Vietnam"
                                >
                            </div>

                            <div class="article-info">

                                <h3>
                                    Vietnam Visa Guide for First-Time Travelers
                                </h3>

                                <div class="article-meta">

                                    <span>
                                        Visa Guide
                                    </span>

                                    <span>
                                        •
                                    </span>

                                    <span>
                                        Sep 18, 2026
                                    </span>

                                </div>

                            </div>

                            <span class="status-badge published">
                                Published
                            </span>

                            <button
                                type="button"
                                class="row-action"
                            >
                                Edit
                            </button>

                        </article>


                        <article class="article-row">

                            <div class="article-thumbnail">
                                <img
                                    src="https://images.unsplash.com/photo-1559592413-7cec4d0cae2b?auto=format&fit=crop&w=300&q=80"
                                    alt="Vietnam travel"
                                >
                            </div>

                            <div class="article-info">

                                <h3>
                                    New Immigration Website Experience
                                </h3>

                                <div class="article-meta">

                                    <span>
                                        News
                                    </span>

                                    <span>
                                        •
                                    </span>

                                    <span>
                                        Sep 12, 2026
                                    </span>

                                </div>

                            </div>

                            <span class="status-badge draft">
                                Draft
                            </span>

                            <button
                                type="button"
                                class="row-action"
                            >
                                Edit
                            </button>

                        </article>


                        <article class="article-row">

                            <div class="article-thumbnail">
                                <img
                                    src="https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=300&q=80"
                                    alt="Vietnam travel"
                                >
                            </div>

                            <div class="article-info">

                                <h3>
                                    Vietnam Business Visa Updates
                                </h3>

                                <div class="article-meta">

                                    <span>
                                        Business Visa
                                    </span>

                                    <span>
                                        •
                                    </span>

                                    <span>
                                        Sep 08, 2026
                                    </span>

                                </div>

                            </div>

                            <span class="status-badge published">
                                Published
                            </span>

                            <button
                                type="button"
                                class="row-action"
                            >
                                Edit
                            </button>

                        </article>


                    </div>

                </div>


                <!-- QUICK ACTIONS -->

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


                        <a
                            href="#"
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


                        <a
                            href="#"
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


                        <a
                            href="#"
                            class="quick-action"
                        >

                            <span class="quick-action-icon green">
                                +
                            </span>

                            <span>

                                <strong>
                                    Edit Page
                                </strong>

                                <small>
                                    Update website content
                                </small>

                            </span>

                            <span class="quick-arrow">
                                →
                            </span>

                        </a>


                    </div>

                </div>


            </section>


            <!-- BOTTOM GRID -->

            <section class="dashboard-bottom">


                <!-- SERVICES -->

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
                            href="#"
                            class="panel-link"
                        >
                            Manage →
                        </a>

                    </div>


                    <div class="simple-list">


                        <div class="simple-list-row">

                            <div class="simple-list-icon">
                                V
                            </div>

                            <div>

                                <strong>
                                    Vietnam E-Visa
                                </strong>

                                <small>
                                    Visa
                                </small>

                            </div>

                            <span class="status-dot active">
                                Published
                            </span>

                        </div>


                        <div class="simple-list-row">

                            <div class="simple-list-icon">
                                B
                            </div>

                            <div>

                                <strong>
                                    Business Visa
                                </strong>

                                <small>
                                    Visa
                                </small>

                            </div>

                            <span class="status-dot active">
                                Published
                            </span>

                        </div>


                        <div class="simple-list-row">

                            <div class="simple-list-icon">
                                W
                            </div>

                            <div>

                                <strong>
                                    Work Permit
                                </strong>

                                <small>
                                    Support
                                </small>

                            </div>

                            <span class="status-dot draft">
                                Draft
                            </span>

                        </div>


                    </div>

                </div>


                <!-- WEBSITE STATUS -->

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


                        <div class="system-status-item">

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

                        </div>


                        <div class="system-status-item">

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