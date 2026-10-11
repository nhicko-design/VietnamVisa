<?php

require_once __DIR__ . '/../common/database.php';
require_once __DIR__ . '/../common/auth.php';


define(
    'ADMIN_BASE_URL',
    '/VietnamVisa/admin'
);

function admin_ini_size_to_bytes(string $value): int
{
    $value = trim($value);

    if ($value === '') {
        return 0;
    }

    $unit = strtolower(substr($value, -1));
    $bytes = (float) $value;

    return match ($unit) {
        'g' => (int) ($bytes * 1024 * 1024 * 1024),
        'm' => (int) ($bytes * 1024 * 1024),
        'k' => (int) ($bytes * 1024),
        default => (int) $bytes,
    };
}

function admin_post_exceeds_server_limit(): bool
{
    $contentLength = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);
    $postLimit = admin_ini_size_to_bytes((string) ini_get('post_max_size'));

    return $contentLength > 0 && $postLimit > 0 && $contentLength > $postLimit;
}

function admin_service_section(PDO $pdo, int $serviceId): string
{
    $statement = $pdo->prepare('SELECT category FROM services WHERE id = ?');
    $statement->execute([$serviceId]);
    $category = (string) $statement->fetchColumn();

    return match ($category) {
        'visa' => 'vietnam-visa',
        'immigration' => 'temporary-resident-card',
        'support' => 'other-services',
        default => 'services',
    };
}

function admin_service_manage_url(PDO $pdo, int $serviceId): string
{
    $section = admin_service_section($pdo, $serviceId);
    $scope = match ($section) {
        'vietnam-visa' => 'vietnam-visa',
        'other-services' => 'other-services',
        default => '',
    };

    return ADMIN_BASE_URL . '/services/manage.php'
        . ($scope !== '' ? '?scope=' . rawurlencode($scope) : '');
}

function admin_media_url(string $path): string
{
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

    return dirname(ADMIN_BASE_URL) . '/' . ltrim(
        str_replace('\\', '/', $path),
        '/'
    );
}

function admin_local_service_upload_path(string $path): ?string
{
    $path = str_replace('\\', '/', trim($path));

    if (
        $path === '' ||
        strpos($path, "\0") !== false ||
        strpos($path, 'uploads/services/') !== 0
    ) {
        return null;
    }

    $uploadRoot = realpath(dirname(__DIR__) . '/uploads/services');
    $candidate = realpath(dirname(__DIR__) . '/' . $path);

    if ($uploadRoot === false || $candidate === false) {
        return null;
    }

    $rootPrefix = rtrim($uploadRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    if (strncasecmp($candidate, $rootPrefix, strlen($rootPrefix)) !== 0) {
        return null;
    }

    return $candidate;
}

function admin_local_news_upload_path(string $path): ?string
{
    $path = str_replace('\\', '/', trim($path));

    if (
        $path === '' ||
        strpos($path, "\0") !== false ||
        strpos($path, 'uploads/news/') !== 0
    ) {
        return null;
    }

    $uploadRoot = realpath(dirname(__DIR__) . '/uploads/news');
    $candidate = realpath(dirname(__DIR__) . '/' . $path);

    if ($uploadRoot === false || $candidate === false) {
        return null;
    }

    $rootPrefix = rtrim($uploadRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    if (strncasecmp($candidate, $rootPrefix, strlen($rootPrefix)) !== 0) {
        return null;
    }

    return $candidate;
}

function admin_local_home_upload_path(string $path): ?string
{
    $path = str_replace('\\', '/', trim($path));

    if (
        $path === '' ||
        strpos($path, "\0") !== false ||
        strpos($path, 'uploads/home/') !== 0
    ) {
        return null;
    }

    $uploadRoot = realpath(dirname(__DIR__) . '/uploads/home');
    $candidate = realpath(dirname(__DIR__) . '/' . $path);

    if ($uploadRoot === false || $candidate === false) {
        return null;
    }

    $rootPrefix = rtrim($uploadRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    return strncasecmp($candidate, $rootPrefix, strlen($rootPrefix)) === 0
        ? $candidate
        : null;
}


function admin_local_page_upload_path(string $path): ?string
{
    $path = str_replace('\\', '/', trim($path));

    if (
        $path === '' ||
        strpos($path, "\0") !== false ||
        strpos($path, 'uploads/pages/') !== 0
    ) {
        return null;
    }

    $uploadRoot = realpath(dirname(__DIR__) . '/uploads/pages');
    $candidate = realpath(dirname(__DIR__) . '/' . $path);

    if ($uploadRoot === false || $candidate === false) {
        return null;
    }

    $rootPrefix = rtrim($uploadRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    return strncasecmp($candidate, $rootPrefix, strlen($rootPrefix)) === 0
        ? $candidate
        : null;
}
