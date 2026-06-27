<?php
require_once "views/layouts/header.php";

$stmt = $pdo->query("
    SELECT o.*, u.name as customer_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.status IN ('Pending', 'Accepted', 'Preparing') 
    ORDER BY o.created_at ASC
");
$orders = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-fire"></i> Kitchen Queue</h2>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($orders)): ?>
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">No pending orders. Kitchen is clear!</h4>
        </div>
    <?php endif; ?>

    <?php foreach ($orders as $order): 
        // Fetch items for this order
        $itemStmt = $pdo->prepare("
            SELECT oi.quantity, f.name 
            FROM order_items oi 
            JOIN foods f ON oi.food_id = f.id 
            WHERE oi.order_id = ?
        ");
        $itemStmt->execute([$order['id']]);
        $items = $itemStmt->fetchAll();
    ?>
    <div class="col-md-6 col-lg-4">
        <div class="card card-shadow h-100 border-top border-4 <?php 
            if($order['status'] == 'Pending') echo 'border-warning';
            elseif($order['status'] == 'Accepted') echo 'border-info';
            elseif($order['status'] == 'Preparing') echo 'border-primary';
        ?>">
            <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom-0 pt-3">
                <h5 class="mb-0">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></h5>
                <span class="badge text-dark bg-light border"><i class="bi bi-clock"></i> <?= date('h:i A', strtotime($order['created_at'])) ?></span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="small text-muted mb-3"><i class="bi bi-person"></i> <?= escape($order['customer_name']) ?></p>
                <ul class="list-group list-group-flush mb-4 flex-grow-1">
                    <?php foreach ($items as $item): ?>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span><?= escape($item['name']) ?></span>
                            <strong>x<?= $item['quantity'] ?></strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
                
                <form action="<?= BASE_URL ?>/index.php?page=kitchen_action" method="POST" class="mt-auto">
                    <?= csrfField() ?>
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    
                    <?php if ($order['status'] === 'Pending'): ?>
                        <input type="hidden" name="status" value="Accepted">
                        <button type="submit" class="btn btn-warning w-100">Accept Order</button>
                    <?php elseif ($order['status'] === 'Accepted'): ?>
                        <input type="hidden" name="status" value="Preparing">
                        <button type="submit" class="btn btn-info w-100 text-white">Start Preparing</button>
                    <?php elseif ($order['status'] === 'Preparing'): ?>
                        <input type="hidden" name="status" value="Ready">
                        <button type="submit" class="btn btn-success w-100">Mark as Ready</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once "views/layouts/footer.php"; ?>
