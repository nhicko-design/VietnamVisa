<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$page_title = 'Temporary Resident Card';
$admin_section = 'temporary-resident-card';
$pageKey = 'temporary-resident-card';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token.';
    } else {
        $deleteId = (int) ($_POST['delete_id'] ?? 0);
        $statement = $pdo->prepare(
            'SELECT 1 FROM service_page_links l
             JOIN services s ON s.id = l.service_id
             WHERE l.page_key = ? AND s.category = ? AND s.id = ?'
        );
        $statement->execute([$pageKey, 'immigration', $deleteId]);

        if (!$statement->fetchColumn()) {
            $errors[] = 'Resident card not found.';
        } else {
            $pdo->prepare('DELETE FROM services WHERE id = ?')->execute([$deleteId]);
            header('Location: temporary-resident-card.php?deleted=1');
            exit;
        }
    }
}

$statement = $pdo->prepare(
    'SELECT s.id, s.title, s.title_vn, s.slug, s.is_active, l.sort_order
     FROM service_page_links l
     JOIN services s ON s.id = l.service_id
     WHERE l.page_key = ? AND s.category = ?
     ORDER BY l.sort_order, l.id'
);
$statement->execute([$pageKey, 'immigration']);
$cards = $statement->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/components/header.php';
?>

<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>

        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">WEBSITE CONTENT</span>
                    <h1>Temporary Resident Card</h1>
                    <p>Manage the cards displayed on the Temporary Resident Card page.</p>
                </div>

                <a class="admin-btn admin-btn-primary" href="temporary-resident-card-create.php">
                    + Add card
                </a>
            </section>

            <?php if (isset($_GET['created'])): ?>
                <div class="notice">Resident card created successfully.</div>
            <?php elseif (isset($_GET['updated'])): ?>
                <div class="notice">Resident card updated successfully.</div>
            <?php elseif (isset($_GET['deleted'])): ?>
                <div class="notice">Resident card deleted successfully.</div>
            <?php endif; ?>

            <?php foreach ($errors as $error): ?>
                <div class="error-box" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endforeach; ?>

            <section class="admin-card management-card">
                <?php if (!$cards): ?>
                    <div class="empty-state">
                        <h2>No resident cards yet</h2>
                        <p>Create the first card to show it on this page.</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Card</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Order</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cards as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <small><?= htmlspecialchars((string) $item['title_vn'], ENT_QUOTES, 'UTF-8') ?></small>
                                        </td>
                                        <td>/<?= htmlspecialchars($item['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td><?= (int) $item['is_active'] ? 'Active' : 'Hidden' ?></td>
                                        <td><?= (int) $item['sort_order'] ?></td>
                                        <td>
                                            <div class="management-actions">
                                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="temporary-resident-card-edit.php?id=<?= (int) $item['id'] ?>">Edit</a>
                                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="services/features.php?id=<?= (int) $item['id'] ?>">Features</a>
                                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="services/steps.php?id=<?= (int) $item['id'] ?>">Steps</a>
                                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="services/faq.php?id=<?= (int) $item['id'] ?>">FAQ</a>
                                                <form method="post" onsubmit="return confirm('Delete this resident card?');">
                                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                                    <input type="hidden" name="delete_id" value="<?= (int) $item['id'] ?>">
                                                    <button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </main>
    </div>
</div>

<?php require __DIR__ . '/components/footer.php'; ?>
