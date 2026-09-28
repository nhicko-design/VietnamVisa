<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();

/*
|--------------------------------------------------------------------------
| ONLY POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| CSRF CHECK
|--------------------------------------------------------------------------
*/

$csrfToken = $_POST['csrf_token'] ?? '';

if (!verify_csrf_token($csrfToken)) {
    http_response_code(403);
    exit('Invalid CSRF token.');
}

/*
|--------------------------------------------------------------------------
| GET MEDIA ID
|--------------------------------------------------------------------------
*/

$mediaId = isset($_POST['id'])
    ? (int) $_POST['id']
    : 0;

if ($mediaId <= 0) {
    header(
        'Location: ' .
        ADMIN_BASE_URL .
        '/services/index.php?error=invalid_id'
    );
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
        file_path
    FROM service_media
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $mediaId
]);

$media = $stmt->fetch();

if (!$media) {
    header(
        'Location: ' .
        ADMIN_BASE_URL .
        '/services/index.php?error=not_found'
    );
    exit;
}

$serviceId = (int) $media['service_id'];

$filePath = trim($media['file_path'] ?? '');

/*
|--------------------------------------------------------------------------
| DELETE DATABASE RECORD
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    DELETE FROM service_media
    WHERE id = :id
");

$stmt->execute([
    ':id' => $mediaId
]);

/*
|--------------------------------------------------------------------------
| DELETE PHYSICAL FILE
|--------------------------------------------------------------------------
|
| Chỉ xóa file nằm trong thư mục uploads/services.
|
| Nếu file_path là external URL như:
| https://example.com/image.jpg
|
| thì không làm gì.
|
*/

if ($filePath !== '') {

    $isLocalUpload =
        strpos($filePath, 'uploads/services/') === 0;

    if ($isLocalUpload) {

        $fullPath =
            dirname(__DIR__, 2) .
            '/' .
            $filePath;

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}

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
    '&deleted=1'
);

exit;