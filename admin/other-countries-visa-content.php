<?php

require_once __DIR__ . '/config.php';
require_admin_login();

$pdo = db();
$ownerType = ($_GET['type'] ?? '') === 'country' ? 'country' : 'region';
$section = in_array($_GET['section'] ?? '', ['feature', 'step', 'faq'], true) ? $_GET['section'] : 'feature';
$ownerId = (int) ($_GET['id'] ?? 0);
$editId = (int) ($_GET['edit'] ?? $_POST['edit_id'] ?? 0);
$table = $ownerType === 'country' ? 'visa_countries' : 'visa_regions';
$backUrl = 'other-countries-visa.php' . ($ownerType === 'country' ? '?region=' . (int) ($_GET['region'] ?? 0) : '');
$page_title = ucfirst($section) . ' Management';
$admin_section = 'other-countries-visa';

$pdo->exec("CREATE TABLE IF NOT EXISTS visa_content_items (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, owner_type ENUM('region','country') NOT NULL, owner_id INT UNSIGNED NOT NULL, item_type ENUM('feature','step','faq') NOT NULL, title VARCHAR(255) NOT NULL, title_vn VARCHAR(255) NULL, content TEXT NULL, content_vn TEXT NULL, sort_order INT NOT NULL DEFAULT 0, is_active TINYINT(1) NOT NULL DEFAULT 1, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, KEY idx_visa_content_owner (owner_type, owner_id, item_type, sort_order)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$lookup = $pdo->prepare("SELECT title FROM $table WHERE id = ?");
$lookup->execute([$ownerId]);
$ownerTitle = $lookup->fetchColumn();
if (!$ownerTitle) { http_response_code(404); exit('Visa card not found.'); }

$editing = [
    'title' => '', 'title_vn' => '', 'content' => '', 'content_vn' => '',
    'sort_order' => 0, 'is_active' => 1,
];

if ($editId) {
    $statement = $pdo->prepare('SELECT * FROM visa_content_items WHERE id = ? AND owner_type = ? AND owner_id = ? AND item_type = ?');
    $statement->execute([$editId, $ownerType, $ownerId, $section]);
    $editing = $statement->fetch(PDO::FETCH_ASSOC) ?: $editing;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && verify_csrf_token($_POST['csrf_token'] ?? null)) {
    if (isset($_POST['delete_id'])) {
        $pdo->prepare('DELETE FROM visa_content_items WHERE id = ? AND owner_type = ? AND owner_id = ?')->execute([(int) $_POST['delete_id'], $ownerType, $ownerId]);
    } else {
        $type = $section;
        $values = [trim($_POST['title'] ?? ''), trim($_POST['title_vn'] ?? ''), trim($_POST['content'] ?? ''), trim($_POST['content_vn'] ?? ''), (int) ($_POST['sort_order'] ?? 0), isset($_POST['is_active']) ? 1 : 0];
        if ($editId) {
            $pdo->prepare('UPDATE visa_content_items SET title=?, title_vn=?, content=?, content_vn=?, sort_order=?, is_active=? WHERE id=? AND owner_type=? AND owner_id=? AND item_type=?')->execute([...$values, $editId, $ownerType, $ownerId, $type]);
        } else {
            $pdo->prepare('INSERT INTO visa_content_items (owner_type, owner_id, item_type, title, title_vn, content, content_vn, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)')->execute([$ownerType, $ownerId, $type, ...$values]);
        }
    }
    header('Location: other-countries-visa-content.php?section=' . $section . '&type=' . $ownerType . '&id=' . $ownerId . ($ownerType === 'country' ? '&region=' . (int) ($_GET['region'] ?? 0) : '')); exit;
}
$items = $pdo->prepare('SELECT * FROM visa_content_items WHERE owner_type = ? AND owner_id = ? AND item_type = ? ORDER BY sort_order, id'); $items->execute([$ownerType, $ownerId, $section]); $items = $items->fetchAll(PDO::FETCH_ASSOC);
$selfUrl = 'other-countries-visa-content.php?section=' . $section . '&type=' . $ownerType . '&id=' . $ownerId . ($ownerType === 'country' ? '&region=' . (int) ($_GET['region'] ?? 0) : '');
require __DIR__ . '/components/header.php';
?>
<div class="admin-layout"><?php require __DIR__ . '/components/sidebar.php'; ?><div class="admin-main"><?php require __DIR__ . '/components/topbar.php'; ?><main class="admin-content"><section class="page-header"><div><span class="page-eyebrow">OTHER COUNTRIES VISA</span><h1><?= htmlspecialchars($ownerTitle) ?></h1><p>Manage <?= htmlspecialchars(ucfirst($section)) ?> items for this card.</p></div><a class="admin-btn admin-btn-secondary" href="<?= htmlspecialchars($backUrl) ?>">← Back</a></section><section class="admin-card form-card"><form method="post" class="admin-form"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>"><div class="form-grid"><label>Order<input name="sort_order" type="number" value="0"></label><label>English title / question<input name="title" required></label><label>Vietnamese title / question<input name="title_vn"></label><label class="full">English description / answer<textarea name="content"></textarea></label><label class="full">Vietnamese description / answer<textarea name="content_vn"></textarea></label><label class="checkbox-row"><input type="checkbox" name="is_active" checked> Show on website</label></div><button class="admin-btn admin-btn-primary">Add <?= htmlspecialchars(ucfirst($section)) ?></button></form></section><section class="admin-card management-card"><div class="table-wrapper"><table class="admin-table"><thead><tr><th><?= htmlspecialchars(ucfirst($section)) ?></th><th>Status</th><th>Order</th><th></th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><strong><?= htmlspecialchars($item['title']) ?></strong><small><?= htmlspecialchars($item['content']) ?></small></td><td><?= $item['is_active'] ? 'Active' : 'Hidden' ?></td><td><?= (int) $item['sort_order'] ?></td><td><form method="post"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token()) ?>"><input type="hidden" name="delete_id" value="<?= (int) $item['id'] ?>"><button class="admin-btn admin-btn-danger admin-btn-small">Delete</button></form></td></tr><?php endforeach; ?></tbody></table></div></section></main></div></div><?php require __DIR__ . '/components/footer.php'; ?>
