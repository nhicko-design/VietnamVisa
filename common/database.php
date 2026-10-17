<?php

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $credentials = [];
    $requestHost = strtolower(
        (string) parse_url(
            '//' . ($_SERVER['HTTP_HOST'] ?? ''),
            PHP_URL_HOST
        )
    );
    $isLocalRequest = in_array(
        $requestHost,
        ['localhost', '127.0.0.1', '::1'],
        true
    );
    $credentialsFile = __DIR__ . (
        $isLocalRequest
            ? '/database.local.php'
            : '/database.credentials.php'
    );

    if (is_file($credentialsFile)) {
        $loadedCredentials = require $credentialsFile;

        if (is_array($loadedCredentials)) {
            $credentials = $loadedCredentials;
        }
    }

    $host = getenv('SUNVIET_DB_HOST') ?: ($credentials['host'] ?? '127.0.0.1');
    $port = getenv('SUNVIET_DB_PORT') ?: ($credentials['port'] ?? '3306');
    $database = getenv('SUNVIET_DB_NAME') ?: ($credentials['database'] ?? 'sunviet');
    $username = getenv('SUNVIET_DB_USER') ?: ($credentials['username'] ?? 'root');
    $password = getenv('SUNVIET_DB_PASSWORD') ?: ($credentials['password'] ?? '');

    if (!preg_match('/^[0-9]{1,5}$/', $port)) {
        throw new RuntimeException('Invalid database port configuration.');
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

    $pdo = new PDO(
        $dsn,
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $pdo;
}
