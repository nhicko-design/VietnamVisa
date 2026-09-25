<?php

if (!isset($page_title)) {
    $page_title = 'Admin';
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
        href="assets/css/admin.css"
    >

</head>

<body class="admin-body">