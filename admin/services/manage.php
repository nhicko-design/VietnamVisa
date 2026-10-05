<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$page_title = 'Services Management';
$admin_section = 'services';
$pdo = db();
$message = '';
$error = '';

if (isset($_GET['created'])) {
    $message = 'Service created successfully.';
} elseif (isset($_GET['updated'])) {
    $message = 'Service updated successfully.';
} elseif (isset($_GET['deleted'])) {
    $message = 'Service deleted successfully.';
}

try {
    $statement = $pdo->query(
        'SELECT id, category, slug, title, price, currency, is_featured, is_active, sort_order
         FROM services
         ORDER BY sort_order ASC, id DESC'
    );

    $services = $statement->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    $services = [];
    $error = 'Unable to load services. Please try again.';
}

require __DIR__ . '/../components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/../components/sidebar.php'; ?>
    <div class="admin-main">
        <?php require __DIR__ . '/../components/topbar.php'; ?>
        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">WEBSITE CONTENT</span>
                    <h1>Services</h1>
                    <p>Manage Vietnam visa and support services.</p>
                </div>

                <a class="admin-btn admin-btn-primary" href="create.php">
                    <span aria-hidden="true">+</span>
                    Add Service
                </a>
            </section>

            <?php if ($message): ?>
                <div class="notice" role="status">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="error-box" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <section class="admin-card management-card">
                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            <?php if (!$services): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <h3>No services found</h3>
                                        <p>Create your first service to get started.</p>
                                        <a class="admin-btn admin-btn-primary" href="create.php">Add Service</a>
                                    </div>
                                </td>
                            </tr>
            <?php else: ?>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($service['title'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <small>/<?= htmlspecialchars($service['slug'], ENT_QUOTES, 'UTF-8') ?></small>
                        </td>
                        <td>
                            <span class="status-badge <?= $service['category'] === 'visa' ? 'status-blue' : 'status-purple' ?>">
                                <?= htmlspecialchars(ucfirst($service['category']), ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($service['price'] !== null && $service['price'] !== ''): ?>
                                <?= htmlspecialchars(number_format((float) $service['price'], 0, '.', ',') . ' ' . ($service['currency'] ?: 'USD'), ENT_QUOTES, 'UTF-8') ?>
                            <?php else: ?>
                                <span class="table-muted">Contact</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?= (int) $service['is_active'] ? 'status-success' : 'status-muted' ?>">
                                <?= (int) $service['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <?php if ((int) $service['is_featured']): ?>
                                <span class="featured-label">Featured</span>
                            <?php else: ?>
                                <span class="table-muted">No</span>
                            <?php endif; ?>
                        </td>
                        <td><?= (int) $service['sort_order'] ?></td>
                        <td>
                            <div class="management-actions">
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="media.php?id=<?= (int) $service['id'] ?>">Media</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="edit.php?id=<?= (int) $service['id'] ?>">Edit</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="features.php?id=<?= (int) $service['id'] ?>">Features</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="steps.php?id=<?= (int) $service['id'] ?>">Steps</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="faq.php?id=<?= (int) $service['id'] ?>">FAQ</a>

                                <form method="post" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    <input type="hidden" name="id" value="<?= (int) $service['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                    <button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>
<?php require __DIR__ . '/../components/footer.php'; ?>
