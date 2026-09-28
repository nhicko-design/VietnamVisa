<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();

$serviceId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

$errors = [];

$service = null;


/*
 * ============================================================
 * CHECK SERVICE ID
 * ============================================================
 */

if ($serviceId <= 0) {

    header(
        'Location: ' .
        ADMIN_BASE_URL .
        '/services/index.php'
    );

    exit;
}


/*
 * ============================================================
 * LOAD SERVICE
 * ============================================================
 */

try {

    $stmt = $pdo->prepare("
        SELECT
            id,
            category,
            slug,
            title,
            short_description,
            description,
            price,
            currency,
            image,
            icon,
            is_featured,
            is_active,
            sort_order,
            created_at,
            updated_at
        FROM services
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([
        'id' => $serviceId,
    ]);

    $service = $stmt->fetch();

} catch (Throwable $e) {

    $errors[] =
        'Unable to load service: ' .
        $e->getMessage();
}


/*
 * ============================================================
 * SERVICE NOT FOUND
 * ============================================================
 */

if (!$service) {

    http_response_code(404);

    exit(
        'Service not found.'
    );
}


/*
 * ============================================================
 * HANDLE FORM SUBMISSION
 * ============================================================
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category = trim(
        $_POST['category'] ?? ''
    );

    $slug = trim(
        $_POST['slug'] ?? ''
    );

    $title = trim(
        $_POST['title'] ?? ''
    );

    $shortDescription = trim(
        $_POST['short_description'] ?? ''
    );

    $description = trim(
        $_POST['description'] ?? ''
    );

    $price = trim(
        $_POST['price'] ?? ''
    );

    $currency = trim(
        $_POST['currency'] ?? 'USD'
    );

    $image = trim(
        $_POST['image'] ?? ''
    );

    $icon = trim(
        $_POST['icon'] ?? ''
    );

    $isFeatured = isset(
        $_POST['is_featured']
    ) ? 1 : 0;

    $isActive = isset(
        $_POST['is_active']
    ) ? 1 : 0;

    $sortOrder = trim(
        $_POST['sort_order'] ?? '0'
    );


    /*
     * ========================================================
     * VALIDATION
     * ========================================================
     */

    if (
        !in_array(
            $category,
            ['visa', 'support'],
            true
        )
    ) {

        $errors[] =
            'Please select a valid category.';
    }


    if ($title === '') {

        $errors[] =
            'Title is required.';
    }


    if ($slug === '') {

        $errors[] =
            'Slug is required.';
    }


    if (
        $price !== '' &&
        !is_numeric($price)
    ) {

        $errors[] =
            'Price must be a valid number.';
    }


    if (
        $sortOrder !== '' &&
        !is_numeric($sortOrder)
    ) {

        $errors[] =
            'Sort order must be a number.';
    }


    /*
     * ========================================================
     * NORMALIZE SLUG
     * ========================================================
     */

    if ($slug !== '') {

        $slug = strtolower($slug);

        $slug = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $slug
        );

        $slug = trim(
            $slug,
            '-'
        );
    }


    /*
     * ========================================================
     * CHECK SLUG DUPLICATE
     * ========================================================
     */

    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                SELECT id
                FROM services
                WHERE slug = :slug
                AND id != :id
                LIMIT 1
            ");

            $stmt->execute([
                'slug' => $slug,
                'id' => $serviceId,
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
     * UPDATE SERVICE
     * ========================================================
     */

    if (empty($errors)) {

        try {

            $stmt = $pdo->prepare("
                UPDATE services
                SET
                    category = :category,
                    slug = :slug,
                    title = :title,
                    short_description = :short_description,
                    description = :description,
                    price = :price,
                    currency = :currency,
                    image = :image,
                    icon = :icon,
                    is_featured = :is_featured,
                    is_active = :is_active,
                    sort_order = :sort_order,
                    updated_at = NOW()
                WHERE id = :id
            ");


            $stmt->execute([

                'category' =>
                    $category,

                'slug' =>
                    $slug,

                'title' =>
                    $title,

                'short_description' =>
                    $shortDescription !== ''
                        ? $shortDescription
                        : null,

                'description' =>
                    $description !== ''
                        ? $description
                        : null,

                'price' =>
                    $price !== ''
                        ? $price
                        : null,

                'currency' =>
                    $currency !== ''
                        ? $currency
                        : 'USD',

                'image' =>
                    $image !== ''
                        ? $image
                        : null,

                'icon' =>
                    $icon !== ''
                        ? $icon
                        : null,

                'is_featured' =>
                    $isFeatured,

                'is_active' =>
                    $isActive,

                'sort_order' =>
                    $sortOrder !== ''
                        ? (int) $sortOrder
                        : 0,

                'id' =>
                    $serviceId,
            ]);


            /*
             * Redirect after successful update.
             */

            header(
                'Location: ' .
                ADMIN_BASE_URL .
                '/services/index.php?updated=1'
            );

            exit;

        } catch (Throwable $e) {

            $errors[] =
                'Unable to update service: ' .
                $e->getMessage();
        }
    }


    /*
     * ========================================================
     * KEEP USER INPUT WHEN VALIDATION FAILS
     * ========================================================
     */

    $service['category'] =
        $category;

    $service['slug'] =
        $slug;

    $service['title'] =
        $title;

    $service['short_description'] =
        $shortDescription;

    $service['description'] =
        $description;

    $service['price'] =
        $price;

    $service['currency'] =
        $currency;

    $service['image'] =
        $image;

    $service['icon'] =
        $icon;

    $service['is_featured'] =
        $isFeatured;

    $service['is_active'] =
        $isActive;

    $service['sort_order'] =
        $sortOrder;
}


$currentAdmin = current_admin();

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
        Edit Service - SunViet Admin
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


        .card h2 {

            margin:
                0 0 25px;

            font-size: 18px;
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
         * ERROR
         * ====================================================
         */

        .error-box {

            margin-bottom: 20px;

            padding:
                14px 16px;

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

</head>


<body>

<div class="page">


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
                    $currentAdmin['name']
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
             SIDEBAR
             ================================================= -->

        <aside class="sidebar">

            <div class="sidebar-title">
                Management
            </div>


            <a
                class="nav-link"
                href="<?= ADMIN_BASE_URL ?>/index.php"
            >
                Dashboard
            </a>


            <a
                class="nav-link active"
                href="<?= ADMIN_BASE_URL ?>/services/index.php"
            >
                Services
            </a>


            <a
                class="nav-link"
                href="<?= ADMIN_BASE_URL ?>/news/index.php"
            >
                News
            </a>


            <a
                class="nav-link"
                href="<?= ADMIN_BASE_URL ?>/settings.php"
            >
                Settings
            </a>

        </aside>


        <!-- =================================================
             CONTENT
             ================================================= -->

        <main class="content">


            <div class="page-header">

                <div>

                    <h1 class="page-title">
                        Edit Service
                    </h1>

                    <p class="page-description">

                        Update service information.

                    </p>

                </div>


                <a
                    class="btn btn-secondary"
                    href="<?= ADMIN_BASE_URL ?>/services/index.php"
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

                                Category

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
                                    <?= $service['category'] === 'visa'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Visa
                                </option>


                                <option
                                    value="support"
                                    <?= $service['category'] === 'support'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Support
                                </option>

                            </select>

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
                                    $service['sort_order']
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
                                    $service['title']
                                ) ?>"
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
                                    $service['slug']
                                ) ?>"
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
                            ><?= htmlspecialchars(
                                $service['short_description']
                                ?? ''
                            ) ?></textarea>

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
                            ><?= htmlspecialchars(
                                $service['description']
                                ?? ''
                            ) ?></textarea>


                            <div class="help">

                                Full description shown on
                                the service detail page.

                            </div>

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
                                    $service['price']
                                    ?? ''
                                ) ?>"
                                min="0"
                                step="0.01"
                            >


                            <div class="help">

                                Leave empty if price is not fixed.

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
                                    <?= ($service['currency'] ?? 'USD') === 'USD'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    USD
                                </option>


                                <option
                                    value="VND"
                                    <?= ($service['currency'] ?? '') === 'VND'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    VND
                                </option>


                                <option
                                    value="EUR"
                                    <?= ($service['currency'] ?? '') === 'EUR'
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
                                    $service['image']
                                    ?? ''
                                ) ?>"
                                placeholder="images/services/evisa.jpg"
                            >


                            <div class="help">

                                Enter an image path or URL.

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
                                    $service['icon']
                                    ?? ''
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
                     VISIBILITY
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
                                <?= (int) $service['is_active'] === 1
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
                                <?= (int) $service['is_featured'] === 1
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
                        href="<?= ADMIN_BASE_URL ?>/services/index.php"
                    >
                        Cancel
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Changes
                    </button>

                </div>


            </form>

        </main>

    </div>

</div>

</body>

</html>