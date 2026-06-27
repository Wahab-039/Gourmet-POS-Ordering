<?php
require_once "config/database.php";
$hash = password_hash('password', PASSWORD_DEFAULT);
$pdo->query("UPDATE users SET password = '$hash' WHERE email = 'admin@admin.com'");

