<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();

/*
|--------------------------------------------------------------------------
| GET MEDIA ID
|--------------------------------------------------------------------------
*/

$mediaId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($mediaId <= 0) {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| GET MEDIA
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        service_id,
        media_type,
        file_path,
        title,
        alt_text,
        sort_order,
        is_active
    FROM service_media
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $mediaId
]);

$media = $stmt->fetch();

if (!$media) {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php');
    exit;
}

$serviceId = (int) $media['service_id'];

/*
|--------------------------------------------------------------------------
| GET SERVICE
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT
        id,
        title
    FROM services
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $serviceId
]);

$service = $stmt->fetch();

if (!$service) {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| DEFAULT FORM VALUES
|--------------------------------------------------------------------------
*/

$mediaType = $media['media_type'];
$filePath = $media['file_path'];
$title = $media['title'];
$altText = $media['alt_text'];
$sortOrder = (string) $media['sort_order'];
$isActive = (int) $media['is_active'];

$error = '';

/*
|--------------------------------------------------------------------------
| HANDLE SUBMIT
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($csrfToken)) {

        $error = 'Invalid CSRF token.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | GET FORM DATA
        |--------------------------------------------------------------------------
        */

        $mediaType = trim($_POST['media_type'] ?? 'image');

        $filePath = trim($_POST['file_path'] ?? '');

        $title = trim($_POST['title'] ?? '');

        $altText = trim($_POST['alt_text'] ?? '');

        $sortOrder = trim($_POST['sort_order'] ?? '0');

        $isActive = isset($_POST['is_active']) ? 1 : 0;


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if (!in_array($mediaType, ['image', 'video'], true)) {

            $error = 'Invalid media type.';

        } elseif ($filePath === '') {

            $error = 'File path or URL is required.';

        } elseif (!is_numeric($sortOrder)) {

            $error = 'Sort order must be a number.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $stmt = $pdo->prepare("
                UPDATE service_media
                SET
                    media_type = :media_type,
                    file_path = :file_path,
                    title = :title,
                    alt_text = :alt_text,
                    sort_order = :sort_order,
                    is_active = :is_active
                WHERE id = :id
            ");

            $stmt->execute([
                ':media_type' => $mediaType,
                ':file_path' => $filePath,
                ':title' => $title,
                ':alt_text' => $altText,
                ':sort_order' => (int) $sortOrder,
                ':is_active' => $isActive,
                ':id' => $mediaId,
            ]);

            header(
                'Location: ' .
                ADMIN_BASE_URL .
                '/services/media.php?id=' .
                $serviceId .
                '&updated=1'
            );

            exit;
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
        Edit Media - <?= htmlspecialchars($service['title']) ?>
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            background: #f5f6fa;
            color: #1f2937;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.05);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .subtitle {
            margin-bottom: 30px;
            color: #6b7280;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }

        input:focus,
        select:focus {
            border-color: #673de6;
        }

        .help {
            margin-top: 6px;
            font-size: 12px;
            color: #6b7280;
        }

        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-row input {
            width: auto;
        }

        .error {
            margin-bottom: 20px;
            padding: 12px 16px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 8px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 11px 18px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: #673de6;
            color: #ffffff;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .preview-box {
            margin-top: 10px;
            padding: 15px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .preview-box img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            display: block;
        }

        .preview-box video {
            width: 100%;
            max-height: 250px;
            border-radius: 8px;
        }

        .video-placeholder {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111827;
            color: #ffffff;
            border-radius: 8px;
            font-weight: 600;
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
            Edit Media
        </h1>

        <div class="subtitle">

            Service:
            <strong>
                <?= htmlspecialchars($service['title']) ?>
            </strong>

        </div>


        <?php if ($error): ?>

            <div class="error">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(csrf_token()) ?>"
            >


            <!-- MEDIA TYPE -->

            <div class="form-group">

                <label for="media_type">
                    Media Type
                </label>

                <select
                    id="media_type"
                    name="media_type"
                >

                    <option
                        value="image"
                        <?= $mediaType === 'image' ? 'selected' : '' ?>
                    >
                        Image
                    </option>

                    <option
                        value="video"
                        <?= $mediaType === 'video' ? 'selected' : '' ?>
                    >
                        Video
                    </option>

                </select>

            </div>


            <!-- FILE PATH -->

            <div class="form-group">

                <label for="file_path">
                    File Path / URL
                </label>

                <input
                    type="text"
                    id="file_path"
                    name="file_path"
                    value="<?= htmlspecialchars($filePath) ?>"
                    placeholder="https://example.com/image.jpg"
                >

                <div class="help">

                    Enter the image or video URL/path.

                </div>


                <!-- CURRENT PREVIEW -->

                <div class="preview-box">

                    <?php if ($mediaType === 'image'): ?>

                        <img
                            src="<?= htmlspecialchars(admin_media_url($filePath), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($altText) ?>"
                        >

                    <?php else: ?>

                        <video
                            controls
                            preload="metadata"
                            src="<?= htmlspecialchars(admin_media_url($filePath), ENT_QUOTES, 'UTF-8') ?>"
                        ></video>

                    <?php endif; ?>

                </div>

            </div>


            <!-- TITLE -->

            <div class="form-group">

                <label for="title">
                    Title
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($title) ?>"
                    placeholder="Vietnam E-Visa"
                >

            </div>


            <!-- ALT TEXT -->

            <div class="form-group">

                <label for="alt_text">
                    Alt Text
                </label>

                <input
                    type="text"
                    id="alt_text"
                    name="alt_text"
                    value="<?= htmlspecialchars($altText) ?>"
                    placeholder="Vietnam electronic visa"
                >

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
                    value="<?= htmlspecialchars($sortOrder) ?>"
                    min="0"
                >

                <div class="help">

                    Smaller numbers appear first.

                </div>

            </div>


            <!-- ACTIVE -->

            <div class="form-group">

                <label class="checkbox-row">

                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        <?= $isActive ? 'checked' : '' ?>
                    >

                    Active

                </label>

            </div>


            <!-- ACTIONS -->

            <div class="actions">

                <a
                    href="<?= ADMIN_BASE_URL ?>/services/media.php?id=<?= (int) $serviceId ?>"
                    class="btn btn-secondary"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Update Media
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>
