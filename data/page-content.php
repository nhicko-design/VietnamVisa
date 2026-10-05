<?php

require_once __DIR__ . '/../common/database.php';


function page_content_value(
    string $page,
    string $field,
    string $fallback = ''
): string {
    static $settings = [];

    $language = $GLOBALS['current_lang'] ?? 'en';
    $cacheKey = $page . ':' . $field . ':' . $language;

    if (array_key_exists($cacheKey, $settings)) {
        return $settings[$cacheKey] !== ''
            ? $settings[$cacheKey]
            : $fallback;
    }

    $settingKey = 'page_' . $page . '_' . $field . '_' . $language;
    $englishKey = 'page_' . $page . '_' . $field . '_en';

    try {
        $statement = db()->prepare(
            'SELECT setting_key, setting_value
             FROM settings
             WHERE setting_key IN (:setting_key, :english_key)'
        );
        $statement->execute([
            'setting_key' => $settingKey,
            'english_key' => $englishKey,
        ]);

        $values = [];

        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $setting) {
            $values[$setting['setting_key']] = trim((string) $setting['setting_value']);
        }

        $value = $values[$settingKey] ?? '';

        if ($value === '' && $language === 'vn') {
            $value = $values[$englishKey] ?? '';
        }

        $settings[$cacheKey] = $value;
    } catch (Throwable $exception) {
        $settings[$cacheKey] = '';
    }

    return $settings[$cacheKey] !== ''
        ? $settings[$cacheKey]
        : $fallback;
}
