<?php

require_once __DIR__ . '/config.php';
$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username  = trim($_POST['username'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $password  = $_POST['password'] ?? '';

    $errors = [];

    // Validate username
    if ($username === '') {
        $errors[] = 'Username is required.';
    }

    // Validate email
    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    // Validate password
    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {

        try {

            // Check username
            $stmt = $pdo->prepare("
                SELECT id
                FROM admin_users
                WHERE username = ?
                LIMIT 1
            ");

            $stmt->execute([$username]);

            if ($stmt->fetch()) {
                $errors[] = 'Username already exists.';
            }

            // Check email
            if (empty($errors)) {

                $stmt = $pdo->prepare("
                    SELECT id
                    FROM admin_users
                    WHERE email = ?
                    LIMIT 1
                ");

                $stmt->execute([$email]);

                if ($stmt->fetch()) {
                    $errors[] = 'Email already exists.';
                }
            }

            // Insert admin
            if (empty($errors)) {

                $password_hash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $stmt = $pdo->prepare("
                    INSERT INTO admin_users
                    (
                        username,
                        password_hash,
                        full_name,
                        email,
                        is_active,
                        created_at,
                        updated_at
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        1,
                        NOW(),
                        NOW()
                    )
                ");

                $stmt->execute([
                    $username,
                    $password_hash,
                    $full_name !== '' ? $full_name : null,
                    $email
                ]);

                $success = 'Admin account created successfully.';
            }

        } catch (PDOException $e) {

            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Admin - SunViet</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            background: #f5f5f7;
        }

        .card {
            width: 100%;
            max-width: 460px;

            background: #ffffff;

            padding: 35px;

            border-radius: 16px;

            box-shadow:
                0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;

            font-size: 28px;
            font-weight: 700;

            margin-bottom: 8px;

            color: #673de6;
        }

        .subtitle {
            text-align: center;

            color: #777;

            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;
            font-weight: 600;

            color: #333;
        }

        input {
            width: 100%;

            padding: 12px 14px;

            border: 1px solid #ddd;

            border-radius: 8px;

            font-size: 15px;

            outline: none;
        }

        input:focus {
            border-color: #673de6;
        }

        button {
            width: 100%;

            padding: 13px;

            border: 0;

            border-radius: 8px;

            background: #673de6;

            color: #fff;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;
        }

        button:hover {
            background: #5630c7;
        }

        .error {
            margin-bottom: 20px;

            padding: 12px 15px;

            border-radius: 8px;

            background: #fff0f0;

            color: #c62828;

            font-size: 14px;
        }

        .success {
            margin-bottom: 20px;

            padding: 12px 15px;

            border-radius: 8px;

            background: #edf9f0;

            color: #26733a;

            font-size: 14px;
        }

        .warning {
            margin-top: 20px;

            padding: 12px 15px;

            border-radius: 8px;

            background: #fff8e6;

            color: #8a6200;

            font-size: 13px;

            line-height: 1.5;
        }

    </style>

</head>

<body>

<div class="card">

    <div class="logo">
        SunViet
    </div>

    <div class="subtitle">
        Create Administrator Account
    </div>


    <?php if (!empty($errors)): ?>

        <div class="error">

            <?php foreach ($errors as $error): ?>

                <div>
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>


    <?php if (!empty($success)): ?>

        <div class="success">

            <?= htmlspecialchars($success) ?>

        </div>

    <?php endif; ?>


    <form
        method="POST"
        autocomplete="off"
    >

        <div class="form-group">

            <label for="username">
                Username
            </label>

            <input
                type="text"
                id="username"
                name="username"
                placeholder="admin"
                required
            >

        </div>


        <div class="form-group">

            <label for="full_name">
                Full Name
            </label>

            <input
                type="text"
                id="full_name"
                name="full_name"
                placeholder="SunViet Administrator"
            >

        </div>


        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="admin@example.com"
                required
            >

        </div>


        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimum 6 characters"
                required
            >

        </div>


        <button type="submit">
            Create Admin Account
        </button>

    </form>


    <div class="warning">

        <strong>Security:</strong>

        After creating the administrator account,
        delete this file:

        <br><br>

        <code>admin/create-admin.php</code>

    </div>

</div>

</body>

</html>