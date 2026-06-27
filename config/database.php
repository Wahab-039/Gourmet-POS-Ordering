<?php
// config/database.php

$host = '127.0.0.1';
$db   = 'restaurant_db';
$user = 'root';
$pass = ''; // Default XAMPP password is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Secure error handling, caught in code
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false, // Real prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // DO NOT echo the actual error for security reasons (prevents path/db info leakage)
    die("Database connection failed. Please ensure the database 'restaurant_db' is created using setup.sql.");
}
