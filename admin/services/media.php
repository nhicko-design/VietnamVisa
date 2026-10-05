<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();

/*
|--------------------------------------------------------------------------
| GET SERVICE ID
|--------------------------------------------------------------------------
*/

$serviceId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

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
        category,
        slug,
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
        is_active,
        created_at
    FROM service_media
    WHERE service_id = :service_id
    ORDER BY sort_order ASC, id ASC
");

$stmt->execute([
    ':service_id' => $serviceId
]);

$mediaList = $stmt->fetchAll();
$message = '';

if (isset($_GET['created'])) {
    $message = 'Media created successfully.';
}

if (isset($_GET['updated'])) {
    $message = 'Media updated successfully.';
}

if (isset($_GET['deleted'])) {
    $message = 'Media deleted successfully.';
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
        Service Media - <?= htmlspecialchars($service['title']) ?>
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
            max-width: 1200px;
            margin: 0 auto;
        }

        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .title-area h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .title-area p {
            margin: 0;
            color: #6b7280;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            border: none;
            cursor: pointer;
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

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: middle;
        }

        th {
            font-size: 13px;
            color: #6b7280;
            background: #f9fafb;
        }

        td {
            font-size: 14px;
        }

        .preview {
            width: 120px;
            height: 75px;
            border-radius: 8px;
            overflow: hidden;
            background: #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 12px;
        }

        .preview img,
        .preview video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .video-preview {
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-image {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-video {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-active {
            background: #dcfce7;
            color: #166534;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            padding: 50px 20px;
            text-align: center;
            color: #6b7280;
        }

        .empty h3 {
            margin-bottom: 8px;
            color: #374151;
        }

        .action-links {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-link {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
        }

        .edit {
            background: #eef2ff;
            color: #4338ca;
        }

        .delete {
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            cursor: pointer;
        }

        @media (max-width: 800px) {

            body {
                padding: 15px;
            }

            .top-bar {
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

<link rel="stylesheet" href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/admin.css?v=' . filemtime(__DIR__ . '/../assets/css/admin.css'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="../assets/css/management.css">

</head>

<body class="admin-body admin-legacy-service-page">
<?php $admin_section = 'services'; require __DIR__ . '/../components/sidebar.php'; ?>

<div class="container">

    <!-- HEADER -->

    <div class="top-bar">

        <div class="title-area">

            <h1>
                Service Media
            </h1>

            <p>
                <?= htmlspecialchars($service['title']) ?>
            </p>

        </div>

        <div class="actions">

            <a
                href="<?= ADMIN_BASE_URL ?>/services/edit.php?id=<?= (int) $service['id'] ?>"
                class="btn btn-secondary"
            >
                Edit Service
            </a>

            <a
                href="<?= ADMIN_BASE_URL ?>/services/media-create.php?service_id=<?= (int) $service['id'] ?>"
                class="btn btn-primary"
            >
                + Add Media
            </a>

        </div>

    </div>


    <!-- MEDIA TABLE -->

    <div class="card">
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
        <?php if (!$mediaList): ?>

            <div class="empty">

                <h3>
                    No media yet
                </h3>

                <p>
                    Add an image or video for this service.
                </p>

                <a
                    href="<?= ADMIN_BASE_URL ?>/services/media-create.php?service_id=<?= (int) $service['id'] ?>"
                    class="btn btn-primary"
                >
                    + Add Media
                </a>

            </div>

        <?php else: ?>

            <table>

                <thead>

                    <tr>

                        <th>
                            Preview
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            File Path
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

                <?php foreach ($mediaList as $media): ?>

                    <tr>

                        <!-- PREVIEW -->

                        <td>

                            <div class="preview">

                                <?php if ($media['media_type'] === 'image'): ?>

                                    <img
                                        src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= htmlspecialchars($media['alt_text'] ?? '') ?>"
                                    >

                                <?php else: ?>

                                    <video
                                        controls
                                        preload="metadata"
                                        src="<?= htmlspecialchars(admin_media_url($media['file_path']), ENT_QUOTES, 'UTF-8') ?>"
                                    ></video>

                                <?php endif; ?>

                            </div>

                        </td>


                        <!-- TYPE -->

                        <td>

                            <?php if ($media['media_type'] === 'image'): ?>

                                <span class="badge badge-image">
                                    Image
                                </span>

                            <?php else: ?>

                                <span class="badge badge-video">
                                    Video
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- TITLE -->

                        <td>

                            <?= htmlspecialchars(
                                $media['title'] ?: '-'
                            ) ?>

                        </td>


                        <!-- FILE PATH -->

                        <td>

                            <div
                                style="
                                    max-width: 250px;
                                    word-break: break-all;
                                "
                            >

                                <?= htmlspecialchars(
                                    $media['file_path']
                                ) ?>

                            </div>

                        </td>


                        <!-- SORT -->

                        <td>

                            <?= (int) $media['sort_order'] ?>

                        </td>


                        <!-- STATUS -->

                        <td>

                            <?php if ((int) $media['is_active'] === 1): ?>

                                <span class="badge badge-active">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="badge badge-inactive">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>


                        <!-- ACTIONS -->

                        <td>

                            <div class="action-links">

                                <a
                                    href="<?= ADMIN_BASE_URL ?>/services/media-edit.php?id=<?= (int) $media['id'] ?>"
                                    class="action-link edit"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="<?= ADMIN_BASE_URL ?>/services/media-delete.php"
                                    style="display:inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this media?');"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int) $media['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="service_id"
                                        value="<?= (int) $service['id'] ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars(csrf_token()) ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="action-link delete"
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
