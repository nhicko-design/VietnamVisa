<?php

require_once __DIR__ . '/../../common/database.php';
require_once __DIR__ . '/../../data/other-country-visas.php';

$pdo = db();
$catalog = other_country_visa_catalog();

$regionStatement = $pdo->prepare(
    'SELECT id FROM visa_regions WHERE slug = ? LIMIT 1'
);
$countryStatement = $pdo->prepare(
    'INSERT INTO visa_countries (region_id, slug, title, title_vn, summary, summary_vn, detail, detail_vn, sort_order, is_active)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)
     ON DUPLICATE KEY UPDATE title=VALUES(title), title_vn=VALUES(title_vn), summary=VALUES(summary), summary_vn=VALUES(summary_vn), detail=VALUES(detail), detail_vn=VALUES(detail_vn), sort_order=VALUES(sort_order), is_active=1'
);

foreach ($catalog as $regionSlug => $region) {
    $regionStatement->execute([$regionSlug]);
    $regionId = (int) $regionStatement->fetchColumn();

    if ($regionId === 0) {
        continue;
    }

    $order = 10;

    foreach ($region['countries'] as $countrySlug => $country) {
        $countryStatement->execute([
            $regionId,
            $countrySlug,
            $country['title'],
            $country['title_vn'] ?? $country['title'],
            $country['summary'] ?? '',
            $country['summary_vn'] ?? ($country['summary'] ?? ''),
            $country['focus'] ?? ($country['summary'] ?? ''),
            $country['focus_vn'] ?? ($country['summary_vn'] ?? ($country['summary'] ?? '')),
            $order,
        ]);
        $order += 10;
    }
}

echo "Other Countries Visa catalogue imported.\n";
