<?php

require_once __DIR__ . '/../config.php';

require_admin_login();

$page_title = 'Services Management';
$pdo = db();
$message = '';
$error = '';
$categoryFilter = trim((string) ($_GET['category'] ?? ''));
$searchQuery = trim((string) ($_GET['q'] ?? ''));
$managementScope = (string) ($_GET['scope'] ?? '');
$admin_section = $managementScope === 'vietnam-visa'
    ? 'vietnam-visa'
    : ($managementScope === 'other-services' ? 'other-services' : 'services');
$scopeTitle = $managementScope === 'vietnam-visa'
    ? 'Vietnam Visa'
    : ($managementScope === 'other-services' ? 'Other Services' : 'Services');
$scopeDescription = $managementScope === 'vietnam-visa'
    ? 'Manage only the cards shown on the Vietnam Visa page.'
    : ($managementScope === 'other-services'
        ? 'Manage only the cards shown on the Other Services page.'
        : 'Manage Visa, Immigration and Support services.');
$scopeCategory = $managementScope === 'vietnam-visa'
    ? 'visa'
    : ($managementScope === 'other-services' ? 'support' : '');

if ($scopeCategory !== '') {
    $categoryFilter = $scopeCategory;
}

if (!in_array($categoryFilter, ['', 'visa', 'immigration', 'support'], true)) {
    $categoryFilter = '';
}

if (isset($_GET['created'])) {
    $message = 'Service created successfully.';
} elseif (isset($_GET['updated'])) {
    $message = 'Service updated successfully.';
} elseif (isset($_GET['deleted'])) {
    $message = 'Service deleted successfully.';
}

try {
    $where = [];
    $parameters = [];

    if ($categoryFilter !== '') {
        $where[] = 'category = :category';
        $parameters['category'] = $categoryFilter;
    }

    if ($searchQuery !== '') {
        $where[] = '(title LIKE :search OR title_vn LIKE :search OR slug LIKE :search)';
        $parameters['search'] = '%' . $searchQuery . '%';
    }

    $statement = $pdo->prepare(
        'SELECT id, category, slug, title, price, currency, is_featured, is_active, sort_order
         FROM services'
        . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
        . ' ORDER BY sort_order ASC, id DESC'
    );
    $statement->execute($parameters);

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
                    <h1><?= htmlspecialchars($scopeTitle, ENT_QUOTES, 'UTF-8') ?></h1>
                    <p><?= htmlspecialchars($scopeDescription, ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <a class="admin-btn admin-btn-primary" href="create.php?<?= $managementScope ? 'scope=' . rawurlencode($managementScope) : '' ?>">
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

            <?php if ($scopeCategory === ''): ?>
            <form class="management-filters" method="get">
                <?php if ($managementScope): ?>
                    <input type="hidden" name="scope" value="<?= htmlspecialchars($managementScope, ENT_QUOTES, 'UTF-8') ?>">
                <?php endif; ?>
                <label>
                    <span class="sr-only">Search services</span>
                    <input type="search" name="q" value="<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>" placeholder="Search title or slug">
                </label>

                <select name="category" aria-label="Filter by category">
                    <option value="">All categories</option>
                    <option value="visa" <?= $categoryFilter === 'visa' ? 'selected' : '' ?>>Visa</option>
                    <option value="immigration" <?= $categoryFilter === 'immigration' ? 'selected' : '' ?>>Immigration</option>
                    <option value="support" <?= $categoryFilter === 'support' ? 'selected' : '' ?>>Support</option>
                </select>

                <button class="admin-btn admin-btn-secondary" type="submit">Filter</button>

                <?php if ($categoryFilter !== '' || $searchQuery !== ''): ?>
                    <a class="admin-btn admin-btn-secondary" href="manage.php<?= $managementScope ? '?scope=' . rawurlencode($managementScope) : '' ?>">Clear</a>
                <?php endif; ?>
            </form>
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
                                        <a class="admin-btn admin-btn-primary" href="create.php<?= $managementScope ? '?scope=' . rawurlencode($managementScope) : '' ?>">Add Service</a>
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
                            <span class="status-badge <?= $service['category'] === 'visa' ? 'status-blue' : ($service['category'] === 'immigration' ? 'status-purple' : 'status-success') ?>">
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
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="edit.php?id=<?= (int) $service['id'] ?><?= $managementScope ? '&scope=' . rawurlencode($managementScope) : '' ?>">Edit</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="features.php?id=<?= (int) $service['id'] ?><?= $managementScope ? '&scope=' . rawurlencode($managementScope) : '' ?>">Features</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="steps.php?id=<?= (int) $service['id'] ?><?= $managementScope ? '&scope=' . rawurlencode($managementScope) : '' ?>">Steps</a>
                                <a class="admin-btn admin-btn-secondary admin-btn-small" href="faq.php?id=<?= (int) $service['id'] ?><?= $managementScope ? '&scope=' . rawurlencode($managementScope) : '' ?>">FAQ</a>

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
