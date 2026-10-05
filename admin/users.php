<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$currentAdmin = current_admin();
$page_title = 'Admin Users';
$admin_section = 'users';
$message = '';
$errors = [];

if (isset($_GET['updated'])) {
    $message = 'Account updated successfully.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } elseif (($_POST['action'] ?? '') === 'toggle') {
        $userId = (int) ($_POST['user_id'] ?? 0);

        if ($userId === (int) $currentAdmin['id']) {
            $errors[] = 'You cannot deactivate your own account.';
        } else {
            $statement = $pdo->prepare('UPDATE admin_users SET is_active = 1 - is_active WHERE id = ?');
            $statement->execute([$userId]);
            $message = 'Account status updated.';
        }
    } elseif (($_POST['action'] ?? '') === 'create') {
        $username = trim((string) ($_POST['username'] ?? ''));
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || !preg_match('/^[a-zA-Z0-9_.-]{3,100}$/', $username)) {
            $errors[] = 'Username must have 3 or more letters, numbers, dots, underscores or hyphens.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if (!$errors) {
            try {
                $statement = $pdo->prepare(
                    'INSERT INTO admin_users (username, password_hash, full_name, email, is_active)
                     VALUES (:username, :password_hash, :full_name, :email, 1)'
                );
                $statement->execute([
                    'username' => $username,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'full_name' => $fullName ?: null,
                    'email' => $email,
                ]);
                $message = 'Admin account created successfully.';
            } catch (PDOException $exception) {
                $errors[] = 'Username or email is already in use.';
            }
        }
    }
}

$users = $pdo->query(
    'SELECT id, username, full_name, email, is_active, last_login_at, created_at
     FROM admin_users
     ORDER BY id ASC'
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>

    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>

        <main class="admin-content">
            <section class="page-header">
                <div>
                    <span class="page-eyebrow">SYSTEM</span>
                    <h1>Admin Users</h1>
                    <p>Create accounts and control access to the administration area.</p>
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

            <section class="admin-card news-form-card">
                <h2 class="media-section-title">Create admin account</h2>

                <form method="post" class="news-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                    <input type="hidden" name="action" value="create">

                    <div class="news-form-grid">
                        <div>
                            <label for="username">Username</label>
                            <input id="username" name="username" required autocomplete="username">
                        </div>
                        <div>
                            <label for="full_name">Full name</label>
                            <input id="full_name" name="full_name" autocomplete="name">
                        </div>
                        <div>
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" required autocomplete="email">
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="news-form-actions">
                        <button class="admin-btn admin-btn-primary" type="submit">Create Admin</button>
                    </div>
                </form>
            </section>

            <section class="admin-card management-card users-list-card">
                <div class="media-list-heading">
                    <h2>Accounts</h2>
                    <span><?= count($users) ?> account(s)</span>
                </div>

                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Admin</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Last login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <strong><?= htmlspecialchars($user['full_name'] ?: $user['username'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <small>@<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <span class="status-badge <?= (int) $user['is_active'] ? 'status-success' : 'status-muted' ?>">
                                            <?= (int) $user['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($user['last_login_at'] ?: 'Never', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <a class="admin-btn admin-btn-secondary admin-btn-small" href="user-edit.php?id=<?= (int) $user['id'] ?>">Edit</a>
                                        <?php if ((int) $user['id'] === (int) $currentAdmin['id']): ?>
                                            <span class="table-muted">Current account</span>
                                        <?php else: ?>
                                            <form method="post">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                                                <input type="hidden" name="action" value="toggle">
                                                <input type="hidden" name="user_id" value="<?= (int) $user['id'] ?>">
                                                <button class="admin-btn admin-btn-secondary admin-btn-small" type="submit">
                                                    <?= (int) $user['is_active'] ? 'Deactivate' : 'Activate' ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</div>
<?php require __DIR__ . '/components/footer.php'; ?>
