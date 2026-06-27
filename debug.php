<?php
require_once "config/database.php";
$stmt = $pdo->query("SELECT * FROM users WHERE email = 'admin@admin.com'");
$user = $stmt->fetch();

echo "<h1>Debug Info</h1>";
if (!$user) {
    echo "<p>Error: The admin user does NOT exist in the database! (Setup.sql might not have been imported correctly)</p>";
} else {
    echo "<p>Admin user exists!</p>";
    echo "<p>Email: " . htmlspecialchars($user['email']) . "</p>";
    
    if (password_verify('password', $user['password'])) {
        echo "<p style='color: green;'>Password Check: SUCCESS! The password is 'password'.</p>";
    } else {
        echo "<p style='color: red;'>Password Check: FAILED! The password is not 'password'.</p>";
        echo "<p>Make sure you visited fix_admin.php first.</p>";
    }
}
