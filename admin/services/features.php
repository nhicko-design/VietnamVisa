<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$serviceId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($serviceId <= 0) {
    header('Location: index.php');
    exit;
}

$pdo = db();

$stmt = $pdo->prepare("
    SELECT
        id,
        category,
        slug,
        title
    FROM services
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([
    $serviceId
]);

$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    header('Location: index.php');
    exit;
}

$serviceManagementUrl = admin_service_manage_url($pdo, $serviceId);


$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        description,
        title_vn,
        description_vn,
        sort_order,
        is_active
    FROM service_features
    WHERE service_id = ?
    ORDER BY sort_order ASC, id ASC
");

$stmt->execute([
    $serviceId
]);

$features = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        Features - <?= htmlspecialchars($service['title']) ?>
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
            background: #f6f7fb;
            color: #17171a;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .title h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .title p {
            margin: 0;
            color: #686871;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        a {
            text-decoration: none;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 16px;
            border-radius: 10px;
            border: 1px solid #dddde5;
            background: white;
            color: #333;
            cursor: pointer;
            font-size: 14px;
        }

        .button-primary {
            background: #673de6;
            color: white;
            border-color: #673de6;
        }

        .card {
            background: white;
            border: 1px solid #e7e7ec;
            border-radius: 16px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 15px 16px;
            border-bottom: 1px solid #eeeeF2;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #fafafd;
            font-size: 13px;
            color: #686871;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .feature-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .feature-description {
            color: #686871;
            line-height: 1.5;
        }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
        }

        .status-active {
            background: #eaf8ef;
            color: #217a42;
        }

        .status-inactive {
            background: #f1f1f4;
            color: #686871;
        }

        .row-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .delete-button {
            border: 0;
            background: #fff0f0;
            color: #c62828;
        }

        .empty {
            padding: 50px 20px;
            text-align: center;
            color: #686871;
        }

        @media (max-width: 700px) {

            .topbar {
                align-items: flex-start;
                flex-direction: column;
            }

            .card {
                overflow-x: auto;
            }

            table {
                min-width: 750px;
            }

        }

    </style>

<link rel="stylesheet" href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/admin.css?v=' . filemtime(__DIR__ . '/../assets/css/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/sunviet-admin-theme.css', ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="../assets/css/management.css">

</head>

<body class="admin-body admin-legacy-service-page">
<?php $admin_section = admin_service_section($pdo, $serviceId); require __DIR__ . '/../components/sidebar.php'; ?>

<div class="admin-main">
    <?php $page_title = 'Service Features'; require __DIR__ . '/../components/topbar.php'; ?>
    <main class="admin-content service-child-page">
        <div class="container">

    <div class="topbar">

        <div class="title">

            <h1>
                Service Features
            </h1>

            <p>
                <?= htmlspecialchars($service['title']) ?>
            </p>

        </div>

        <div class="actions">

            <a
                class="button"
                href="<?= htmlspecialchars($serviceManagementUrl, ENT_QUOTES, 'UTF-8') ?>"
            >
                Back to Services
            </a>

            <a
                class="button button-primary"
                href="feature-create.php?id=<?= $serviceId ?>"
            >
                + Add Feature
            </a>

        </div>

    </div>


    <div class="card">

        <?php if (!$features): ?>

            <div class="empty">

                <h3>
                    No features yet
                </h3>

                <p>
                    Add the first feature for this service.
                </p>

            </div>

        <?php else: ?>

            <table>

                <thead>

                    <tr>

                        <th>
                            #
                        </th>

                        <th>
                            Feature
                        </th>

                        <th>
                            Order
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                <?php foreach ($features as $feature): ?>

                    <tr>

                        <td>
                            <?= (int) $feature['id'] ?>
                        </td>

                        <td>

                            <div class="feature-title">
                                <?= htmlspecialchars(
                                    $feature['title']
                                ) ?>
                            </div>

                            <?php if (
                                !empty(
                                    $feature['description']
                                )
                            ): ?>

                                <div class="feature-description">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $feature['description']
                                        )
                                    ) ?>

                                </div>

                            <?php endif; ?>

                            <?php if (!empty($feature['title_vn'])): ?>
                                <div class="feature-title">
                                    Vietnamese: <?= htmlspecialchars($feature['title_vn'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($feature['description_vn'])): ?>
                                <div class="feature-description">
                                    <?= nl2br(htmlspecialchars($feature['description_vn'], ENT_QUOTES, 'UTF-8')) ?>
                                </div>
                            <?php endif; ?>

                        </td>

                        <td>
                            <?= (int) $feature['sort_order'] ?>
                        </td>

                        <td>

                            <?php if (
                                (int) $feature['is_active'] === 1
                            ): ?>

                                <span class="status status-active">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="status status-inactive">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <div class="row-actions">

                                <a
                                    class="button"
                                    href="feature-edit.php?id=<?= (int) $feature['id'] ?>"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="feature-delete.php"
                                    onsubmit="return confirm('Delete this feature?');"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $feature['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="service_id"
                                        value="<?= $serviceId ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(
                                            csrf_token()
                                        ) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="button delete-button"
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

        <?php endif; ?>

    </div>

</div>
    </main>
</div>

<script src="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/js/admin.js', ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>
