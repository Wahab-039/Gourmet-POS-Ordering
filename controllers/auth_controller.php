<?php
// controllers/auth_controller.php
require_once "config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');

    if ($page === 'register') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        
        if (empty($name) || empty($email) || empty($password)) {
            setFlashMessage('error', 'Please fill in all required fields.');
            redirect('index.php?page=register');
        }
        
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            setFlashMessage('error', 'Email is already registered.');
            redirect('index.php?page=register');
        }
        
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (role_id, name, email, password, phone, address) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([ROLE_CUSTOMER, $name, $email, $hashed, $phone, $address])) {
            setFlashMessage('success', 'Registration successful. Please login.');
            redirect('index.php?page=login');
        } else {
            setFlashMessage('error', 'Registration failed.');
            redirect('index.php?page=register');
        }
    } 
    elseif ($page === 'login') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['user_name'] = $user['name'];
            
            // Redirect based on role
            switch ($user['role_id']) {
                case ROLE_ADMIN: redirect('index.php?page=admin_dashboard'); break;
                case ROLE_KITCHEN: redirect('index.php?page=kitchen_dashboard'); break;
                case ROLE_CASHIER: redirect('index.php?page=cashier_dashboard'); break;
                default: redirect('index.php?page=menu'); break;
            }
        } else {
            setFlashMessage('error', 'Invalid email or password.');
            redirect('index.php?page=login');
        }
    }
} else {
    // GET requests
    if ($page === 'login') {
        require_once "views/auth/login.php";
    } elseif ($page === 'register') {
        require_once "views/auth/register.php";
    }
}
