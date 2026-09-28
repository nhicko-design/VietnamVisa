<?php

require_once __DIR__ . '/../common/auth.php';

logout_admin();

header(
    'Location: /immigra/admin/login.php'
);

exit;