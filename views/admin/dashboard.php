<?php require_once "views/layouts/header.php"; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
    </div>
</div>

<?php
// Quick stats
$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
    'foods' => $pdo->query("SELECT COUNT(*) FROM foods")->fetchColumn(),
    'orders' => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
];
?>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card card-shadow bg-primary text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="display-4"><?= $stats['users'] ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="<?= BASE_URL ?>/index.php?page=admin_users" class="text-white text-decoration-none">Manage Users <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow bg-success text-white h-100">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <h2 class="display-4"><?= $stats['categories'] ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="<?= BASE_URL ?>/index.php?page=admin_categories" class="text-white text-decoration-none">Manage Categories <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow bg-warning text-dark h-100">
            <div class="card-body">
                <h5 class="card-title">Food Items</h5>
                <h2 class="display-4"><?= $stats['foods'] ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="<?= BASE_URL ?>/index.php?page=admin_foods" class="text-dark text-decoration-none">Manage Foods <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-shadow bg-info text-dark h-100">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <h2 class="display-4"><?= $stats['orders'] ?></h2>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="#" class="text-dark text-decoration-none">View Orders <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
