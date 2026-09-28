<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$pdo = db();

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
| GET SERVICE ID
|--------------------------------------------------------------------------
*/

$serviceId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($serviceId <= 0) {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php?error=invalid_id');
    exit;
}

/*
|--------------------------------------------------------------------------
| CHECK SERVICE EXISTS
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    SELECT id, title
    FROM services
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ':id' => $serviceId
]);

$service = $stmt->fetch();

if (!$service) {
    header('Location: ' . ADMIN_BASE_URL . '/services/index.php?error=not_found');
    exit;
}

/*
|--------------------------------------------------------------------------
| DELETE SERVICE
|--------------------------------------------------------------------------
|
| service_media có foreign key ON DELETE CASCADE,
| nên media của service này sẽ được xóa theo.
|
*/

$stmt = $pdo->prepare("
    DELETE FROM services
    WHERE id = :id
");

$stmt->execute([
    ':id' => $serviceId
]);

header(
    'Location: ' . ADMIN_BASE_URL . '/services/index.php?deleted=1'
);

exit;