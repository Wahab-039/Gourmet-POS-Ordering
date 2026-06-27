<?php
// config/constants.php

define('APP_NAME', 'Gourmet POS & Ordering');

// Dynamically generate BASE_URL based on where the script is running
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);
// If running at web root, dirname is '\' or '/', we need to handle that gracefully
$base_url_path = ($script === '\\' || $script === '/') ? '' : $script;

define('BASE_URL', $protocol . '://' . $host . str_replace('\\', '/', $base_url_path));

define('UPLOAD_DIR', __DIR__ . '/../assets/images/foods/');
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}
