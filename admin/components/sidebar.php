<aside
    class="admin-sidebar"
    data-sidebar
>

    <div class="sidebar-top">


        <!-- BRAND -->

        <a
            href="<?= htmlspecialchars(ADMIN_BASE_URL . '/dashboard.php', ENT_QUOTES, 'UTF-8') ?>"
            class="admin-brand"
        >

            <img
                class="admin-brand-logo"
                src="<?= htmlspecialchars(dirname(ADMIN_BASE_URL) . '/assets/images/sunviet-logo.png', ENT_QUOTES, 'UTF-8') ?>"
                alt="SunViet"
            >

            <span class="admin-brand-text">
                SunViet
                <small>
                    ADMIN
                </small>
            </span>

        </a>


        <!-- NAVIGATION -->

        <nav class="sidebar-nav">


            <div class="nav-group">

                <span class="nav-group-title">
                    MAIN
                </span>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/dashboard.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'dashboard' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ◉
                    </span>

                    <span>
                        Dashboard
                    </span>

                </a>

            </div>


            <div class="nav-group">

                <span class="nav-group-title">
                    WEBSITE
                </span>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/vietnam-visa.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'vietnam-visa' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ◇
                    </span>

                    <span>
                        Vietnam Visa
                    </span>

                </a>

                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/temporary-resident-card.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'temporary-resident-card' ? 'active' : '' ?>"
                >
                    <span class="sidebar-icon">T</span>
                    <span>Resident Card</span>
                </a>

                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/other-countries-visa.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'other-countries-visa' ? 'active' : '' ?>"
                >
                    <span class="sidebar-icon">G</span>
                    <span>Other Countries Visa</span>
                </a>

                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/other-services.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'other-services' ? 'active' : '' ?>"
                >
                    <span class="sidebar-icon">S</span>
                    <span>Other Services</span>
                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/news/manage.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'news' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ▤
                    </span>

                    <span>
                        News
                    </span>

                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/inquiries.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'inquiries' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        R
                    </span>

                    <span>
                        Requests
                    </span>

                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/faqs.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'faqs' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ?
                    </span>

                    <span>
                        FAQ
                    </span>

                </a>

            </div>


            <div class="nav-group">

                <span class="nav-group-title">
                    CONTENT
                </span>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/pages.php?page=home', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'pages' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        H
                    </span>

                    <span>
                        Homepage
                    </span>

                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/pages.php?page=about', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'pages' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        A
                    </span>

                    <span>
                        About
                    </span>

                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/pages.php?page=visa', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'pages' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        V
                    </span>

                    <span>
                        Vietnam Visa Content
                    </span>

                </a>

                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/pages.php?page=support', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'pages' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        S
                    </span>

                    <span>
                        Support Content
                    </span>

                </a>

            </div>


            <div class="nav-group">

                <span class="nav-group-title">
                    SYSTEM
                </span>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/settings.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'settings' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ⚙
                    </span>

                    <span>
                        Settings
                    </span>

                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/users.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'users' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ♙
                    </span>

                    <span>
                        Admin Users
                    </span>

                </a>

            </div>


        </nav>

    </div>


    <!-- SIDEBAR FOOTER -->

    <div class="sidebar-footer">

        <a
            href="<?= htmlspecialchars(dirname(ADMIN_BASE_URL) . '/en', ENT_QUOTES, 'UTF-8') ?>"
            target="_blank"
            class="sidebar-footer-link"
        >

            <span>
                ↗
            </span>

            View Website

        </a>


        <a
            href="<?= htmlspecialchars(ADMIN_BASE_URL . '/logout.php', ENT_QUOTES, 'UTF-8') ?>"
            class="sidebar-footer-link logout"
        >

            <span>
                ⇥
            </span>

            Logout

        </a>

    </div>

</aside>
