<?php

require_once __DIR__ . '/config.php';

require_admin_login();

header('Location: ' . ADMIN_BASE_URL . '/services/manage.php?category=visa&scope=vietnam-visa');
exit;
