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


function public_origin(): string
{
    $isHttps = !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off';

    $host = (string) ($_SERVER['HTTP_HOST'] ?? 'localhost');

    if (!preg_match('/^[a-z0-9.-]+(?::[0-9]{1,5})?$/i', $host)) {
        $host = 'localhost';
    }

    return ($isHttps ? 'https' : 'http') . '://' . $host;
}


function absolute_url(
    string $path = '',
    ?string $lang = null
): string {
    return public_origin() . url($path, $lang);
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
    if (preg_match('~^https?://~i', $path)) {
        return $path;
    }

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


function field(array $item, string $key): string
{
    global $current_lang;

    $lang = $current_lang ?? 'en';

    $langKey = $key . '_' . $lang;

    if (array_key_exists($langKey, $item)) {
        return (string) $item[$langKey];
    }

    if (array_key_exists($key, $item)) {
        return (string) $item[$key];
    }

    return '';
}


function field_array(array $item, string $key): array
{
    global $current_lang;

    $lang = $current_lang ?? 'en';

    $langKey = $key . '_' . $lang;

    if (
        array_key_exists($langKey, $item)
        && is_array($item[$langKey])
    ) {
        return $item[$langKey];
    }

    if (
        array_key_exists($key, $item)
        && is_array($item[$key])
    ) {
        return $item[$key];
    }

    return [];
}


/**
 * Find one active service by slug.
 *
 * Service data is loaded directly from MySQL,
 * including media, features, steps and FAQ.
 */
function find_service(string $slug): ?array
{
    require_once __DIR__ . '/database.php';

    $pdo = db();


    /*
     * ---------------------------------------------------------
     * Load service
     * ---------------------------------------------------------
     */

    $stmt = $pdo->prepare("
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

        WHERE slug = ?
          AND is_active = 1

        LIMIT 1
    ");

    $stmt->execute([$slug]);

    $service = $stmt->fetch(PDO::FETCH_ASSOC);


    if (!$service) {
        return null;
    }


    /*
     * ---------------------------------------------------------
     * Basic service data
     * ---------------------------------------------------------
     */

    $titleEn = (string) ($service['title'] ?? '');
    $titleVn = (string) ($service['title_vn'] ?? '');

    $shortEn = (string) ($service['short_description'] ?? '');
    $shortVn = (string) ($service['short_description_vn'] ?? '');

    $descriptionEn = (string) ($service['description'] ?? '');
    $descriptionVn = (string) ($service['description_vn'] ?? '');


    /*
     * Vietnamese fallback
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


    /*
     * ---------------------------------------------------------
     * Features
     * ---------------------------------------------------------
     */

    $stmt = $pdo->prepare("
        SELECT
            id,

            title,
            description,

            title_vn,
            description_vn,

            sort_order,
            is_active

        FROM service_features

        WHERE service_id = ?
          AND is_active = 1

        ORDER BY
            sort_order ASC,
            id ASC
    ");

    $stmt->execute([
        (int) $service['id']
    ]);

    $featureRows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $featuresEn = [];
    $featuresVn = [];


    foreach ($featureRows as $feature) {

        $featureTitleEn =
            (string) ($feature['title'] ?? '');

        $featureDescriptionEn =
            (string) ($feature['description'] ?? '');

        $featureTitleVn =
            (string) ($feature['title_vn'] ?? '');

        $featureDescriptionVn =
            (string) ($feature['description_vn'] ?? '');


        if ($featureTitleVn === '') {
            $featureTitleVn = $featureTitleEn;
        }

        if ($featureDescriptionVn === '') {
            $featureDescriptionVn = $featureDescriptionEn;
        }


        $featuresEn[] = [
            'id' => (int) $feature['id'],
            'title' => $featureTitleEn,
            'description' => $featureDescriptionEn,
            'sort_order' => (int) $feature['sort_order'],
        ];


        $featuresVn[] = [
            'id' => (int) $feature['id'],
            'title' => $featureTitleVn,
            'description' => $featureDescriptionVn,
            'sort_order' => (int) $feature['sort_order'],
        ];
    }


    /*
     * ---------------------------------------------------------
     * Steps
     * ---------------------------------------------------------
     */

    $stmt = $pdo->prepare("
        SELECT
            id,
            step_number,

            title,
            description,

            title_vn,
            description_vn,

            sort_order,
            is_active

        FROM service_steps

        WHERE service_id = ?
          AND is_active = 1

        ORDER BY
            sort_order ASC,
            step_number ASC,
            id ASC
    ");

    $stmt->execute([
        (int) $service['id']
    ]);

    $stepRows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $stepsEn = [];
    $stepsVn = [];


    foreach ($stepRows as $step) {

        $stepTitleEn =
            (string) ($step['title'] ?? '');

        $stepDescriptionEn =
            (string) ($step['description'] ?? '');

        $stepTitleVn =
            (string) ($step['title_vn'] ?? '');

        $stepDescriptionVn =
            (string) ($step['description_vn'] ?? '');


        if ($stepTitleVn === '') {
            $stepTitleVn = $stepTitleEn;
        }

        if ($stepDescriptionVn === '') {
            $stepDescriptionVn = $stepDescriptionEn;
        }


        $stepsEn[] = [
            'id' => (int) $step['id'],
            'step_number' => (int) $step['step_number'],
            'title' => $stepTitleEn,
            'description' => $stepDescriptionEn,
            'sort_order' => (int) $step['sort_order'],
        ];


        $stepsVn[] = [
            'id' => (int) $step['id'],
            'step_number' => (int) $step['step_number'],
            'title' => $stepTitleVn,
            'description' => $stepDescriptionVn,
            'sort_order' => (int) $step['sort_order'],
        ];
    }


    /*
     * ---------------------------------------------------------
     * FAQ
     * ---------------------------------------------------------
     */

    $stmt = $pdo->prepare("
        SELECT
            id,

            question,
            answer,

            question_vn,
            answer_vn,

            sort_order,
            is_active

        FROM service_faq

        WHERE service_id = ?
          AND is_active = 1

        ORDER BY
            sort_order ASC,
            id ASC
    ");

    $stmt->execute([
        (int) $service['id']
    ]);

    $faqRows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $faqEn = [];
    $faqVn = [];


    foreach ($faqRows as $faq) {

        $questionEn =
            (string) ($faq['question'] ?? '');

        $answerEn =
            (string) ($faq['answer'] ?? '');

        $questionVn =
            (string) ($faq['question_vn'] ?? '');

        $answerVn =
            (string) ($faq['answer_vn'] ?? '');


        if ($questionVn === '') {
            $questionVn = $questionEn;
        }

        if ($answerVn === '') {
            $answerVn = $answerEn;
        }


        $faqEn[] = [
            'id' => (int) $faq['id'],
            'q' => $questionEn,
            'a' => $answerEn,
            'sort_order' => (int) $faq['sort_order'],
        ];


        $faqVn[] = [
            'id' => (int) $faq['id'],
            'q' => $questionVn,
            'a' => $answerVn,
            'sort_order' => (int) $faq['sort_order'],
        ];
    }


    /*
     * ---------------------------------------------------------
     * Service media
     * ---------------------------------------------------------
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

        ORDER BY
            sort_order ASC,
            id ASC
    ");

    $stmt->execute([
        (int) $service['id']
    ]);

    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);


    /*
     * ---------------------------------------------------------
     * Return final service structure
     * ---------------------------------------------------------
     */

    return [

        'id' => (int) $service['id'],

        'slug' => (string) $service['slug'],

        'category' => (string) $service['category'],

        'icon' => (string) ($service['icon'] ?? ''),

        'image' => (string) ($service['image'] ?? ''),


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
         * Generic compatibility fields
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
         * Status
         */

        'is_featured' =>
            (int) ($service['is_featured'] ?? 0),

        'is_active' =>
            (int) ($service['is_active'] ?? 0),

        'sort_order' =>
            (int) ($service['sort_order'] ?? 0),


        /*
         * Features
         */

        'features_en' => $featuresEn,

        'features_vn' => $featuresVn,


        /*
         * Steps
         */

        'steps_en' => $stepsEn,

        'steps_vn' => $stepsVn,


        /*
         * FAQ
         */

        'faq_en' => $faqEn,

        'faq_vn' => $faqVn,


        /*
         * Media
         */

        'media' => $media,
    ];
}


function find_news(
    string $slug
): ?array {
    global $NEWS;

    foreach ($NEWS as $article) {
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
