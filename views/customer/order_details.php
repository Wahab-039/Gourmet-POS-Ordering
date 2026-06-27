<?php
require_once "views/layouts/header.php";

$order_id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    setFlashMessage('error', 'Order not found.');
    redirect('index.php?page=history');
}

$stmt = $pdo->prepare("
    SELECT oi.*, f.name, f.image_path 
    FROM order_items oi 
    JOIN foods f ON oi.food_id = f.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <a href="<?= BASE_URL ?>/index.php?page=history" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Back to Orders</a>
        <h2>Order #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h2>
        <p class="text-muted">Placed on <?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-shadow mb-4">
            <div class="card-body">
                <h5 class="mb-3">Items Ordered</h5>
                <ul class="list-group list-group-flush">
                    <?php foreach ($items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div class="d-flex align-items-center">
                            <?php if ($item['image_path']): ?>
                                <img src="<?= BASE_URL ?>/assets/images/foods/<?= escape($item['image_path']) ?>" alt="Food" width="50" height="50" class="rounded me-3 object-fit-cover">
                            <?php else: ?>
                                <div class="bg-secondary text-white rounded d-flex align-items-center justify-content-center me-3" style="width:50px; height:50px;"><i class="bi bi-image"></i></div>
                            <?php endif; ?>
                            <div>
                                <h6 class="my-0"><?= escape($item['name']) ?></h6>
                                <small class="text-muted">Qty: <?= $item['quantity'] ?> x <?= formatCurrency($item['price_at_time']) ?></small>
                            </div>
                        </div>
                        <span class="text-muted"><?= formatCurrency($item['price_at_time'] * $item['quantity']) ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="card-footer bg-white text-end py-3">
                <h5 class="mb-0">Total: <span class="text-primary"><?= formatCurrency($order['total_amount']) ?></span></h5>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card card-shadow">
            <div class="card-body">
                <h5 class="card-title">Order Status</h5>
                <?php
                $badge = 'bg-secondary';
                if ($order['status'] === 'Pending') $badge = 'bg-warning text-dark';
                elseif ($order['status'] === 'Accepted') $badge = 'bg-info text-dark';
                elseif ($order['status'] === 'Preparing') $badge = 'bg-primary';
                elseif ($order['status'] === 'Ready') $badge = 'bg-success';
                elseif ($order['status'] === 'Completed') $badge = 'bg-success bg-gradient';
                elseif ($order['status'] === 'Cancelled') $badge = 'bg-danger';
                ?>
                <h4 class="mt-3"><span class="badge <?= $badge ?> w-100 py-2"><?= escape($order['status']) ?></span></h4>
                
                <hr class="my-4">
                
                <?php if ($order['status'] === 'Completed'): ?>
                    <h6 class="text-center mb-3">Leave a Review</h6>
                    <form action="<?= BASE_URL ?>/index.php?page=submit_review" method="POST">
                        <?= csrfField() ?>
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <div class="mb-2">
                            <select name="food_id" class="form-select form-select-sm" required>
                                <option value="">Select Item to Review...</option>
                                <?php foreach ($items as $item): ?>
                                    <option value="<?= $item['food_id'] ?>"><?= escape($item['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-2">
                            <select name="rating" class="form-select form-select-sm" required>
                                <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                                <option value="4">⭐⭐⭐⭐ (4/5)</option>
                                <option value="3">⭐⭐⭐ (3/5)</option>
                                <option value="2">⭐⭐ (2/5)</option>
                                <option value="1">⭐ (1/5)</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <textarea name="comment" class="form-control form-control-sm" rows="2" placeholder="Your review..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary w-100">Submit Review</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
