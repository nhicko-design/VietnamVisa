<header class="admin-topbar">


    <div class="topbar-left">

        <button
            type="button"
            class="mobile-menu-btn"
            data-sidebar-toggle
            aria-label="Open menu"
        >
            ☰
        </button>


        <div class="breadcrumb">

            <span>
                Admin
            </span>

            <span>
                /
            </span>

            <strong>
                <?= htmlspecialchars($page_title ?? 'Dashboard') ?>
            </strong>

        </div>

    </div>


    <div class="topbar-right">


        <a
            href="../en"
            target="_blank"
            class="topbar-website"
        >
            View website ↗
        </a>


        <button
            type="button"
            class="notification-btn"
            aria-label="Notifications"
        >

            ♧

            <span class="notification-dot"></span>

        </button>


        <div class="topbar-user">

            <div class="user-avatar">
                A
            </div>

            <div class="user-info">

                <strong>
                    Admin
                </strong>

                <span>
                    Super Admin
                </span>

            </div>

            <span class="user-chevron">
                ▾
            </span>

        </div>

    </div>

</header>