<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$page_title = 'Page FAQs';
$admin_section = 'faqs';
$pageOptions = [
    'about' => 'About',
    'services' => 'Services',
    'vietnam-visa' => 'Vietnam Visa',
    'temporary-resident-card' => 'Temporary Resident Card',
    'work-permit' => 'Work Permit',
    'other-countries-visa' => 'Other Countries Visa',
    'other-services' => 'Other Services',
    'news' => 'News',
    'contact' => 'Contact',
    'apply' => 'Apply Online',
    'quotation' => 'Quotation',
    'visa' => 'Visa (legacy page)',
    'support' => 'Support (legacy page)',
];
$selectedPage = (string) ($_GET['page'] ?? $_POST['page_key'] ?? 'vietnam-visa');

if (!isset($pageOptions[$selectedPage])) {
    $selectedPage = 'vietnam-visa';
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } elseif (isset($_POST['delete_id'])) {
        $statement = $pdo->prepare('DELETE FROM page_faq WHERE id = :id AND page_key = :page_key');
        $statement->execute([
            'id' => (int) $_POST['delete_id'],
            'page_key' => $selectedPage,
        ]);
        header('Location: faqs.php?page=' . rawurlencode($selectedPage) . '&deleted=1');
        exit;
    } else {
        $question = trim((string) ($_POST['question'] ?? ''));
        $answer = trim((string) ($_POST['answer'] ?? ''));

        if ($question === '' || $answer === '') {
            $errors[] = 'English question and answer are required.';
        } else {
            $statement = $pdo->prepare(
                'INSERT INTO page_faq (page_key, question, answer, question_vn, answer_vn, sort_order, is_active)
                 VALUES (:page_key, :question, :answer, :question_vn, :answer_vn, :sort_order, :is_active)'
            );
            $statement->execute([
                'page_key' => $selectedPage,
                'question' => $question,
                'answer' => $answer,
                'question_vn' => trim((string) ($_POST['question_vn'] ?? '')) ?: null,
                'answer_vn' => trim((string) ($_POST['answer_vn'] ?? '')) ?: null,
                'sort_order' => max(0, (int) ($_POST['sort_order'] ?? 0)),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ]);
            header('Location: faqs.php?page=' . rawurlencode($selectedPage) . '&created=1');
            exit;
        }
    }
}

$statement = $pdo->prepare(
    'SELECT id, question, answer, question_vn, answer_vn, sort_order, is_active
     FROM page_faq WHERE page_key = :page_key ORDER BY sort_order ASC, id ASC'
);
$statement->execute(['page_key' => $selectedPage]);
$faqs = $statement->fetchAll(PDO::FETCH_ASSOC);

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
                    <h1>Page FAQs</h1>
                    <p>Add FAQs to any public page except Homepage. The FAQ section stays hidden until it has an active item.</p>
                </div>
            </section>

            <?php if ($errors): ?>
                <div class="error-box" role="alert"><?= htmlspecialchars(implode(' ', $errors), ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form class="management-filters" method="get">
                <select name="page" aria-label="Choose a page">
                    <?php foreach ($pageOptions as $key => $label): ?>
                        <option value="<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>" <?= $selectedPage === $key ? 'selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="admin-btn admin-btn-secondary" type="submit">Choose page</button>
            </form>

            <section class="admin-card form-card">
                <h2>Add FAQ for <?= htmlspecialchars($pageOptions[$selectedPage], ENT_QUOTES, 'UTF-8') ?></h2>
                <form method="post" class="admin-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="page_key" value="<?= htmlspecialchars($selectedPage, ENT_QUOTES, 'UTF-8') ?>">
                    <div class="form-grid">
                        <label>English question<input name="question" required></label>
                        <label>Vietnamese question<input name="question_vn"></label>
                        <label class="full">English answer<textarea name="answer" required rows="4"></textarea></label>
                        <label class="full">Vietnamese answer<textarea name="answer_vn" rows="4"></textarea></label>
                        <label>Order<input type="number" name="sort_order" min="0" value="0"></label>
                        <label class="checkbox-row"><input type="checkbox" name="is_active" value="1" checked> Show on website</label>
                    </div>
                    <button class="admin-btn admin-btn-primary" type="submit">Add FAQ</button>
                </form>
            </section>

            <section class="admin-card management-card">
                <div class="table-wrapper"><table class="admin-table"><thead><tr><th>Question</th><th>Status</th><th>Order</th><th>Action</th></tr></thead><tbody>
                    <?php if (!$faqs): ?><tr><td colspan="4"><div class="empty-state"><h3>No FAQs yet</h3><p>Add the first FAQ above. Nothing will show on this page until an active FAQ exists.</p></div></td></tr><?php endif; ?>
                    <?php foreach ($faqs as $faq): ?><tr><td><strong><?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars((string) ($faq['question_vn'] ?: ''), ENT_QUOTES, 'UTF-8') ?></small></td><td><span class="status-badge <?= (int) $faq['is_active'] ? 'status-success' : 'status-muted' ?>"><?= (int) $faq['is_active'] ? 'Active' : 'Hidden' ?></span></td><td><?= (int) $faq['sort_order'] ?></td><td><form method="post" onsubmit="return confirm('Delete this FAQ?');"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="page_key" value="<?= htmlspecialchars($selectedPage, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="delete_id" value="<?= (int) $faq['id'] ?>"><button class="admin-btn admin-btn-danger admin-btn-small" type="submit">Delete</button></form></td></tr><?php endforeach; ?>
                </tbody></table></div>
            </section>
        </main>
    </div>
</div>
<?php require __DIR__ . '/components/footer.php'; ?>
