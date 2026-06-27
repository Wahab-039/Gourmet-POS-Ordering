<?php
require_once "views/layouts/header.php";

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    setFlashMessage('error', 'Your cart is empty.');
    redirect('index.php?page=menu');
}

$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Get user info
$stmt = $pdo->prepare("SELECT phone, address FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-credit-card"></i> Checkout</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card card-shadow mb-4">
            <div class="card-body">
                <h4 class="mb-4">Delivery Details</h4>
                <form action="<?= BASE_URL ?>/index.php?page=place_order" method="POST" id="checkoutForm">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="<?= escape($user['phone']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Delivery Address</label>
                        <textarea name="address" class="form-control" rows="3" required><?= escape($user['address']) ?></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Card on Delivery">Card on Delivery</option>
                        </select>
                        <small class="text-muted">You will pay the Cashier when receiving the order.</small>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-2 fs-5">Confirm Order</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card card-shadow bg-light">
            <div class="card-body">
                <h5 class="mb-4">Order Summary</h5>
                <ul class="list-group mb-3">
                    <?php foreach ($cart as $item): ?>
                    <li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-0"><?= escape($item['name']) ?></h6>
                            <small class="text-muted">Qty: <?= $item['quantity'] ?></small>
                        </div>
                        <span class="text-muted"><?= formatCurrency($item['price'] * $item['quantity']) ?></span>
                    </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between bg-white border-top border-2">
                        <span>Total (USD)</span>
                        <strong><?= formatCurrency($total) ?></strong>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
