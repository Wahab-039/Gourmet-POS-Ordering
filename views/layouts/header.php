<?php
// views/layouts/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; color: #ff6b6b !important; }
        .card-shadow { box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none; border-radius: 12px; }
        .btn-primary { background-color: #ff6b6b; border-color: #ff6b6b; }
        .btn-primary:hover { background-color: #fa5252; border-color: #fa5252; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>"><i class="bi bi-shop"></i> <?= APP_NAME ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if (isLoggedIn()): ?>
                    <?php if (hasRole(ROLE_CUSTOMER)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=menu">Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=history">My Orders</a></li>
                    <?php elseif (hasRole(ROLE_ADMIN)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=admin_dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=admin_foods">Manage Menu</a></li>
                    <?php elseif (hasRole(ROLE_KITCHEN)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=kitchen_dashboard">Kitchen Queue</a></li>
                    <?php elseif (hasRole(ROLE_CASHIER)): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=cashier_dashboard">Cashier Register</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <?php if (hasRole(ROLE_CUSTOMER)): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/index.php?page=cart">
                                <i class="bi bi-cart3"></i> Cart
                                <?php 
                                $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                                if ($cart_count > 0): ?>
                                    <span class="badge bg-danger rounded-pill"><?= $cart_count ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= escape($_SESSION['user_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/index.php?page=logout">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/index.php?page=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <?php if ($msg = getFlashMessage('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= escape($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($msg = getFlashMessage('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= escape($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
