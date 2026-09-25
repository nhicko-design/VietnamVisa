<?php

/**
 * Immigra - Database Connection
 *
 * XAMPP default MySQL configuration:
 *
 * Host:     127.0.0.1
 * Port:     3306
 * Database: immigra
 * Username: root
 * Password: empty
 */

function db(): PDO
{
    static $pdo = null;

    // Reuse existing connection
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // ==============================
    // DATABASE CONFIGURATION
    // ==============================

    $host = '127.0.0.1';
    $port = '3306';
    $database = 'immigra';
    $username = 'root';
    $password = '';

    // ==============================
    // PDO CONNECTION
    // ==============================

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