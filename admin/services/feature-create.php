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
    SELECT id, title
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

$title = '';
$description = '';
$titleVn = '';
$descriptionVn = '';
$sortOrder = 0;
$isActive = 1;

$errors = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !verify_csrf_token($_POST['csrf_token'])
    ) {
        $errors[] = 'Invalid CSRF token.';
    }

    $title = trim(
        $_POST['title'] ?? ''
    );

    $description = trim(
        $_POST['description'] ?? ''
    );

    $titleVn = trim($_POST['title_vn'] ?? '');
    $descriptionVn = trim($_POST['description_vn'] ?? '');

    $sortOrder = (int) (
        $_POST['sort_order'] ?? 0
    );

    $isActive = isset(
        $_POST['is_active']
    ) ? 1 : 0;


    if ($title === '') {
        $errors[] = 'Feature title is required.';
    }


    if (!$errors) {

        $stmt = $pdo->prepare("
            INSERT INTO service_features (
                service_id,
                title,
                description,
                title_vn,
                description_vn,
                sort_order,
                is_active,
                created_at,
                updated_at
            )
            VALUES (
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                NOW(),
                NOW()
            )
        ");

        $stmt->execute([
            $serviceId,
            $title,
            $description !== ''
                ? $description
                : null,
            $titleVn !== '' ? $titleVn : null,
            $descriptionVn !== '' ? $descriptionVn : null,
            $sortOrder,
            $isActive
        ]);

        header(
            'Location: features.php?id='
            . $serviceId
            . '&created=1'
        );

        exit;
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
        Add Feature
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
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 24px;
        }

        .card {
            background: white;
            border: 1px solid #e7e7ec;
            border-radius: 16px;
            padding: 30px;
        }

        h1 {
            margin: 0 0 8px;
        }

        .subtitle {
            color: #686871;
            margin-bottom: 30px;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dcdce4;
            border-radius: 10px;
            font: inherit;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        .checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox input {
            width: auto;
        }

        .errors {
            margin-bottom: 20px;
            padding: 14px 16px;
            background: #fff0f0;
            color: #b42318;
            border-radius: 10px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 17px;
            border-radius: 10px;
            border: 1px solid #dddde5;
            background: white;
            color: #333;
            text-decoration: none;
            cursor: pointer;
        }

        .primary {
            background: #673de6;
            border-color: #673de6;
            color: white;
        }

    </style>

<link rel="stylesheet" href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/admin.css?v=' . filemtime(__DIR__ . '/../assets/css/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="../assets/css/management.css">

</head>

<body class="admin-body admin-legacy-service-page">
<?php $admin_section = 'services'; require __DIR__ . '/../components/sidebar.php'; ?>

<div class="container">

    <div class="card">

        <h1>
            Add Feature
        </h1>

        <div class="subtitle">
            <?= htmlspecialchars($service['title']) ?>
        </div>


        <?php if ($errors): ?>

            <div class="errors">

                <?php foreach ($errors as $error): ?>

                    <div>
                        <?= htmlspecialchars($error) ?>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    csrf_token()
                ) ?>"
            >


            <div class="field">

                <label>
                    Feature title
                </label>

                <input
                    type="text"
                    name="title"
                    value="<?= htmlspecialchars($title) ?>"
                    required
                >

            </div>


            <div class="field">

                <label>
                    Description
                </label>

                <textarea
                    name="description"
                ><?= htmlspecialchars($description) ?></textarea>

            </div>

            <div class="field">
                <label>Vietnamese feature title</label>
                <input type="text" name="title_vn" value="<?= htmlspecialchars($titleVn, ENT_QUOTES, 'UTF-8') ?>">
            </div>

            <div class="field">
                <label>Vietnamese description</label>
                <textarea name="description_vn"><?= htmlspecialchars($descriptionVn, ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>


            <div class="field">

                <label>
                    Sort order
                </label>

                <input
                    type="number"
                    name="sort_order"
                    value="<?= $sortOrder ?>"
                >

            </div>


            <div class="field">

                <label class="checkbox">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        <?= $isActive
                            ? 'checked'
                            : '' ?>
                    >

                    Active

                </label>

            </div>


            <div class="actions">

                <a
                    class="button"
                    href="features.php?id=<?= $serviceId ?>"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="button primary"
                >
                    Save Feature
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>
