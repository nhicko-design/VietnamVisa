<?php

if (!isset($page_title)) {
    $page_title = 'Admin';
}

$admin_css_path = __DIR__ . '/../assets/css/admin.css';
$admin_css_version = is_file($admin_css_path)
    ? (string) filemtime($admin_css_path)
    : '1';

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="theme-color"
        content="#111114"
    >

    <title>
        <?= htmlspecialchars($page_title) ?>
        - SunViet Admin
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet"
    >


    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/admin.css?v=' . $admin_css_version, ENT_QUOTES, 'UTF-8') ?>"
    >

    <link
        rel="stylesheet"
        href="<?= htmlspecialchars(ADMIN_BASE_URL . '/assets/css/sunviet-admin-theme.css', ENT_QUOTES, 'UTF-8') ?>"
    >

</head>

<body class="admin-body">
