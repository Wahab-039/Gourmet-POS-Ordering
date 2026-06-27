<?php
require_once "views/layouts/header.php";
$cart = $_SESSION['cart'] ?? [];
$total = 0;
?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-cart3"></i> Your Shopping Cart</h2>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card card-shadow mb-4">
            <div class="card-body">
                <?php if (empty($cart)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Your cart is empty.</h4>
                        <a href="<?= BASE_URL ?>/index.php?page=menu" class="btn btn-primary mt-3">Browse Menu</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $id => $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td><?= escape($item['name']) ?></td>
                                    <td><?= formatCurrency($item['price']) ?></td>
                                    <td style="width: 150px;">
                                        <form action="<?= BASE_URL ?>/index.php?page=cart_action&action=update" method="POST" class="d-flex">
                                            <?= csrfField() ?>
                                            <input type="hidden" name="food_id" value="<?= $id ?>">
                                            <input type="number" name="quantity" class="form-control form-control-sm me-2" value="<?= $item['quantity'] ?>" min="1" max="20">
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i></button>
                                        </form>
                                    </td>
                                    <td><?= formatCurrency($subtotal) ?></td>
                                    <td>
                                        <form action="<?= BASE_URL ?>/index.php?page=cart_action&action=remove" method="POST">
                                            <?= csrfField() ?>
                                            <input type="hidden" name="food_id" value="<?= $id ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <form action="<?= BASE_URL ?>/index.php?page=cart_action&action=clear" method="POST">
                            <?= csrfField() ?>
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Clear your entire cart?');">Clear Cart</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if (!empty($cart)): ?>
    <div class="col-lg-4">
        <div class="card card-shadow">
            <div class="card-body">
                <h4 class="card-title mb-4">Order Summary</h4>
                <div class="d-flex justify-content-between mb-3">
                    <span>Subtotal</span>
                    <span><?= formatCurrency($total) ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <strong>Total Amount</strong>
                    <strong class="text-primary fs-5"><?= formatCurrency($total) ?></strong>
                </div>
                <a href="<?= BASE_URL ?>/index.php?page=checkout" class="btn btn-primary w-100 py-2 fs-5">Proceed to Checkout</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once "views/layouts/footer.php"; ?>
