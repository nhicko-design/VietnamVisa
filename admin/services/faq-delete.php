<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

if (
    !isset($_POST['csrf_token']) ||
    !verify_csrf_token($_POST['csrf_token'])
) {
    die('Invalid CSRF token.');
}

$id = isset($_POST['id'])
    ? (int) $_POST['id']
    : 0;

$serviceId = isset($_POST['service_id'])
    ? (int) $_POST['service_id']
    : 0;

if (
    $id <= 0 ||
    $serviceId <= 0
) {
    header('Location: index.php');
    exit;
}

$pdo = db();

$stmt = $pdo->prepare("
    DELETE FROM service_faq
    WHERE id = ?
      AND service_id = ?
");

$stmt->execute([
    $id,
    $serviceId
]);

header(
    'Location: faq.php?id='
    . $serviceId
    . '&deleted=1'
);

exit;