<?php

/**
 * ============================================================
 * IMMIGRA ADMIN AUTHENTICATION
 * ============================================================
 */

require_once __DIR__ . '/database.php';


/**
 * ============================================================
 * SESSION
 * ============================================================
 */

/**
 * Start session safely.
 */
function start_admin_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (
        !empty($_SERVER['HTTPS']) &&
        $_SERVER['HTTPS'] !== 'off'
    );

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

start_admin_session();


/**
 * ============================================================
 * CSRF
 * ============================================================
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(
            random_bytes(32)
        );
    }

    return $_SESSION['csrf_token'];
}


function verify_csrf_token(?string $token): bool
{
    if (
        empty($token) ||
        empty($_SESSION['csrf_token'])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION['csrf_token'],
        $token
    );
}


/**
 * ============================================================
 * LOGIN STATE
 * ============================================================
 */

function is_admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}


/**
 * Get currently logged-in admin.
 */
function current_admin(): ?array
{
    if (!is_admin_logged_in()) {
        return null;
    }

    return [
        'id' => $_SESSION['admin_id'] ?? null,
        'name' => $_SESSION['admin_name'] ?? '',
        'email' => $_SESSION['admin_email'] ?? '',
        'role' => $_SESSION['admin_role'] ?? '',
    ];
}


/**
 * ============================================================
 * REQUIRE LOGIN
 * ============================================================
 */

function require_admin_login(): void
{
    if (is_admin_logged_in()) {
        return;
    }

    header(
        'Location: /immigra/admin/login.php'
    );

    exit;
}


/**
 * ============================================================
 * LOGIN
 * ============================================================
 */

function login_admin(
    string $email,
    string $password
): array {

    $email = trim($email);

    if ($email === '') {
        return [
            'success' => false,
            'message' => 'Please enter your email address.',
        ];
    }

    if ($password === '') {
        return [
            'success' => false,
            'message' => 'Please enter your password.',
        ];
    }

    try {

        /**
         * Get PDO connection.
         *
         * database.php provides the db() function.
         */
        $pdo = db();


        /**
         * ====================================================
         * Find admin by email
         * ====================================================
         *
         * Current admin_users structure:
         *
         * id
         * username
         * password_hash
         * full_name
         * email
         * is_active
         * last_login_at
         * created_at
         * updated_at
         */

        $sql = "
            SELECT
                id,
                username,
                full_name,
                email,
                password_hash,
                is_active
            FROM admin_users
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'email' => $email,
        ]);

        $admin = $stmt->fetch();


        /**
         * Admin does not exist.
         */
        if (!$admin) {

            return [
                'success' => false,
                'message' => 'Invalid email or password.',
            ];
        }


        /**
         * ====================================================
         * Check account status
         * ====================================================
         *
         * is_active:
         *
         * 1 = active
         * 0 = inactive
         */

        if ((int) $admin['is_active'] !== 1) {

            return [
                'success' => false,
                'message' => 'This account has been deactivated.',
            ];
        }


        /**
         * ====================================================
         * Verify password
         * ====================================================
         */

        if (
            !password_verify(
                $password,
                $admin['password_hash']
            )
        ) {

            return [
                'success' => false,
                'message' => 'Invalid email or password.',
            ];
        }


        /**
         * ====================================================
         * Prevent session fixation
         * ====================================================
         */

        session_regenerate_id(true);


        /**
         * ====================================================
         * Save admin information into session
         * ====================================================
         */

        $_SESSION['admin_id'] = (int) $admin['id'];

        $_SESSION['admin_name'] =
            $admin['full_name']
            ?: $admin['username'];

        $_SESSION['admin_email'] =
            $admin['email'];

        /**
         * The current database does not have a role column.
         */
        $_SESSION['admin_role'] = 'admin';

        $_SESSION['logged_in_at'] = time();


        /**
         * ====================================================
         * Update last login time
         * ====================================================
         */

        $update = $pdo->prepare("
            UPDATE admin_users
            SET last_login_at = NOW()
            WHERE id = :id
        ");

        $update->execute([
            'id' => $admin['id'],
        ]);


        /**
         * ====================================================
         * Regenerate CSRF token after login
         * ====================================================
         */

        $_SESSION['csrf_token'] = bin2hex(
            random_bytes(32)
        );


        return [
            'success' => true,
            'message' => 'Login successful.',
        ];


    } catch (Throwable $e) {

        /**
         * Do not expose database details to the user.
         */
        return [
            'success' => false,
            'message' => 'Unable to connect to the database.',
        ];
    }
}


/**
 * ============================================================
 * LOGOUT
 * ============================================================
 */

function logout_admin(): void
{
    start_admin_session();

    $_SESSION = [];

    if (
        ini_get('session.use_cookies')
    ) {

        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'] ?? '',
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}


/**
 * ============================================================
 * ROLE
 * ============================================================
 */

function admin_is_super_admin(): bool
{
    return (
        is_admin_logged_in() &&
        ($_SESSION['admin_role'] ?? '') === 'super_admin'
    );
}


function require_super_admin(): void
{
    require_admin_login();

    if (!admin_is_super_admin()) {

        http_response_code(403);

        exit(
            '403 - You do not have permission to access this page.'
        );
    }
}