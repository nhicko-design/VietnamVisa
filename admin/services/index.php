<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$query = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
header('Location: manage.php' . $query);
exit;

$pdo = db();
$message = '';

if (isset($_GET['created'])) {
    $message = 'Service created successfully.';
}

if (isset($_GET['updated'])) {
    $message = 'Service updated successfully.';
}

if (isset($_GET['deleted'])) {
    $message = 'Service deleted successfully.';
}
try {

    $stmt = $pdo->query("
        SELECT
            id,
            category,
            slug,
            title,
            short_description,
            price,
            currency,
            image,
            is_featured,
            is_active,
            sort_order,
            created_at,
            updated_at
        FROM services
        ORDER BY
            sort_order ASC,
            id DESC
    ");

    $services = $stmt->fetchAll();

} catch (Throwable $e) {

    $services = [];

    $error = 'Unable to load services. Please try again.';
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

    <title>Services - SunViet Admin</title>

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

        .topbar {
            height: 70px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 30px;

            background: #ffffff;

            border-bottom: 1px solid #e5e5e5;
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

        .layout {
            display: flex;

            min-height: calc(100vh - 70px);
        }

        .sidebar {
            width: 240px;

            flex-shrink: 0;

            background: #171719;

            padding: 25px 15px;
        }

        .sidebar-title {
            padding: 0 15px 20px;

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

        .content {
            flex: 1;

            padding: 30px;

            overflow-x: auto;
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
            margin: 7px 0 0;

            color: #777;

            font-size: 14px;
        }

        .btn {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 11px 18px;

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

        .card {
            background: #fff;

            border-radius: 12px;

            border: 1px solid #e7e7e7;

            overflow: hidden;
        }

        .table-wrapper {
            width: 100%;

            overflow-x: auto;
        }

        table {
            width: 100%;

            min-width: 950px;

            border-collapse: collapse;
        }

        th {
            padding: 14px 16px;

            background: #fafafa;

            border-bottom: 1px solid #e5e5e5;

            text-align: left;

            font-size: 12px;

            color: #666;

            text-transform: uppercase;

            letter-spacing: .4px;
        }

        td {
            padding: 15px 16px;

            border-bottom: 1px solid #eeeeee;

            font-size: 14px;

            vertical-align: middle;
        }

        tbody tr:hover {
            background: #fafafa;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .service-title {
            font-weight: 600;

            color: #222;
        }

        .service-slug {
            margin-top: 4px;

            color: #888;

            font-size: 12px;
        }

        .badge {
            display: inline-block;

            padding: 5px 9px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: 700;

            text-transform: uppercase;
        }

        .badge-visa {
            background: #eee9ff;

            color: #673de6;
        }

        .badge-support {
            background: #e8f7ee;

            color: #237a45;
        }

        .badge-active {
            background: #e8f7ee;

            color: #237a45;
        }

        .badge-inactive {
            background: #fff0f0;

            color: #c62828;
        }

        .badge-featured {
            background: #fff6dc;

            color: #9a6b00;
        }

        .price {
            font-weight: 700;

            white-space: nowrap;
        }

        .muted {
            color: #999;
        }

        .actions {
            display: flex;

            gap: 8px;
        }

        .action-btn {
            display: inline-flex;

            align-items: center;

            justify-content: center;

            padding: 7px 11px;

            border-radius: 6px;

            text-decoration: none;

            font-size: 12px;

            font-weight: 600;
        }

        .edit {
            background: #eee9ff;

            color: #673de6;
        }

        .delete {
            background: #fff0f0;

            color: #c62828;
        }

        .empty {
            padding: 60px 30px;

            text-align: center;

            color: #777;
        }

        .error {
            margin-bottom: 20px;

            padding: 14px 16px;

            background: #fff0f0;

            border: 1px solid #ffd0d0;

            border-radius: 8px;

            color: #c62828;

            font-size: 14px;
        }

        @media (max-width: 800px) {

            .sidebar {
                width: 190px;
            }

            .content {
                padding: 20px;
            }

            .page-header {
                align-items: flex-start;

                flex-direction: column;
            }

        }

    </style>

</head>

<body>

<div class="page">

    <!-- TOPBAR -->

    <header class="topbar">

        <div class="brand">
            SunViet Admin
        </div>

        <div class="admin-info">

            <span class="admin-name">
                <?= htmlspecialchars(
                    $currentAdmin['name'] ?? 'Administrator'
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

        <!-- SIDEBAR -->

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


        <!-- CONTENT -->

        <main class="content">

            <div class="page-header">

                <div>

                    <h1 class="page-title">
                        Services
                    </h1>
                    <?php if ($message): ?>
                        <div
                            style="
                                margin-bottom: 20px;
                                padding: 12px 16px;
                                background: #ecfdf5;
                                color: #166534;
                                border: 1px solid #bbf7d0;
                                border-radius: 8px;
                            "
                        >
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    <p class="page-description">
                        Manage Vietnam visa and support services.
                    </p>

                </div>

                <a
                    class="btn btn-primary"
                    href="<?= ADMIN_BASE_URL ?>/services/create.php"
                >
                    + Add Service
                </a>

            </div>


            <?php if (!empty($error)): ?>

                <div class="error">

                    Unable to load services.

                    <br>

                    <?= htmlspecialchars($error) ?>

                </div>

            <?php endif; ?>


            <div class="card">

                <?php if (empty($services)): ?>

                    <div class="empty">

                        <h3>
                            No services found
                        </h3>

                        <p>
                            Create your first service to get started.
                        </p>

                        <a
                            class="btn btn-primary"
                            href="<?= ADMIN_BASE_URL ?>/services/create.php"
                        >
                            Add Service
                        </a>

                    </div>

                <?php else: ?>

                    <div class="table-wrapper">

                        <table>

                            <thead>

                                <tr>

                                    <th>
                                        Service
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Price
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Featured
                                    </th>

                                    <th>
                                        Order
                                    </th>

                                    <th>
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php foreach ($services as $service): ?>

                                <tr>

                                    <!-- SERVICE -->

                                    <td>

                                        <div class="service-title">

                                            <?= htmlspecialchars(
                                                $service['title']
                                            ) ?>

                                        </div>

                                        <div class="service-slug">

                                            /<?= htmlspecialchars(
                                                $service['slug']
                                            ) ?>

                                        </div>

                                    </td>


                                    <!-- CATEGORY -->

                                    <td>

                                        <?php if (
                                            $service['category'] === 'visa'
                                        ): ?>

                                            <span class="badge badge-visa">
                                                Visa
                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-support">
                                                Support
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- PRICE -->

                                    <td>

                                        <?php if (
                                            $service['price'] !== null &&
                                            $service['price'] !== ''
                                        ): ?>

                                            <span class="price">

                                                <?= htmlspecialchars(
                                                    number_format(
                                                        (float) $service['price'],
                                                        0,
                                                        '.',
                                                        ','
                                                    )
                                                ) ?>

                                                <?= htmlspecialchars(
                                                    $service['currency'] ?? 'USD'
                                                ) ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="muted">
                                                Contact
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <?php if (
                                            (int) $service['is_active'] === 1
                                        ): ?>

                                            <span class="badge badge-active">
                                                Active
                                            </span>

                                        <?php else: ?>

                                            <span class="badge badge-inactive">
                                                Inactive
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- FEATURED -->

                                    <td>

                                        <?php if (
                                            (int) $service['is_featured'] === 1
                                        ): ?>

                                            <span class="badge badge-featured">
                                                Featured
                                            </span>

                                        <?php else: ?>

                                            <span class="muted">
                                                No
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- SORT ORDER -->

                                    <td>

                                        <?= (int) $service['sort_order'] ?>

                                    </td>


                                    <!-- ACTIONS -->

                                    <td>

                                        <div class="actions">
                                            <a
                                                href="<?= ADMIN_BASE_URL ?>/services/media.php?id=<?= (int) $service['id'] ?>"
                                                style="
                                                    display: inline-block;
                                                    padding: 7px 10px;
                                                    border-radius: 6px;
                                                    background: #ede9fe;
                                                    color: #6d28d9;
                                                    text-decoration: none;
                                                    font-size: 12px;
                                                "
                                            >
                                                Media
                                            </a>
                                            <a
                                                class="action-btn edit"
                                                href="<?= ADMIN_BASE_URL ?>/services/edit.php?id=<?= (int) $service['id'] ?>"
                                            >
                                                Edit
                                            </a>
                                            <a
                                                class="action-btn"
                                                href="features.php?id=<?= (int) $service['id'] ?>"
                                            >
                                                Features
                                            </a>
                                            <a
                                                class="action-btn"
                                                href="steps.php?id=<?= (int) $service['id'] ?>"
                                            >
                                                Steps
                                            </a>
                                            <a
                                                class="action-btn"
                                                href="faq.php?id=<?= (int) $service['id'] ?>"
                                            >
                                                FAQ
                                            </a>
                                            <form
                                                method="POST"
                                                action="<?= ADMIN_BASE_URL ?>/services/delete.php"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this service?');"
                                            >
                                                <input
                                                    type="hidden"
                                                    name="id"
                                                    value="<?= (int) $service['id'] ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="csrf_token"
                                                    value="<?= htmlspecialchars(csrf_token()) ?>"
                                                >

                                                <button
                                                    type="submit"
                                                    style="
                                                        border: none;
                                                        background: #dc2626;
                                                        color: #fff;
                                                        padding: 7px 12px;
                                                        border-radius: 6px;
                                                        cursor: pointer;
                                                    "
                                                >
                                                    Delete
                                                </button>
                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                <?php endif; ?>

            </div>

        </main>

    </div>

</div>

</body>

</html>
