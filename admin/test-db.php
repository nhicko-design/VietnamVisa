<?php

require_once __DIR__ . '/config.php';

require_admin_login();

try {
    db()->query('SELECT 1');

    http_response_code(204);
} catch (Throwable $exception) {
    error_log('Admin database health check failed: ' . $exception->getMessage());

    http_response_code(503);
}
