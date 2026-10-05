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

            <span class="admin-brand-mark">
                S
            </span>

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
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/pages.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'pages' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        □
                    </span>

                    <span>
                        Pages
                    </span>

                </a>


                <a
                    href="<?= htmlspecialchars(ADMIN_BASE_URL . '/services/index.php', ENT_QUOTES, 'UTF-8') ?>"
                    class="sidebar-link <?= ($admin_section ?? '') === 'services' ? 'active' : '' ?>"
                >

                    <span class="sidebar-icon">
                        ◇
                    </span>

                    <span>
                        Services
                    </span>

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
                    href="#"
                    class="sidebar-link"
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
                        Visa
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
                        Support
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
            href="/VietnamVisa/admin/logout.php"
            class="sidebar-footer-link logout"
        >

            <span>
                ⇥
            </span>

            Logout

        </a>

    </div>

</aside>
