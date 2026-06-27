<?php
// index.php
require_once 'config/constants.php';
require_once 'config/database.php';
require_once 'includes/session.php';
require_once 'includes/security.php';
require_once 'includes/helpers.php';

$page = $_GET['page'] ?? 'home';

// Basic routing
switch ($page) {
    case 'home':
        // We will default to login if not logged in, or dashboard based on role
        if (isLoggedIn()) {
            if (hasRole(ROLE_CUSTOMER)) redirect('index.php?page=menu');
            if (hasRole(ROLE_KITCHEN)) redirect('index.php?page=kitchen_dashboard');
            if (hasRole(ROLE_CASHIER)) redirect('index.php?page=cashier_dashboard');
            if (hasRole(ROLE_ADMIN)) redirect('index.php?page=admin_dashboard');
        } else {
            redirect('index.php?page=login');
        }
        break;
        
    case 'login':
    case 'register':
        require_once "controllers/auth_controller.php";
        break;
        
    case 'logout':
        session_destroy();
        redirect('index.php?page=login');
        break;

    // Admin routes
    case 'admin_dashboard':
        requireRole(ROLE_ADMIN);
        require_once "views/admin/dashboard.php";
        break;
    case 'admin_categories':
        requireRole(ROLE_ADMIN);
        require_once "views/admin/categories.php";
        break;
    case 'admin_foods':
        requireRole(ROLE_ADMIN);
        require_once "views/admin/foods.php";
        break;
    case 'admin_users':
        requireRole(ROLE_ADMIN);
        require_once "views/admin/users.php";
        break;
    case 'admin_action':
        requireRole(ROLE_ADMIN);
        require_once "controllers/admin_controller.php";
        break;
        
    // Kitchen routes
    case 'kitchen_dashboard':
        requireRole(ROLE_KITCHEN);
        require_once "views/kitchen/queue.php";
        break;
    case 'kitchen_action':
        requireRole(ROLE_KITCHEN);
        require_once "controllers/kitchen_controller.php";
        break;
        
    // Cashier routes
    case 'cashier_dashboard':
        requireRole(ROLE_CASHIER);
        require_once "views/cashier/dashboard.php";
        break;
    case 'cashier_action':
        requireRole(ROLE_CASHIER);
        require_once "controllers/cashier_controller.php";
        break;
    case 'cashier_receipt':
        requireRole(ROLE_CASHIER);
        require_once "views/cashier/receipt.php";
        break;
        
    // Customer routes
    case 'menu':
        requireRole(ROLE_CUSTOMER);
        require_once "views/customer/menu.php";
        break;
    case 'cart':
        requireRole(ROLE_CUSTOMER);
        require_once "views/customer/cart.php";
        break;
    case 'cart_action':
        requireRole(ROLE_CUSTOMER);
        require_once "controllers/cart_controller.php";
        break;
    case 'checkout':
        requireRole(ROLE_CUSTOMER);
        require_once "views/customer/checkout.php";
        break;
    case 'place_order':
        requireRole(ROLE_CUSTOMER);
        require_once "controllers/order_controller.php";
        break;
    case 'history':
        requireRole(ROLE_CUSTOMER);
        require_once "views/customer/history.php";
        break;
    case 'order_details':
        requireRole(ROLE_CUSTOMER);
        require_once "views/customer/order_details.php";
        break;
    case 'submit_review':
        requireRole(ROLE_CUSTOMER);
        require_once "controllers/review_controller.php";
        break;

    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        break;
}
