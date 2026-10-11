<?php

/** Validate a multi-file upload before saving the owner record. */
function admin_media_upload_errors(array $files): array
{
    if (admin_post_exceeds_server_limit()) {
        return ['The selected files exceed the server POST limit.'];
    }

    $errors = [];

    foreach (($files['error'] ?? []) as $index => $error) {
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($error !== UPLOAD_ERR_OK || ($files['size'][$index] ?? 0) > 100 * 1024 * 1024) {
            $errors[] = 'One media file failed to upload or exceeds 100 MB.';
            continue;
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($files['tmp_name'][$index]);

        if (!isset(admin_media_mime_map()[$mime])) {
            $errors[] = 'Media must be JPG, PNG, WEBP, GIF, MP4, WEBM or MOV.';
        }
    }

    return $errors;
}

function admin_media_mime_map(): array
{
    return [
        'image/jpeg' => ['image', 'jpg'],
        'image/png' => ['image', 'png'],
        'image/webp' => ['image', 'webp'],
        'image/gif' => ['image', 'gif'],
        'video/mp4' => ['video', 'mp4'],
        'video/webm' => ['video', 'webm'],
        'video/quicktime' => ['video', 'mov'],
    ];
}

/** Returns the first uploaded image path so it can be used as a cover. */
function admin_save_media_uploads(PDO $pdo, array $files, string $kind, int $ownerId): ?string
{
    $targets = [
        'service' => ['service_media', 'service_id', 'services'],
        'news' => ['news_media', 'news_id', 'news'],
        'region' => ['visa_media', 'region_id', 'visa'],
        'country' => ['visa_media', 'country_id', 'visa'],
    ];

    if (!isset($targets[$kind])) {
        throw new InvalidArgumentException('Unsupported media owner.');
    }

    [$table, $ownerColumn, $folder] = $targets[$kind];
    $firstImage = null;
    $orderStatement = $pdo->prepare(
        "SELECT COALESCE(MAX(sort_order), 0) FROM $table WHERE $ownerColumn = ?"
    );
    $orderStatement->execute([$ownerId]);
    $nextSortOrder = (int) $orderStatement->fetchColumn() + 1;

    foreach (($files['error'] ?? []) as $index => $error) {
        if ($error === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($files['tmp_name'][$index]);
        [$mediaType, $extension] = admin_media_mime_map()[$mime];
        $relativeDirectory = 'uploads/' . $folder . '/' . $mediaType . 's/';
        $directory = dirname(__DIR__) . '/' . $relativeDirectory;

        if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
            throw new RuntimeException('Unable to create media directory.');
        }

        $fileName = bin2hex(random_bytes(16)) . '.' . $extension;

        if (!move_uploaded_file($files['tmp_name'][$index], $directory . $fileName)) {
            throw new RuntimeException('Unable to save media file.');
        }

        $path = $relativeDirectory . $fileName;
        $statement = $pdo->prepare(
            "INSERT INTO $table ($ownerColumn, media_type, file_path, title, alt_text, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, 1)"
        );
        $statement->execute([
            $ownerId,
            $mediaType,
            $path,
            pathinfo((string) ($files['name'][$index] ?? ''), PATHINFO_FILENAME),
            $mediaType === 'image' ? pathinfo((string) ($files['name'][$index] ?? ''), PATHINFO_FILENAME) : null,
            $nextSortOrder++,
        ]);

        try {
            $admin = function_exists('current_admin') ? current_admin() : null;
            $libraryStatement = $pdo->prepare(
                'INSERT INTO editor_media
                 (admin_id, media_type, file_path, original_name, title, alt_text)
                 VALUES (?, ?, ?, ?, ?, ?)'
            );
            $originalName = (string) ($files['name'][$index] ?? '');
            $mediaTitle = pathinfo($originalName, PATHINFO_FILENAME);
            $libraryStatement->execute([
                (int) ($admin['id'] ?? 0) ?: null,
                $mediaType,
                $path,
                $originalName ?: null,
                $mediaTitle ?: null,
                $mediaType === 'image' ? ($mediaTitle ?: null) : null,
            ]);
        } catch (Throwable $exception) {
            // Hero/gallery upload remains usable while the editor library migration is pending.
        }

        if ($mediaType === 'image' && $firstImage === null) {
            $firstImage = $path;
        }
    }

    return $firstImage;
}
