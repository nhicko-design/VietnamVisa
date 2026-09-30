<?php

/**
 * Load services from database
 *
 * Keeps the same $SERVICES structure
 * expected by the existing frontend.
 */

require_once __DIR__ . '/../common/database.php';

$pdo = db();

$stmt = $pdo->query("
    SELECT
        id,
        category,
        slug,

        title,
        title_vn,

        short_description,
        short_description_vn,

        description,
        description_vn,

        price,
        currency,
        image,
        icon,

        is_featured,
        is_active,
        sort_order,

        created_at,
        updated_at

    FROM services

    WHERE is_active = 1

    ORDER BY
        sort_order ASC,
        id DESC
");

$dbServices = $stmt->fetchAll(PDO::FETCH_ASSOC);

$SERVICES = [];


foreach ($dbServices as $service) {

    $titleEn = (string) ($service['title'] ?? '');
    $titleVn = (string) ($service['title_vn'] ?? '');

    $shortEn = (string) ($service['short_description'] ?? '');
    $shortVn = (string) ($service['short_description_vn'] ?? '');

    $descriptionEn = (string) ($service['description'] ?? '');
    $descriptionVn = (string) ($service['description_vn'] ?? '');


    /*
     * Fallback:
     *
     * If Vietnamese content is empty,
     * use English content instead.
     */

    if ($titleVn === '') {
        $titleVn = $titleEn;
    }

    if ($shortVn === '') {
        $shortVn = $shortEn;
    }

    if ($descriptionVn === '') {
        $descriptionVn = $descriptionEn;
    }


    $SERVICES[] = [

        'id' => (int) $service['id'],

        'slug' => (string) $service['slug'],

        'icon' => (string) ($service['icon'] ?? ''),

        'category' => (string) ($service['category'] ?? ''),

        'featured' =>
            (int) ($service['is_featured'] ?? 0) === 1,


        /*
         * English
         */

        'title_en' => $titleEn,

        'short_en' => $shortEn,

        'description_en' => $descriptionEn,


        /*
         * Vietnamese
         */

        'title_vn' => $titleVn,

        'short_vn' => $shortVn,

        'description_vn' => $descriptionVn,


        /*
         * Generic fields
         *
         * These are kept for compatibility
         * with existing frontend code.
         */

        'title' => $titleEn,

        'short_description' => $shortEn,

        'description' => $descriptionEn,


        /*
         * Pricing
         */

        'price' =>
            $service['price'] !== null
                ? (float) $service['price']
                : null,

        'currency' =>
            (string) ($service['currency'] ?? 'USD'),


        /*
         * Media
         */

        'image' =>
            (string) ($service['image'] ?? ''),


        /*
         * Status
         */

        'is_featured' =>
            (int) ($service['is_featured'] ?? 0),

        'is_active' =>
            (int) ($service['is_active'] ?? 0),

        'sort_order' =>
            (int) ($service['sort_order'] ?? 0),


        /*
         * Related content
         *
         * These will be loaded by find_service()
         * when opening the detail page.
         */

        'features_en' => [],
        'features_vn' => [],

        'steps_en' => [],
        'steps_vn' => [],

        'faq_en' => [],
        'faq_vn' => [],
    ];
}