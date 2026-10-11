<?php

require_once __DIR__ . '/../config.php';

require_admin_login();
require_once __DIR__ . '/../media-upload.php';

$pdo = db();

$errors = [];

$old = [
    'category' => 'visa',
    'slug' => '',
    'title' => '',
    'short_description' => '',
    'description' => '',
    'title_vn' => '',
    'short_description_vn' => '',
    'description_vn' => '',
    'price' => '',
    'currency' => 'USD',
    'image' => '',
    'icon' => '',
    'is_featured' => 0,
    'is_active' => 1,
    'sort_order' => 0,
];

$managementScope = (string) ($_GET['scope'] ?? $_POST['scope'] ?? '');
$managementUrl = ADMIN_BASE_URL . '/services/manage.php'
    . ($managementScope ? '?scope=' . rawurlencode($managementScope) : '');

if ($managementScope === 'vietnam-visa') {
    $old['category'] = 'visa';
} elseif ($managementScope === 'other-services') {
    $old['category'] = 'support';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = array_merge($errors, admin_media_upload_errors($_FILES['media_files'] ?? []));

    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    }

    $old['category'] = $managementScope === 'vietnam-visa'
        ? 'visa'
        : ($managementScope === 'other-services' ? 'support' : trim($_POST['category'] ?? ''));

    $old['slug'] = trim(
        $_POST['slug'] ?? ''
    );

    $old['title'] = trim(
        $_POST['title'] ?? ''
    );

    $old['short_description'] = trim(
        $_POST['short_description'] ?? ''
    );

    $old['description'] = trim(
        $_POST['description'] ?? ''
    );

    $old['title_vn'] = trim($_POST['title_vn'] ?? '');
    $old['short_description_vn'] = trim($_POST['short_description_vn'] ?? '');
    $old['description_vn'] = trim($_POST['description_vn'] ?? '');

    $old['price'] = trim(
        $_POST['price'] ?? ''
    );

    $old['currency'] = trim(
        $_POST['currency'] ?? 'USD'
    );

    $old['image'] = trim(
        $_POST['image'] ?? ''
    );

    $old['icon'] = trim(
        $_POST['icon'] ?? ''
    );

    $old['is_featured'] = isset(
        $_POST['is_featured']
    ) ? 1 : 0;

    $old['is_active'] = isset(
        $_POST['is_active']
    ) ? 1 : 0;

    $old['sort_order'] = trim(
        $_POST['sort_order'] ?? '0'
    );


    /*
     * ========================================================
     * VALIDATION
     * ========================================================
     */

    if (
        !in_array(
            $old['category'],
            ['visa', 'immigration', 'support'],
            true
        )
    ) {
        $errors[] = 'Please select a valid category.';
    }


    if ($old['title'] === '') {
        $errors[] = 'Title is required.';
    }

    if ($old['title_vn'] === '') {
        $errors[] = 'Vietnamese title is required.';
    }


    if ($old['slug'] === '') {
        $errors[] = 'Slug is required.';
    }


    if (
        $old['price'] !== '' &&
        !is_numeric($old['price'])
    ) {
        $errors[] = 'Price must be a valid number.';
    }


    if (
        $old['sort_order'] !== '' &&
        !is_numeric($old['sort_order'])
    ) {
        $errors[] = 'Sort order must be a number.';
    }


    /*
     * ========================================================
     * SLUG NORMALIZATION
     * ========================================================
     */

    if ($old['slug'] !== '') {

        $old['slug'] = strtolower(
            trim($old['slug'])
        );

        $old['slug'] = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $old['slug']
        );

        $old['slug'] = trim(
            $old['slug'],
            '-'
        );

        if ($old['slug'] === '') {
            $errors[] = 'Slug must contain letters or numbers.';
        }
    }


    /*
     * ========================================================
     * CHECK SLUG
     * ========================================================
     */

    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                SELECT id
                FROM services
                WHERE slug = :slug
                LIMIT 1
            ");

            $stmt->execute([
                'slug' => $old['slug'],
            ]);

            if ($stmt->fetch()) {

                $errors[] =
                    'This slug already exists. Please use another slug.';
            }


        } catch (Throwable $e) {

            $errors[] =
                'Unable to check the service slug.';
        }
    }


    /*
     * ========================================================
     * INSERT SERVICE
     * ========================================================
     */

    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO services
                (
                    category,
                    slug,
                    title,
                    short_description,
                    description,
                    title_vn,
                    short_description_vn,
                    description_vn,
                    price,
                    currency,
                    image,
                    icon,
                    is_featured,
                    is_active,
                    sort_order,
                    created_at,
                    updated_at
                )
                VALUES
                (
                    :category,
                    :slug,
                    :title,
                    :short_description,
                    :description,
                    :title_vn,
                    :short_description_vn,
                    :description_vn,
                    :price,
                    :currency,
                    :image,
                    :icon,
                    :is_featured,
                    :is_active,
                    :sort_order,
                    NOW(),
                    NOW()
                )
            ");


            $price = (
                $old['price'] !== ''
            )
                ? $old['price']
                : null;


            $sortOrder = (
                $old['sort_order'] !== ''
            )
                ? (int) $old['sort_order']
                : 0;


            $stmt->execute([

                'category' =>
                    $old['category'],

                'slug' =>
                    $old['slug'],

                'title' =>
                    $old['title'],

                'short_description' =>
                    $old['short_description'] !== ''
                        ? $old['short_description']
                        : null,

                'description' =>
                    $old['description'] !== ''
                        ? $old['description']
                        : null,

                'title_vn' => $old['title_vn'],
                'short_description_vn' => $old['short_description_vn'] !== ''
                    ? $old['short_description_vn']
                    : null,
                'description_vn' => $old['description_vn'] !== ''
                    ? $old['description_vn']
                    : null,

                'price' =>
                    $price,

                'currency' =>
                    $old['currency'] !== ''
                        ? $old['currency']
                        : 'USD',

                'image' =>
                    $old['image'] !== ''
                        ? $old['image']
                        : null,

                'icon' =>
                    $old['icon'] !== ''
                        ? $old['icon']
                        : null,

                'is_featured' =>
                    $old['is_featured'],

                'is_active' =>
                    $old['is_active'],

                'sort_order' =>
                    $sortOrder,
            ]);

            $newServiceId = (int) $pdo->lastInsertId();

            if (!empty($_FILES['media_files']['name'])) {
                $firstImage = admin_save_media_uploads($pdo, $_FILES['media_files'], 'service', $newServiceId);

                if ($firstImage && $old['image'] === '') {
                    $pdo->prepare('UPDATE services SET image = ? WHERE id = ?')->execute([$firstImage, $newServiceId]);
                }
            }


            /*
             * Redirect after successful insert.
             */
            header(
                'Location: ' .
                ADMIN_BASE_URL .
                '/services/manage.php?created=1' .
                ($managementScope ? '&scope=' . rawurlencode($managementScope) : '')
            );

            exit;


        } catch (Throwable $e) {

            $errors[] = 'Unable to create service. Please try again.';
        }
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
        Add Service - SunViet Admin
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f5f6fa;

            color: #222;
        }


        .page {

            min-height: 100vh;
        }


        /*
         * ====================================================
         * TOPBAR
         * ====================================================
         */

        .topbar {

            height: 70px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;

            background: #ffffff;

            border-bottom:
                1px solid #e5e5e5;
        }


        .brand {

            font-size: 22px;

            font-weight: 700;

            color: #673de6;
        }


        .admin-info {

            display: flex;

            align-items: center;

            gap: 15px;

            font-size: 14px;
        }


        .admin-name {

            font-weight: 600;
        }


        .logout {

            text-decoration: none;

            color: #c62828;

            font-weight: 600;
        }


        /*
         * ====================================================
         * LAYOUT
         * ====================================================
         */

        .layout {

            display: flex;

            min-height:
                calc(100vh - 70px);
        }


        .sidebar {

            width: 240px;

            flex-shrink: 0;

            background: #171719;

            padding: 25px 15px;
        }


        .sidebar-title {

            padding:
                0 15px 20px;

            color: #888;

            font-size: 12px;

            text-transform: uppercase;

            letter-spacing: 1px;
        }


        .nav-link {

            display: block;

            padding: 12px 15px;

            margin-bottom: 5px;

            border-radius: 8px;

            color: #ddd;

            text-decoration: none;

            font-size: 14px;
        }


        .nav-link:hover,
        .nav-link.active {

            background: #673de6;

            color: #fff;
        }


        /*
         * ====================================================
         * CONTENT
         * ====================================================
         */

        .content {

            flex: 1;

            padding: 30px;

            max-width: 1200px;
        }


        .page-header {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 25px;
        }


        .page-title {

            margin: 0;

            font-size: 28px;
        }


        .page-description {

            margin:
                7px 0 0;

            color: #777;

            font-size: 14px;
        }


        /*
         * ====================================================
         * BUTTONS
         * ====================================================
         */

        .btn {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding:
                11px 18px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;

            border: 0;
        }


        .btn-primary {

            background: #673de6;

            color: #fff;
        }


        .btn-primary:hover {

            background: #5630c7;
        }


        .btn-secondary {

            background: #eeeeee;

            color: #333;
        }


        /*
         * ====================================================
         * CARD
         * ====================================================
         */

        .card {

            background: #fff;

            border:
                1px solid #e7e7e7;

            border-radius: 12px;

            padding: 30px;

            margin-bottom: 20px;
        }


        /*
         * ====================================================
         * FORM
         * ====================================================
         */

        .form-grid {

            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 20px;
        }


        .form-group {

            margin-bottom: 20px;
        }


        .form-group.full {

            grid-column:
                1 / -1;
        }


        label {

            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: 600;

            color: #333;
        }


        .required {

            color: #c62828;
        }


        input,
        select,
        textarea {

            width: 100%;

            padding:
                11px 13px;

            border:
                1px solid #dcdcdc;

            border-radius: 8px;

            background: #fff;

            font-family: inherit;

            font-size: 14px;

            outline: none;
        }


        input:focus,
        select:focus,
        textarea:focus {

            border-color: #673de6;

            box-shadow:
                0 0 0 3px
                rgba(103, 61, 230, .08);
        }


        textarea {

            min-height: 120px;

            resize: vertical;
        }


        textarea.description {

            min-height: 260px;
        }


        .help {

            margin-top: 6px;

            color: #888;

            font-size: 12px;

            line-height: 1.5;
        }


        /*
         * ====================================================
         * CHECKBOX
         * ====================================================
         */

        .checkbox-row {

            display: flex;

            gap: 25px;

            align-items: center;

            padding-top: 8px;
        }


        .checkbox-item {

            display: flex;

            align-items: center;

            gap: 8px;

            font-size: 14px;

            cursor: pointer;
        }


        .checkbox-item input {

            width: auto;

            margin: 0;
        }


        /*
         * ====================================================
         * ERRORS
         * ====================================================
         */

        .error-box {

            margin-bottom: 20px;

            padding: 14px 16px;

            background: #fff0f0;

            border:
                1px solid #ffd0d0;

            border-radius: 8px;

            color: #c62828;

            font-size: 14px;

            line-height: 1.6;
        }


        /*
         * ====================================================
         * FORM ACTIONS
         * ====================================================
         */

        .form-actions {

            display: flex;

            justify-content: flex-end;

            gap: 10px;

            padding-top: 10px;
        }


        @media (max-width: 800px) {

            .sidebar {

                width: 190px;
            }


            .content {

                padding: 20px;
            }


            .form-grid {

                grid-template-columns:
                    1fr;
            }


            .form-group.full {

                grid-column:
                    auto;
            }

        }

    </style>

<link rel="stylesheet" href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/admin.css?v=' . filemtime(__DIR__ . '/../assets/css/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="../assets/css/management.css">

</head>


<body class="admin-body">

<div class="admin-layout">

    <?php
    $admin_section = 'services';
    require __DIR__ . '/../components/sidebar.php';
    ?>

    <div class="admin-main">

        <div class="page service-create-page">


    <!-- =====================================================
         TOPBAR
         ===================================================== -->

    <header class="topbar">

        <div class="brand">
            SunViet Admin
        </div>


        <div class="admin-info">

            <span class="admin-name">

                <?= htmlspecialchars(
                    current_admin()['name']
                    ?? 'Administrator'
                ) ?>

            </span>


            <a
                class="logout"
                href="<?= ADMIN_BASE_URL ?>/logout.php"
            >
                Logout
            </a>

        </div>

    </header>


    <div class="layout">


        <!-- =================================================
             MAIN CONTENT
             ================================================= -->

        <main class="content">


            <div class="page-header">

                <div>

                    <h1 class="page-title">
                        Add Service
                    </h1>

                    <p class="page-description">
                        Create a new visa or support service.
                    </p>

                </div>


                <a
                    class="btn btn-secondary"
                    href="<?= htmlspecialchars($managementUrl, ENT_QUOTES, 'UTF-8') ?>"
                >
                    ← Back to Services
                </a>

            </div>


            <!-- =================================================
                 ERRORS
                 ================================================= -->

            <?php if (!empty($errors)): ?>

                <div class="error-box">

                    <?php foreach ($errors as $error): ?>

                        <div>
                            •
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 FORM
                 ================================================= -->

            <form
                method="POST"
                action=""
                enctype="multipart/form-data"
            >

                <input type="hidden" name="scope" value="<?= htmlspecialchars($managementScope, ENT_QUOTES, 'UTF-8') ?>">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>"
                >


                <!-- BASIC INFORMATION -->

                <div class="card">

                    <h2>
                        Basic Information
                    </h2>


                    <div class="form-grid">


                        <!-- CATEGORY -->

                        <div class="form-group">

                            <label for="category">

                                Service Category

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <select
                                id="category"
                                name="category"
                                required
                            >

                                <option
                                    value="visa"
                                    <?= $old['category'] === 'visa'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Vietnam Visa
                                </option>

                                <option
                                    value="immigration"
                                    <?= $old['category'] === 'immigration'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Immigration / Residence
                                </option>


                                <option
                                    value="support"
                                    <?= $old['category'] === 'support'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Other Services / Support
                                </option>

                            </select>

                            <div class="help">
                                Choose where this service is grouped on the website.
                            </div>

                        </div>


                        <!-- SORT ORDER -->

                        <div class="form-group">

                            <label for="sort_order">
                                Sort Order
                            </label>


                            <input
                                type="number"
                                id="sort_order"
                                name="sort_order"
                                value="<?= htmlspecialchars(
                                    $old['sort_order']
                                ) ?>"
                                min="0"
                            >


                            <div class="help">
                                Smaller numbers appear first.
                            </div>

                        </div>


                        <!-- TITLE -->

                        <div class="form-group full">

                            <label for="title">

                                Title

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                id="title"
                                name="title"
                                value="<?= htmlspecialchars(
                                    $old['title']
                                ) ?>"
                                placeholder="Vietnam E-Visa"
                                required
                            >

                        </div>


                        <!-- VIETNAMESE TITLE -->
                        <div class="form-group full">
                            <label for="title_vn">Vietnamese Title <span class="required">*</span></label>
                            <input
                                type="text"
                                id="title_vn"
                                name="title_vn"
                                value="<?= htmlspecialchars($old['title_vn'], ENT_QUOTES, 'UTF-8') ?>"
                                required
                            >
                        </div>

                        <!-- SLUG -->

                        <div class="form-group full">

                            <label for="slug">

                                Slug

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="text"
                                id="slug"
                                name="slug"
                                value="<?= htmlspecialchars(
                                    $old['slug']
                                ) ?>"
                                placeholder="vietnam-e-visa"
                                required
                            >


                            <div class="help">
                                Example:
                                vietnam-e-visa
                            </div>

                        </div>


                        <!-- SHORT DESCRIPTION -->

                        <div class="form-group full">

                            <label for="short_description">
                                Short Description
                            </label>


                            <textarea
                                id="short_description"
                                name="short_description"
                                placeholder="Short description shown in service cards."
                            ><?= htmlspecialchars(
                                $old['short_description']
                            ) ?></textarea>

                        </div>


                        <!-- VIETNAMESE SHORT DESCRIPTION -->
                        <div class="form-group full">
                            <label for="short_description_vn">Vietnamese Short Description</label>
                            <textarea
                                id="short_description_vn"
                                name="short_description_vn"
                            ><?= htmlspecialchars($old['short_description_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <!-- DESCRIPTION -->

                        <div class="form-group full">

                            <label for="description">
                                Description
                            </label>


                            <textarea
                                class="description"
                                id="description"
                                name="description"
                                data-rich-editor="English service description"
                                placeholder="Full service description..."
                            ><?= htmlspecialchars(
                                $old['description']
                            ) ?></textarea>


                            <div class="help">
                                This content will be used on the service detail page.
                            </div>

                        </div>

                        <div class="form-group full">
                            <label for="description_vn">Vietnamese Description</label>
                            <textarea
                                class="description"
                                id="description_vn"
                                name="description_vn"
                                data-rich-editor="Vietnamese service description"
                            ><?= htmlspecialchars($old['description_vn'], ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="form-group full admin-media-picker">
                            <label for="media_files">Images and videos</label>
                            <input id="media_files" name="media_files[]" type="file" accept="image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime" multiple>
                            <div class="help">Choose one or multiple files. Images and videos rotate in the service Hero in saved order. The first image becomes the cover when no image is set below. Up to 100 MB per file and 128 MB total per save.</div>
                        </div>

                    </div>

                </div>


                <!-- =================================================
                     PRICE
                     ================================================= -->

                <div class="card">

                    <h2>
                        Pricing
                    </h2>


                    <div class="form-grid">


                        <div class="form-group">

                            <label for="price">
                                Price
                            </label>


                            <input
                                type="number"
                                id="price"
                                name="price"
                                value="<?= htmlspecialchars(
                                    $old['price']
                                ) ?>"
                                min="0"
                                step="0.01"
                                placeholder="45"
                            >


                            <div class="help">
                                Leave empty if the customer needs to contact SunViet.
                            </div>

                        </div>


                        <div class="form-group">

                            <label for="currency">
                                Currency
                            </label>


                            <select
                                id="currency"
                                name="currency"
                            >

                                <option
                                    value="USD"
                                    <?= $old['currency'] === 'USD'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    USD
                                </option>


                                <option
                                    value="VND"
                                    <?= $old['currency'] === 'VND'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    VND
                                </option>


                                <option
                                    value="EUR"
                                    <?= $old['currency'] === 'EUR'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    EUR
                                </option>

                            </select>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     MEDIA
                     ================================================= -->

                <div class="card">

                    <h2>
                        Media
                    </h2>


                    <div class="form-grid">


                        <div class="form-group">

                            <label for="image">
                                Image
                            </label>


                            <input
                                type="text"
                                id="image"
                                name="image"
                                value="<?= htmlspecialchars(
                                    $old['image']
                                ) ?>"
                                placeholder="images/services/evisa.jpg"
                            >


                            <div class="help">
                                Enter the image path or URL.
                            </div>

                        </div>


                        <div class="form-group">

                            <label for="icon">
                                Icon
                            </label>


                            <input
                                type="text"
                                id="icon"
                                name="icon"
                                value="<?= htmlspecialchars(
                                    $old['icon']
                                ) ?>"
                                placeholder="fa-passport"
                            >


                            <div class="help">
                                Optional icon identifier.
                            </div>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     STATUS
                     ================================================= -->

                <div class="card">

                    <h2>
                        Visibility
                    </h2>


                    <div class="checkbox-row">


                        <label class="checkbox-item">

                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                <?= (int) $old['is_active'] === 1
                                    ? 'checked'
                                    : '' ?>
                            >

                            Active

                        </label>


                        <label class="checkbox-item">

                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                <?= (int) $old['is_featured'] === 1
                                    ? 'checked'
                                    : '' ?>
                            >

                            Featured

                        </label>

                    </div>

                </div>


                <!-- =================================================
                     ACTIONS
                     ================================================= -->

                <div class="form-actions">

                    <a
                        class="btn btn-secondary"
                        href="<?= htmlspecialchars($managementUrl, ENT_QUOTES, 'UTF-8') ?>"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Create Service
                    </button>

                </div>


            </form>

        </main>

    </div>

</div>

    </div>

</div>

<script>
window.SunVietEditor = <?= json_encode(['endpoint' => ADMIN_BASE_URL . '/editor-media.php', 'csrfToken' => csrf_token()], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/js/rich-editor.js?v=' . filemtime(__DIR__ . '/../assets/js/rich-editor.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>
