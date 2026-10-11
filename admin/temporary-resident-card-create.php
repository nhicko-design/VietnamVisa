<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/media-upload.php';

require_admin_login();

$pdo = db();
$page_title = 'Add Resident Card';
$admin_section = 'temporary-resident-card';
$pageKey = 'temporary-resident-card';
$errors = [];
$card = [
    'title' => '',
    'title_vn' => '',
    'slug' => '',
    'short_description' => '',
    'short_description_vn' => '',
    'description' => '',
    'description_vn' => '',
    'sort_order' => 0,
    'is_active' => 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (admin_post_exceeds_server_limit()) {
        $errors[] = 'The selected files exceed the server POST limit.';
    } elseif (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token.';
    } else {
        $errors = admin_media_upload_errors($_FILES['media_files'] ?? []);

        foreach (['title', 'title_vn', 'slug', 'short_description', 'short_description_vn', 'description', 'description_vn'] as $field) {
            $card[$field] = trim((string) ($_POST[$field] ?? ''));
        }

        $card['slug'] = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($card['slug'])), '-');
        $card['sort_order'] = max(0, (int) ($_POST['sort_order'] ?? 0));
        $card['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        if ($card['title'] === '' || $card['title_vn'] === '' || $card['slug'] === '') {
            $errors[] = 'English title, Vietnamese title and slug are required.';
        }

        if (!$errors) {
            try {
                $statement = $pdo->prepare(
                    "INSERT INTO services (category, slug, title, title_vn, short_description, short_description_vn, description, description_vn, icon, is_active, sort_order)
                     VALUES ('immigration', ?, ?, ?, ?, ?, ?, ?, 'TRC', ?, ?)"
                );
                $statement->execute([
                    $card['slug'], $card['title'], $card['title_vn'], $card['short_description'],
                    $card['short_description_vn'], $card['description'], $card['description_vn'],
                    $card['is_active'], $card['sort_order'],
                ]);
                $serviceId = (int) $pdo->lastInsertId();
                $pdo->prepare('INSERT INTO service_page_links (page_key, service_id, sort_order) VALUES (?, ?, ?)')
                    ->execute([$pageKey, $serviceId, $card['sort_order']]);
                $firstImage = admin_save_media_uploads($pdo, $_FILES['media_files'] ?? [], 'service', $serviceId);

                if ($firstImage) {
                    $pdo->prepare('UPDATE services SET image=? WHERE id=?')->execute([$firstImage, $serviceId]);
                }

                header('Location: temporary-resident-card.php?created=1');
                exit;
            } catch (Throwable $exception) {
                $errors[] = 'Unable to save. Check that the slug is unique.';
            }
        }
    }
}

$formTitle = 'Add Resident Card';
$submitLabel = 'Create card';
$isEdit = false;
require __DIR__ . '/components/header.php';
require __DIR__ . '/temporary-resident-card-form.php';
