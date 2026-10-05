<?php

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('SUNVIET_DB_HOST') ?: '127.0.0.1';
    $port = getenv('SUNVIET_DB_PORT') ?: '3306';
    $database = getenv('SUNVIET_DB_NAME') ?: 'sunviet';
    $username = getenv('SUNVIET_DB_USER') ?: 'root';
    $password = getenv('SUNVIET_DB_PASSWORD') ?: '';

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
