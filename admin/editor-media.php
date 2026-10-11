<?php

require_once __DIR__ . '/config.php';
require_admin_login();

header('Content-Type: application/json; charset=utf-8');

$pdo = db();
$action = (string) ($_GET['action'] ?? $_POST['action'] ?? 'list');

function editor_media_response(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

function editor_media_public_url(string $path): string
{
    return dirname(ADMIN_BASE_URL) . '/' . ltrim(str_replace('\\', '/', $path), '/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && admin_post_exceeds_server_limit()) {
    editor_media_response(['ok' => false, 'message' => 'The selected file exceeds the server upload limit.'], 413);
}

if ($action === 'list') {
    $items = $pdo->query(
        'SELECT id, media_type, file_path, original_name, title, alt_text, created_at
         FROM editor_media ORDER BY id DESC LIMIT 200'
    )->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as &$item) {
        $item['url'] = editor_media_public_url((string) $item['file_path']);
    }

    editor_media_response(['ok' => true, 'items' => $items]);
}

if ($action !== 'upload' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    editor_media_response(['ok' => false, 'message' => 'Unsupported action.'], 405);
}

if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
    editor_media_response(['ok' => false, 'message' => 'Invalid security token. Refresh the page and try again.'], 403);
}

$file = $_FILES['media_file'] ?? null;
if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    editor_media_response(['ok' => false, 'message' => 'The file could not be uploaded.'], 422);
}

if ((int) $file['size'] > 100 * 1024 * 1024) {
    editor_media_response(['ok' => false, 'message' => 'Maximum file size is 100 MB.'], 422);
}

$allowed = [
    'image/jpeg' => ['image', 'jpg'],
    'image/png' => ['image', 'png'],
    'image/webp' => ['image', 'webp'],
    'image/gif' => ['image', 'gif'],
    'video/mp4' => ['video', 'mp4'],
    'video/webm' => ['video', 'webm'],
    'video/quicktime' => ['video', 'mov'],
];
$mime = (new finfo(FILEINFO_MIME_TYPE))->file((string) $file['tmp_name']);

if (!isset($allowed[$mime])) {
    editor_media_response(['ok' => false, 'message' => 'Use JPG, PNG, WEBP, GIF, MP4, WEBM or MOV.'], 422);
}

[$mediaType, $extension] = $allowed[$mime];
$relativeDirectory = 'uploads/editor/' . $mediaType . 's/';
$directory = dirname(__DIR__) . '/' . $relativeDirectory;

if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
    editor_media_response(['ok' => false, 'message' => 'Unable to create the upload folder.'], 500);
}

$fileName = bin2hex(random_bytes(16)) . '.' . $extension;
if (!move_uploaded_file((string) $file['tmp_name'], $directory . $fileName)) {
    editor_media_response(['ok' => false, 'message' => 'Unable to save the uploaded file.'], 500);
}

$path = $relativeDirectory . $fileName;
$originalName = mb_substr((string) ($file['name'] ?? ''), 0, 255);
$title = mb_substr(pathinfo($originalName, PATHINFO_FILENAME), 0, 255);
$admin = current_admin();
$statement = $pdo->prepare(
    'INSERT INTO editor_media (admin_id, media_type, file_path, original_name, title, alt_text)
     VALUES (?, ?, ?, ?, ?, ?)'
);
$statement->execute([
    (int) ($admin['id'] ?? 0) ?: null,
    $mediaType,
    $path,
    $originalName ?: null,
    $title ?: null,
    $mediaType === 'image' ? ($title ?: null) : null,
]);

editor_media_response([
    'ok' => true,
    'item' => [
        'id' => (int) $pdo->lastInsertId(),
        'media_type' => $mediaType,
        'file_path' => $path,
        'url' => editor_media_public_url($path),
        'title' => $title,
        'alt_text' => $mediaType === 'image' ? $title : '',
    ],
]);
