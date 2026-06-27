<?php
require_once "views/layouts/header.php";
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$foods = $pdo->query("
    SELECT f.*, c.name as category_name 
    FROM foods f 
    JOIN categories c ON f.category_id = c.id 
    ORDER BY f.id DESC
")->fetchAll();
?>

<div class="row mb-4">
    <div class="col-md-6">
        <h2><i class="bi bi-egg-fried"></i> Manage Food Items</h2>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFoodModal">
            <i class="bi bi-plus-circle"></i> Add Food Item
        </button>
    </div>
</div>

<div class="card card-shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($foods as $food): ?>
                    <tr>
                        <td>
                            <?php if ($food['image_path']): ?>
                                <img src="<?= BASE_URL ?>/assets/images/foods/<?= escape($food['image_path']) ?>" alt="Food" width="50" height="50" class="rounded object-fit-cover">
                            <?php else: ?>
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><?= escape($food['name']) ?></td>
                        <td><?= escape($food['category_name']) ?></td>
                        <td><?= formatCurrency($food['price']) ?></td>
                        <td>
                            <?php if ($food['is_available']): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Out of Stock</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?= BASE_URL ?>/index.php?page=admin_action&action=delete_food" method="POST" class="d-inline" onsubmit="return confirm('Delete this food item?');">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $food['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($foods)): ?>
                    <tr><td colspan="6" class="text-center">No food items found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Food Modal -->
<div class="modal fade" id="addFoodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/index.php?page=admin_action&action=add_food" method="POST" enctype="multipart/form-data">
                <?= csrfField() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add New Food Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= escape($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Food Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Image (JPG, PNG, WEBP)</label>
                        <input type="file" name="image" class="form-control" accept="image/png, image/jpeg, image/webp">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_available" id="isAvailable" checked>
                        <label class="form-check-label" for="isAvailable">Available for Order</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Food Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
