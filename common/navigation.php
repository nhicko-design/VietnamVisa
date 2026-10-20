<?php

/** Build the public navigation from the active services already loaded by the site. */
function public_navigation_items(array $services, string $currentPath, string $language): array
{
    $isVietnamese = $language === 'vn';
    $serviceLink = static function (array $item) use ($isVietnamese): array {
        return [
            'label' => $isVietnamese && !empty($item['title_vn'])
                ? (string) $item['title_vn']
                : (string) ($item['title_en'] ?? $item['title'] ?? ''),
            'path' => 'service/' . $item['slug'],
        ];
    };

    $visaChildren = [];
    $residentChildren = [];
    $supportChildren = [];

    foreach ($services as $item) {
        if (empty($item['slug']) || empty($item['is_active'])) {
            continue;
        }

        switch (strtolower((string) ($item['category'] ?? ''))) {
            case 'visa':
                $visaChildren[] = $serviceLink($item);
                break;
            case 'immigration':
                if (!in_array($item['slug'], ['work-permit', 'temporary-residence-card'], true)) {
                    $residentChildren[] = $serviceLink($item);
                }
                break;
            case 'support':
                $supportChildren[] = $serviceLink($item);
                break;
        }
    }

    try {
        $statement = db()->prepare(
            'SELECT s.slug, s.title, s.title_vn
             FROM service_page_links l
             JOIN services s ON s.id = l.service_id
             WHERE l.page_key = ? AND s.is_active = 1
             ORDER BY l.sort_order ASC, l.id ASC'
        );
        $statement->execute(['temporary-resident-card']);
        $residentChildren = array_map($serviceLink, $statement->fetchAll(PDO::FETCH_ASSOC));
    } catch (Throwable $exception) {
        // Older installations use the active immigration services until migrated.
    }

    $internationalChildren = [];

    try {
        $regions = db()->query(
            'SELECT slug, title, title_vn
             FROM visa_regions
             WHERE is_active = 1
             ORDER BY sort_order ASC, id ASC'
        )->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $exception) {
        require_once __DIR__ . '/../data/other-country-visas.php';
        $regions = [];

        foreach (other_country_visa_catalog() as $key => $region) {
            $region['slug'] = $key;
            $regions[] = $region;
        }
    }

    foreach ($regions as $region) {
        $internationalChildren[] = [
            'label' => $isVietnamese && !empty($region['title_vn'])
                ? (string) $region['title_vn']
                : (string) $region['title'],
            'path' => 'other-countries-visa/' . $region['slug'],
        ];
    }

    $items = [
        [
            'label' => $isVietnamese ? 'Trang chủ' : 'Home',
            'path' => '',
            'children' => [],
        ],
        [
            'label' => $isVietnamese ? 'Visa Việt Nam' : 'Vietnam Visa',
            'path' => 'vietnam-visa',
            'children' => $visaChildren,
        ],
        [
            'label' => $isVietnamese ? 'Thẻ Tạm Trú' : 'Temporary Resident Card',
            'path' => 'temporary-resident-card',
            'children' => $residentChildren,
        ],
        [
            'label' => $isVietnamese ? 'Giấy Phép Lao Động' : 'Work Permit',
            'path' => 'work-permit',
            'children' => [],
        ],
        [
            'label' => $isVietnamese ? 'Visa Nước Ngoài' : 'Other Countries Visa',
            'path' => 'other-countries-visa',
            'children' => $internationalChildren,
        ],
        [
            'label' => $isVietnamese ? 'Dịch Vụ Khác' : 'Other Services',
            'path' => 'other-services',
            'children' => $supportChildren,
        ],
        [
            'label' => $isVietnamese ? 'Tin Tức' : 'News',
            'path' => 'news',
            'children' => [],
        ],
    ];

    foreach ($items as &$item) {
        $item['current'] = $currentPath === $item['path'];
        $item['active'] = $item['current'] || (
            $item['path'] !== '' && strpos($currentPath, $item['path'] . '/') === 0
        );

        if ($item['path'] === 'news' && strpos($currentPath, 'article/') === 0) {
            $item['active'] = true;
        }

        foreach ($item['children'] as &$child) {
            $child['current'] = $currentPath === $child['path'];
            $child['active'] = $child['current'] || strpos($currentPath, $child['path'] . '/') === 0;
            $item['active'] = $item['active'] || $child['active'];
        }
        unset($child);
    }
    unset($item);

    return $items;
}
