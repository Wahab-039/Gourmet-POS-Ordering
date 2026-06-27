<?php
requireRole(ROLE_CASHIER);

$order_id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT o.*, u.name as customer_name, p.created_at as payment_date, p.method 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    LEFT JOIN payments p ON o.id = p.order_id 
    WHERE o.id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

$stmt = $pdo->prepare("
    SELECT oi.*, f.name 
    FROM order_items oi 
    JOIN foods f ON oi.food_id = f.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt - Order #<?= $order['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .receipt-container { max-width: 400px; margin: 50px auto; background: #fff; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; }
        .dashed-line { border-top: 2px dashed #ccc; margin: 15px 0; }
        @media print {
            body { background: #fff; }
            .receipt-container { box-shadow: none; margin: 0; padding: 0; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="text-center mb-4">
            <h3 class="mb-1 fw-bold"><?= APP_NAME ?></h3>
            <p class="text-muted mb-0">Order Receipt</p>
        </div>
        
        <div class="mb-3">
            <div><strong>Order ID:</strong> #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></div>
            <div><strong>Date:</strong> <?= date('d M Y, H:i', strtotime($order['payment_date'] ?? $order['updated_at'])) ?></div>
            <div><strong>Customer:</strong> <?= escape($order['customer_name']) ?></div>
            <div><strong>Cashier:</strong> <?= escape($_SESSION['user_name']) ?></div>
        </div>
        
        <div class="dashed-line"></div>
        
        <table class="table table-borderless table-sm mb-0">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Amt</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= escape($item['name']) ?></td>
                    <td class="text-center"><?= $item['quantity'] ?></td>
                    <td class="text-end"><?= formatCurrency($item['price_at_time'] * $item['quantity']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="dashed-line"></div>
        
        <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
            <span>TOTAL</span>
            <span><?= formatCurrency($order['total_amount']) ?></span>
        </div>
        
        <div class="text-center mb-2">
            <div><small>Payment Method: <?= escape($order['method'] ?? 'Cash') ?></small></div>
            <div><small>Status: Paid</small></div>
        </div>
        
        <div class="text-center mt-4 text-muted">
            <p class="mb-0">Thank you for your order!</p>
        </div>
        
        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary me-2">Print Receipt</button>
            <a href="<?= BASE_URL ?>/index.php?page=cashier_dashboard" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
