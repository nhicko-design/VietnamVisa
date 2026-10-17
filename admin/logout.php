<?php

require_once __DIR__ . '/../common/auth.php';

logout_admin();

header(
    'Location: ' . admin_base_url() . '/login.php'
);

exit;
