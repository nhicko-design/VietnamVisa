<?php

/**
 * ============================================================
 * VietnamVisa ADMIN CONFIGURATION
 * ============================================================
 */

require_once __DIR__ . '/../common/database.php';


/**
 * Admin base URL.
 *
 * XAMPP:
 * http://localhost/VietnamVisa/admin
 */
define(
    'ADMIN_BASE_URL',
    '/VietnamVisa/admin'
);

$siteDefaults = [
    'site_name' => 'SunViet',
    'site_email' => 'info@sunviettravel.com',
    'site_phone' => '+84 90 123 4567',
    'default_language' => 'en',
    'site_address' => '',
    'facebook_url' => '',
    'messenger_url' => '',
    'zalo_url' => '',
];

try {
    $statement = db()->query(
        "SELECT setting_key, setting_value
         FROM settings
         WHERE setting_key IN (
            'site_name', 'site_email', 'site_phone', 'default_language',
            'site_address', 'facebook_url', 'messenger_url', 'zalo_url'
         )"
    );

    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $setting) {
        if ($setting['setting_value'] !== null && $setting['setting_value'] !== '') {
            $siteDefaults[$setting['setting_key']] = (string) $setting['setting_value'];
        }
    }
} catch (Throwable $exception) {
    // The website can still load before the settings table has been migrated.
}

if (!defined('SITE_NAME')) {
    define('SITE_NAME', $siteDefaults['site_name']);
}

if (!defined('SITE_EMAIL')) {
    define('SITE_EMAIL', $siteDefaults['site_email']);
}

if (!defined('SITE_PHONE')) {
    define('SITE_PHONE', $siteDefaults['site_phone']);
}

if (!defined('DEFAULT_LANGUAGE')) {
    define('DEFAULT_LANGUAGE', $siteDefaults['default_language']);
}

if (!defined('SITE_ADDRESS')) {
    define('SITE_ADDRESS', $siteDefaults['site_address']);
}

if (!defined('FACEBOOK_URL')) {
    define('FACEBOOK_URL', $siteDefaults['facebook_url']);
}

if (!defined('MESSENGER_URL')) {
    define('MESSENGER_URL', $siteDefaults['messenger_url']);
}

if (!defined('ZALO_URL')) {
    define('ZALO_URL', $siteDefaults['zalo_url']);
}
