<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$page_title = 'Customer Requests';
$admin_section = 'inquiries';
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $inquiryId = (int) ($_POST['inquiry_id'] ?? 0);
        $status = (string) ($_POST['status'] ?? '');
        $allowedStatuses = ['new', 'in_progress', 'closed'];

        if ($inquiryId <= 0 || !in_array($status, $allowedStatuses, true)) {
            $errors[] = 'Invalid request status.';
        } else {
            $statement = $pdo->prepare(
                'UPDATE inquiries
                 SET status = :status
                 WHERE id = :id'
            );
            $statement->execute([
                'status' => $status,
                'id' => $inquiryId,
            ]);

            header('Location: inquiries.php?updated=1');
            exit;
        }
    }
}

if (isset($_GET['updated'])) {
    $message = 'Request status updated.';
}

$filter = (string) ($_GET['status'] ?? 'all');
$allowedFilters = ['all', 'new', 'in_progress', 'closed'];

if (!in_array($filter, $allowedFilters, true)) {
    $filter = 'all';
}

$sql = 'SELECT
            id, inquiry_type, status, full_name, email, phone, service,
            nationality, entry_date, entry_point, message, language_code,
            created_at
        FROM inquiries';
$parameters = [];

if ($filter !== 'all') {
    $sql .= ' WHERE status = :status';
    $parameters['status'] = $filter;
}

$sql .= ' ORDER BY created_at DESC, id DESC';

$statement = $pdo->prepare($sql);
$statement->execute($parameters);
$inquiries = $statement->fetchAll(PDO::FETCH_ASSOC);

$counts = [
    'all' => 0,
    'new' => 0,
    'in_progress' => 0,
    'closed' => 0,
];

foreach ($pdo->query('SELECT status, COUNT(*) AS total FROM inquiries GROUP BY status') as $count) {
    $counts[$count['status']] = (int) $count['total'];
    $counts['all'] += (int) $count['total'];
}

require __DIR__ . '/components/header.php';

?>

<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>

        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">CUSTOMER CARE</span>
                    <h1>Customer Requests</h1>
                    <p>Review contact messages and application requests from the website.</p>
                </div>
            </section>

            <?php if ($message): ?>
                <div class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if ($errors): ?>
                <div class="error-box" role="alert">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="management-actions request-filters">
                <?php foreach ($counts as $status => $count): ?>
                    <a
                        class="admin-btn admin-btn-secondary admin-btn-small <?= $filter === $status ? 'is-selected' : '' ?>"
                        href="inquiries.php?status=<?= urlencode($status) ?>"
                    >
                        <?= htmlspecialchars($status === 'all' ? 'All' : ucwords(str_replace('_', ' ', $status)), ENT_QUOTES, 'UTF-8') ?>
                        (<?= $count ?>)
                    </a>
                <?php endforeach; ?>
            </div>

            <section class="admin-card management-card">
                <?php if (!$inquiries): ?>
                    <div class="empty-state">
                        <h3>No customer requests</h3>
                        <p>New Contact and Apply Online submissions will appear here.</p>
                    </div>
                <?php else: ?>
                    <div class="table-wrapper">
                        <table class="admin-table inquiries-table">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Request</th>
                                    <th>Details</th>
                                    <th>Received</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inquiries as $inquiry): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($inquiry['full_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                                            <small><?= htmlspecialchars($inquiry['email'], ENT_QUOTES, 'UTF-8') ?></small>
                                            <?php if ($inquiry['phone']): ?>
                                                <small><?= htmlspecialchars($inquiry['phone'], ENT_QUOTES, 'UTF-8') ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="status-badge <?= $inquiry['inquiry_type'] === 'application' ? 'status-purple' : 'status-blue' ?>">
                                                <?= $inquiry['inquiry_type'] === 'application' ? 'Application' : 'Contact' ?>
                                            </span>
                                            <small><?= htmlspecialchars($inquiry['service'] ?: 'General question', ENT_QUOTES, 'UTF-8') ?></small>
                                        </td>
                                        <td>
                                            <?php if ($inquiry['nationality']): ?>
                                                <small><?= htmlspecialchars($inquiry['nationality'], ENT_QUOTES, 'UTF-8') ?></small>
                                            <?php endif; ?>
                                            <?php if ($inquiry['entry_date']): ?>
                                                <small>Entry: <?= htmlspecialchars($inquiry['entry_date'], ENT_QUOTES, 'UTF-8') ?></small>
                                            <?php endif; ?>
                                            <?php if ($inquiry['message']): ?>
                                                <small><?= htmlspecialchars(mb_strimwidth($inquiry['message'], 0, 110, '…', 'UTF-8'), ENT_QUOTES, 'UTF-8') ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= htmlspecialchars($inquiry['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                                            <small><?= htmlspecialchars(strtoupper($inquiry['language_code']), ENT_QUOTES, 'UTF-8') ?></small>
                                        </td>
                                        <td>
                                            <span class="status-badge <?= $inquiry['status'] === 'new' ? 'status-warning' : ($inquiry['status'] === 'closed' ? 'status-muted' : 'status-success') ?>">
                                                <?= htmlspecialchars(ucwords(str_replace('_', ' ', $inquiry['status'])), ENT_QUOTES, 'UTF-8') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="post" class="management-actions">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="inquiry_id" value="<?= (int) $inquiry['id'] ?>">
                                                <select name="status" aria-label="Request status">
                                                    <?php foreach (['new' => 'New', 'in_progress' => 'In progress', 'closed' => 'Closed'] as $value => $label): ?>
                                                        <option value="<?= $value ?>" <?= $inquiry['status'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="admin-btn admin-btn-secondary admin-btn-small" type="submit">Save</button>
                                            </form>
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
