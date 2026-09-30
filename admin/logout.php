<?php

require_once __DIR__ . '/../common/auth.php';

logout_admin();

header(
    'Location: /VietnamVisa/admin/login.php'
);

exit;