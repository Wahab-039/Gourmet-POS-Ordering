<?php
// includes/helpers.php

function setFlashMessage($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function getFlashMessage($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function formatCurrency($amount) {
    return '$' . number_format((float)$amount, 2, '.', ',');
}

// Helper for redirects
function redirect($url) {
    header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
    exit;
}

// Audit logging helper
function logAction($pdo, $user_id, $action, $details = '') {
    $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}
