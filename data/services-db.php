<?php

/**
 * Services loaded from MySQL
 *
 * This file converts database data into the same
 * structure used by the existing frontend.
 */

require_once __DIR__ . '/../common/database.php';

$pdo = db();

$stmt = $pdo->query("
    SELECT
        id,
        category,
        slug,
        title,
        short_description,
        description,
        price,
        currency,
        image,
        icon,
        is_featured,
        is_active,
        sort_order
    FROM services
    WHERE is_active = 1
    ORDER BY sort_order ASC, id DESC
");

$dbServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

$SERVICES = [];

foreach ($dbServices as $service) {

    $title = (string) ($service['title'] ?? '');
    $short = (string) ($service['short_description'] ?? '');
    $description = (string) ($service['description'] ?? '');

    $SERVICES[] = [

        'id' => (int) $service['id'],

        'slug' => (string) ($service['slug'] ?? ''),

        'icon' => (string) ($service['icon'] ?? ''),

        'category' => (string) ($service['category'] ?? ''),

        'featured' => (int) ($service['is_featured'] ?? 0) === 1,

        /*
        |--------------------------------------------------------------------------
        | Multilingual fields
        |--------------------------------------------------------------------------
        |
        | Current database has one title/description field.
        | We temporarily expose it through the existing
        | EN/VN structure.
        |
        */

        'title_en' => $title,
        'title_vn' => $title,

        'short_en' => $short,
        'short_vn' => $short,

        'description_en' => $description,
        'description_vn' => $description,

        /*
        |--------------------------------------------------------------------------
        | Original database values
        |--------------------------------------------------------------------------
        */

        'title' => $title,

        'short_description' => $short,

        'description' => $description,

        'price' => $service['price'] !== null
            ? (float) $service['price']
            : null,

        'currency' => (string) ($service['currency'] ?? 'USD'),

        'image' => (string) ($service['image'] ?? ''),

        'is_featured' => (int) ($service['is_featured'] ?? 0),

        'is_active' => (int) ($service['is_active'] ?? 0),

        'sort_order' => (int) ($service['sort_order'] ?? 0),

        /*
        |--------------------------------------------------------------------------
        | Existing frontend structure
        |--------------------------------------------------------------------------
        */

        'features_en' => [],
        'features_vn' => [],

        'steps_en' => [],
        'steps_vn' => [],

        'faq_en' => [],
        'faq_vn' => [],
    ];
}