<?php
require_once "views/layouts/header.php";

// Fetch Ready orders for cashier to process payment
$stmt = $pdo->query("
    SELECT o.*, u.name as customer_name 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    WHERE o.status = 'Ready' 
    ORDER BY o.updated_at ASC
");
$orders = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-cash-coin"></i> Cashier Register</h2>
    </div>
</div>

<div class="card card-shadow">
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="bi bi-check-circle text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">No pending payments. All caught up!</h4>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= escape($order['customer_name']) ?></td>
                            <td><strong><?= formatCurrency($order['total_amount']) ?></strong></td>
                            <td><span class="badge bg-success">Ready</span></td>
                            <td>
                                <form action="<?= BASE_URL ?>/index.php?page=cashier_action" method="POST" class="d-inline" onsubmit="return confirm('Confirm payment received for Order #<?= $order['id'] ?>?');">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Process Payment</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
