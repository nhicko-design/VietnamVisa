<?php

require_once __DIR__ . '/../common/database.php';

try {

    $pdo = db();

    echo '<h2>Database connection: SUCCESS</h2>';

    echo '<p>Database: sunviet</p>';

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    echo '<h3>Tables:</h3>';

    echo '<ul>';

    foreach ($tables as $table) {
        echo '<li>' . htmlspecialchars($table) . '</li>';
    }

    echo '</ul>';

} catch (Throwable $e) {

    echo '<h2>Database connection: FAILED</h2>';

    echo '<pre>';
    echo htmlspecialchars($e->getMessage());
    echo '</pre>';
}