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
        title
    FROM services
    WHERE id = ?
    LIMIT 1
");

$stmt->execute([$serviceId]);

$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        id,
        step_number,
        title,
        description,
        sort_order,
        is_active
    FROM service_steps
    WHERE service_id = ?
    ORDER BY sort_order ASC, id ASC
");

$stmt->execute([$serviceId]);

$steps = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        Steps - <?= htmlspecialchars($service['title']) ?>
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f6f7fb;
            color: #17171a;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
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

        h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .subtitle {
            color: #686871;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
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
            text-decoration: none;
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
            color: #686871;
            font-size: 13px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .step-number {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #673de6;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .step-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .step-description {
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
            background: #fff0f0;
            color: #c62828;
            border: 0;
        }

        .empty {
            padding: 50px 20px;
            text-align: center;
            color: #686871;
        }

        @media (max-width: 700px) {

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }

            .card {
                overflow-x: auto;
            }

            table {
                min-width: 850px;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="topbar">

        <div>

            <h1>
                Service Steps
            </h1>

            <div class="subtitle">
                <?= htmlspecialchars($service['title']) ?>
            </div>

        </div>

        <div class="actions">

            <a
                class="button"
                href="index.php"
            >
                Back to Services
            </a>

            <a
                class="button button-primary"
                href="step-create.php?id=<?= $serviceId ?>"
            >
                + Add Step
            </a>

        </div>

    </div>


    <div class="card">

        <?php if (!$steps): ?>

            <div class="empty">

                <h3>
                    No steps yet
                </h3>

                <p>
                    Add the first step for this service.
                </p>

            </div>

        <?php else: ?>

            <table>

                <thead>

                    <tr>

                        <th>
                            Step
                        </th>

                        <th>
                            Title
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

                <?php foreach ($steps as $step): ?>

                    <tr>

                        <td>

                            <span class="step-number">
                                <?= (int) $step['step_number'] ?>
                            </span>

                        </td>

                        <td>

                            <div class="step-title">
                                <?= htmlspecialchars(
                                    $step['title']
                                ) ?>
                            </div>

                            <?php if (
                                !empty($step['description'])
                            ): ?>

                                <div class="step-description">

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $step['description']
                                        )
                                    ) ?>

                                </div>

                            <?php endif; ?>

                        </td>

                        <td>
                            <?= (int) $step['sort_order'] ?>
                        </td>

                        <td>

                            <?php if (
                                (int) $step['is_active'] === 1
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
                                    href="step-edit.php?id=<?= (int) $step['id'] ?>"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="step-delete.php"
                                    onsubmit="return confirm('Delete this step?');"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $step['id'] ?>"
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

</body>

</html>