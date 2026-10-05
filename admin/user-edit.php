<?php

require_once __DIR__ . '/config.php';

require_admin_login();

$pdo = db();
$currentAdmin = current_admin();
$userId = (int) ($_GET['id'] ?? 0);
$page_title = 'Edit Admin User';
$admin_section = 'users';
$errors = [];

$statement = $pdo->prepare('SELECT id, username, full_name, email, is_active, last_login_at FROM admin_users WHERE id = ?');
$statement->execute([$userId]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: users.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        $errors[] = 'Invalid security token. Please try again.';
    } else {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }

        if ($password !== '' && strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($userId === (int) $currentAdmin['id'] && !$isActive) {
            $errors[] = 'You cannot deactivate your own account.';
        }

        if (!$errors) {
            try {
                if ($password !== '') {
                    $statement = $pdo->prepare(
                        'UPDATE admin_users
                         SET full_name = :full_name, email = :email, is_active = :is_active,
                             password_hash = :password_hash
                         WHERE id = :id'
                    );
                    $statement->execute([
                        'full_name' => $fullName ?: null,
                        'email' => $email,
                        'is_active' => $isActive,
                        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                        'id' => $userId,
                    ]);
                } else {
                    $statement = $pdo->prepare(
                        'UPDATE admin_users
                         SET full_name = :full_name, email = :email, is_active = :is_active
                         WHERE id = :id'
                    );
                    $statement->execute([
                        'full_name' => $fullName ?: null,
                        'email' => $email,
                        'is_active' => $isActive,
                        'id' => $userId,
                    ]);
                }

                header('Location: users.php?updated=1');
                exit;
            } catch (PDOException $exception) {
                $errors[] = 'Email is already in use.';
            }
        }

        $user['full_name'] = $fullName;
        $user['email'] = $email;
        $user['is_active'] = $isActive;
    }
}

require __DIR__ . '/components/header.php';

?>
<div class="admin-layout">
    <?php require __DIR__ . '/components/sidebar.php'; ?>
    <div class="admin-main">
        <?php require __DIR__ . '/components/topbar.php'; ?>
        <main class="admin-content">
            <section class="page-header">
                <div><span class="page-eyebrow">SYSTEM</span><h1>Edit Admin User</h1><p>@<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></p></div>
                <a class="admin-btn admin-btn-secondary" href="users.php">Back to Users</a>
            </section>
            <?php if ($errors): ?><div class="error-box" role="alert"><?php foreach ($errors as $error): ?><div><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endforeach; ?></div><?php endif; ?>
            <form method="post" class="news-form"><input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                <section class="admin-card news-form-card"><div class="news-form-grid">
                    <div><label>Username</label><input value="<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>" disabled></div>
                    <div><label for="full_name">Full name</label><input id="full_name" name="full_name" value="<?= htmlspecialchars((string) $user['full_name'], ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div><label for="email">Email</label><input id="email" type="email" name="email" required value="<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>"></div>
                    <div><label for="password">New password</label><input id="password" type="password" name="password" minlength="8" autocomplete="new-password"><small>Leave blank to keep the current password.</small></div>
                    <label class="news-checkbox"><input type="checkbox" name="is_active" value="1" <?= (int) $user['is_active'] ? 'checked' : '' ?>> Active account</label>
                </div></section>
                <div class="news-form-actions"><button class="admin-btn admin-btn-primary" type="submit">Save User</button></div>
            </form>
        </main>
    </div>
</div>
<?php require __DIR__ . '/components/footer.php'; ?>
