<?php
// includes/session.php
if (session_status() === PHP_SESSION_NONE) {
    // Set secure session cookie parameters
    session_set_cookie_params([
        'lifetime' => 86400,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/index.php?page=login');
        exit;
    }
}

function hasRole($role_id) {
    return isset($_SESSION['role_id']) && $_SESSION['role_id'] == $role_id;
}

function requireRole($role_id) {
    requireLogin();
    if (!hasRole($role_id)) {
        header('HTTP/1.1 403 Forbidden');
        die('Access Denied. You do not have permission to view this page.');
    }
}

// Role IDs based on the setup.sql
define('ROLE_CUSTOMER', 1);
define('ROLE_KITCHEN', 2);
define('ROLE_CASHIER', 3);
define('ROLE_ADMIN', 4);
