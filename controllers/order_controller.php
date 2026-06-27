<?php
// controllers/order_controller.php
requireRole(ROLE_CUSTOMER);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');
    
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        redirect('index.php?page=menu');
    }
    
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (empty($phone) || empty($address)) {
        setFlashMessage('error', 'Delivery details are required.');
        redirect('index.php?page=checkout');
    }
    
    // Update user info
    $stmt = $pdo->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?");
    $stmt->execute([$phone, $address, $_SESSION['user_id']]);
    
    // Calculate total
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    
    // Begin Transaction
    try {
        $pdo->beginTransaction();
        
        // Insert Order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'Pending')");
        $stmt->execute([$_SESSION['user_id'], $total]);
        $order_id = $pdo->lastInsertId();
        
        // Insert Order Items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, food_id, quantity, price_at_time) VALUES (?, ?, ?, ?)");
        foreach ($cart as $food_id => $item) {
            $stmt->execute([$order_id, $food_id, $item['quantity'], $item['price']]);
        }
        
        $pdo->commit();
        
        // Clear Cart
        $_SESSION['cart'] = [];
        setFlashMessage('success', 'Order placed successfully! Track your order status here.');
        redirect('index.php?page=history');
        
    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('error', 'Failed to place order. Please try again.');
        redirect('index.php?page=checkout');
    }
} else {
    redirect('index.php?page=checkout');
}
