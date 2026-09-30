<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../common/auth.php';


/**
 * If already logged in,
 * go directly to dashboard.
 */
if (is_admin_logged_in()) {

    header(
        'Location: /VietnamVisa/admin/dashboard.php'
    );

    exit;
}


$error = '';

$email = '';


/**
 * ============================================================
 * HANDLE LOGIN
 * ============================================================
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim(
        $_POST['email'] ?? ''
    );

    $password = $_POST['password'] ?? '';

    $csrf = $_POST['csrf_token'] ?? '';


    /**
     * Check CSRF.
     */
    if (!verify_csrf_token($csrf)) {

        $error = 'Your session has expired. Please try again.';

    } else {

        $result = login_admin(
            $email,
            $password
        );

        if ($result['success']) {

            header(
                'Location: /VietnamVisa/admin/dashboard.php'
            );

            exit;
        }

        $error = $result['message'];
    }
}

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
        Sign In — SunViet Admin
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

    <style>

        :root {

            --purple: #673de6;
            --purple-dark: #5428d5;

            --dark: #111114;
            --dark-soft: #1b1b21;

            --text: #17171b;
            --muted: #74747d;

            --border: #e7e7eb;

            --white: #ffffff;

        }


        * {
            box-sizing: border-box;
        }


        html,
        body {
            margin: 0;
            padding: 0;

            min-height: 100%;

            font-family:
                "DM Sans",
                Arial,
                sans-serif;

            background: #f6f6f8;

            color: var(--text);
        }


        body {
            min-height: 100vh;
        }


        .login-page {

            min-height: 100vh;

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                minmax(480px, 0.85fr);

        }


        /* =====================================================
           LEFT
        ===================================================== */

        .login-form-side {

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 50px;

            background: #ffffff;

        }


        .login-box {

            width: 100%;

            max-width: 430px;

        }


        .brand {

            display: inline-flex;

            align-items: center;

            gap: 11px;

            margin-bottom: 52px;

            text-decoration: none;

            color: var(--text);

        }


        .brand-mark {

            width: 40px;

            height: 40px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #673de6,
                    #8b68f1
                );

            color: white;

            font-size: 18px;

            font-weight: 800;

        }


        .brand-text {

            font-family:
                "Manrope",
                sans-serif;

            font-size: 19px;

            font-weight: 800;

        }


        .brand-text span {

            color: var(--purple);

        }


        .login-title {

            margin: 0 0 10px;

            font-family:
                "Manrope",
                sans-serif;

            font-size: 38px;

            line-height: 1.15;

            letter-spacing: -1.2px;

        }


        .login-subtitle {

            margin: 0 0 34px;

            color: var(--muted);

            font-size: 15px;

            line-height: 1.7;

        }


        /* =====================================================
           ERROR
        ===================================================== */

        .alert-error {

            display: flex;

            gap: 10px;

            align-items: flex-start;

            margin-bottom: 22px;

            padding: 13px 15px;

            border: 1px solid #ffd1d1;

            border-radius: 10px;

            background: #fff4f4;

            color: #a52222;

            font-size: 14px;

            line-height: 1.5;

        }


        /* =====================================================
           FORM
        ===================================================== */

        .form-group {

            margin-bottom: 20px;

        }


        .form-label {

            display: block;

            margin-bottom: 8px;

            font-size: 14px;

            font-weight: 600;

        }


        .input-wrap {

            position: relative;

        }


        .form-input {

            width: 100%;

            height: 52px;

            padding:
                0 15px;

            border: 1px solid var(--border);

            border-radius: 10px;

            outline: none;

            background: #ffffff;

            color: var(--text);

            font-family: inherit;

            font-size: 15px;

            transition:
                border-color .2s ease,
                box-shadow .2s ease;

        }


        .form-input:focus {

            border-color: var(--purple);

            box-shadow:
                0 0 0 4px
                rgba(103, 61, 230, .10);

        }


        .password-input {

            padding-right: 54px;

        }


        .password-toggle {

            position: absolute;

            top: 50%;

            right: 14px;

            transform: translateY(-50%);

            border: 0;

            background: transparent;

            cursor: pointer;

            color: #85858e;

            font-size: 13px;

            font-weight: 600;

        }


        .password-toggle:hover {

            color: var(--purple);

        }


        .form-options {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin: 3px 0 24px;

            font-size: 13px;

        }


        .remember {

            display: flex;

            gap: 8px;

            align-items: center;

            color: #666670;

        }


        .remember input {

            width: 15px;

            height: 15px;

            accent-color: var(--purple);

        }


        .forgot {

            color: var(--purple);

            text-decoration: none;

            font-weight: 600;

        }


        .forgot:hover {

            text-decoration: underline;

        }


        .login-button {

            width: 100%;

            height: 54px;

            border: 0;

            border-radius: 11px;

            background: var(--purple);

            color: #ffffff;

            cursor: pointer;

            font-family: inherit;

            font-size: 15px;

            font-weight: 700;

            transition:
                transform .2s ease,
                background .2s ease;

        }


        .login-button:hover {

            background: var(--purple-dark);

            transform: translateY(-1px);

        }


        .back-link {

            display: block;

            margin-top: 24px;

            text-align: center;

            color: #777780;

            font-size: 14px;

            text-decoration: none;

        }


        .back-link:hover {

            color: var(--purple);

        }


        /* =====================================================
           RIGHT
        ===================================================== */

        .login-visual {

            position: relative;

            overflow: hidden;

            display: flex;

            align-items: center;

            padding: 70px;

            background: var(--dark);

            color: white;

        }


        .glow {

            position: absolute;

            width: 420px;

            height: 420px;

            border-radius: 50%;

            filter: blur(10px);

            opacity: .45;

        }


        .glow-one {

            top: -140px;

            right: -120px;

            background:
                radial-gradient(
                    circle,
                    #673de6 0%,
                    transparent 68%
                );

        }


        .glow-two {

            bottom: -180px;

            left: -150px;

            background:
                radial-gradient(
                    circle,
                    #8b68f1 0%,
                    transparent 68%
                );

        }


        .visual-content {

            position: relative;

            z-index: 2;

            max-width: 520px;

        }


        .visual-eyebrow {

            display: inline-block;

            margin-bottom: 24px;

            padding: 8px 12px;

            border: 1px solid
                rgba(255,255,255,.15);

            border-radius: 999px;

            background:
                rgba(255,255,255,.06);

            color: #d7cdfb;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: .7px;

            text-transform: uppercase;

        }


        .visual-title {

            margin: 0 0 22px;

            font-family:
                "Manrope",
                sans-serif;

            font-size: clamp(
                42px,
                4vw,
                68px
            );

            line-height: 1.02;

            letter-spacing: -2.5px;

        }


        .visual-title span {

            color: #a98df6;

        }


        .visual-text {

            margin: 0;

            max-width: 470px;

            color: #a7a7b0;

            font-size: 16px;

            line-height: 1.8;

        }


        .visual-card {

            margin-top: 45px;

            padding: 20px;

            border: 1px solid
                rgba(255,255,255,.09);

            border-radius: 18px;

            background:
                rgba(255,255,255,.05);

            backdrop-filter: blur(12px);

        }


        .visual-card-row {

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 12px 0;

        }


        .visual-dot {

            width: 9px;

            height: 9px;

            border-radius: 50%;

            background: #8b68f1;

            box-shadow:
                0 0 16px
                rgba(139,104,241,.7);

        }


        .visual-card-row span {

            color: #d8d8df;

            font-size: 14px;

        }


        @media (max-width: 950px) {

            .login-page {

                grid-template-columns: 1fr;

            }

            .login-visual {

                display: none;

            }

            .login-form-side {

                padding: 30px 20px;

            }

        }


        @media (max-width: 520px) {

            .login-title {

                font-size: 32px;

            }

            .brand {

                margin-bottom: 38px;

            }

        }

    </style>

</head>


<body>

<div class="login-page">

    <!-- =====================================================
         LOGIN FORM
    ====================================================== -->

    <section class="login-form-side">

        <div class="login-box">

            <a
                href="/VietnamVisa/en"
                class="brand"
            >

                <span class="brand-mark">
                    S
                </span>

                <span class="brand-text">
                    Sun<span>Viet</span>
                </span>

            </a>


            <h1 class="login-title">
                Welcome back
            </h1>


            <p class="login-subtitle">
                Sign in to manage your website
                content, services and articles.
            </p>


            <?php if ($error): ?>

                <div class="alert-error">

                    <span>⚠</span>

                    <span>
                        <?= htmlspecialchars(
                            $error,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>

                </div>

            <?php endif; ?>


            <form
                method="POST"
                action=""
                autocomplete="on"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(
                        csrf_token(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >


                <div class="form-group">

                    <label
                        class="form-label"
                        for="email"
                    >
                        Email address
                    </label>

                    <input
                        id="email"
                        class="form-input"
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars(
                            $email,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        placeholder="admin@sunviet.local"
                        autocomplete="username"
                        required
                        autofocus
                    >

                </div>


                <div class="form-group">

                    <label
                        class="form-label"
                        for="password"
                    >
                        Password
                    </label>

                    <div class="input-wrap">

                        <input
                            id="password"
                            class="form-input password-input"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                        >
                            Show
                        </button>

                    </div>

                </div>


                <div class="form-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                        >

                        Remember me

                    </label>


                    <a
                        href="#"
                        class="forgot"
                        onclick="return false;"
                    >
                        Forgot password?
                    </a>

                </div>


                <button
                    type="submit"
                    class="login-button"
                >
                    Sign in
                </button>

            </form>


            <a
                href="/VietnamVisa/en"
                class="back-link"
            >
                ← Back to website
            </a>

        </div>

    </section>


    <!-- =====================================================
         VISUAL SIDE
    ====================================================== -->

    <section class="login-visual">

        <div class="glow glow-one"></div>

        <div class="glow glow-two"></div>


        <div class="visual-content">

            <div class="visual-eyebrow">
                SunViet Admin
            </div>


            <h2 class="visual-title">

                Manage your
                <span>digital experience.</span>

            </h2>


            <p class="visual-text">

                Manage services, articles,
                FAQs and website content from
                one simple workspace.

            </p>


            <div class="visual-card">

                <div class="visual-card-row">

                    <span class="visual-dot"></span>

                    <span>
                        Services management
                    </span>

                </div>


                <div class="visual-card-row">

                    <span class="visual-dot"></span>

                    <span>
                        News & articles
                    </span>

                </div>


                <div class="visual-card-row">

                    <span class="visual-dot"></span>

                    <span>
                        Pages & FAQ
                    </span>

                </div>

            </div>

        </div>

    </section>

</div>


<script>

    const passwordInput =
        document.getElementById('password');

    const passwordToggle =
        document.getElementById('passwordToggle');


    if (
        passwordInput &&
        passwordToggle
    ) {

        passwordToggle.addEventListener(
            'click',
            function () {

                const isPassword =
                    passwordInput.type === 'password';

                passwordInput.type =
                    isPassword
                        ? 'text'
                        : 'password';

                passwordToggle.textContent =
                    isPassword
                        ? 'Hide'
                        : 'Show';

            }
        );

    }

</script>

</body>

</html>