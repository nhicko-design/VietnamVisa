<?php

require_once __DIR__ . '/data/services-db.php';

echo '<h1>Service DB Test</h1>';

echo '<pre>';

echo 'Number of services: ' . count($SERVICES);

echo "\n\n";

print_r($SERVICES);

echo '</pre>';