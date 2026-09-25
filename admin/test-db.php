<?php

require_once __DIR__ . '/config.php';

$message = '';
$error = '';

$tables = [];

$adminCount = 0;
$serviceCount = 0;
$newsCount = 0;

try {

    // Connect database
    $pdo = db();

    // ==========================================
    // Get database tables
    // ==========================================

    $tables = $pdo
        ->query('SHOW TABLES')
        ->fetchAll(PDO::FETCH_COLUMN);

    // ==========================================
    // Count admin users
    // ==========================================

    $adminCount = (int) $pdo
        ->query('SELECT COUNT(*) FROM admin_users')
        ->fetchColumn();

    // ==========================================
    // Count services
    // ==========================================

    $serviceCount = (int) $pdo
        ->query('SELECT COUNT(*) FROM services')
        ->fetchColumn();

    // ==========================================
    // Count news
    // ==========================================

    $newsCount = (int) $pdo
        ->query('SELECT COUNT(*) FROM news')
        ->fetchColumn();

    $message = 'Database connection successful.';

} catch (Throwable $e) {

    $error = $e->getMessage();
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

    <title>Immigra - Database Test</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 40px 20px;

            background: #f6f7fb;

            color: #17171b;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        .container {
            width: 100%;
            max-width: 760px;

            margin: 0 auto;

            background: #ffffff;

            border-radius: 18px;

            padding: 32px;

            box-shadow:
                0 12px 40px
                rgba(0, 0, 0, 0.08);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 24px;

            font-size: 28px;
        }

        .success {
            padding: 16px 18px;

            border-radius: 12px;

            background: #eaf8ef;

            color: #176b37;

            border: 1px solid #c9efd6;
        }

        .error {
            padding: 16px 18px;

            border-radius: 12px;

            background: #fff0f0;

            color: #a61b1b;

            border: 1px solid #ffd1d1;

            line-height: 1.6;
        }

        .stats {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 14px;

            margin: 24px 0;
        }

        .stat {

            padding: 20px;

            background: #f7f7fa;

            border-radius: 14px;

            border: 1px solid #eeeeF2;
        }

        .number {

            font-size: 30px;

            font-weight: 700;

            margin-bottom: 5px;
        }

        .label {

            color: #777;

            font-size: 14px;
        }

        h2 {

            margin-top: 30px;

            font-size: 19px;
        }

        ul {

            margin: 12px 0 0;

            padding-left: 24px;

            line-height: 1.9;
        }

        code {

            padding: 3px 7px;

            border-radius: 5px;

            background: #f1f1f4;

            font-size: 13px;
        }

        .warning {

            margin-top: 28px;

            padding: 15px 17px;

            border-radius: 12px;

            background: #fff7df;

            color: #725300;

            line-height: 1.6;
        }

        @media (max-width: 600px) {

            body {
                padding: 20px 12px;
            }

            .container {
                padding: 22px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <h1>
        Immigra Database Test
    </h1>

    <?php if ($error): ?>

        <!-- ========================= -->
        <!-- CONNECTION ERROR           -->
        <!-- ========================= -->

        <div class="error">

            <strong>
                Database connection failed.
            </strong>

            <br>

            <?= htmlspecialchars(
                $error,
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </div>

        <p>
            Please make sure that:
        </p>

        <ul>

            <li>
                MySQL is running in XAMPP.
            </li>

            <li>
                Database
                <code>immigra</code>
                exists.
            </li>

            <li>
                MySQL username/password
                is correct.
            </li>

        </ul>

    <?php else: ?>

        <!-- ========================= -->
        <!-- SUCCESS                    -->
        <!-- ========================= -->

        <div class="success">

            <strong>
                <?= htmlspecialchars(
                    $message,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>

        </div>


        <!-- ========================= -->
        <!-- DATABASE STATISTICS        -->
        <!-- ========================= -->

        <div class="stats">

            <div class="stat">

                <div class="number">
                    <?= $adminCount ?>
                </div>

                <div class="label">
                    Admin Users
                </div>

            </div>


            <div class="stat">

                <div class="number">
                    <?= $serviceCount ?>
                </div>

                <div class="label">
                    Services
                </div>

            </div>


            <div class="stat">

                <div class="number">
                    <?= $newsCount ?>
                </div>

                <div class="label">
                    News
                </div>

            </div>

        </div>


        <!-- ========================= -->
        <!-- DATABASE TABLES            -->
        <!-- ========================= -->

        <h2>
            Database Tables
        </h2>

        <?php if (empty($tables)): ?>

            <p>
                No tables found.
            </p>

        <?php else: ?>

            <ul>

                <?php foreach ($tables as $table): ?>

                    <li>

                        <?= htmlspecialchars(
                            $table,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </li>

                <?php endforeach; ?>

            </ul>

        <?php endif; ?>


        <!-- ========================= -->
        <!-- SECURITY WARNING            -->
        <!-- ========================= -->

        <div class="warning">

            <strong>
                Important:
            </strong>

            After confirming that the
            database connection works,
            delete:

            <code>
                admin/test-db.php
            </code>

            from the project.

        </div>

    <?php endif; ?>

</div>

</body>

</html>
