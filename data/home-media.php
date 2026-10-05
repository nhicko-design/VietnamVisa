<?php

require_once __DIR__ . '/../common/database.php';

$HOME_MEDIA = [];

try {
    $statement = db()->query(
        'SELECT section, media_type, file_path, title, description, link_url
         FROM home_media
         WHERE is_active = 1
         ORDER BY section ASC, sort_order ASC, id ASC'
    );

    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $media) {
        $HOME_MEDIA[$media['section']][] = $media;
    }
} catch (Throwable $exception) {
    // Homepage falls back to its built-in images until the media table is available.
}
