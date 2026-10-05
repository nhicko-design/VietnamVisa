<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();

/*
|--------------------------------------------------------------------------
| GET SERVICE ID
|--------------------------------------------------------------------------
*/

$serviceId = isset($_GET['service_id'])
    ? (int) $_GET['service_id']
    : 0;

if ($serviceId <= 0) {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php');
    exit;
}

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
| DEFAULT VALUES
|--------------------------------------------------------------------------
*/

$mediaType = 'image';
$filePath = '';
$title = '';
$altText = '';
$sortOrder = '0';
$isActive = 1;

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

        } elseif (!is_numeric($sortOrder)) {

            $error = 'Sort order must be a number.';

        } else {

            /*
            |--------------------------------------------------------------------------
            | CHECK UPLOAD
            |--------------------------------------------------------------------------
            */

            $hasUpload =
                isset($_FILES['media_file']) &&
                $_FILES['media_file']['error'] !== UPLOAD_ERR_NO_FILE;


            /*
            |--------------------------------------------------------------------------
            | UPLOAD FILE
            |--------------------------------------------------------------------------
            */

            if ($hasUpload) {

                $file = $_FILES['media_file'];

                /*
                |--------------------------------------------------------------------------
                | UPLOAD ERROR
                |--------------------------------------------------------------------------
                */

                if ($file['error'] !== UPLOAD_ERR_OK) {

                    $error = 'File upload failed.';

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | MAX FILE SIZE
                    |--------------------------------------------------------------------------
                    |
                    | 50 MB
                    |
                    */

                    $maxSize = 50 * 1024 * 1024;

                    if ($file['size'] > $maxSize) {

                        $error = 'File is too large. Maximum size is 50 MB.';

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | MIME TYPE
                        |--------------------------------------------------------------------------
                        */

                        $finfo = finfo_open(FILEINFO_MIME_TYPE);

                        $mimeType = finfo_file(
                            $finfo,
                            $file['tmp_name']
                        );

                        finfo_close($finfo);


                        /*
                        |--------------------------------------------------------------------------
                        | ALLOWED TYPES
                        |--------------------------------------------------------------------------
                        */

                        $allowedImages = [
                            'image/jpeg' => 'jpg',
                            'image/png' => 'png',
                            'image/webp' => 'webp',
                            'image/gif' => 'gif',
                        ];

                        $allowedVideos = [
                            'video/mp4' => 'mp4',
                            'video/webm' => 'webm',
                            'video/quicktime' => 'mov',
                        ];


                        /*
                        |--------------------------------------------------------------------------
                        | CHECK MEDIA TYPE
                        |--------------------------------------------------------------------------
                        */

                        $extension = null;

                        if ($mediaType === 'image') {

                            if (!isset($allowedImages[$mimeType])) {

                                $error =
                                    'Invalid image format. Allowed: JPG, PNG, WEBP, GIF.';

                            } else {

                                $extension =
                                    $allowedImages[$mimeType];
                            }

                        } else {

                            if (!isset($allowedVideos[$mimeType])) {

                                $error =
                                    'Invalid video format. Allowed: MP4, WEBM, MOV.';

                            } else {

                                $extension =
                                    $allowedVideos[$mimeType];
                            }
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SAVE FILE
                        |--------------------------------------------------------------------------
                        */

                        if (!$error && $extension) {

                            if ($mediaType === 'image') {

                                $uploadDirectory =
                                    dirname(__DIR__, 2) .
                                    '/uploads/services/images/';

                                $relativeDirectory =
                                    'uploads/services/images/';

                            } else {

                                $uploadDirectory =
                                    dirname(__DIR__, 2) .
                                    '/uploads/services/videos/';

                                $relativeDirectory =
                                    'uploads/services/videos/';
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | CREATE DIRECTORY
                            |--------------------------------------------------------------------------
                            */

                            if (!is_dir($uploadDirectory)) {

                                mkdir(
                                    $uploadDirectory,
                                    0755,
                                    true
                                );
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | GENERATE UNIQUE FILE NAME
                            |--------------------------------------------------------------------------
                            */

                            $fileName =
                                date('YmdHis') .
                                '_' .
                                bin2hex(random_bytes(6)) .
                                '.' .
                                $extension;


                            $destination =
                                $uploadDirectory .
                                $fileName;


                            /*
                            |--------------------------------------------------------------------------
                            | MOVE FILE
                            |--------------------------------------------------------------------------
                            */

                            if (!move_uploaded_file(
                                $file['tmp_name'],
                                $destination
                            )) {

                                $error =
                                    'Unable to save uploaded file.';

                            } else {

                                $filePath =
                                    $relativeDirectory .
                                    $fileName;
                            }
                        }
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | URL / PATH
            |--------------------------------------------------------------------------
            */

            if (!$error && !$hasUpload) {

                if ($filePath === '') {

                    $error =
                        'Please upload a file or enter a file URL/path.';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | INSERT DATABASE
            |--------------------------------------------------------------------------
            */

            if (!$error) {

                $stmt = $pdo->prepare("
                    INSERT INTO service_media (
                        service_id,
                        media_type,
                        file_path,
                        title,
                        alt_text,
                        sort_order,
                        is_active,
                        created_at
                    )
                    VALUES (
                        :service_id,
                        :media_type,
                        :file_path,
                        :title,
                        :alt_text,
                        :sort_order,
                        :is_active,
                        NOW()
                    )
                ");

                $stmt->execute([
                    ':service_id' => $serviceId,
                    ':media_type' => $mediaType,
                    ':file_path' => $filePath,
                    ':title' => $title,
                    ':alt_text' => $altText,
                    ':sort_order' => (int) $sortOrder,
                    ':is_active' => $isActive,
                ]);


                /*
                |--------------------------------------------------------------------------
                | REDIRECT
                |--------------------------------------------------------------------------
                */

                header(
                    'Location: ' .
                    ADMIN_BASE_URL .
                    '/services/media.php?id=' .
                    $serviceId .
                    '&created=1'
                );

                exit;
            }
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
        Add Media - <?= htmlspecialchars($service['title']) ?>
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

        .upload-box {
            padding: 20px;
            background: #f9fafb;
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
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
            Add Media
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


        <form
            method="POST"
            enctype="multipart/form-data"
        >

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


            <!-- FILE UPLOAD -->

            <div class="form-group">

                <label for="media_file">
                    Upload File
                </label>

                <div class="upload-box">

                    <input
                        type="file"
                        id="media_file"
                        name="media_file"
                        accept="image/*,video/*"
                    >

                    <div class="help">

                        Image: JPG, PNG, WEBP, GIF

                        <br>

                        Video: MP4, WEBM, MOV

                        <br>

                        Maximum size: 50 MB

                    </div>

                </div>

            </div>


            <!-- URL -->

            <div class="form-group">

                <label for="file_path">
                    Or File Path / URL
                </label>

                <input
                    type="text"
                    id="file_path"
                    name="file_path"
                    value="<?= htmlspecialchars($filePath) ?>"
                    placeholder="https://example.com/image.jpg"
                >

                <div class="help">

                    If you upload a file, the uploaded file will be used
                    instead of this URL.

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


            <!-- ALT -->

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
                    Upload / Save Media
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>
