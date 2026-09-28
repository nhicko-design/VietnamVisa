<?php

function request_path(): string
{
    $path = trim(
        parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ),
        '/'
    );

    $base = trim(
        str_replace(
            '\\',
            '/',
            dirname($_SERVER['SCRIPT_NAME'] ?? '')
        ),
        '/'
    );

    if (
        $base !== '' &&
        strpos($path, $base) === 0
    ) {
        $path = trim(
            substr(
                $path,
                strlen($base)
            ),
            '/'
        );
    }

    return $path;
}


function app_base(): string
{
    $base = str_replace(
        '\\',
        '/',
        dirname($_SERVER['SCRIPT_NAME'] ?? '')
    );

    return $base === '/'
        ? ''
        : rtrim($base, '/');
}


function url(
    string $path = '',
    ?string $lang = null
): string {
    global $current_lang;

    $lang = $lang ?: (
        $current_lang ?? 'en'
    );

    $path = trim($path, '/');

    return app_base()
        . '/'
        . $lang
        . (
            $path
                ? '/' . $path
                : ''
        );
}

function asset_url(string $path): string
{
    $path = ltrim(
        str_replace('\\', '/', $path),
        '/'
    );

    return app_base() . '/' . $path;
}

function e($value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


function field(
    array $item,
    string $key,
    ?string $lang = null
): string {
    global $current_lang;

    $lang = $lang ?: (
        $current_lang ?? 'en'
    );

    $value =
        $item[$key . '_' . $lang]
        ?? $item[$key . '_en']
        ?? $item[$key]
        ?? '';

    return is_string($value)
        ? $value
        : '';
}


function field_array(
    array $item,
    string $key,
    ?string $lang = null
): array {
    global $current_lang;

    $lang = $lang ?: (
        $current_lang ?? 'en'
    );

    $value =
        $item[$key . '_' . $lang]
        ?? $item[$key . '_en']
        ?? $item[$key]
        ?? [];

    return is_array($value)
        ? $value
        : [];
}


/**
 * Find one active service by slug.
 *
 * Service data is loaded directly from MySQL,
 * including media, features, steps and FAQ.
 */
function find_service(
    string $slug
): ?array {

    require_once __DIR__ . '/../common/database.php';

    $pdo = db();

    /*
     * =========================================================
     * SERVICE
     * =========================================================
     */

    $stmt = $pdo->prepare("
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
            sort_order,
            created_at,
            updated_at
        FROM services
        WHERE slug = ?
          AND is_active = 1
        LIMIT 1
    ");

    $stmt->execute([
        $slug
    ]);

    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        return null;
    }


    $serviceId = (int) $service['id'];


    /*
     * =========================================================
     * BASIC SERVICE DATA
     * =========================================================
     */

    $title = (string) (
        $service['title'] ?? ''
    );

    $short = (string) (
        $service['short_description'] ?? ''
    );

    $description = (string) (
        $service['description'] ?? ''
    );


    /*
     * =========================================================
     * FEATURES
     * =========================================================
     */

    $stmt = $pdo->prepare("
        SELECT
            id,
            title,
            description,
            sort_order,
            is_active
        FROM service_features
        WHERE service_id = ?
          AND is_active = 1
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        $serviceId
    ]);

    $features = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
     * =========================================================
     * STEPS
     * =========================================================
     */

    $stmt = $pdo->prepare("
        SELECT
            id,
            step_number,
            title,
            description,
            sort_order,
            is_active
        FROM service_steps
        WHERE service_id = ?
          AND is_active = 1
        ORDER BY sort_order ASC, step_number ASC, id ASC
    ");

    $stmt->execute([
        $serviceId
    ]);

    $steps = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
     * =========================================================
     * FAQ
     * =========================================================
     */

    $stmt = $pdo->prepare("
        SELECT
            id,
            question,
            answer,
            sort_order,
            is_active
        FROM service_faq
        WHERE service_id = ?
          AND is_active = 1
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        $serviceId
    ]);

    $faq = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
     * =========================================================
     * MEDIA
     * =========================================================
     */

    $stmt = $pdo->prepare("
        SELECT
            id,
            media_type,
            file_path,
            title,
            alt_text,
            sort_order,
            is_active
        FROM service_media
        WHERE service_id = ?
          AND is_active = 1
        ORDER BY sort_order ASC, id ASC
    ");

    $stmt->execute([
        $serviceId
    ]);

    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
     * =========================================================
     * CONVERT TO EXISTING FRONTEND STRUCTURE
     * =========================================================
     */

    $featureTitles = [];

    foreach ($features as $feature) {

        $featureTitles[] = [
            'id' => (int) $feature['id'],
            'title' => (string) ($feature['title'] ?? ''),
            'description' => (string) ($feature['description'] ?? ''),
            'sort_order' => (int) ($feature['sort_order'] ?? 0),
        ];
    }


    $stepData = [];

    foreach ($steps as $step) {

        $stepData[] = [
            'id' => (int) $step['id'],
            'step_number' => (int) ($step['step_number'] ?? 1),
            'title' => (string) ($step['title'] ?? ''),
            'description' => (string) ($step['description'] ?? ''),
            'sort_order' => (int) ($step['sort_order'] ?? 0),
        ];
    }


    $faqData = [];

    foreach ($faq as $item) {

        $faqData[] = [
            'id' => (int) $item['id'],
            'q' => (string) ($item['question'] ?? ''),
            'a' => (string) ($item['answer'] ?? ''),
            'sort_order' => (int) ($item['sort_order'] ?? 0),
        ];
    }


    /*
     * =========================================================
     * RETURN SERVICE
     * =========================================================
     */

    return [

        'id' => $serviceId,

        'slug' => (string) (
            $service['slug'] ?? ''
        ),

        'icon' => (string) (
            $service['icon'] ?? ''
        ),

        'category' => (string) (
            $service['category'] ?? ''
        ),

        'featured' =>
            (int) (
                $service['is_featured'] ?? 0
            ) === 1,


        /*
         * Existing multilingual structure.
         *
         * At the moment the DB has one title/description
         * field, so it is used for both EN and VN.
         */

        'title_en' => $title,
        'title_vn' => $title,

        'short_en' => $short,
        'short_vn' => $short,

        'description_en' => $description,
        'description_vn' => $description,


        /*
         * Existing direct fields
         */

        'title' => $title,

        'short_description' => $short,

        'description' => $description,


        /*
         * Price
         */

        'price' =>
            $service['price'] !== null
                ? (float) $service['price']
                : null,

        'currency' => (string) (
            $service['currency'] ?? 'USD'
        ),


        /*
         * Main service image
         */

        'image' => (string) (
            $service['image'] ?? ''
        ),


        /*
         * Status
         */

        'is_featured' => (int) (
            $service['is_featured'] ?? 0
        ),

        'is_active' => (int) (
            $service['is_active'] ?? 0
        ),

        'sort_order' => (int) (
            $service['sort_order'] ?? 0
        ),


        /*
         * Timestamps
         */

        'created_at' =>
            $service['created_at'] ?? null,

        'updated_at' =>
            $service['updated_at'] ?? null,


        /*
         * =====================================================
         * RELATED DATA
         * =====================================================
         */

        'features' => $featureTitles,
        'features_en' => $featureTitles,
        'features_vn' => $featureTitles,

        'steps' => $stepData,
        'steps_en' => $stepData,
        'steps_vn' => $stepData,

        'faq' => $faqData,
        'faq_en' => $faqData,
        'faq_vn' => $faqData,

        'media' => $media,
    ];
}


function find_news(
    string $slug
): ?array {
    global $NEWS;

    foreach (
        $NEWS as $article
    ) {
        if (
            ($article['slug'] ?? '')
            === $slug
        ) {
            return $article;
        }
    }

    return null;
}


function active(
    string $currentRoute
): string {
    global $route;

    return $route === $currentRoute
        ? 'is-active'
        : '';
}


function format_date(
    string $date
): string {
    $time = strtotime($date);

    return $time
        ? date('M d, Y', $time)
        : $date;
}