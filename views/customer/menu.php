<?php
require_once "views/layouts/header.php";

$search = $_GET['search'] ?? '';
$cat_id = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;

$query = "SELECT f.*, c.name as category_name 
          FROM foods f 
          JOIN categories c ON f.category_id = c.id 
          WHERE f.is_available = 1";
$params = [];

if ($search) {
    $query .= " AND (f.name LIKE ? OR f.description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($cat_id > 0) {
    $query .= " AND f.category_id = ?";
    $params[] = $cat_id;
}

$query .= " ORDER BY f.name ASC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$foods = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4"><i class="bi bi-book"></i> Our Menu</h2>
        
        <!-- Search and Filter -->
        <div class="card card-shadow mb-4">
            <div class="card-body">
                <form action="<?= BASE_URL ?>/index.php" method="GET" class="row g-3">
                    <input type="hidden" name="page" value="menu">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Search for food..." value="<?= escape($search) ?>">
                    </div>
                    <div class="col-md-4">
                        <select name="cat_id" class="form-select">
                            <option value="0">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat_id == $cat['id'] ? 'selected' : '' ?>><?= escape($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <?php foreach ($foods as $food): ?>
    <div class="col-md-4 col-lg-3">
        <div class="card card-shadow h-100">
            <?php if ($food['image_path']): ?>
                <img src="<?= BASE_URL ?>/assets/images/foods/<?= escape($food['image_path']) ?>" class="card-img-top object-fit-cover" alt="Food" style="height: 200px;">
            <?php else: ?>
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                </div>
            <?php endif; ?>
            
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?= escape($food['name']) ?></h5>
                <p class="text-muted small mb-2"><?= escape($food['category_name']) ?></p>
                <p class="card-text flex-grow-1"><?= escape($food['description']) ?></p>
                <h4 class="text-primary mb-3"><?= formatCurrency($food['price']) ?></h4>
                
                <form action="<?= BASE_URL ?>/index.php?page=cart_action&action=add" method="POST" class="mt-auto">
                    <?= csrfField() ?>
                    <input type="hidden" name="food_id" value="<?= $food['id'] ?>">
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="20" required>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-cart-plus"></i> Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    
    <?php if (empty($foods)): ?>
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No food items found matching your criteria.</h4>
        </div>
    <?php endif; ?>
</div>

<?php require_once "views/layouts/footer.php"; ?>
