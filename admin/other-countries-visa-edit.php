<?php

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $_GET['edit'] = $id;
}

$_GET['form_page'] = '1';

require __DIR__ . '/other-countries-visa.php';
