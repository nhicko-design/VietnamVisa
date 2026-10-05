<?php

require_once __DIR__ . '/../common/database.php';
require_once __DIR__ . '/../common/auth.php';


define(
    'ADMIN_BASE_URL',
    '/VietnamVisa/admin'
);

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
