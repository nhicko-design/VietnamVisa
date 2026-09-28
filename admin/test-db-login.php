<?php

require_once __DIR__ . '/../common/database.php';

echo '<h2>Database Connection Test</h2>';

try {

    $pdo = db();

    echo '<p style="color:green;">
        ✓ Database connection successful
    </p>';

    echo '<p>Database: sunviet</p>';

    $stmt = $pdo->query("SELECT COUNT(*) FROM admin_users");

    $count = $stmt->fetchColumn();

    echo '<p>Admin users: ' . (int) $count . '</p>';

} catch (Throwable $e) {

    echo '<p style="color:red;">
        ✗ Database connection failed
    </p>';

    echo '<pre>';
    echo htmlspecialchars(
        $e->getMessage(),
        ENT_QUOTES,
        'UTF-8'
    );
    echo '</pre>';
}