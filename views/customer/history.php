<?php
require_once "views/layouts/header.php";

$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-clock-history"></i> My Orders</h2>
    </div>
</div>

<div class="card card-shadow">
    <div class="card-body">
        <?php if (empty($orders)): ?>
            <div class="text-center py-5">
                <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">You haven't placed any orders yet.</h4>
                <a href="<?= BASE_URL ?>/index.php?page=menu" class="btn btn-primary mt-3">Order Now</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('M d, Y h:i A', strtotime($order['created_at'])) ?></td>
                            <td><?= formatCurrency($order['total_amount']) ?></td>
                            <td>
                                <?php
                                $badge = 'bg-secondary';
                                if ($order['status'] === 'Pending') $badge = 'bg-warning text-dark';
                                elseif ($order['status'] === 'Accepted') $badge = 'bg-info text-dark';
                                elseif ($order['status'] === 'Preparing') $badge = 'bg-primary';
                                elseif ($order['status'] === 'Ready') $badge = 'bg-success';
                                elseif ($order['status'] === 'Completed') $badge = 'bg-success bg-gradient';
                                elseif ($order['status'] === 'Cancelled') $badge = 'bg-danger';
                                ?>
                                <span class="badge <?= $badge ?>"><?= escape($order['status']) ?></span>
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>/index.php?page=order_details&id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View Details</a>
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
