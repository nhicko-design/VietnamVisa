<?php
require_once __DIR__ . '/../config.php';
require_admin_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf_token($_POST['csrf_token'] ?? null)) { http_response_code(403); exit('Invalid security token.'); }
$id = (int) ($_POST['id'] ?? 0);
if ($id > 0) { $stmt = db()->prepare('DELETE FROM news WHERE id = ?'); $stmt->execute([$id]); }
header('Location: manage.php?deleted=1'); exit;
