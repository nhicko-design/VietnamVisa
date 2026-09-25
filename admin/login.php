<?php

$page_title = 'Admin Login';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($page_title) ?> - SunViet Admin
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="assets/css/admin.css"
    >
</head>

<body class="admin-auth-page">

    <main class="auth-wrapper">

        <div class="auth-card">

            <a
                href="../en"
                class="auth-brand"
            >
                <span class="auth-brand-mark">
                    S
                </span>

                <span class="auth-brand-name">
                    SunViet
                </span>
            </a>


            <div class="auth-heading">

                <span class="auth-eyebrow">
                    ADMINISTRATION
                </span>

                <h1>
                    Welcome back
                </h1>

                <p>
                    Sign in to manage your website content.
                </p>

            </div>


            <form
                class="auth-form"
                action="#"
                method="post"
            >

                <div class="form-field">

                    <label for="email">
                        Email address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="admin@example.com"
                        autocomplete="email"
                        required
                    >

                </div>


                <div class="form-field">

                    <div class="field-label-row">

                        <label for="password">
                            Password
                        </label>

                        <a href="#">
                            Forgot password?
                        </a>

                    </div>

                    <div class="password-field">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            aria-label="Show password"
                            data-password-toggle
                        >
                            Show
                        </button>

                    </div>

                </div>


                <label class="checkbox-field">

                    <input
                        type="checkbox"
                        name="remember"
                    >

                    <span>
                        Remember me
                    </span>

                </label>


                <button
                    type="submit"
                    class="admin-btn admin-btn-primary admin-btn-full"
                >
                    Sign in
                </button>

            </form>


            <div class="auth-footer">

                <a href="../en">
                    ← Back to website
                </a>

            </div>

        </div>


        <div class="auth-side">

            <div class="auth-side-content">

                <span class="auth-side-label">
                    SUNVIET
                </span>

                <h2>
                    Manage your website
                    <span>with ease.</span>
                </h2>

                <p>
                    Manage services, articles, pages and
                    website content from one simple dashboard.
                </p>

                <div class="auth-side-points">

                    <div>
                        <span>✓</span>
                        Easy content management
                    </div>

                    <div>
                        <span>✓</span>
                        Manage services and articles
                    </div>

                    <div>
                        <span>✓</span>
                        Multi-language content
                    </div>

                </div>

            </div>

        </div>

    </main>


    <script src="assets/js/admin.js"></script>

</body>

</html>