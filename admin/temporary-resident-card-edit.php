<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/media-upload.php';

require_admin_login();

$pdo = db();
$page_title = 'Edit Resident Card';
$admin_section = 'temporary-resident-card';
$pageKey = 'temporary-resident-card';
$errors = [];
$serviceId = (int) ($_GET['id'] ?? 0);

if ($serviceId <= 0) {
    header('Location: temporary-resident-card.php');
    exit;
}

$statement = $pdo->prepare(
    'SELECT s.* FROM service_page_links l
     JOIN services s ON s.id = l.service_id
     WHERE l.page_key = ? AND s.category = ? AND s.id = ?'
);
$statement->execute([$pageKey, 'immigration', $serviceId]);
$card = $statement->fetch(PDO::FETCH_ASSOC);

if (!$card) {
    http_response_code(404);
    exit('Resident card not found.');
}

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
                    'UPDATE services SET title=?, title_vn=?, slug=?, short_description=?, short_description_vn=?, description=?, description_vn=?, sort_order=?, is_active=?, updated_at=NOW() WHERE id=?'
                );
                $statement->execute([
                    $card['title'], $card['title_vn'], $card['slug'], $card['short_description'],
                    $card['short_description_vn'], $card['description'], $card['description_vn'],
                    $card['sort_order'], $card['is_active'], $serviceId,
                ]);
                $pdo->prepare('UPDATE service_page_links SET sort_order=? WHERE page_key=? AND service_id=?')
                    ->execute([$card['sort_order'], $pageKey, $serviceId]);

                $firstImage = admin_save_media_uploads($pdo, $_FILES['media_files'] ?? [], 'service', $serviceId);
                if ($firstImage && empty($card['image'])) {
                    $pdo->prepare('UPDATE services SET image=? WHERE id=?')->execute([$firstImage, $serviceId]);
                }

                header('Location: temporary-resident-card.php?updated=1');
                exit;
            } catch (Throwable $exception) {
                $errors[] = 'Unable to save. Check that the slug is unique.';
            }
        }
    }
}

$formTitle = 'Edit Resident Card';
$submitLabel = 'Save changes';
$isEdit = true;
require __DIR__ . '/components/header.php';
require __DIR__ . '/temporary-resident-card-form.php';
